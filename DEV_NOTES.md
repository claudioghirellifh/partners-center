## DEV NOTES — Partners Center

### Sessão 001 (bootstrap do projeto)
- **Objetivo:** Inicializar o projeto Laravel 12 com Livewire 3, Tailwind + Vite, documentação e git.
- **Resultado:** Projeto criado, dependências instaladas, README/DEV_NOTES adicionados, `.env` configurado para SQLite.

### Decisões e convenções iniciais
- **Papéis:** Root (global), Admin (dono da empresa), equipes de parceria (modeladas como `Sellers` no código atual).
- **Multiempresa:** Tenant identificado pelo segmento da URI (`/{company}`); suporte a domínios dedicados fica como evolução futura.
- **Internacionalização:** `locale` selecionado por empresa; padrão `pt_BR`, fallback `en`.
- **Banco:** SQLite (`database/database.sqlite`) durante o MVP.
- **Front-end:** Tailwind CSS 4 + Vite 7; compilação via container `nodejs`.
- **Execução local:** Servidor web pelo container `nginx` (`http://local.partners`); comandos PHP via `php83`.
- **Código:** PSR-12; UI e documentação em pt-BR por padrão.

### Notas operacionais
- Executar comandos PHP/Artisan dentro do container `php83` (`docker exec -it php83 bash -lc "cd /var/www/PartnersCenter && <comando>"`).
- Compilação/front-end: usar container `nodejs` (`docker exec -it nodejs bash -lc "cd /var/www/PartnersCenter && npm run dev"`).
- Ao configurar novas empresas, basta informar a URI (slug). Futuro suporte a domínios dedicados exigirá ajustes de DNS/hosts.
- Node.js ≥ 22.12.0 elimina avisos de engine do Vite/Laravel Vite Plugin.

### Sessão 002 (painel Root)
- **Objetivo:** Disponibilizar console Root acessível via `/adminroot` com autenticação dedicada.
- **Entrega:** 
  - Middleware `auth.root`, guard e variáveis de ambiente configuráveis.
  - Comando `php artisan root:create-user` para criar/atualizar usuário Root (prompts interativos).
  - Fluxo de login/logout com validações e dashboard inicial (layout dark com menu lateral).
  - Migração para campos `role` e `is_active` em `users`, testes de autenticação e do comando.

### Sessão 003 (dark/light manual com Livewire — Tailwind v4)
- **Problema:** `dark:` não surtia efeito. No Tailwind v4, a variante `dark` não é ativada automaticamente só com config; é necessário registrá-la via CSS com `@custom-variant` quando usando a estratégia por classe.
- **Solução aplicada:**
  - CSS: `resources/css/app.css`
    - Adicionado `@custom-variant dark (&:where(.dark, .dark *));` logo após `@import 'tailwindcss';`.
    - Adicionado `@layer base { :root { color-scheme: light; } .dark { color-scheme: dark; } }` para coerência de elementos nativos.
  - Livewire: criado componente `app/Livewire/ThemeToggle.php` e view `resources/views/livewire/theme-toggle.blade.php` para alternar o tema manualmente (sem preferência do sistema), persistindo em sessão e disparando evento `theme-updated`.
  - Layouts: `resources/views/adminroot/layouts/app.blade.php` e `resources/views/adminroot/layouts/guest.blade.php` passam a definir `<html class="... {{ session('theme')==='dark' ? 'dark' : '' }}">` e recebem o toggle no header.
  - Views ajustadas para `dark:`: login e dashboard usam variantes de cores claras/escura.
  - Tailwind config: `tailwind.config.js` presente com `darkMode: 'class'` (opcional em v4, mantido por clareza); conteúdo mapeado.
- **Passo operacional importante:** reiniciar o Vite (`npm run dev`) após inserir `@custom-variant`, pois o processo precisa reprocessar o CSS.
- **Critérios de aceite:**
  - Clique no toggle alterna classe `dark` em `<html>` e atualiza cores de fundos/textos/bordas nos cartões/inputs.
  - Preferências de sistema não são consideradas; apenas a sessão controla o tema.

---

## Containers & comandos (cheat sheet)

- PHP / Artisan — container `php83`:
  - `docker exec -it php83 bash -lc "cd /var/www/PartnersCenter && php -v"`
  - `docker exec -it php83 bash -lc "cd /var/www/PartnersCenter && composer install"`
  - `docker exec -it php83 bash -lc "cd /var/www/PartnersCenter && php artisan migrate"`
  - `docker exec -it php83 bash -lc "cd /var/www/PartnersCenter && php artisan root:create-user"`
  - `docker exec -it php83 bash -lc "cd /var/www/PartnersCenter && php artisan route:list"`
  - Cache/config (sem tocar no banco):
    - `docker exec -it php83 bash -lc "cd /var/www/PartnersCenter && php artisan optimize:clear"`

- Frontend (npm/yarn) — container `nodejs`:
  - `docker exec -it nodejs bash -lc "cd /var/www/PartnersCenter && npm ci"`
  - `docker exec -it nodejs bash -lc "cd /var/www/PartnersCenter && npm run dev"` (manter aberto durante o dev)
  - `docker exec -it nodejs bash -lc "cd /var/www/PartnersCenter && npm run build"`

- MySQL — container `mysql` (usar apenas se `DB_CONNECTION=mysql`; hoje o projeto usa SQLite):
  - `docker exec -it mysql bash -lc "mysql -uroot -p$MYSQL_ROOT_PASSWORD -e 'SHOW DATABASES;'"`
  - Dump/restore (exemplos): `mysqldump` / `mysql` dentro do container.

### Sessão e tema
- O tema (light/dark) é persistido em sessão (servidor) e aplicado via classe `dark` no `<html>`.
- Para “resetar” o tema, basta sair/entrar ou limpar o cookie `laravel_session` no navegador. Não é necessário nenhum comando no servidor.
- Em caso de comportamento estranho de cache, execute apenas `optimize:clear` no `php83`. Evitar `migrate:fresh`/`db:wipe` no desenvolvimento cotidiano.

### Sessão 004 (Empresas — CRUD + admin inicial)
- **Entrega:** CRUD de empresas no painel Root (index/create/edit/delete) com uploads de logo/favicon (disk `public`) e criação automática do Admin inicial (senha temporária e e‑mail de instruções).
- **Arquivos-chave:**
  - Modelo/Migrações: `app/Models/Company.php`, `2025_10_17_100000_create_companies_table.php`, `2025_10_17_100100_add_company_id_to_users_table.php`.
  - Controller/Requests: `CompanyController`, `CompanyStoreRequest`, `CompanyUpdateRequest`.
  - Views: `resources/views/adminroot/companies/*`.
- **Notas:**
  - `storage:link` obrigatório para servir imagens; usar `Storage::disk('public')->url(...)`.
  - Form de edição exibe prévia visual e links rápidos da URI (`APP_URL/{uri}` e `/admin/login`).

### Sessão 005 (Branding e assets)
- **Cor da marca:** adicionada coluna `brand_color` em `companies` e pickers nos forms; normalização `#RRGGBB`.
- **Cor padrão do produto:** laranja `#F27327` substituiu acentos anteriores.
- **Logos/Favicon via Vite:** imagens em `resources/images/root/`; inclusão garantida no build com `import.meta.glob('../images/root/*', { eager: true, import: 'default', query: '?url' })` em `resources/js/brand-assets.js`.
- **Requisito Node:** Vite 7 exige Node 20.19+ ou 22.12+; atualizar ambiente (22.3 é insuficiente).

### Sessão 006 (Usuários — gestão no Root)
- **Listagem:** tela de usuários com empresa, papel e status; exclusão com proteções (não excluir root, nem a si próprio).
- **Edição:** alterar nome/e‑mail/status. Troca de e‑mail (não‑root) reenvia instruções com senha temporária.
- **UX:** botões de excluir em index e na página de edição; correção de forms aninhados (delete separado do update).

### Sessão 007 (Admin por empresa — login + reset)
- **Resolução por URI:** middleware `ResolveCompanyFromUri` (alias `tenant`).
- **Proteção admin:** middleware `EnsureCompanyAdmin` (alias `auth.company`) — garante `role=admin`, `is_active` e `company_id` correspondente.
- **Rotas:** sob `/{company}/admin` (URI) — login/logout/dashboard + fluxo de esqueci minha senha.
- **Views:** layouts/login/dashboard com branding (logo, cor, favicon) e dark/light.
- **Esqueci minha senha:** fluxo de reset com e‑mail customizado de acordo com a empresa.
  - Notificação: `App\Notifications\AdminResetPassword` usa `route('admin.password.reset', [...])`.
  - E‑mail de reset com branding em `resources/views/emails/admin_password_reset.blade.php` (força light mode).

### Sessão 008 (Tenancy por URI + ajustes de onboarding)
- **Objetivo:** substituir a dependência de domínio próprio por URIs em formato slug e atualizar fluxos correlatos.
- **Entrega:**
  - Campo `uri` nos `companies` (migração original ajustada) e chave de roteamento baseada nesse slug.
  - Middleware `ResolveCompanyFromUri` compartilhando a empresa com controllers/views e forçando locale.
  - Rotas admin sob `/{company}/admin/*` com `scopeBindings`.
  - Rota pública `/{company}` apontando para o formulário de login customizado (usa logo, favicon e cor cadastrados).
  - CRUD da empresa (views + requests) validando a URI e exibindo prévias das URLs.
  - Flag `is_active` em empresas e usuários permitindo suspender/reactivar acessos pelo painel Root.
  - E-mails/notificações e dashboard root atualizados para usar `route()` com a URI apropriada.
- **Notas:** Preparado para futura evolução com domínios dedicados, mantendo slug obrigatório durante o MVP.

### Sessão 009 (Admin da empresa — gestão de admins)
- **Objetivo:** permitir que administradores de uma empresa convidem e gerenciem outros admins com branding próprio.
- **Entrega:**
  - Rotas `/{company}/admin/admins/*` com controlador dedicado (`Admin\AdminUserController`).
  - Requests específicos (`Admin\AdminUserStoreRequest` / `AdminUserUpdateRequest`) garantindo unicidade de e-mail e controle de status.
  - Views no painel admin (`resources/views/admin/admins/*`) para listar, criar e editar, incluindo suspensão/reativação com proteção para não auto-suspender.
  - E-mails de boas-vindas reutilizam `AdminWelcomeMail`, aplicando logo/cor da empresa nos convites.
  - Navegação do painel admin atualizada com o item “Administradores”.
- **Notas:** usuários suspensos (ou empresas suspensas) são bloqueados pelo middleware `ResolveCompanyFromUri`/`EnsureCompanyAdmin`.

### Sessão 010 (Impersonation Root → Empresa)
- **Objetivo:** permitir que o Root entre rapidamente no painel de uma empresa para suporte/diagnóstico.
- **Entrega:**
  - Serviço `ImpersonationManager` e tabela `company_impersonations` para registrar quem iniciou o modo.
  - Botão “Entrar como” no `adminroot.companies.index` que autentica um admin ativo da empresa e redireciona para `/{uri}/admin`.
  - Banner persistente no layout do painel admin avisando o modo, com ação de “Voltar ao painel Root”.
  - Rotas `/adminroot/companies/{company}/impersonate` e `/adminroot/impersonation/leave` protegidas por `auth.root`.
- **Notas:** sessão root permanece válida; apenas o guard `web` é alternado. Empresas suspensas ou sem admins ativos geram mensagem de erro.

### Sessão 011 (Planos SaaS)
- **Objetivo:** centralizar a gestão de planos (mensal/anual) no painel Root.
- **Entrega:**
  - Tabela `plans` com campos de preço mensal, anual e descrição.
  - CRUD completo (`AdminRoot\PlanController`) com validações dedicadas e paginação.
  - Views no painel Root (`resources/views/adminroot/plans/*`) e item de menu “Planos”.
- **Notas:** os valores mensal/anual servem de catálogo base para comercialização.

### Sessão 012 (Projetos / Clientes por empresa)
- **Objetivo:** permitir que cada empresa registre seus clientes/projetos vinculados aos planos existentes.
- **Entrega:**
  - Tabela `projects` (company_id, plan_id, nome, ciclo mensal/anual, início e notas).
  - CRUD (`Admin\ProjectController`) em `/{company}/admin/projects`, com validação e paginação.
  - Views `resources/views/admin/projects/*` e item de menu “Projetos” no painel admin.
- **Notas:** ciclo é literal (monthly/annual); futuras regras de faturamento podem usar esses dados. Projetos são isolados por empresa.

### Sessão 013 (Integração Iugu — credenciais)
- **Objetivo:** armazenar o API token da Iugu para uso futuro em cobranças.
- **Entrega:**
  - Tabela `settings` e repositório `SettingRepository` para persistir pares grupo/chave.
  - Tela `adminroot/integrations` com formulário para salvar o token Iugu.
  - Menu “Integrações” no painel Root.
- **Notas:** valor salvo como texto simples; etapas futuras incluirão validação via API e funções de billing.

### Sessão 014 (Sincronização de planos com Iugu)
- **Objetivo:** manter o catálogo local em sincronia com os planos da Iugu e vice-versa.
- **Entrega:**
  - Serviço `IuguClient` encapsulando chamadas REST (listar/criar/atualizar/deletar planos).
  - Botão “Sincronizar planos” em `adminroot/plans` que importa os planos existentes na Iugu.
  - Criação/edição/remoção de planos passa a refletir na Iugu (identificador salvo em `plans.iugu_identifier`).
- **Notas:** requisições falham graciosamente quando o token não está configurado; `price_cents` usa o preço mensal e anual é derivado localmente.

### Observações operacionais recentes
- Rodar migrações novas: `php artisan migrate` (campo `uri` em companies, brand_color, FK cascade users→companies).
- Mailtrap no `.env` para testes de e-mail (SMTP). Evitar commitar credenciais.
- Evitar forms aninhados: manter formulários de delete fora dos formulários de update.

### Pendências registradas
- Documentar passo a passo da configuração de Nginx e hosts locais quando o tenant estiver ativo.
- Implementar gestão de identidade visual e idioma por empresa.
- Implementar fluxo completo de Admins e equipes de parceria (cadastro, permissões, dashboards).

### Histórico
- **001** — Bootstrap inicial e documentação.
- **002** — Console Root (autenticação, comando artisan, layout dashboard).
- **003** — Tema dark/light manual com Livewire e Tailwind v4.
- **004** — CRUD de empresas + admin inicial.
- **005** — Branding e assets com Vite.
- **006** — Gestão de usuários no Root.
- **007** — Autenticação admin por empresa (login/reset).
- **008** — Tenancy por URI + ajustes de onboarding.
- **009** — Gestão de admins por empresa.
- **010** — Impersonation Root → Empresa.
- **011** — Planos SaaS no Root.
- **012** — Projetos/Clientes por empresa.
- **013** — Integração Iugu (token).
- **014** — Sincronização de planos com Iugu.
- **015** — Versão/changelog no painel admin, modal clicável com notas/avisos, badge de não lido no navegador e CRUD de release notes no Root.
