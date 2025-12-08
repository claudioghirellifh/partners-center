## Partners Center

Partners Center é uma central SaaS para gestão de parceiros, canais e alianças estratégicas. O sistema é multiempresa (cada empresa possui uma URI própria no formato `APP_URL/{uri}`), multilíngue e conta com três níveis de acesso:

- Root: usuário global que enxerga todas as empresas, define configurações gerais e cria novos admins.
- Admin: dono da empresa; gerencia apenas a sua empresa, podendo criar novos Admins e equipes parceiras.
- Equipes de parceria (Sellers no modelo atual): cuidam da operação diária com parceiros, acordos e integrações.

Cada empresa personaliza URI, identidade visual (logo, favicon, duas cores principais) e idioma de interface, garantindo uma experiência alinhada à marca da rede de parceiros.

---

## Stack

- **Backend:** PHP 8.3, Laravel 12, Livewire 3.
- **Frontend:** Vite 7 + Tailwind CSS 4 (via `@tailwindcss/vite`).
- **Banco:** SQLite (MVP).
- **Servidor local:** seu servidor web (ex.: Nginx) ou `php artisan serve`. Exemplo de `APP_URL`: `http://local.partners`.
- **Node.js:** ≥ 22.12.0 recomendado para evitar avisos do Vite.

---

## Pré-requisitos

- PHP 8.3 e Composer instalados.
- Node.js ≥ 22.12.0 (ou compatível com Vite 7 / Tailwind 4).
- Extensões PHP: `pdo_sqlite`, `mbstring`, `openssl`, `json`, `ctype`, `tokenizer`.

---

## Instalação (desenvolvimento)

1. Clonar o repositório:
   ```bash
   git clone <repo> PartnersCenter
   cd PartnersCenter
   ```
2. Instalar dependências PHP:
   ```bash
   composer install
   ```
3. Copiar o `.env` (se necessário):
   ```bash
   cp .env.example .env
   ```
4. Garantir configuração SQLite no `.env`:
    ```env
    DB_CONNECTION=sqlite
    DB_DATABASE=database/database.sqlite
    APP_URL=http://local.partners
    APP_LOCALE=pt_BR
    APP_FALLBACK_LOCALE=en
    ADMIN_ROOT_PATH=adminroot
    ```
5. Criar o arquivo do banco e executar migrações:
   ```bash
   touch database/database.sqlite
   php artisan migrate
   ```
6. Instalar dependências do frontend:
   ```bash
   npm install
   ```

---

## Execução

- **Aplicação web:** acesse a URL definida em `APP_URL` (ex.: `http://local.partners`).
- **Hot reload (Vite):**
  ```bash
  npm run dev
  ```
- **Build de produção:**
  ```bash
  npm run build
  ```
- **Comandos Artisan:**
  ```bash
  php artisan migrate
  ```

### Uploads e Storage (importante)

- Para servir imagens enviadas (logo/favicon) via `public/storage`, crie o symlink:
  ```bash
  php artisan storage:link
  ```
- Garanta que `APP_URL` no `.env` seja `http://local.partners` para que `Storage::url()` gere URLs corretas.

---

## Painel Root

- Caminho configurável via `ADMIN_ROOT_PATH` (padrão `/adminroot`).
- Criar ou atualizar o usuário Root:
  ```bash
  docker exec -it php83 bash -lc "cd /var/www/PartnersCenter && php artisan root:create-user"
  ```
- Acessar o painel através de `http://local.partners/<ADMIN_ROOT_PATH>`.
- Ao autenticar, o painel apresenta layout com menu lateral (modo escuro) e dashboard inicial pronto para evoluir com indicadores.

### Empresas (CRUD)

- Acesse: `Painel Root` → menu `Empresas`.
- Criar empresa exige:
  - Nome, URI (slug sem espaços, ex.: `catus` → `http://local.partners/catus`), idioma (`pt-BR`, `en`, `es-AR`).
  - Status (Ativa ou Suspensa). Empresas suspensas bloqueiam o login de todos os usuários vinculados até nova ativação.
  - Logo (qualquer extensão, até 1MB) e favicon (`.ico`, `.png` ou `.svg`).
  - Admin inicial (nome e e-mail). Ele recebe e-mail com instruções de acesso contendo login e senha temporária.
- Editar empresa permite reenvio de logo/favicon e mostra prévias das imagens atuais, além de links rápidos da URI pública e do login admin.
- O login dos parceiros fica disponível diretamente em `http://local.partners/{uri}` (com formulário de acesso estilizado com logo/cor da empresa).
- Menu `Planos`: crie e edite planos SaaS com preço mensal e anual. Esses valores serão usados em ofertas futuras.
- Menu `Integrações` → Iugu: informe o API token para futuras cobranças automáticas.
- Botão “Sincronizar planos” importa planos existentes na Iugu para o catálogo local (necessita o token configurado).

---

## Painel Admin (empresa)

- Rota: `http://local.partners/{uri}/admin`. Cada empresa acessa com seus próprios administradores.
- Visão geral com indicadores iniciais e acesso rápido às ações.
- Gestão de administradores (`/{uri}/admin/admins`): criar novos acessos, suspender/reativar e atualizar dados. Convites enviam e-mail com logo, favicon e cor da empresa.
- Usuários suspensos (ou empresas suspensas) não conseguem autenticar até nova ativação.
- Root pode acionar o modo “Entrar como empresa” a partir do painel `adminroot`, navegando diretamente pelo ambiente da companhia (banner de aviso permite retornar ao painel Root).
- Planos configurados no painel Root servem como catálogo-base de valores (mensal e anual) para comercialização.
- Módulo `/{uri}/admin/projects`: cadastrar clientes/projetos vinculados a um plano específico, escolhendo ciclo mensal ou anual.
  - Status de instalação (informativo, exibido na edição/listagem):
    - `installation_requested` — Solicitação de Instalação
    - `installing` — Em Instalação
    - `installation_cancelled` — Instalação Cancelada
    - `installation_done` — Instalação Concluída

---

## Estrutura multiempresa e idiomas

- Cada empresa define uma URI dedicada (ex.: `http://local.partners/catus`).
- O tenant é identificado via segmento da URI (`/{company}`) e resolvido antes das rotas admin.
- Idiomas suportados serão configuráveis por empresa. O padrão global é `pt_BR` com fallback `en`.

---

## E-mail (Mailtrap)

- Configuração recomendada para desenvolvimento no `.env` (não comitar credenciais):
  ```env
  MAIL_MAILER=smtp
  MAIL_HOST=sandbox.smtp.mailtrap.io
  MAIL_PORT=2525
  MAIL_USERNAME=<seu-usuario>
  MAIL_PASSWORD=<sua-senha>
  MAIL_ENCRYPTION=tls
  MAIL_FROM_ADDRESS=no-reply@local.partners
  MAIL_FROM_NAME="${APP_NAME}"
  ```
- Ao criar uma empresa, o Admin recebe um e-mail "Bem-vindo(a) como Admin" com:
  - URL de login: `http://local.partners/{uri}/admin/login`
  - E-mail e senha temporária.
- Se estiver usando filas, lembre-se de rodar o worker:
  ```bash
  php artisan queue:work
  ```

---

## Próximos recursos planejados

- Suporte opcional a tenants por host (Nginx + domínios dedicados).
- Gestão de identidade visual e idioma por empresa.
- Fluxos completos para Admins e equipes de parceria (criação, permissões, programas e indicadores).

---

## Comandos úteis

- `php artisan migrate` — Executa migrações.
- `php artisan migrate:fresh` — Reinicia o banco SQLite.
- `php artisan route:list` — Lista rotas cadastradas.
- `php artisan root:create-user` — Cria ou atualiza o usuário Root.
- `npm run dev` / `npm run build` — Compilação Tailwind/Vite.

---

## Convenções do projeto

- Código segue PSR-12 e recursos nomeados em inglês; interface do usuário e documentação em pt-BR.
- Componentes interativos usarão Livewire 3.
- Banco SQLite deve permanecer versionado apenas com a estrutura (arquivo vazio `database.sqlite`); dados sensíveis não devem ser commitados.

---

## Contato e suporte

- Registre issues e pull requests seguindo o fluxo interno da empresa.
- Para dúvidas rápidas, consulte o documento `DEV_NOTES.md` ou abra uma nova sessão com o assistente indicando esse histórico.
