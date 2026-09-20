# wir-gegen-papier.de

Laravel MVP for the campaign site `wir-gegen-papier.de`.

## Campaign Core

Claim:

```text
Digital, wenn ich will.
```

The site asks for a recipient right to receive invoices and comparable
documents digitally when the recipient wants that. It does not demand a general
paper ban.

## Local Setup

```bash
composer install
cp .env.example .env
php artisan key:generate
php artisan serve
```

Open:

```text
http://localhost:8000
```

No Vite build is required for the MVP. The landing page uses inline CSS in the
Blade view.

## Production Deployment

Production host:

```text
ai
```

Repository checkout:

```text
/var/www/wir-gegen-papier
```

Apache document root:

```text
/var/www/wir-gegen-papier/public
```

The production `.env` file must exist only on the server and must not be
committed.

Required production values:

```text
APP_NAME="Wir gegen Papier"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://wir-gegen-papier.de
APP_LOCALE=de
APP_FALLBACK_LOCALE=de
SESSION_DRIVER=file
CACHE_STORE=file
QUEUE_CONNECTION=sync
```

Deployment update:

```bash
cd /var/www/wir-gegen-papier
git pull --ff-only
composer install --no-dev --optimize-autoloader
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

Writable runtime directories:

```text
storage/
bootstrap/cache/
```

## DNS And TLS

DNS is hosted in Hetzner DNS.

Current target:

```text
wir-gegen-papier.de      A 94.130.7.235
www.wir-gegen-papier.de  A 94.130.7.235
```

HTTPS is provided by Let's Encrypt on the AI server.

## Legal

The footer links to the existing imprint:

```text
https://magnetix.cologne/
```
