# SmartBiz Setup Instructions

## 📋 System Requirements

### Minimum Requirements
- **PHP**: 8.2 or higher
- **Node.js**: 18.0 or higher
- **Database**: MySQL 8.0+ / MariaDB 10.3+
- **Composer**: 2.0 or higher
- **npm/yarn**: Latest version
- **Web Server**: Apache 2.4+ or Nginx 1.18+

### Recommended Requirements
- **PHP**: 8.3
- **Node.js**: 20.0+
- **Database**: MySQL 8.0+ / MariaDB 10.11+
- **RAM**: 4GB minimum, 8GB recommended
- **Storage**: 10GB minimum, 50GB recommended

## 🚀 Installation Guide

### Step 1: Clone the Repository

```bash
# Clone from GitHub
git clone https://github.com/your-username/smartbiz.git
cd smartbiz

# Or download and extract the ZIP file
# Navigate to the project directory
```

### Step 2: Install Backend Dependencies

```bash
# Install PHP dependencies
composer install

# If you encounter memory issues, use:
composer install --memory-limit=2G
```

### Step 3: Install Frontend Dependencies

```bash
# Install Node.js dependencies
npm install

# Or using yarn
yarn install
```

### Step 4: Environment Configuration

```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate

# (Optional) Generate encryption keys for passport
php artisan passport:keys
```

### Step 5: Configure Environment Variables

Edit the `.env` file with your specific settings:

```env
# Application Settings
APP_NAME=SmartBiz
APP_ENV=local
APP_KEY=base64:your-generated-key
APP_DEBUG=true
APP_URL=http://localhost:8000

# Database Configuration
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=smartbiz
DB_USERNAME=your_username
DB_PASSWORD=your_password

# Mail Configuration (Optional)
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls

# Filesystem
FILESYSTEM_DISK=local

# Cache & Session
CACHE_DRIVER=database
SESSION_DRIVER=database
SESSION_LIFETIME=120

# Queue
QUEUE_CONNECTION=database
```

### Step 6: Database Setup

#### Option A: Create Database Manually

```sql
CREATE DATABASE smartbiz CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'smartbiz_user'@'localhost' IDENTIFIED BY 'your_password';
GRANT ALL PRIVILEGES ON smartbiz.* TO 'smartbiz_user'@'localhost';
FLUSH PRIVILEGES;
```

#### Option B: Use phpMyAdmin/Database Tool

1. Create a new database named `smartbiz`
2. Create a user with full privileges
3. Update `.env` with the database credentials

### Step 7: Run Database Migrations

```bash
# Run migrations to create tables
php artisan migrate

# Seed the database with demo data
php artisan db:seed

# Or do both in one command
php artisan migrate:fresh --seed
```

### Step 8: Build Frontend Assets

```bash
# Development build (with hot reload)
npm run dev

# Production build (optimized)
npm run build

# Preview production build
npm run preview
```

### Step 9: Start Development Servers

#### Option A: Separate Terminals

```bash
# Terminal 1: Start Laravel server
php artisan serve

# Terminal 2: Start Vite dev server
npm run dev
```

#### Option B: Single Terminal (Background)

```bash
# Start Laravel server in background
php artisan serve --host=127.0.0.1 --port=8000 &

# Start Vite dev server
npm run dev
```

### Step 10: Access the Application

- **Frontend**: http://localhost:8000
- **API Endpoints**: http://localhost:8000/api
- **API Documentation**: http://localhost:8000/api/docs

## 🔧 Development Setup

### IDE Configuration

#### VS Code Extensions (Recommended)
```json
{
  "recommendations": [
    "bmewburn.vscode-intelephense-client",
    "Vue.volar",
    "bradlc.vscode-tailwindcss",
    "formulahendry.auto-rename-tag",
    "christian-kohler.path-intellisense",
    "ms-vscode.vscode-json"
  ]
}
```

#### VS Code Settings
```json
{
  "php.suggest.basic": false,
  "intelephense.files.maxSize": 5000000,
  "intelephense.completion.fullyQualifyGlobalConstantsAndFunctions": false,
  "volar.takeover": true
}
```

### Database Tools

#### Recommended GUI Tools
- **TablePlus** (macOS/Windows/Linux)
- **Sequel Pro** (macOS)
- **HeidiSQL** (Windows)
- **DBeaver** (Cross-platform)

### Testing Setup

```bash
# Install testing dependencies
composer require --dev phpunit/phpunit

# Run tests
php artisan test

# Run specific test
php artisan test --filter AppointmentTest

# Generate code coverage
php artisan test --coverage
```

## 🌍 Production Deployment

### Server Requirements

#### Web Server Configuration

**Apache (.htaccess)**
```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteCond %{REQUEST_URI} !^/public/
    RewriteRule ^(.*)$ /public/$1 [L]
</IfModule>
```

**Nginx**
```nginx
server {
    listen 80;
    server_name your-domain.com;
    root /path/to/smartbiz/public;
    index index.php index.html;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }
}
```

### Production Deployment Steps

```bash
# 1. Clone repository to server
git clone https://github.com/your-username/smartbiz.git /var/www/smartbiz

# 2. Install dependencies
cd /var/www/smartbiz
composer install --no-dev --optimize-autoloader
npm install --production

# 3. Set permissions
chown -R www-data:www-data /var/www/smartbiz
chmod -R 755 /var/www/smartbiz/storage
chmod -R 755 /var/www/smartbiz/bootstrap/cache

# 4. Configure environment
cp .env.example .env
php artisan key:generate

# 5. Run migrations
php artisan migrate --force

# 6. Cache configuration
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 7. Build assets
npm run build

# 8. Set up queue worker (if using queues)
php artisan queue:work --daemon
```

### Environment Optimization

```env
# Production .env settings
APP_ENV=production
APP_DEBUG=false
APP_URL=https://your-domain.com

# Cache settings
CACHE_DRIVER=redis
SESSION_DRIVER=redis
QUEUE_CONNECTION=redis

# Log settings
LOG_CHANNEL=daily
LOG_LEVEL=warning

# Performance
OPCACHE_ENABLE=1
```

## 🔍 Troubleshooting

### Common Issues

#### 1. Composer Memory Issues
```bash
# Increase memory limit
php -d memory_limit=2G /usr/local/bin/composer install

# Or use composer preload
composer install --preload
```

#### 2. Node.js Version Issues
```bash
# Check Node.js version
node --version

# Use nvm to switch versions
nvm use 18
nvm use 20
```

#### 3. Database Connection Issues
```bash
# Test database connection
php artisan tinker
>>> DB::connection()->getPdo();

# Check database credentials
php artisan config:cache
php artisan config:clear
```

#### 4. Permission Issues
```bash
# Fix storage permissions
sudo chown -R www-data:www-data storage bootstrap/cache
sudo chmod -R 775 storage bootstrap/cache
```

#### 5. Vite Build Issues
```bash
# Clear node modules and reinstall
rm -rf node_modules package-lock.json
npm install

# Clear Vite cache
npm run build -- --mode production
```

### Debug Mode

```bash
# Enable debug mode
APP_DEBUG=true

# Clear all caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Check logs
tail -f storage/logs/laravel.log
```

### Performance Issues

```bash
# Optimize autoloader
composer dump-autoload --optimize

# Cache configuration
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Check database queries
php artisan db:show
```

## 📞 Support Resources

### Documentation
- **Laravel Documentation**: https://laravel.com/docs
- **Vue.js Documentation**: https://vuejs.org/guide
- **Tailwind CSS**: https://tailwindcss.com/docs

### Community
- **Laravel Forums**: https://laracasts.com/discuss
- **Vue.js Discord**: https://discord.gg/vue
- **Stack Overflow**: Tag with `laravel` and `vuejs`

### Professional Support
- **Laravel Partners**: https://laravel.com/partners
- **Vue.js Consultants**: https://vuejs.org/support
- **SmartBiz Support**: support@smartbiz.app

---

**Need help? Check our troubleshooting guide or reach out to our support team!**
