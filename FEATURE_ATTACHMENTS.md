# Prompt para IA executora — Feature: Anexo de Arquivos em Mensagens

O sistema já possui: autenticação com dois perfis (`cliente` e `atendente`), CRUD de tickets, e um chat de mensagens dentro de cada ticket (tabela `messages`, persistida no PostgreSQL, com notificação assíncrona disparada via Job em fila).

## Objetivo desta tarefa

Implementar a funcionalidade de **anexar arquivos às mensagens enviadas pelo cliente** dentro de um ticket, persistindo os metadados no banco através de uma tabela `attachments`.

## Passo 0 — Varredura obrigatória do sistema

Antes de escrever qualquer código, faça uma varredura completa do projeto para mapear os arquivos que precisarão ser criados ou alterados. No mínimo, identifique e liste:

- Migration e Model de `messages` (para entender a relação que `attachments` vai ter)
- Controller responsável por criar mensagens (ex.: `MessageController` ou similar)
- Form Request de validação de mensagens (se existir)
- Componente Vue do chat/mensagens dentro do ticket (ex.: `Show.vue`, `TicketChat.vue`, ou equivalente)
- Rotas relacionadas a mensagens (`routes/web.php`)
- Arquivo de configuração de disks (`config/filesystems.php`)
- Arquivo `.env` / `.env.example` (para variáveis do S3)
- Policies/Middlewares que controlam papéis de usuário (cliente vs atendente), para replicar a mesma lógica de autorização
- Seeders/Factories existentes de `messages`, para criar um equivalente de `AttachmentFactory`/seeder se fizer sentido

Ao final da varredura, apresente um resumo dos arquivos que serão criados/alterados antes de gerar o código, para que eu possa validar o plano.

## Requisitos funcionais

### 1. Regra de negócio e visibilidade

- A opção de anexar arquivo deve estar disponível **somente para o usuário com papel `cliente`**. O atendente não deve visualizar o botão de anexo.
- É permitido anexar **mais de um arquivo por mensagem**.
- O envio do(s) arquivo(s) deve ocorrer **junto com o envio da mensagem** (mesmo submit), não como uma ação separada.

### 2. Banco de dados — tabela `attachments`

Criar o Model `Attachment` com:
- Relação `belongsTo(Message::class)`
- Um accessor/método auxiliar para gerar a URL pública/assinada do arquivo (compatível tanto com disco `local` quanto `s3`)
- Um método/accessor para identificar o tipo de exibição (`image` ou `pdf`) a partir do `mime_type`

Adicionar em `Message` a relação `hasMany(Attachment::class)`.

### 3. Upload e armazenamento

- Utilizar o sistema de disks padrão do Laravel (`config/filesystems.php`).
- Configurar o disco padrão de uploads como `local` (ex.: `storage/app/public/attachments`, com `php artisan storage:link`), mas deixar **pronta a configuração do disco `s3`** (driver `s3` já configurado em `filesystems.php`, lendo credenciais de variáveis de ambiente: `AWS_ACCESS_KEY_ID`, `AWS_SECRET_ACCESS_KEY`, `AWS_DEFAULT_REGION`, `AWS_BUCKET`, `AWS_USE_PATH_STYLE_ENDPOINT`), de forma que trocar de `local` para `s3` em produção seja apenas uma alteração de variável de ambiente (`FILESYSTEM_DISK`), sem alteração de código.
- Adicionar `league/flysystem-aws-s3-v3` como dependência via composer (necessária para o driver `s3` funcionar) e documentar isso no README.
- Validar tipo e tamanho de arquivo no backend (Form Request), aceitando apenas **imagens (jpg, jpeg, png, gif, webp)** e **PDF**, com limite de tamanho razoável (ex.: 10MB por arquivo). Rejeitar qualquer outro mimetype.
- Ao salvar, gerar nome de arquivo único (evitar colisão/sobrescrita) mantendo `original_name` separado para exibição.

### 4. Fluxo no Controller

- Alterar o método de criação de mensagem (ex.: `store`) para aceitar um array de arquivos (`request->file('attachments')` ou similar).
- Dentro de uma transaction: criar a `Message`, depois iterar os arquivos, armazená-los no disco configurado e criar os registros correspondentes em `attachments`.
- Manter o disparo do Job de notificação assíncrona já existente, sem duplicar lógica.
- Retornar a mensagem já carregada com o relacionamento `attachments` para o Inertia renderizar imediatamente sem reload.

### 5. Interface (Vue 3 / Composition API)

- No formulário de envio de mensagem, adicionar um botão com **ícone de clipe (paperclip)** seguido do texto **"Anexar arquivo"** (usar um ícone SVG inline ou uma lib de ícones já usada no projeto, se houver — verificar na varredura antes de introduzir uma nova dependência).
- Esse botão deve aparecer **apenas quando o usuário logado tiver o papel `cliente`** (usar o mesmo mecanismo de checagem de papel já usado em outras partes da UI, ex.: `$page.props.auth.user.role` via Inertia shared data).
- Ao clicar no botão, abrir o seletor de arquivos nativo (`<input type="file" multiple>` oculto, acionado via `ref`), permitindo múltipla seleção.
- Exibir uma prévia/lista dos arquivos selecionados antes do envio, com opção de remover algum item da seleção antes de submeter.
- Validar no frontend (tipo e tamanho) espelhando as regras do backend, exibindo mensagem de erro amigável antes do submit.

### 6. Exibição dos anexos na mensagem já enviada

Para cada anexo listado dentro de uma mensagem já enviada:

- Se `mime_type` for de imagem: exibir uma **miniatura/ícone de imagem**. Ao clicar, abrir a imagem em um **modal** (componente Vue de modal, reaproveitando algum já existente no projeto se houver, senão criar um simples e reutilizável).
- Se `mime_type` for `application/pdf`: exibir um **ícone de PDF**. Ao clicar, abrir o arquivo em **uma nova aba/página** (`target="_blank"`), apontando para a rota que serve a URL do arquivo (local ou assinada, no caso do S3).
- Exibir também o `original_name` do arquivo ao lado do ícone.

### 7. Rota de acesso ao arquivo

Criar uma rota (ex.: `GET /attachments/{attachment}`) que:
- Verifica se o usuário autenticado tem permissão para acessar aquele anexo (é o cliente dono do ticket ou é atendente).
- Retorna o arquivo (via `Storage::disk($disk)->response()` ou redirect para URL assinada no caso do S3).

### 8. Testes

Adicionar pelo menos 1 teste automatizado (Feature) cobrindo:
- Cliente consegue enviar mensagem com um ou mais anexos válidos e os registros são criados corretamente em `attachments`.
- Upload de arquivo com mimetype não permitido é rejeitado com erro de validação.
- (Opcional, se der tempo) Atendente não consegue enviar mensagem com anexo, caso essa regra também deva ser reforçada no backend — **reforçar essa checagem de papel no backend, não confiar apenas na ausência do botão no frontend**.

### 9. Documentação

Atualizar o README com:
- Passo `php artisan storage:link` no setup.
- Explicação de como alternar entre disco `local` e `s3` via `.env`.
- Variáveis de ambiente necessárias para S3 (listadas no `.env.example`).

## Restrições importantes

- Não alterar a stack ou infraestrutura já definida no projeto (sem Redis, sem broker externo, driver de fila continua `database`).
- Seguir o padrão de código e organização de pastas já existente no projeto (não criar uma estrutura paralela).
- Não quebrar funcionalidades existentes de mensagens e notificações assíncronas.
- Ao final, apresentar um resumo das mudanças feitas e os comandos necessários para aplicar (migration, `storage:link`, dependência composer nova, etc.).
