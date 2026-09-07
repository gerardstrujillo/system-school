<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateTablesIfNotExists extends Seeder
{
    public function run(): void
    {
        echo "\n🔄 Creando tablas del sistema...\n";

        // Tablas de Laravel (users, cache, sessions, jobs, etc)
        $this->createUsersTable();
        $this->createCacheTables();
        $this->createJobsTables();
        $this->createPasswordResetTable();
        $this->createSessionsTable();
        $this->createFailedJobsTable();

        // Tablas custom del proyecto
        echo "\n📚 Creando tablas del proyecto...\n";
        $this->createAdministradoresTable();
        $this->createMatriculasTable();
        $this->createGradosTable();
        $this->createPeriodosTable();
        $this->createMateriasTable();
        $this->createNotasTable();
        $this->createProfesorTable();
        $this->createPensionesTable();
        $this->createPublicacionesTable();
        $this->createFotosTable();
        $this->createDocumentosTable();
        $this->createVerificationCodesTable();
        $this->createHistorialTable();
        $this->createSisbenTable();
        $this->createDimensionesTable();
        $this->createEvidenciaTable();
        $this->createInformacionPadresTable();

        echo "\n✅ ¡Todas las tablas creadas/verificadas!\n";
    }

    private function createUsersTable(): void
    {
        if (!Schema::hasTable('users')) {
            DB::statement("
                CREATE TABLE users (
                    id BIGSERIAL PRIMARY KEY,
                    name VARCHAR(255) NOT NULL,
                    email VARCHAR(255) NOT NULL UNIQUE,
                    email_verified_at TIMESTAMP NULL,
                    password VARCHAR(255) NOT NULL,
                    remember_token VARCHAR(100),
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
                )
            ");
            echo "✅ users\n";
        }
    }

    private function createCacheTables(): void
    {
        if (!Schema::hasTable('cache')) {
            DB::statement("
                CREATE TABLE cache (
                    key VARCHAR(255) PRIMARY KEY,
                    value TEXT NOT NULL,
                    expiration INTEGER NOT NULL
                )
            ");
            echo "✅ cache\n";
        }

        if (!Schema::hasTable('cache_locks')) {
            DB::statement("
                CREATE TABLE cache_locks (
                    key VARCHAR(255) PRIMARY KEY,
                    owner VARCHAR(255) NOT NULL,
                    expiration INTEGER NOT NULL
                )
            ");
            echo "✅ cache_locks\n";
        }
    }

    private function createJobsTables(): void
    {
        if (!Schema::hasTable('jobs')) {
            DB::statement("
                CREATE TABLE jobs (
                    id BIGSERIAL PRIMARY KEY,
                    queue VARCHAR(255) NOT NULL,
                    payload TEXT NOT NULL,
                    attempts INTEGER NOT NULL DEFAULT 0,
                    reserved_at INTEGER,
                    available_at INTEGER NOT NULL,
                    created_at INTEGER NOT NULL
                )
            ");
            echo "✅ jobs\n";
        }

        if (!Schema::hasTable('job_batches')) {
            DB::statement("
                CREATE TABLE job_batches (
                    id VARCHAR(255) PRIMARY KEY,
                    name VARCHAR(255) NOT NULL,
                    total_jobs INTEGER NOT NULL,
                    pending_jobs INTEGER NOT NULL,
                    failed_jobs INTEGER NOT NULL,
                    failed_job_ids TEXT NOT NULL,
                    options TEXT,
                    cancelled_at INTEGER,
                    created_at INTEGER NOT NULL,
                    finished_at INTEGER
                )
            ");
            echo "✅ job_batches\n";
        }
    }

    private function createPasswordResetTable(): void
    {
        if (!Schema::hasTable('password_reset_tokens')) {
            DB::statement("
                CREATE TABLE password_reset_tokens (
                    email VARCHAR(255) NOT NULL,
                    token VARCHAR(255) NOT NULL,
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    PRIMARY KEY (email)
                )
            ");
            echo "✅ password_reset_tokens\n";
        }
    }

    private function createSessionsTable(): void
    {
        if (!Schema::hasTable('sessions')) {
            DB::statement("
                CREATE TABLE sessions (
                    id VARCHAR(255) PRIMARY KEY,
                    user_id BIGINT,
                    ip_address VARCHAR(45),
                    user_agent TEXT,
                    payload TEXT NOT NULL,
                    last_activity INTEGER NOT NULL
                )
            ");
            echo "✅ sessions\n";
        }
    }

    private function createFailedJobsTable(): void
    {
        if (!Schema::hasTable('failed_jobs')) {
            DB::statement("
                CREATE TABLE failed_jobs (
                    id BIGSERIAL PRIMARY KEY,
                    uuid VARCHAR(255) UNIQUE NOT NULL,
                    connection TEXT NOT NULL,
                    queue TEXT NOT NULL,
                    payload TEXT NOT NULL,
                    exception TEXT NOT NULL,
                    failed_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
                )
            ");
            echo "✅ failed_jobs\n";
        }
    }

    // ========== TABLAS DEL PROYECTO ==========

    private function createAdministradoresTable(): void
    {
        if (!Schema::hasTable('administradores')) {
            DB::statement("
                CREATE TABLE administradores (
                    id_usuario BIGSERIAL PRIMARY KEY,
                    name VARCHAR(255),
                    email VARCHAR(255) UNIQUE,
                    password VARCHAR(255),
                    is_admin BOOLEAN COMMENT 'Solo para compatibilidad',
                    force_change_password BOOLEAN DEFAULT false,
                    id_grado INTEGER,
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
                )
            ");
            echo "✅ administradores\n";
        }
    }

    private function createMatriculasTable(): void
    {
        if (!Schema::hasTable('matriculasestudiantes')) {
            DB::statement("
                CREATE TABLE matriculasestudiantes (
                    id_estudiante SERIAL PRIMARY KEY,
                    nombres VARCHAR(255) NOT NULL,
                    primer_apellido VARCHAR(255) NOT NULL,
                    segundo_apellido VARCHAR(255) NOT NULL,
                    fecha_nacimiento TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    lugar_nacimiento VARCHAR(255),
                    genero VARCHAR(255),
                    id_documento INTEGER,
                    n_documento VARCHAR(255),
                    rh VARCHAR(5),
                    eps VARCHAR(255),
                    id_sisben INTEGER,
                    estrato_social VARCHAR(255),
                    discapacidad VARCHAR(255),
                    telefono VARCHAR(20),
                    direccion VARCHAR(255),
                    barrio VARCHAR(255),
                    municipio VARCHAR(255),
                    departamento VARCHAR(255),
                    id_grado INTEGER,
                    estado_matricula VARCHAR(255) DEFAULT 'Sin pagar',
                    documento_adjunto VARCHAR(255),
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    estado VARCHAR(50) DEFAULT 'Activo'
                )
            ");
            echo "✅ matriculasestudiantes\n";
        }
    }

    private function createGradosTable(): void
    {
        if (!Schema::hasTable('gradosacademicos')) {
            DB::statement("
                CREATE TABLE gradosacademicos (
                    id_grado SERIAL PRIMARY KEY,
                    grado VARCHAR(255) NOT NULL,
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
                )
            ");
            echo "✅ gradosacademicos\n";
        }
    }

    private function createPeriodosTable(): void
    {
        if (!Schema::hasTable('periodos_academicos')) {
            DB::statement("
                CREATE TABLE periodos_academicos (
                    id_periodo SERIAL PRIMARY KEY,
                    periodo VARCHAR(255) NOT NULL,
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
                )
            ");
            echo "✅ periodos_academicos\n";
        }
    }

    private function createMateriasTable(): void
    {
        if (!Schema::hasTable('materias')) {
            DB::statement("
                CREATE TABLE materias (
                    id_materia SERIAL PRIMARY KEY,
                    nombre_materia VARCHAR(100) NOT NULL,
                    id_grado INTEGER,
                    es_submateria BOOLEAN DEFAULT false,
                    id_materia_padre INTEGER,
                    porcentaje DECIMAL(5,2) DEFAULT 100
                )
            ");
            echo "✅ materias\n";
        }
    }

    private function createNotasTable(): void
    {
        if (!Schema::hasTable('notas')) {
            DB::statement("
                CREATE TABLE notas (
                    id_nota SERIAL PRIMARY KEY,
                    id_estudiante INTEGER NOT NULL,
                    id_materia INTEGER,
                    id_periodo INTEGER,
                    nota DECIMAL(4,2),
                    observaciones VARCHAR(255),
                    fecha_registro TIMESTAMP DEFAULT CURRENT_TIMESTAMP
                )
            ");
            echo "✅ notas\n";
        }
    }

    private function createProfesorTable(): void
    {
        if (!Schema::hasTable('profesores')) {
            DB::statement("
                CREATE TABLE profesores (
                    id_usuario BIGSERIAL PRIMARY KEY,
                    name VARCHAR(255) NOT NULL,
                    email VARCHAR(255) UNIQUE,
                    password VARCHAR(255) NOT NULL,
                    id_grado INTEGER,
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
                )
            ");
            echo "✅ profesores\n";
        }
    }

    private function createPensionesTable(): void
    {
        if (!Schema::hasTable('pensiones')) {
            DB::statement("
                CREATE TABLE pensiones (
                    id_pension SERIAL PRIMARY KEY,
                    id_estudiante INTEGER NOT NULL,
                    estado_pago VARCHAR(255) NOT NULL,
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
                )
            ");
            echo "✅ pensiones\n";
        }
    }

    private function createPublicacionesTable(): void
    {
        if (!Schema::hasTable('publicaciones')) {
            DB::statement("
                CREATE TABLE publicaciones (
                    id_publicacion SERIAL PRIMARY KEY,
                    titulo VARCHAR(255),
                    descripcion TEXT,
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
                )
            ");
            echo "✅ publicaciones\n";
        }
    }

    private function createFotosTable(): void
    {
        if (!Schema::hasTable('fotos_publicaciones')) {
            DB::statement("
                CREATE TABLE fotos_publicaciones (
                    id_foto SERIAL PRIMARY KEY,
                    id_publicacion INTEGER,
                    ruta_foto VARCHAR(255)
                )
            ");
            echo "✅ fotos_publicaciones\n";
        }
    }

    private function createDocumentosTable(): void
    {
        if (!Schema::hasTable('documentotipo')) {
            DB::statement("
                CREATE TABLE documentotipo (
                    id_documento SERIAL PRIMARY KEY,
                    tipo_documento VARCHAR(255)
                )
            ");
            echo "✅ documentotipo\n";
        }

        if (!Schema::hasTable('documentos_estudiantes')) {
            DB::statement("
                CREATE TABLE documentos_estudiantes (
                    id_documento SERIAL PRIMARY KEY,
                    id_estudiante INTEGER,
                    tipo_documento VARCHAR(255),
                    archivo VARCHAR(255)
                )
            ");
            echo "✅ documentos_estudiantes\n";
        }

        if (!Schema::hasTable('documentos_publicaciones')) {
            DB::statement("
                CREATE TABLE documentos_publicaciones (
                    id_documento SERIAL PRIMARY KEY,
                    id_publicacion INTEGER,
                    tipo_documento VARCHAR(255),
                    archivo VARCHAR(255)
                )
            ");
            echo "✅ documentos_publicaciones\n";
        }
    }

    private function createVerificationCodesTable(): void
    {
        if (!Schema::hasTable('verification_codes')) {
            DB::statement("
                CREATE TABLE verification_codes (
                    id SERIAL PRIMARY KEY,
                    email VARCHAR(255) NOT NULL,
                    code VARCHAR(10) NOT NULL,
                    expires_at TIMESTAMP NOT NULL,
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
                )
            ");
            echo "✅ verification_codes\n";
        }
    }

    private function createHistorialTable(): void
    {
        if (!Schema::hasTable('historial_academico')) {
            DB::statement("
                CREATE TABLE historial_academico (
                    id_historial SERIAL PRIMARY KEY,
                    id_estudiante INTEGER,
                    id_materia INTEGER,
                    id_periodo INTEGER,
                    nota DECIMAL(4,2),
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
                )
            ");
            echo "✅ historial_academico\n";
        }

        if (!Schema::hasTable('historial_academico_dimensiones')) {
            DB::statement("
                CREATE TABLE historial_academico_dimensiones (
                    id_historial_dim SERIAL PRIMARY KEY,
                    id_historial INTEGER,
                    id_dimension INTEGER,
                    nota DECIMAL(4,2)
                )
            ");
            echo "✅ historial_academico_dimensiones\n";
        }
    }

    private function createSisbenTable(): void
    {
        if (!Schema::hasTable('sisben')) {
            DB::statement("
                CREATE TABLE sisben (
                    id_sisben SERIAL PRIMARY KEY,
                    clasificacion VARCHAR(255)
                )
            ");
            echo "✅ sisben\n";
        }
    }

    private function createDimensionesTable(): void
    {
        if (!Schema::hasTable('capacidadesdimensiones')) {
            DB::statement("
                CREATE TABLE capacidadesdimensiones (
                    id_capacidad_dim SERIAL PRIMARY KEY,
                    nombre_capacidad VARCHAR(255),
                    descripcion TEXT
                )
            ");
            echo "✅ capacidadesdimensiones\n";
        }

        if (!Schema::hasTable('materias_dimensiones')) {
            DB::statement("
                CREATE TABLE materias_dimensiones (
                    id_materia_dim SERIAL PRIMARY KEY,
                    id_materia INTEGER,
                    id_capacidad INTEGER,
                    porcentaje DECIMAL(5,2)
                )
            ");
            echo "✅ materias_dimensiones\n";
        }

        if (!Schema::hasTable('notas_dimensiones')) {
            DB::statement("
                CREATE TABLE notas_dimensiones (
                    id_nota_dim SERIAL PRIMARY KEY,
                    id_nota INTEGER,
                    id_capacidad INTEGER,
                    nota DECIMAL(4,2)
                )
            ");
            echo "✅ notas_dimensiones\n";
        }
    }

    private function createEvidenciaTable(): void
    {
        if (!Schema::hasTable('evidencia_planilla')) {
            DB::statement("
                CREATE TABLE evidencia_planilla (
                    id SERIAL PRIMARY KEY,
                    id_docente BIGINT,
                    id_grado INTEGER,
                    id_periodo INTEGER,
                    id_materia INTEGER,
                    id_materia_dim INTEGER,
                    file_path VARCHAR(255),
                    file_type VARCHAR(50),
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
                )
            ");
            echo "✅ evidencia_planilla\n";
        }
    }

    private function createInformacionPadresTable(): void
    {
        if (!Schema::hasTable('informacionpadres')) {
            DB::statement("
                CREATE TABLE informacionpadres (
                    id_padre SERIAL PRIMARY KEY,
                    id_estudiante INTEGER,
                    nombre_padre VARCHAR(255),
                    apellido_padre VARCHAR(255),
                    telefono_padre VARCHAR(20),
                    email_padre VARCHAR(255),
                    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
                )
            ");
            echo "✅ informacionpadres\n";
        }
    }
}
