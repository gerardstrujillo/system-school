# 💻 Comandos Exactos que Railway Ejecuta

## 🎯 Respuesta Directa a tu Pregunta

**¿Cuál es la línea de comandos que ejecuta Railway?**

```bash
# FASE 1: BUILD
composer install --no-dev
npm install
npm run build

# FASE 2: RELEASE (migraciones)
php artisan migrate --force
php artisan db:seed --force

# FASE 3: START (iniciar app)
php artisan serve --host=0.0.0.0 --port=$PORT
```

**Esos comandos se ejecutan automáticamente**, tú no tienes que escribirlos.

---

## 📝 Archivo que Define los Comandos: `Procfile`

Contenido del `Procfile` (que creé en tu proyecto):

```
web: php -S 0.0.0.0:${PORT:-8000} -t public
release: php artisan migrate --force && php artisan db:seed --force
```

**Traducción:**
- Línea 1: "Para iniciar la app, ejecuta: `php -S` (servidor PHP) en puerto PORT"
- Línea 2: "Antes de iniciar, ejecuta migraciones y seed"

---

## 🔄 Secuencia Exacta de Ejecución

### PASO 1: Build Phase

```bash
# Railway detecta que es un proyecto PHP
# Automáticamente ejecuta:

$ composer install --no-dev
  ✅ Instala todas las librerías de composer.json
  
$ npm install  
  ✅ Instala todas las librerías de package.json
  
$ npm run build
  ✅ Compila CSS (Tailwind), JavaScript, etc.
  
# Total: ~1-2 minutos
```

**Configurado en:** `railway.json` (builder: nixpacks)

### PASO 2: Release Phase (Pre-start)

```bash
# Ejecuta lo que está en Procfile → release:

$ php artisan migrate --force
  ✅ Crea todas las tablas en Supabase
  ✅ --force porque es producción
  
$ php artisan db:seed --force
  ✅ Llena las tablas con datos de prueba
  ✅ Crea usuario admin, estudiantes, etc.
  
# Total: ~10-30 segundos
```

**Configurado en:** `Procfile` (línea: release:)

### PASO 3: Start Phase (App online)

```bash
# Ejecuta lo que está en Procfile → web:

$ php -S 0.0.0.0:${PORT:-8000} -t public
  ✅ Inicia servidor PHP
  ✅ Escucha en puerto 8000 (Railway lo redirige a 443 HTTPS)
  ✅ Sirve archivos desde carpeta /public
  ✅ App está VIVA
  
# Total: 10 segundos (permanente)
```

**Configurado en:** `Procfile` (línea: web:)

---

## 🎯 Ejemplo Visual: Cada Línea de Comando

### Build Phase - Desglosado

```bash
┌─ composer install --no-dev
│  └─ Lee composer.json
│  └─ Descarga: laravel/framework, barryvdh/laravel-dompdf, etc.
│  └─ Instala en vendor/
│  └─ Genera autoloader
│
├─ npm install
│  └─ Lee package.json
│  └─ Descarga dependencias JS
│  └─ Instala en node_modules/
│
└─ npm run build
   └─ Ejecuta: npm run build (ver scripts en package.json)
   └─ Compila Tailwind CSS
   └─ Compila Vite
   └─ Genera public/build/
```

### Release Phase - Desglosado

```bash
┌─ php artisan migrate --force
│  ├─ Lee database/migrations/*.php
│  ├─ Crea tabla 'users'
│  ├─ Crea tabla 'students'
│  ├─ Crea tabla 'grades'
│  └─ ... (todas las migraciones)
│
└─ php artisan db:seed --force
   ├─ Lee database/seeders/*.php
   ├─ Crea usuario admin (email: admin@example.com)
   ├─ Crea 5 estudiantes de prueba
   └─ ... (todos los seeds)
```

### Start Phase - Desglosado

```bash
└─ php -S 0.0.0.0:${PORT:-8000} -t public
   ├─ Inicia servidor PHP built-in
   ├─ Escucha en 0.0.0.0:8000 (todas las interfaces)
   ├─ Raíz del servidor: /public
   ├─ Redirige todas las requests a /public/index.php
   ├─ index.php carga Laravel
   ├─ Laravel router determina qué hacer
   └─ Responde al cliente
```

---

## 🌊 Flujo Completo con Comandos Bash

```
LOCAL PUSH
┌─────────────────┐
│ git push origin │
│     main        │
└────────┬────────┘
         │
         ▼
GITHUB WEBHOOK
┌──────────────────────┐
│ GitHub notifica a    │
│ Railway que hay      │
│ cambios nuevos       │
└────────┬─────────────┘
         │
         ▼
RAILWAY INICIA BUILD
┌────────────────────────────────────────┐
│ $ git clone git@github.com:.../repo.git│
│ $ cd /app                              │
└────────┬───────────────────────────────┘
         │
         ▼
BUILD PHASE
┌────────────────────────────────────────┐
│ $ composer install --no-dev            │
│ $ npm install                          │
│ $ npm run build                        │
│                                        │
│ [Espera 1-2 minutos]                   │
└────────┬───────────────────────────────┘
         │
         ▼
RELEASE PHASE (Pre-start)
┌────────────────────────────────────────┐
│ $ php artisan migrate --force          │
│ $ php artisan db:seed --force          │
│                                        │
│ [Espera 10-30 segundos]                │
└────────┬───────────────────────────────┘
         │
         ▼
START PHASE
┌────────────────────────────────────────┐
│ $ php -S 0.0.0.0:8000 -t public        │
│                                        │
│ [APP ONLINE] 🚀                        │
│ https://tuapp.railway.app              │
└────────────────────────────────────────┘
```

---

## 📊 Comandos por Tipo

### Comandos BASH que Railway ejecuta:

```bash
composer install --no-dev
npm install
npm run build
php -S 0.0.0.0:8000 -t public
```

### Comandos PHP (Artisan) que Railway ejecuta:

```bash
php artisan migrate --force
php artisan db:seed --force
```

### Flags importantes:

- `--no-dev` → No instala dependencias de desarrollo
- `--force` → En producción, confirma automáticamente
- `--host=0.0.0.0` → Escucha en todas las interfaces
- `-t public` → Raíz del servidor es /public

---

## 🔍 Dónde ver EXACTAMENTE qué comandos se ejecutaron

### Railway Panel:

1. Ve a [railway.app](https://railway.app)
2. Abre tu proyecto
3. Vé a **Deployments**
4. Click en [tu deployment]
5. Pestaña **Logs**

Verás algo como:

```
[Build] Installing dependencies...
> $ composer install --no-dev
Installing laravel/framework (v11.0.1)
✓ composer done

[Build] Installing npm...
> $ npm install
added 150 packages
✓ npm done

[Build] Building assets...
> $ npm run build
building...
✓ build done

[Release] Running migrations...
> $ php artisan migrate --force
Migration: 2024_01_15_create_users_table
✓ Migration success

[Release] Seeding database...
> $ php artisan db:seed --force
Seeder: UserSeeder
✓ Seeding done

[Start] Starting application...
> $ php -S 0.0.0.0:8000 -t public
Listening on http://0.0.0.0:8000
✓ APP ONLINE
```

---

## 🎯 Resumen: Qué Escribes vs Qué Escribe Railway

### TÚ escribes (en tu computadora):

```bash
git push origin main
```

### Railway ejecuta automáticamente:

```bash
composer install --no-dev
npm install
npm run build
php artisan migrate --force
php artisan db:seed --force
php -S 0.0.0.0:8000 -t public
```

**Total: 6 comandos, 0 intervención tuya**

---

## ❓ Preguntas Frecuentes

**P: ¿Dónde escribo estos comandos?**
> A: En `Procfile` (ya lo hice) + `railway.json`

**P: ¿Por qué --force en migrate?**
> A: Porque en producción no hay interacción humana, confirma automáticamente

**P: ¿Y si quiero cambiar los comandos?**
> A: Edita `Procfile` y `railway.json`, luego `git push`

**P: ¿Cómo ejecuto comandos extra después del deploy?**
> A: Usa Railway CLI: `railway run php artisan cache:clear`

**P: ¿Qué pasa si un comando falla?**
> A: Railway detiene el deploy, ves error en Logs, haces fix y repushes

---

## 🚀 Tu checklist hoy:

- [ ] Entiendes que son comandos AUTOMÁTICOS
- [ ] No necesitas ejecutar nada en "terminal de cloud"
- [ ] Solo tienes que `git push`
- [ ] Railway hace el resto
- [ ] Los comandos están definidos en Procfile
- [ ] Ves los logs en Railway Panel

---

**¿Listo para el primer deploy?** 🎬

Solo necesitas:
1. Cambios en tu código
2. `git push origin main`
3. Esperar 3-5 minutos
4. ¡LIVE! 🚀
