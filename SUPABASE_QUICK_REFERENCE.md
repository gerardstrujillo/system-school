# ⚡ Referencia Rápida: Supabase + Laravel

## 🎯 Lo Esencial en 30 segundos

**Tu Supabase:**
```
postgresql://postgres:supabase.com@db.ohofjvljqhyysnyzemuo.supabase.co:5432/postgres
```

**Tu Laravel .env:**
```env
DB_CONNECTION=pgsql
DB_HOST=db.ohofjvljqhyysnyzemuo.supabase.co
DB_PORT=5432
DB_DATABASE=postgres
DB_USERNAME=postgres
DB_PASSWORD=supabase.com
```

**Comandos clave:**
```bash
cp .env.supabase .env              # Usar configuración Supabase
php artisan key:generate           # Generar clave APP
php artisan migrate                # Crear tablas en Supabase
php artisan db:seed                # Llenar datos de prueba
php artisan serve                  # Ver funcionando
```

---

## 🔧 Qué necesitas instalar

| Componente | ¿Ya incluido? | ¿Qué instalar? |
|---|---|---|
| **Laravel Framework** | ✅ | Nada (ya en composer.json) |
| **PHP PDO** | ✅ | Generalmente ya viene |
| **PDO PostgreSQL** | ⚠️ | `sudo apt install php-pgsql` (si falta) |
| **PostgreSQL Cliente** | ⚠️ | Opcional, para conectar desde terminal |

---

## 🚀 Checklist de Configuración

- [ ] Tengo credenciales de Supabase
- [ ] Copié `.env.supabase` a `.env`
- [ ] Ejecuté `php artisan key:generate`
- [ ] Ejecuté `php artisan migrate`
- [ ] Veo la app funcionando: `php artisan serve`
- [ ] Puedo ver datos en Supabase Dashboard

---

## 🧪 Test de Conexión

```bash
# Test 1: ¿Conecta a BD?
php artisan db
# Debe entrar a psql prompt

# Test 2: ¿Leen datos?
php artisan tinker
>>> App\Models\User::count()

# Test 3: ¿Funciona la app?
php artisan serve
# Abre http://localhost:8000
```

---

## 📍 Diagrama de Arquitectura

```
┌─────────────────────┐
│  Tu Computadora     │
│  (Local)            │
│ ┌─────────────────┐ │
│ │  Laravel App    │ │
│ │  (PHP 8.2)      │ │
│ └────────┬────────┘ │
│          │          │
│ ┌────────▼────────┐ │
│ │ PDO PostgreSQL  │ │
│ │ Extension       │ │
│ └────────┬────────┘ │
└─────────┼───────────┘
          │
          │ TCP conexión (puerto 5432)
          │
┌─────────▼──────────────────────────────┐
│  Supabase Cloud (AWS)                  │
│  db.ohofjvljqhyysnyzemuo.supabase.co   │
│  ┌──────────────────────────────────┐  │
│  │  PostgreSQL                      │  │
│  │  ├─ users                        │  │
│  │  ├─ students                     │  │
│  │  ├─ grades                       │  │
│  │  └─ ... (más tablas)             │  │
│  └──────────────────────────────────┘  │
└──────────────────────────────────────────┘
```

---

## 🔐 Detalles Técnicos

**¿Cómo Laravel conecta a PostgreSQL?**

1. Lees `.env` → obtiene DB_CONNECTION=pgsql
2. Carga `config/database.php` → usa config pgsql
3. PDO extension interpreta la conexión
4. Se conecta al host:puerto con credenciales
5. ¡Listo, ahora puedes hacer queries!

**¿Qué drivers soporta Laravel?**
- ✅ SQLite (desarrollo)
- ✅ MySQL / MariaDB
- ✅ PostgreSQL (tu caso)
- ✅ SQL Server

---

## 📦 Packages Necesarios (ya están)

```json
{
  "require": {
    "php": "^8.2",
    "laravel/framework": "^11.0"  // ← Soporta PostgreSQL nativo
  }
}
```

**No necesitas:** `doctrine/dbal`, drivers adicionales, nada.

---

## 🎓 Conceptos Clave

**PDO (PHP Data Objects)**
- Abstracción de PHP para conectar a cualquier BD
- Ya viene con PHP en extensiones
- Laravel usa PDO internamente

**postgresql:// (URI Connection)**
- Formato estándar PostgreSQL
- Puede convertirse a variables individuales
- Supabase te da ambas

**Migraciones**
- Scripts SQL que crean/modifican tablas
- Controladas por Git
- Se ejecutan con `php artisan migrate`

---

## 🚨 Si Algo Falla

### Error: "could not find driver"
```bash
# PDO PostgreSQL no está instalado
sudo apt install php-pgsql
sudo systemctl restart apache2
```

### Error: "SQLSTATE[08006]"
```bash
# Verifica que el host sea correcto:
# db.ohofjvljqhyysnyzemuo.supabase.co ← Exacto así
```

### Error: "SQLSTATE[28P01]"
```bash
# Usuario o contraseña incorrecta
# Verifica: DB_USERNAME=postgres, DB_PASSWORD=supabase.com
```

### Error: "relation "users" does not exist"
```bash
# Migraciones no se han ejecutado
php artisan migrate
```

---

## 📞 Soporte

**Documentación oficial:**
- [Laravel Database](https://laravel.com/docs/database)
- [Supabase + Laravel](https://supabase.com/docs/guides/database/connecting-to-postgres)
- [PDO PostgreSQL](https://www.php.net/manual/en/ref.pdo-pgsql.php)

**En este proyecto:**
- Ver: `SUPABASE_CONNECTION.md` (guía detallada)
- Ver: `DEPLOYMENT_GUIDE.md` (para cloud)

---

## ✨ Estado Actual

```
✅ Supabase creado
✅ Credenciales obtenidas
✅ .env.supabase creado
⏳ Falta: php artisan migrate
⏳ Falta: Desplegar en Railway
```

**¿Listo para conectar?** 🎯
