# ticket_manager
Gerenciador simplificado de tickets, utilizando PHP, Vue.js e Laravel Queues

## Etapa atual concluida

Stack base instalada e configurada em `./src`:

- Laravel 13
- Inertia.js (`inertiajs/inertia-laravel`)
- Vue 3 (`@inertiajs/vue3` + `vue`)
- Vite (`vite` + `laravel-vite-plugin`)
- Breeze com scaffold de autenticacao Inertia + Vue

Arquivos base criados/configurados para frontend Inertia:

- `resources/views/app.blade.php`
- `resources/js/app.js`
- `resources/js/Layouts/AuthenticatedLayout.vue`
- `resources/js/Layouts/GuestLayout.vue`

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

## Pontos de acesso para teste

Com os containers em execucao, os endpoints abaixo ja podem ser acessados:

- `GET /` -> `http://localhost:8080/` (Welcome Inertia)
- `GET /login` -> `http://localhost:8080/login`
- `GET /register` -> `http://localhost:8080/register`
- `GET /dashboard` -> `http://localhost:8080/dashboard` (requer autenticacao)
- `GET /profile` -> `http://localhost:8080/profile` (requer autenticacao)

Fluxo rapido de teste:

```bash
# 1) subir os containers
docker compose up -d --build

# 2) instalar dependencias PHP e JS (caso ainda nao tenham sido instaladas)
docker compose exec app composer install
docker compose exec node npm install

# 3) gerar chave da aplicacao
docker compose exec app php artisan key:generate

# 4) configurar banco no .env e executar migrations
docker compose exec app php artisan migrate
```

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
