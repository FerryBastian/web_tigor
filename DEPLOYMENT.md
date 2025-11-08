# Deployment Guide

## Prerequisites

- PHP >= 8.2
- Composer
- MySQL >= 5.7 atau MariaDB >= 10.3
- Node.js >= 20.19 atau >= 22.12
- NPM

## Production Deployment Steps

### 1. Clone Repository

```bash
git clone <repository-url>
cd web_tigor
```

### 2. Install Dependencies

```bash
composer install --optimize-autoloader --no-dev
npm install
npm run build
```

### 3. Environment Configuration

```bash
cp .env.example .env
php artisan key:generate
```

Edit `.env` file:
- Set `APP_ENV=production`
- Set `APP_DEBUG=false`
- Configure database credentials
- Set `APP_URL` to your domain

### 4. Database Setup

```bash
php artisan migrate --force
php artisan db:seed --class=DatabaseSeeder
```

### 5. Optimize Application

```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### 6. Set Permissions

```bash
chmod -R 755 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

### 7. Web Server Configuration

#### Apache (.htaccess sudah tersedia)

Pastikan mod_rewrite diaktifkan.

#### Nginx

```nginx
server {
    listen 80;
    server_name your-domain.com;
    root /path/to/web_tigor/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

## Security Checklist

- [ ] Set `APP_DEBUG=false` in production
- [ ] Use strong database passwords
- [ ] Configure proper CORS if using API from different domain
- [ ] Set up SSL/HTTPS
- [ ] Configure firewall rules
- [ ] Regular backups of database
- [ ] Keep dependencies updated

## Maintenance Commands

```bash
# Clear all caches
php artisan optimize:clear

# Re-optimize
php artisan optimize

# Run migrations
php artisan migrate

# Check application status
php artisan about
```

