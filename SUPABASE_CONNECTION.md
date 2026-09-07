# 🔗 Guía Completa: Conectar Laravel a PostgreSQL (Supabase)

## 📋 Resumen Rápido

**Lo que necesitas:**
- ✅ PHP con extensión PDO PostgreSQL (generalmente ya instalada)
- ✅ Archivo `.env` configurado
- ✅ Ejecutar migraciones
- ✅ ¡Listo!

**No necesitas instalar nada nuevo** - Laravel ya tiene soporte nativo para PostgreSQL.

---

## 🚀 Pasos para Conectar

### Paso 1: Verificar que tienes PDO PostgreSQL

```bash
# En tu servidor/computadora, ejecuta:
php -m | grep pdo

# Debería mostrar:
# PDO
# pdo_pgsql
```

Si no aparece `pdo_pgsql`, instálalo:

**Ubuntu/Debian:**
```bash
sudo apt update
sudo apt install php-pgsql
sudo systemctl restart apache2  # o nginx
```

**macOS (Homebrew):**
```bash
brew install php@8.2-pgsql
```

**Windows:**
Descomenta en `php.ini`:
```
;extension=pdo_pgsql
```
→ Quita el `;` y reinicia Apache/IIS

---

### Paso 2: Crear archivo `.env` con credenciales de Supabase

**Opción A: Usar el archivo que creé (.env.supabase)**

```bash
cp .env.supabase .env
```

**Opción B: Editar .env manualmente**

```env
# Cambiar la sección de BD de:
DB_CONNECTION=sqlite
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=laravel
# DB_USERNAME=root
# DB_PASSWORD=

# A esto (usando Connection Pooler):
DB_CONNECTION=pgsql
DB_HOST=aws-0-us-east-2.pooler.supabase.com
DB_PORT=6543
DB_DATABASE=postgres
DB_USERNAME=postgres.ohofjvljqhyysnyzemuo
DB_PASSWORD=supabase.com
```

---

### Paso 3: Generar APP_KEY

```bash
php artisan key:generate
```

Esto genera una clave única en tu `.env`:
```
APP_KEY=base64:XXXXXXXXXXXXXXXXX
```

---

### Paso 4: Probar la conexión a BD

```bash
# Ejecuta este comando para verificar que conecta a Supabase
php artisan db

# Si funciona, verás el prompt de psql:
postgres=# 
# Escribe: \q para salir
```

Si hay error, verifica:
- ✓ HOST correcto
- ✓ Credenciales correctas
- ✓ Puerto 5432 abierto
- ✓ Extensión PDO instalada

---

### Paso 5: Ejecutar Migraciones

```bash
# Ejecutar todas las migraciones
php artisan migrate

# O si hay error de permissions:
php artisan migrate --force
```

Esto crea todas las tablas en Supabase.

---

### Paso 6: Rellenar BD con datos de prueba (Seeding)

```bash
# Ejecutar los seeders
php artisan db:seed

# O todo junto:
php artisan migrate:fresh --seed
```

Esto llena la BD con usuarios, estudiantes, notas, etc. de prueba.

---

## ✅ Verificar que Funciona

### Opción A: Ver datos en Supabase Dashboard

1. Ve a [supabase.com](https://supabase.com)
2. Abre tu proyecto
3. Ve a **SQL Editor**
4. Ejecuta:
```sql
SELECT * FROM users;
SELECT * FROM students;
SELECT * FROM grades;
```

Deberías ver tus datos.

### Opción B: En Laravel

```bash
# Abrir Tinker (consola interactiva)
php artisan tinker

# Ver usuarios
>>> App\Models\User::count()
# Debería mostrar: 5 (o los que hayas seeded)

>>> App\Models\User::all()
# Ver todos los usuarios
```

---

## 🔐 Dependencias de PHP para PostgreSQL

**Ya están incluidas en Laravel**, solo necesitas la extensión PDO:

```
Composer packages (ya en composer.json):
- laravel/framework  ← tiene driver PostgreSQL nativo
- PDO extension      ← extensión del sistema
```

**No necesitas descargar nada más**, Laravel maneja todo internamente.

---

## 📊 Estructura de la Conexión

```
Tu Aplicación Laravel
        ↓
   config/database.php (lee .env)
        ↓
   PDO extension de PHP
        ↓
   Connection Pooler de Supabase
   (aws-0-us-east-2.pooler.supabase.com:6543)
        ↓
   PostgreSQL en Supabase
```

---

## 🛠 Comandos Útiles

```bash
# Ver status de migraciones
php artisan migrate:status

# Deshacer última migración
php artisan migrate:rollback

# Deshacer todas
php artisan migrate:reset

# Rehacer todo de cero
php artisan migrate:refresh --seed

# Ver BD actual
php artisan db
```

---

## ⚠️ Errores Comunes

| Error | Solución |
|-------|----------|
| `SQLSTATE[08006]` | Host/puerto incorrecto |
| `SQLSTATE[28P01]` | Usuario/contraseña incorrecta |
| `could not find driver` | Extensión pdo_pgsql no instalada |
| `relation "users" does not exist` | Ejecuta `php artisan migrate` |
| `connection refused` | Puerto 5432 bloqueado o BD caída |

---

## 🚀 Siguiente: Desplegar en Cloud

Una vez que funciona localmente con Supabase:

1. Sube tu código a GitHub
2. Crea proyecto en Railway/Render
3. Añade las mismas variables `.env`
4. Ejecuta migraciones en el servidor
5. ¡Listo!

Ver: `DEPLOYMENT_GUIDE.md`

---

## 📞 ¿Preguntas?

**¿No conecta a BD?**
→ Ejecuta: `php artisan db` para debug

**¿Migraciones fallan?**
→ Ejecuta: `php artisan migrate:status` para ver qué falló

**¿Quieres ver datos en Supabase?**
→ Panel → Table Editor → mira cualquier tabla

---

**Estado actual:**
- ✅ Supabase creado
- ✅ Credenciales copiadas
- ⏳ Falta: Conectar localmente
- ⏳ Falta: Ejecutar migraciones
- ⏳ Falta: Desplegar en cloud

¿Empezamos? 🚀
