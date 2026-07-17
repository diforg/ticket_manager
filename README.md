# ticket_manager
Gerenciador simplificado de tickets, utilizando PHP, Vue.js e Laravel Queues

## Infraestrutura Docker

Este repositório possui infraestrutura conteinerizada completa com os serviços:

- `app` (PHP-FPM 8.3)
- `nginx` (servidor web)
- `db` (PostgreSQL 16)
- `queue` (worker dedicado do Laravel Queue)
- `node` (Vite para frontend Vue)

### Estrutura esperada

O código da aplicação Laravel deve ficar em `./src`.

### Como subir

```bash
docker compose up -d --build
```

Aplicação web: `http://localhost:8080`

Vite (dev server): `http://localhost:5173`

### Variáveis de banco (Docker)

Caso não sejam definidas no ambiente, os padrões no `docker-compose.yml` serão:

- `DB_DATABASE=ticket_manager`
- `DB_USERNAME=ticket_manager`
- `DB_PASSWORD=ticket_manager`

### Configuração no `.env` do Laravel

No arquivo `.env` dentro de `./src`, use:

```env
DB_CONNECTION=pgsql
DB_HOST=db
DB_PORT=5432
DB_DATABASE=ticket_manager
DB_USERNAME=ticket_manager
DB_PASSWORD=ticket_manager

QUEUE_CONNECTION=database
```

### Comandos úteis

```bash
# Instalar dependências PHP
docker compose exec app composer install

# Instalar dependências JS
docker compose exec node npm install

# Rodar migrations (incluindo jobs/failed_jobs)
docker compose exec app php artisan migrate

# Rodar worker manualmente
docker compose exec queue php artisan queue:work

# Reprocessar jobs com falha
docker compose exec app php artisan queue:retry all
```
