# 🚀 Guía de Deployment - Sistema Notas Colegio

## Tabla de Contenidos
1. [Preparación Local](#preparación-local)
2. [Railway + Supabase (Recomendado)](#railway--supabase-recomendado)
3. [Render.com + Neon](#rendercom--neon)
4. [Configuración de Dominio](#configuración-de-dominio)
5. [Monitoreo y Mantenimiento](#monitoreo-y-mantenimiento)

---

## 📦 Preparación Local

### 1. Instalar dependencias locales

```bash
# Instalar dependencias PHP
composer install --no-dev

# Instalar dependencias frontend
npm install
npm run build
```

### 2. Preparar archivo `.env` para producción

```bash
cp .env.example .env
php artisan key:generate
```

**Variables clave a configurar:**

```env
APP_ENV=production
APP_DEBUG=false
APP_URL=https://tudominio.com

# Base de datos (se cambia después según el proveedor)
DB_CONNECTION=pgsql
DB_HOST=tu-host-db.com
DB_PORT=5432
DB_DATABASE=tubd
DB_USERNAME=usuario
DB_PASSWORD=contraseña

# Mail (opcional, puedes usar Mailtrap o SendGrid)
MAIL_MAILER=smtp
MAIL_HOST=smtp.mailtrap.io
MAIL_PORT=465
MAIL_USERNAME=tu_username
MAIL_PASSWORD=tu_password

# Storage (para archivos, usa S3 o local)
FILESYSTEM_DISK=local
# O si usas S3:
# FILESYSTEM_DISK=s3
# AWS_ACCESS_KEY_ID=...
# AWS_SECRET_ACCESS_KEY=...
# AWS_DEFAULT_REGION=us-east-1
# AWS_BUCKET=...

SESSION_DRIVER=database
CACHE_STORE=database
QUEUE_CONNECTION=database
```

### 3. Crear archivo `.gitignore` (ya debe existir)

Asegúrate que incluya:
```
.env
.env.local
node_modules/
vendor/
*.log
storage/logs/*
bootstrap/cache/*
```

### 4. Preparar base de datos para migraciones

En producción se corre automáticamente:
```bash
php artisan migrate --force
php artisan db:seed --class=DatabaseSeeder
```

---

## 🚂 Railway + Supabase (Recomendado)

### ✅ Por qué Railway es la mejor opción:
- **500 horas/mes GRATIS** (suficiente para una app mediana)
- Soporte nativo para Laravel
- Fácil de desplegar con Git
- Base de datos incluida (pero usaremos Supabase por mejor precio)
- Escalable con solo aumentar recursos

### Paso 1: Crear base de datos en Supabase

1. Ve a [supabase.com](https://supabase.com)
2. Crea cuenta y nuevo proyecto
3. Espera a que se cree (2-3 minutos)
4. En el panel, ve a **Settings → Database**
5. Copia la **Connection String (URI)** - será algo como:
   ```
   postgresql://postgres:[PASSWORD]@db.xxx.supabase.co:5432/postgres
   ```

### Paso 2: Crear aplicación en Railway

1. Ve a [railway.app](https://railway.app)
2. Crea cuenta (con GitHub es más fácil)
3. Crea nuevo proyecto → "Deploy from GitHub"
4. Conecta tu repositorio de GitHub
5. Railway detectará que es Laravel automáticamente

### Paso 3: Configurar variables de entorno en Railway

En el panel de Railway, ve a **Variables**:

```
APP_ENV=production
APP_DEBUG=false
APP_KEY=base64:XXXXXXXXXXX (generado con php artisan key:generate)
APP_URL=https://tuapp.railway.app (o tu dominio)

DB_CONNECTION=pgsql
DB_HOST=db.xxx.supabase.co
DB_PORT=5432
DB_DATABASE=postgres
DB_USERNAME=postgres
DB_PASSWORD=tu_contraseña_supabase

MAIL_MAILER=log (o configurar SMTP después)
SESSION_DRIVER=database
CACHE_STORE=database
QUEUE_CONNECTION=database
```

### Paso 4: Ejecutar migraciones

En Railway:
1. Ve a **Deployments**
2. Busca el build actual
3. En la sección de logs, verifica que no haya errores
4. Ejecuta manualmente (en Railway CLI o en el panel):
   ```bash
   php artisan migrate --force
   php artisan db:seed --force
   ```

### Paso 5: Esperar deployment

Railway hace build automático desde tu repo y deploy en 2-5 minutos.

---

## 🎨 Render.com + Neon

### ✅ Ventajas de Render:
- **750 horas/mes GRATIS** (mucho más que Railway)
- PostgreSQL gratis en Neon
- Excelente para desarrollo/MVP
- Build automático desde GitHub

### Paso 1: Crear BD en Neon

1. Ve a [neon.tech](https://neon.tech)
2. Crea proyecto
3. Copia el **Connection String** (similar a Supabase)

### Paso 2: Desplegar en Render

1. Ve a [render.com](https://render.com)
2. New → Web Service
3. Conecta tu repo de GitHub
4. Configuración:
   - **Runtime:** Python (ejecutará comandos custom)
   - **Build Command:**
     ```bash
     composer install && npm install && npm run build
     ```
   - **Start Command:**
     ```bash
     php artisan migrate --force && php -S 0.0.0.0:8000 -t public
     ```

### Paso 3: Variables de entorno

Igual al paso anterior (Railway), pero con los datos de Neon en lugar de Supabase.

---

## 🌐 Configuración de Dominio

### Opción A: Usar dominio gratuito

- **Freenom:** .tk, .ml, .ga gratis (no recomendado para producción)
- **Vercel/Netlify:** Dan subdominio gratis

### Opción B: Comprar dominio barato
- **Namecheap:** ~$1-5/año
- **Google Domains:** ~$12/año
- **Porkbun:** ~$2-8/año

### Configurar DNS en Railway/Render

1. En tu proveedor de dominio, ve a **DNS**
2. Añade registro CNAME:
   ```
   Tipo: CNAME
   Nombre: www
   Valor: [el que te da Railway/Render]
   TTL: 3600
   ```
3. Para raíz (@), añade A record hacia la IP de Railway/Render

---

## 📊 Monitoreo y Mantenimiento

### Comandos útiles en producción

```bash
# Ver logs
railway logs  # o en panel de Render

# Ejecutar migraciones
php artisan migrate --force

# Limpiar caché
php artisan cache:clear
php artisan config:clear

# Generar optimizaciones
php artisan optimize

# Ver estado de queue
php artisan queue:failed
```

### Backups de BD

**Supabase:**
- Panel → Backups (automático diario)

**Neon:**
- Panel → Backups (automático)

### Monitoreo de errores

Opción 1: **Sentry** (gratis 5000 eventos/mes)
```env
SENTRY_LARAVEL_DSN=https://xxxxx@yyyyy.ingest.sentry.io/zzzz
```

Opción 2: **Bugsnag** (gratis 50 errores/día)

Opción 3: **Logs de Railway/Render** (incluidos)

---

## 🔒 Checklist de Seguridad

- [ ] `APP_DEBUG=false` en producción
- [ ] `APP_ENV=production`
- [ ] Usar HTTPS (Railway/Render lo hacen automático)
- [ ] Contraseñas BD fuertes (Supabase/Neon las generan)
- [ ] Firewall de BD configurado
- [ ] `.env` no en Git
- [ ] Backups automáticos activados
- [ ] CORS configurado si tienes API
- [ ] Rate limiting en rutas públicas

---

## ❓ Troubleshooting

### Error: "SQLSTATE[HY000]: General error"
→ Ejecuta: `php artisan migrate --force`

### Error: "No database available"
→ Verifica las credenciales de BD en `.env`

### Error: "Application key missing"
→ Ejecuta: `php artisan key:generate` localmente y copia a Railway

### Storage de archivos no funciona
→ Cambia `FILESYSTEM_DISK=s3` en `.env` o usa volumen en Railway

---

## 📚 Enlaces útiles

- [Documentación Railway](https://docs.railway.app/guides/laravel)
- [Documentación Render](https://render.com/docs)
- [Supabase Laravel](https://supabase.com/docs/guides/database/connecting-to-postgres)
- [Neon PostgreSQL](https://neon.tech/docs/introduction)
- [Laravel Deployment](https://laravel.com/docs/deployment)

---

**¿Preguntas? Déjame saber en qué paso necesitas más ayuda.**
