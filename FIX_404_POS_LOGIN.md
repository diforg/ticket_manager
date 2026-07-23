# Prompt: Corrigir erro 404 após login (Laravel + Inertia.js + Vue 3)

## Contexto do sistema
Plataforma de gerenciamento de tickets de suporte construída com:
- PHP 8.0+ / Laravel
- Vue.js 3 (Composition API)
- Inertia.js (SPA sem API REST separada)
- PostgreSQL
- Laravel Queues (driver database)
- Docker / docker-compose

## Bug a corrigir

**Sintoma:** Após o usuário fazer login com sucesso, a aplicação exibe uma mensagem de erro **404** momentaneamente, em vez de redirecionar automaticamente para o dashboard. Ao dar um **refresh manual** na página, o usuário é corretamente levado para a tela de dashboard.

**Comportamento esperado:** Após autenticação bem-sucedida, o usuário deve ser redirecionado automaticamente para o dashboard, sem erros intermediários e sem necessidade de refresh manual.

## Diagnóstico esperado

Esse padrão (erro na navegação via Inertia, mas funciona no full page reload) é característico de uma inconsistência entre o redirect do back-end e a resolução de página no front-end via Inertia. Investigue, nesta ordem, as seguintes hipóteses:

1. **Cache de rotas desatualizado**
   - Verifique se há cache de rotas ativo (`bootstrap/cache/routes-v7.php` ou similar).
   - Rode `php artisan route:clear` e `php artisan route:cache` (ou remova o cache em ambiente de dev) e teste novamente.
   - Confirme, com `php artisan route:list`, que a rota nomeada usada no redirect do login (ex.: `dashboard`) existe, está com o método correto (GET) e não está duplicada/conflitando com outra rota.

2. **Redirect no controller de autenticação**
   - Localize o controller responsável pelo login (ex.: `AuthenticatedSessionController` ou `LoginController`) e confira exatamente qual rota é usada no `redirect()` após autenticação (`redirect()->route('dashboard')` ou `redirect()->intended('/dashboard')`).
   - Confirme que o nome da rota bate exatamente com o definido em `routes/web.php`, incluindo prefixos de grupo/middleware.
   - Verifique se essa rota está dentro do middleware `auth` corretamente e se não está sendo interceptada antes por uma rota `fallback()` (404 customizado) ou por um middleware que a bloqueia antes do redirect ser processado.

3. **Resolução de componente no Inertia (front-end)**
   - Verifique a função `resolve()` no `resources/js/app.js` (ou equivalente), que mapeia o nome da página retornado pelo back-end (`Inertia::render('Dashboard')`) ao componente Vue correspondente.
   - Confirme que o nome passado em `Inertia::render()` no controller do dashboard bate exatamente (case-sensitive) com o caminho/nome do arquivo `.vue` na pasta `resources/js/Pages`.
   - Se o resolver usa `import.meta.glob`, confira se o padrão do glob cobre corretamente o caminho do componente do Dashboard.

4. **Fluxo de requisição do login (Inertia visit)**
   - Confirme se o formulário de login usa `router.post()` do Inertia (e não um `<form>` HTML tradicional, que causaria full reload e mascararia o bug).
   - Verifique se o back-end, ao responder a uma requisição de login vinda via Inertia (com o header `X-Inertia`), está de fato retornando um redirect padrão (302) e não uma resposta JSON pura, o que quebraria o protocolo esperado pelo Inertia.
   - Cheque se existe necessidade de um `Inertia::location()` (redirect forçando full page reload) em vez de um redirect comum — isso costuma ser necessário em cenários de regeneração de sessão/CSRF token após login, e sua ausência pode causar exatamente esse tipo de falha intermitente.

5. **Sessão e CSRF**
   - Confirme que o middleware `VerifyCsrfToken` não está bloqueando a requisição de navegação subsequente ao login (ex.: token desatualizado no client após regeneração de sessão pós-login).
   - Verifique se o Inertia está tratando corretamente respostas 409 (Conflict) que o Inertia usa internamente para forçar reload quando detecta mudança de versão de assets ou necessidade de refresh de sessão.

## O que fazer

1. Reproduza o bug localmente e capture o log completo do Laravel (`storage/logs/laravel.log`) e o console do navegador (Network tab) durante o momento exato do login, prestando atenção à requisição que retorna 404 (URL solicitada, método, headers `X-Inertia`).
2. Identifique a causa raiz entre as hipóteses acima (pode ser mais de uma).
3. Aplique a correção necessária no código (controller, rotas, `app.js`, ou configuração de cache).
4. Adicione um teste automatizado (Feature test) que faça login via `postJson` ou helper do Inertia e valide que a resposta redireciona corretamente para a rota do dashboard com status esperado (302 seguido de 200, ou verificação do header `X-Inertia-Location` quando aplicável).
5. Documente no README, se relevante, algum passo adicional necessário para evitar cache de rotas obsoleto em ambiente Docker (ex.: rodar `route:clear` no entrypoint do container).

## Critério de aceite
- Após login bem-sucedido, o usuário é redirecionado automaticamente para o dashboard, **sem** exibição de erro 404 em nenhum momento, mesmo em navegação puramente via Inertia (sem refresh manual).
- O teste automatizado criado cobre esse cenário e falha caso a regressão volte a ocorrer.
