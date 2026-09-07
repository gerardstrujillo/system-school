# ⚡ Checklist Rápido - Desplegar en Railway

## Paso 1: Preparar el código (5 minutos)

- [ ] Asegúrate que tu código esté en GitHub
- [ ] Revisa que no haya archivos `.env` en Git (verifica `.gitignore`)
- [ ] Ejecuta localmente:
  ```bash
  composer install
  npm install && npm run build
  php artisan key:generate
  ```

## Paso 2: Crear BD en Supabase (3 minutos)

1. Ve a [supabase.com](https://supabase.com) → Sign Up
2. Crea nuevo proyecto
3. Espera a que se cree
4. Ve a **Settings → Database → Connection String**
5. Copia la URI (postgres://...)

```
📋 Guarda estos datos:
- Host: db.xxx.supabase.co
- Port: 5432
- Database: postgres
- User: postgres
- Password: [tu_contraseña]
```

## Paso 3: Desplegar en Railway (5 minutos)

1. Ve a [railway.app](https://railway.app) → Sign Up (con GitHub es fácil)
2. **New Project → Deploy from GitHub**
3. Selecciona tu repositorio
4. Railway detecta que es Laravel automáticamente
5. En **Variables**, añade:

```env
APP_ENV=production
APP_DEBUG=false
APP_KEY=base64:XXXXXXXXXXXX (copia la que generaste localmente)
APP_URL=https://[tu-app].railway.app

DB_CONNECTION=pgsql
DB_HOST=db.xxx.supabase.co
DB_PORT=5432
DB_DATABASE=postgres
DB_USERNAME=postgres
DB_PASSWORD=[tu_contraseña_supabase]

MAIL_MAILER=log
SESSION_DRIVER=database
CACHE_STORE=database
QUEUE_CONNECTION=database
```

6. **Deploy** (espera 2-5 minutos)

## Paso 4: Ejecutar migraciones (2 minutos)

Opción A: En Railway CLI:
```bash
railway run php artisan migrate --force --seed
```

Opción B: En panel Railway:
- Ve a tu proyecto
- Click en el contenedor
- Terminal → ejecuta el comando anterior

## Paso 5: Verificar que funciona ✅

- Ve a `https://[tu-app].railway.app`
- Deberías ver tu app Laravel funcionando
- Login con credenciales del seeder

## Paso 6: Conectar dominio propio (10 minutos) - OPCIONAL

### Opción A: Comprar dominio barato
1. Ve a [Namecheap](https://namecheap.com) (~$3/año)
2. Compra tu dominio
3. Ve a tu dominio → **Advanced DNS**
4. Añade CNAME:
   ```
   Type: CNAME
   Host: www
   Value: [tu-app].railway.app
   TTL: 3600
   ```

### Opción B: Usar Railway Custom Domain
1. En Railway → Project Settings → Custom Domain
2. Ingresa `tudominio.com`
3. Railway genera un certificado SSL automáticamente

---

## 🔧 Troubleshooting Rápido

| Problema | Solución |
|----------|----------|
| **Error 500** | Revisa logs en Railway → Deployments |
| **Base de datos no conecta** | Verifica credenciales en Variables |
| **Migraciones no corren** | Ejecuta manualmente con `railway run php artisan migrate --force` |
| **Assets no cargan** | Ejecuta: `php artisan storage:link` |
| **Archivos no se guardan** | Cambia `FILESYSTEM_DISK=s3` o usa volumen Railway |

---

## 📊 Comparativa de Costos

| Proveedor | Almacenamiento | Precio/mes | Notas |
|-----------|---|---|---|
| Railway | 500h/mes | $0-5 | Muy generoso |
| Supabase | 500MB BD | $0-25 | Excelente PostgreSQL |
| Render | 750h/mes | $0-25 | Mejor que Railway |
| Neon | 3GB BD | $0 | PostgreSQL gratis |
| AWS RDS | 20GB | $0 (12 meses) | Free tier limitado |

---

## 🎯 Próximos pasos

1. **Configura correo** → Usa Mailtrap o SendGrid
2. **Añade SSL** → Automático en Railway
3. **Monitoreo** → Instala Sentry (gratis 5k eventos/mes)
4. **Backups** → Supabase hace automático
5. **CDN** → Opcional con Cloudflare (gratis)

---

**¿Necesitas ayuda en algún paso? Pregunta aquí.**
