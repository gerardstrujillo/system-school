# ⚡ Comandos de Deploy - Comparativa Local vs Railway

## 🎯 Resumen Ejecutivo

```
LOCAL (Tu computadora)          RAILWAY (Cloud automático)
═══════════════════════════     ════════════════════════════
php artisan migrate       →     Ejecuta automáticamente
php artisan db:seed       →     Ejecuta automáticamente  
php artisan serve         →     Ejecuta automáticamente
↓
Accedes a localhost:8000        Accedes a https://tuapp.railway.app
```

**En Railway, tú solo haces: `git push`**

---

## 📋 Secuencia de Comandos Automáticos

### Railway ejecuta en este ORDEN:

```bash
# 1️⃣ BUILD PHASE (instalación de dependencias)
composer install --no-dev
npm install
npm run build
# [Duración: ~1-2 min]

# 2️⃣ RELEASE PHASE (preparación de BD)
php artisan migrate --force
# [Duración: ~10-30 seg]

# 3️⃣ START PHASE (iniciar la aplicación)
php artisan serve --host=0.0.0.0 --port=$PORT
# [Duración: permanente hasta redeploy]
```

**Total: 2-3 minutos**

---

## 🔧 Dónde se Configuran estos Comandos

### En el archivo `Procfile` (que creé):

```procfile
web: php -S 0.0.0.0:${PORT:-8000} -t public
release: php artisan migrate --force && php artisan db:seed --force
```

**Explicación:**
- `web:` → Comando que se ejecuta cuando la app inicia
- `release:` → Comando que se ejecuta ANTES de web (migraciones)

### En el archivo `railway.json` (que creé):

```json
{
  "build": {
    "builder": "nixpacks"
  },
  "deploy": {
    "startCommand": "php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=$PORT"
  }
}
```

**Explicación:**
- `builder: nixpacks` → USA Nixpacks para detectar lenguaje
- `startCommand` → Ejecuta migraciones + inicia app

---

## 📊 Tabla: Local vs Railway

| Acción | Local | Railway | Comando |
|---|---|---|---|
| **Instalar Composer** | Tú ejecutas | Automático | `composer install` |
| **Instalar npm** | Tú ejecutas | Automático | `npm install` |
| **Build assets** | Tú ejecutas | Automático | `npm run build` |
| **Migraciones BD** | Tú ejecutas | Automático | `php artisan migrate` |
| **Seed BD** | Tú ejecutas | Automático | `php artisan db:seed` |
| **Iniciar servidor** | Tú ejecutas | Automático | `php artisan serve` |
| **Acceder app** | localhost:8000 | URL de Railway | https://tuapp.railway.app |

---

## 🚀 Flujo de Deployment paso a paso

### Tu acción (LOCAL):
```bash
# 1. Cambias archivo (ej: app/Models/User.php)

# 2. Pruebas localmente
php artisan serve
# http://localhost:8000 ✅

# 3. Subes a GitHub
git add .
git commit -m "Cambios de producción"
git push origin main
# [Listo, tu parte terminó]
```

### Acciones automáticas (RAILWAY):

```
[1] Webhook de GitHub → Railway
    ↓
[2] Railway descarga código
    ↓
[3] DETECT ENVIRONMENT
    └─ Detecta: PHP, Composer, npm
    
[4] BUILD PHASE
    ├─ $ composer install --no-dev
    ├─ $ npm install
    └─ $ npm run build
    
[5] RELEASE PHASE (Procfile: release:)
    └─ $ php artisan migrate --force
    └─ $ php artisan db:seed --force
    
[6] START PHASE (Procfile: web:)
    └─ $ php -S 0.0.0.0:PORT -t public
    
[7] URL LIVE
    └─ https://tuapp.railway.app ✅
```

---

## 🎯 Comandos que TÚ NECESITAS Ejecutar

### En desarrollo (LOCAL):

```bash
# Una vez al clonar el repo
composer install
npm install

# Cada vez que cambias código
php artisan serve              # Ver cambios en http://localhost:8000

# Opcional: Resetear BD local
php artisan migrate:refresh --seed

# Antes de subir a GitHub
git status                      # Ver cambios
git add .                       # Preparar cambios
git commit -m "Descripción"     # Guardar cambios
git push origin main            # Subir a GitHub
```

### En Railway (AUTOMÁTICO):

```
NO EJECUTAS NADA
(Railway lo hace automáticamente cuando ve un push)
```

---

## ⚠️ Si Necesitas Ejecutar Comandos en Railway

### Situación 1: Deploy fallido, necesitas debug

**Opción A: Ver logs del deploy**
```
Railway Panel → Deployments → [tu deploy] → Logs
```

**Opción B: Usar Railway CLI**
```bash
# En tu computadora
npm install -g @railway/cli
railway login
railway run php artisan migrate --force
railway run php artisan cache:clear
```

### Situación 2: Cambiar BD después de estar en vivo

```bash
# Con Railway CLI:
railway run php artisan migrate:rollback
railway run php artisan migrate
```

### Situación 3: Ver datos en BD

```bash
# Opción A: Supabase Panel
https://supabase.com → Tu proyecto → Table Editor

# Opción B: Con Tinker en Railway
railway run php artisan tinker
>>> App\Models\User::count()
```

---

## 📈 Ejemplo Real: Ciclo Completo

### Día 1: Primer Deploy

**Local:**
```bash
git clone https://github.com/tuuser/Sistema-Notas-Colegiooo
cd Sistema-Notas-Colegiooo
composer install
npm install && npm run build
php artisan key:generate
# Configurar .env con Supabase
php artisan migrate --seed
php artisan serve
# Probar en http://localhost:8000
git add .
git push origin main
```

**Railway:**
```
1. Detecta push
2. composer install
3. npm build
4. php artisan migrate
5. php artisan serve
6. ✅ APP LIVE en https://tuapp.railway.app
```

### Día 2: Cambio de código

**Local:**
```bash
# Cambias algo, ej: archivo.php
php artisan serve
# Verificas en http://localhost:8000
git add .
git commit -m "Cambio en validación"
git push origin main
```

**Railway:**
```
1. Detecta push
2. Build
3. Migraciones (si hay nuevas)
4. Start
5. ✅ APP ACTUALIZADA
```

---

## 🎓 Conceptos Clave

### Builder (Nixpacks)
- Detecta el lenguaje (PHP)
- Instala dependencias automáticamente
- Es parte del `railroad.json`

### Procfile
- Define comandos para Railway/Heroku
- `web:` = comando principal
- `release:` = pre-start script

### Release Phase
- Se ejecuta UNA VEZ antes de iniciar
- Perfecto para migraciones
- Para de varias líneas: `&&`

---

## ✅ Checklist: ¿Está todo configurado?

- [ ] Archivo `Procfile` existe en raíz
- [ ] Archivo `railway.json` existe en raíz
- [ ] `.env` tiene credenciales Supabase
- [ ] Hiciste `git push` a main
- [ ] Railway Panel muestra "Deployment in progress"
- [ ] Esperas 3-5 min
- [ ] Ves URL LIVE en Railway
- [ ] Accedes y ves tu app

---

## 🆘 Troubleshooting: Comandos que Fallaron

| Falla | Comando que Falló | Solución |
|---|---|---|
| `composer install` falló | Build Phase | Verifica `composer.json` localmente |
| `npm run build` falló | Build Phase | Verifica `package.json` y dependencias |
| `migrate --force` falló | Release Phase | Verifica credenciales BD en Variables |
| App no inicia | Start Phase | Verifica logs en Railway Panel |

---

## 📞 Resumen Final

**¿Qué comandos ejecutas?**
```
LOCAL:
- composer install (una vez)
- npm run build (una vez)
- php artisan migrate (desarrollo)
- php artisan serve (desarrollo)
- git push (cada cambio)

RAILWAY:
- Nada, todo automático
```

**¿Dónde se configuran comandos?**
```
→ Procfile (release: y web:)
→ railway.json (startCommand:)
```

**¿Cuándo se ejecutan?**
```
→ Cada vez que haces git push
→ Automáticamente sin intervención
→ Ver progreso en Railway Panel
```

---

**¿Listo para hacer tu primer deploy? 🚀**
