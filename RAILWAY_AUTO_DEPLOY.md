# 🚀 Deploy Automático en Railway - Guía Completa

## 🎯 Cómo Funciona Railway

Railway automatiza TODO. Tú solo subes código a GitHub, y Railway:

```
1. Detecta cambios en GitHub
2. Hace un BUILD (instala dependencias)
3. Ejecuta comandos de BUILD
4. Ejecuta comando de RELEASE (migraciones)
5. INICIA la app
6. ¡Listo!
```

**No necesitas terminal, Railway lo hace solo.**

---

## 📋 Archivos de Configuración que Crée

Ya creé los archivos necesarios en tu proyecto:

### 1. `railway.json` (PRINCIPAL para Railway)

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

**Esto le dice a Railway:**
- `build` → Instala PHP, Composer, npm
- `startCommand` → Ejecuta migraciones + inicia servidor

### 2. `Procfile` (ALTERNATIVA para Render/Heroku)

```
web: php -S 0.0.0.0:${PORT:-8000} -t public
release: php artisan migrate --force && php artisan db:seed --force
```

**Líneas:**
- `web` → Comando para iniciar la app
- `release` → Comando que corre ANTES de iniciar

### 3. `.env` Variables en Railway

Railway necesita saber dónde está la BD, credenciales, etc.

---

## 🔧 Comandos que Railway Ejecuta Automáticamente

### Build Phase (automático)

```bash
# Railway detecta que es Laravel y ejecuta:
composer install --no-dev
npm install
npm run build
```

**Tú no haces nada** - Railway lo detecta.

### Release Phase (automático)

```bash
# Antes de iniciar la app, ejecuta:
php artisan migrate --force
php artisan db:seed --force
```

**Configurado en `Procfile` o `railway.json`**

### Start Phase (automático)

```bash
# Railway inicia la app con:
php artisan serve --host=0.0.0.0 --port=$PORT
```

---

## 📊 Flujo Completo de Deploy en Railway

```
┌──────────────────────────────────────┐
│ 1. Haces push a GitHub               │
│    git push origin main              │
└────────────┬─────────────────────────┘
             │
┌────────────▼─────────────────────────┐
│ 2. Railway detecta el cambio          │
│    (webhook automático)               │
└────────────┬─────────────────────────┘
             │
┌────────────▼─────────────────────────┐
│ 3. FASE BUILD (automática)            │
│    ├─ Clona repo                      │
│    ├─ composer install                │
│    ├─ npm install && npm run build    │
│    └─ [Sin intervención tuya]         │
└────────────┬─────────────────────────┘
             │
┌────────────▼─────────────────────────┐
│ 4. FASE RELEASE (automática)          │
│    ├─ php artisan migrate --force     │
│    ├─ php artisan db:seed --force     │
│    └─ [Configurado en Procfile]       │
└────────────┬─────────────────────────┘
             │
┌────────────▼─────────────────────────┐
│ 5. FASE START (automática)            │
│    ├─ php artisan serve               │
│    ├─ Escucha en puerto $PORT         │
│    └─ ¡APP LIVE! 🚀                   │
└──────────────────────────────────────┘
```

---

## ⚡ Lo que TÚ Haces

### Paso 1: Preparar código localmente

```bash
# En tu computadora:
cd ~/Sistema-Notas-Colegiooo

# Asegúrate que .env esté configurado
cat .env | grep DB_CONNECTION
# Debe ser: pgsql

# Verifica que funciona localmente
php artisan migrate
php artisan db:seed
php artisan serve
# Abre http://localhost:8000 y verifica
```

### Paso 2: Subir a GitHub

```bash
# En tu computadora:
git add .
git commit -m "Configurar para Supabase"
git push origin main
# (o master, depende de tu rama)
```

### Paso 3: Railway hace el resto

```
AUTOMÁTICO - No haces nada

Railway ve el push → Build → Release → Start → ✅ LIVE
```

### Paso 4: Ver resultado

1. Ve a [railway.app](https://railway.app)
2. Abre tu proyecto
3. Ve a **Deployments**
4. Espera a que termine (2-5 min)
5. Click en **URL** → Ve tu app funcionando

---

## 🎛️ Panel de Railway - Dónde ver TODO

### Logs de Build (si falla el build)
```
Railway Panel → Deployments → [tu deploy] → Logs
```

Aquí ves:
- ✅ composer install OK
- ✅ npm run build OK
- ✅ migraciones OK
- ❌ errores si los hay

### Logs de Runtime (si la app falla)
```
Railway Panel → Project → [contenedor] → Logs
```

Aquí ves:
- Requests HTTP
- Errores de la app
- Debug info

### Variables de Entorno
```
Railway Panel → [proyecto] → Variables
```

Aquí configuras:
```
DB_CONNECTION=pgsql
DB_HOST=aws-0-us-east-2.pooler.supabase.com
DB_PORT=6543
DB_DATABASE=postgres
DB_USERNAME=postgres.ohofjvljqhyysnyzemuo
DB_PASSWORD=supabase.com
APP_URL=https://tuapp.railway.app
```

---

## 🖥️ Usar Terminal en Railway (SI LO NECESITAS)

Si por alguna razón necesitas ejecutar comandos DESPUÉS del deploy:

### Opción A: Railway CLI (desde tu computadora)

```bash
# Instalar Railway CLI
npm install -g @railway/cli

# Loguéate
railway login

# Ejecutar comando en el servidor
railway run php artisan cache:clear
railway run php artisan config:clear
railway run php artisan migrate --force
```

### Opción B: Panel de Railway

1. Railway Panel → [tu proyecto] → [contenedor]
2. Click en la pestaña **Commands** o **Deployment Shell**
3. Ejecuta comandos directamente

---

## 🔄 Workflow Completo

```
DESARROLLO LOCAL          PRODUCTION (Railway)
└─ Cambias código   →     Subes a GitHub
└─ Pruebas local    →     Railway detecta push
└─ git push         →     Build automático
                    →     Migraciones automáticas
                    →     App en vivo
```

**No hay terminal que ejecutes en Railway**, todo es automático.

---

## ⚠️ Si Algo Falla en Deploy

### Error en BUILD
```
Ver logs → Railway Panel → Deployments → Logs
Errores comunes:
- ❌ composer install falla
- ❌ npm run build falla
→ Verifica composer.json y package.json localmente
```

### Error en RELEASE
```
Ver logs → Rails Panel → Logs
Errores comunes:
- ❌ php artisan migrate falla
- ❌ Conexión a BD fallida
→ Verifica credenciales en Variables
```

### Error en START
```
Ver logs
Errores comunes:
- ❌ Puerto no disponible
- ❌ Memoria insuficiente
→ Aumenta recursos en Railway Plan
```

---

## 📝 Comandos Clave en tu Proyecto

Estos ya están configurados en `Procfile` y se ejecutan AUTOMÁTICAMENTE:

```bash
# BUILD (automático cuando detecta cambios)
composer install --no-dev

# RELEASE (automático antes de iniciar)
php artisan migrate --force
php artisan db:seed --force

# START (automático después de release)
php artisan serve --host=0.0.0.0 --port=$PORT
```

**Tú NO los escribes en terminal - Railway lo hace.**

---

## 🎯 Checklist de Deploy a Railway

- [ ] Código en GitHub (rama `main` o `master`)
- [ ] Archivo `.env` tiene credenciales de Supabase
- [ ] `Procfile` está en la raíz (ya creado ✅)
- [ ] `railway.json` está en la raíz (ya creado ✅)
- [ ] Proyecto creado en [railway.app](https://railway.app)
- [ ] Conectaste GitHub a Railway
- [ ] Variables de entorno añadidas en Railway
- [ ] Haces `git push` a main
- [ ] Esperas 3-5 minutos
- [ ] ¡Accedes a tu URL!

---

## 🚀 Resumen: Qué Haces vs Qué Hace Railway

### TÚ haces:
```bash
1. Cambias código localmente
2. Pruebas: php artisan serve
3. Subes: git push origin main
4. ¡Listo!
```

### Railway hace:
```
1. Detecta cambio en GitHub (automático)
2. composer install (automático)
3. npm build (automático)
4. php artisan migrate (automático)
5. php artisan serve (automático)
6. ¡APP LIVE! 🎉
```

---

## 📞 Preguntas Frecuentes

**P: ¿Necesito ejecutar `php artisan migrate` en Railway?**
> A: No, está en el Procfile. Se ejecuta automáticamente cada deploy.

**P: ¿Y si necesito ejecutar migrate manualmente?**
> A: Usa Railway CLI: `railway run php artisan migrate --force`

**P: ¿Cómo evito que se ejecute `db:seed` en producción?**
> A: Quita `--seed` del Procfile (ya está sin él en release)

**P: ¿Cada cuánto redeploy?**
> A: Solo cuando haces `git push`. Puedes triggerearlo manualmente en Railway Panel.

**P: ¿Cómo veo errores si falla?**
> A: Railway Panel → Deployments → Logs

---

## 🎓 Conceptos Clave

**Procfile:**
- Archivo que dice qué comando ejecutar para iniciar
- `web:` → comando para iniciar app
- `release:` → comando antes de iniciar

**railway.json:**
- Alternativa más específica para Railway
- Define builder (cómo buildear)
- Define startCommand

**Build vs Release vs Start:**
- Build = Instalar dependencias
- Release = Migraciones, setup
- Start = Iniciar servidor

---

## 🎬 Próximos Pasos

1. **Hoy:**
   ```bash
   git add .
   git commit -m "Add Procfile and railway.json"
   git push origin main
   ```

2. **En Railway Panel:**
   - Crea proyecto
   - Conecta GitHub
   - Añade variables `.env`

3. **Espera 3-5 min → ¡Live! 🚀**

---

**¿Preguntas sobre el deploy automático?**

Estoy aquí para aclarar cualquier duda.
