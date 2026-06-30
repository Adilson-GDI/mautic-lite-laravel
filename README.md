# Mautic Lite Laravel

Sistema leve de e-mail marketing feito em Laravel, com painel administrativo para gerenciar campanhas, contatos, listas, provedores de envio e rastreamento de engajamento.

O projeto nasceu como uma alternativa simples para operacoes que precisam disparar campanhas, controlar listas e acompanhar eventos essenciais sem a complexidade de uma plataforma maior.

## Recursos

- Dashboard com resumo de provedores, contatos e mensagens.
- Cadastro de provedores de envio.
- Suporte a Google Workspace/SMTP e AWS SES.
- Cadastro e importacao de contatos via CSV.
- Organizacao de contatos em listas.
- Criacao e gerenciamento de campanhas.
- Geracao de mensagens por campanha.
- Processamento de mensagens pendentes.
- Controle de status: pendente, enviada, aberta, clicada, falha, bounce e cancelada.
- Tracking de abertura, clique e descadastro.
- Tela de descadastro por token.
- Interface administrativa com o template SB Admin 2.

## Stack

- PHP 8.2+
- Laravel 12
- MySQL ou MariaDB
- Queue do Laravel
- Vite
- Bootstrap / SB Admin 2

## Instalacao

Clone o repositorio:

```bash
git clone https://github.com/SEU-USUARIO/mautic-lite-laravel.git
cd mautic-lite-laravel
```

Instale as dependencias PHP:

```bash
composer install
```

Instale as dependencias front-end:

```bash
npm install
```

Crie o arquivo de ambiente:

```bash
cp .env.example .env
```

Gere a chave da aplicacao:

```bash
php artisan key:generate
```

Configure o banco de dados no `.env`:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=mautic_lite
DB_USERNAME=root
DB_PASSWORD=
```

Execute as migrations:

```bash
php artisan migrate
```

Compile os assets:

```bash
npm run build
```

Inicie o servidor local:

```bash
php artisan serve
```

Acesse:

```text
http://127.0.0.1:8000/admin/email
```

## Configuracao de URL

Em ambiente local:

```env
APP_URL=http://127.0.0.1:8000
```

Se o projeto estiver publicado em um subdiretorio, por exemplo:

```text
https://salvaimerainha.org.br/vox-mautic
```

configure:

```env
APP_URL=https://salvaimerainha.org.br/vox-mautic
```

Depois limpe o cache:

```bash
php artisan config:clear
php artisan route:clear
php artisan cache:clear
php artisan view:clear
```

## Rotas principais

```text
/admin/email
/admin/email/providers
/admin/email/contacts
/admin/email/lists
/admin/email/campaigns
```

Rotas publicas de tracking:

```text
/email/track/open/{token}
/email/track/click/{token}
/email/unsubscribe/{token}
```

## Processamento de envios

O projeto usa jobs e fila para processar mensagens. Em desenvolvimento, rode:

```bash
php artisan queue:work
```

Tambem existe uma acao no painel para processar mensagens pendentes.

## Deploy em Nginx

Para Laravel, o `root` do servidor deve apontar para a pasta `public`.

Exemplo em dominio dedicado:

```nginx
root /caminho/do/projeto/mautic-lite-laravel/public;
```

Se for instalar em subdiretorio junto de outro site, configure um `location` especifico para a aplicacao e envie as rotas para o `public/index.php` do Laravel.

## Template administrativo

Este projeto usa o template SB Admin 2, localizado em:

```text
public/startbootstrap-sb-admin-2-gh-pages
```

O layout principal fica em:

```text
resources/views/layouts/admin.blade.php
```

## Estrutura importante

```text
app/Http/Controllers/EmailMarketing
app/Jobs/EmailMarketing
app/Models/EmailMarketing
app/Services/EmailMarketing
resources/views/email-marketing
routes/web.php
database/migrations
```

## Licenca

Este projeto segue a licenca MIT.

## Status

Projeto em desenvolvimento. Use, adapte e evolua conforme a necessidade da sua operacao de e-mail marketing.
