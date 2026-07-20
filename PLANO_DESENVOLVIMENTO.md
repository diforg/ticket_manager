# PLANO_DESENVOLVIMENTO – Ticket Manager

## 1. Visão Geral do Projeto
Sistema simplificado de helpdesk onde **clientes** abrem chamados e **atendentes** gerenciam e respondem. O projeto simula um ambiente real de atendimento, com processamento assíncrono de notificações via filas e infraestrutura totalmente conteinerizada.

---

## 2. Stack Tecnológica (Obrigatória)
PHP 8.0+: Back-end principal da aplicação
Vue.js 3: Interface (Composition API)
Inertia.js: Ponte SPA sem API REST separada
PostgreSQL: Banco de dados relacional
Laravel Queues: Processamento assíncrono com banco de dados como driver
Docker: Toda a infraestrutura via docker-compose
Linux / SSH: README com instruções claras de setup

---

## 3. Requisitos Funcionais

### 3.1. Autenticação
- Login e logout com sessão gerenciada pelo Laravel.
- Dois perfis: `cliente` e `atendente`.

### 3.2. CRUD de Tickets
- **Cliente**: pode criar ticket (título + descrição) e visualizar apenas seus próprios tickets.
- **Atendente**: visualiza **todos** os tickets com filtro por status (`aberto`, `em andamento`, `resolvido`) e pode alterar o status.
- Paginação *server-side* na listagem.
- Soft delete com possibilidade de restauração.
- Busca textual por título ou descrição.

### 3.3. Mensagens por Ticket
- Dentro de cada ticket, cliente e atendente podem enviar mensagens (formato chat/comentários).
- Mensagens persistidas no PostgreSQL.

### 3.4. Processamento Assíncrono (Laravel Queues)
- Ao enviar uma nova mensagem, um **Job** é despachado para a fila.
- O job simula o envio de notificação (log, e-mail fake ou registro em tabela).
- Driver obrigatório: **database** (tabelas `jobs` e `failed_jobs` no PostgreSQL).
- Tratamento de falhas: jobs com erro vão para `failed_jobs` e podem ser reprocessados com `php artisan queue:retry`.

### 3.5. Upload de Arquivo (Diferencial)
- Permitir anexar imagem ou PDF a uma mensagem.

---

## 4. Modelagem de Dados (Estrutura das Tabelas)

### 4.1. Tabela `users`
| Campo          | Tipo          | Descrição                              |
|----------------|---------------|----------------------------------------|
| id             | bigint        | PK                                     |
| name           | string        | Nome do usuário                        |
| email          | string        | Único, usado para login                |
| password       | string        | Hash                                   |
| role           | enum/string   | `client` ou `attendant`                |
| remember_token | string        | -                                      |
| timestamps     | -             | created_at / updated_at                |

### 4.2. Tabela `tickets`
| Campo          | Tipo          | Descrição                                      |
|----------------|---------------|------------------------------------------------|
| id             | bigint        | PK                                             |
| user_id        | foreign key   | Cliente que abriu (relacionamento)             |
| title          | string        | Título do chamado                              |
| description    | text          | Descrição inicial                              |
| status         | enum/string   | `open`, `in_progress`, `resolved` (default: open) |
| deleted_at     | timestamp     | Soft delete (nullable)                         |
| timestamps     | -             | created_at / updated_at                        |

### 4.3. Tabela `messages`
| Campo          | Tipo          | Descrição                                      |
|----------------|---------------|------------------------------------------------|
| id             | bigint        | PK                                             |
| ticket_id      | foreign key   | Ticket relacionado (onDelete cascade)          |
| user_id        | foreign key   | Quem enviou (cliente ou atendente)             |
| body           | text          | Conteúdo da mensagem                           |
| timestamps     | -             | created_at / updated_at                        |

### 4.4. Tabela `attachments` (opcional, para upload)
| Campo          | Tipo          | Descrição                                      |
|----------------|---------------|------------------------------------------------|
| id             | bigint        | PK                                             |
| message_id     | foreign key   | Mensagem à qual o arquivo pertence             |
| file_path      | string        | Caminho do arquivo armazenado                  |
| file_name      | string        | Nome original                                  |
| mime_type      | string        | Tipo MIME (ex: image/png, application/pdf)     |
| size           | integer       | Tamanho em bytes                               |
| timestamps     | -             | -                                              |

### 4.5. Tabelas de Fila (geradas pelo Laravel)
- `jobs` – armazena os jobs pendentes.
- `failed_jobs` – armazena jobs que falharam após várias tentativas.

---

## 5. Infraestrutura com Docker
Toda a aplicação deve subir com um único comando: docker-compose up.
Serviços mínimos esperados no compose:
• app — PHP / Laravel
• nginx (ou servidor embutido)
• db — PostgreSQL
• queue worker — container dedicado rodando php artisan queue:work
O Dockerfile não deve usar imagens all-in-one prontas sem personalização.

---

## 6. Fluxos Principais

### 6.1. Fluxo do Cliente
1. Faz login.
2. Visualiza lista de seus tickets (paginação + busca).
3. Cria novo ticket (título + descrição).
4. Abre um ticket para ver mensagens.
5. Envia novas mensagens (dispara job de notificação).
6. Pode anexar arquivos às mensagens.

### 6.2. Fluxo do Atendente
1. Faz login.
2. Visualiza todos os tickets (com filtros por status).
3. Abre um ticket para ver mensagens.
4. Altera o status do ticket (aberto → em andamento → resolvido).
5. Envia mensagens de resposta (dispara job de notificação).
6. Pode anexar arquivos às mensagens.

### 6.3. Fluxo da Notificação Assíncrona
1. Usuário envia mensagem → controller persiste a mensagem.
2. Controller despacha `SendNotificationJob` com dados (mensagem, ticket, usuário).
3. Job é inserido na tabela `jobs`.
4. **Queue Worker** (container separado) processa o job:
   - Simula envio de e-mail (log) ou insere registro em tabela `notifications`.
   - Em caso de exceção → job vai para `failed_jobs`.
5. Atendente pode rodar `php artisan queue:retry` para reprocessar falhas.
