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

Required mail values for supporter double opt-in on `ai`:

```text
MAIL_MAILER=sendmail
MAIL_SENDMAIL_PATH="/usr/sbin/sendmail -bs -i"
MAIL_FROM_ADDRESS=kontakt@wir-gegen-papier.de
MAIL_FROM_NAME="Wir gegen Papier"
```

Use the local production mail transport convention on `ai`; do not commit
mail secrets or `.env`.

Deployment update:

```bash
cd /var/www/wir-gegen-papier
git pull --ff-only
composer install --no-dev --optimize-autoloader
php artisan migrate --force
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

## Supporter Double Opt-In

Supporters are stored in the `supporters` table.

Flow:

1. A visitor submits an email address on the landing page.
2. The address is normalized and stored with `pending` status.
3. A cryptographically random confirmation token is sent by email.
4. Only the SHA-256 token hash is stored.
5. The supporter is counted publicly only after a valid confirmation link is
   opened before expiry.
6. A confirmed supporter can request a separate unsubscribe email and confirm
   the withdrawal link.

Status values:

```text
pending
confirmed
unsubscribed
```

Consent version:

```text
supporter-v1
```

The supporter double opt-in is not a newsletter consent. If a newsletter is
added later, it needs a separate consent text and a separate double opt-in.
