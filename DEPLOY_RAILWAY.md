# Deploiement Laravel sur Railway

## 1) Service Web
- Connecte ce repo GitHub a Railway.
- Railway utilisera `railway.json` / `Procfile` pour demarrer l'app.

## 2) Base de donnees
- Ajoute un service `MySQL` dans le meme projet Railway.
- Laisse Railway generer les variables MySQL.

## 3) Variables d'environnement (service web)
- `APP_NAME=Notre Archive`
- `APP_ENV=production`
- `APP_DEBUG=false`
- `APP_URL=https://${{RAILWAY_PUBLIC_DOMAIN}}`
- `APP_KEY=base64:...` (genere localement avec `php artisan key:generate --show`)
- `DB_CONNECTION=mysql` (important: en minuscule)
- `DB_HOST=${{MySQL.MYSQLHOST}}`
- `DB_PORT=${{MySQL.MYSQLPORT}}`
- `DB_DATABASE=${{MySQL.MYSQLDATABASE}}`
- `DB_USERNAME=${{MySQL.MYSQLUSER}}`
- `DB_PASSWORD=${{MySQL.MYSQLPASSWORD}}`
- `SESSION_DRIVER=database`
- `CACHE_STORE=database`
- `QUEUE_CONNECTION=database`
- `FILESYSTEM_DISK=public`

## 4) Commandes a lancer apres le 1er deploy
Depuis le shell Railway du service web:

```bash
php artisan migrate --force
php artisan storage:link
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## 5) Point important pour les fichiers uploades
Les fichiers dans `storage/app/public` ne sont pas garantis persistants entre redemarrages/deploiements sur un conteneur stateless.

Pour une vraie production:
- utilise S3/Cloudflare R2 (recommande), ou
- monte un volume persistant Railway et adapte la strategie de stockage.

## 6) Optionnel: service Worker
Si tu utilises les files de jobs Laravel, cree un 2eme service Railway (meme repo) avec:

```bash
php artisan queue:work --sleep=1 --tries=3 --timeout=120
```
