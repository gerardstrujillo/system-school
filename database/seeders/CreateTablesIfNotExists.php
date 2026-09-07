<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateTablesIfNotExists extends Seeder
{
    public function run(): void
    {
        $this->info('🔄 Verificando y creando tablas...');

        // Crear tabla users si no existe
        if (!Schema::hasTable('users')) {
            DB::statement("
                CREATE TABLE users (
                    id BIGSERIAL PRIMARY KEY,
                    name VARCHAR(255) NOT NULL,
                    email VARCHAR(255) NOT NULL UNIQUE,
                    email_verified_at TIMESTAMP NULL,
                    password VARCHAR(255) NOT NULL,
                    remember_token VARCHAR(100) NULL,
                    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
                    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP
                )
            ");
            $this->info('✅ Tabla users creada');
        }

        // Crear otras tablas...
        $this->createTableIfNotExists('cache', "
            CREATE TABLE cache (
                key VARCHAR(255) PRIMARY KEY,
                value TEXT NOT NULL,
                expiration INTEGER NOT NULL
            )
        ");

        $this->createTableIfNotExists('cache_locks', "
            CREATE TABLE cache_locks (
                key VARCHAR(255) PRIMARY KEY,
                owner VARCHAR(255) NOT NULL,
                expiration INTEGER NOT NULL
            )
        ");

        $this->createTableIfNotExists('password_reset_tokens', "
            CREATE TABLE password_reset_tokens (
                email VARCHAR(255) NOT NULL,
                token VARCHAR(255) NOT NULL,
                created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (email)
            )
        ");

        $this->createTableIfNotExists('sessions', "
            CREATE TABLE sessions (
                id VARCHAR(255) PRIMARY KEY,
                user_id BIGINT NULL,
                ip_address VARCHAR(45) NULL,
                user_agent TEXT NULL,
                payload TEXT NOT NULL,
                last_activity INTEGER NOT NULL
            )
        ");

        $this->createTableIfNotExists('jobs', "
            CREATE TABLE jobs (
                id BIGSERIAL PRIMARY KEY,
                queue VARCHAR(255) NOT NULL,
                payload LONGTEXT NOT NULL,
                attempts INTEGER NOT NULL DEFAULT 0,
                reserved_at INTEGER NULL,
                available_at INTEGER NOT NULL,
                created_at INTEGER NOT NULL
            )
        ");

        $this->createTableIfNotExists('job_batches', "
            CREATE TABLE job_batches (
                id VARCHAR(255) PRIMARY KEY,
                name VARCHAR(255) NOT NULL,
                total_jobs INTEGER NOT NULL,
                pending_jobs INTEGER NOT NULL,
                failed_jobs INTEGER NOT NULL,
                failed_job_ids LONGTEXT NOT NULL,
                options LONGTEXT NULL,
                cancelled_at INTEGER NULL,
                created_at INTEGER NOT NULL,
                finished_at INTEGER NULL
            )
        ");

        $this->createTableIfNotExists('failed_jobs', "
            CREATE TABLE failed_jobs (
                id BIGSERIAL PRIMARY KEY,
                uuid VARCHAR(255) UNIQUE NOT NULL,
                connection TEXT NOT NULL,
                queue TEXT NOT NULL,
                payload LONGTEXT NOT NULL,
                exception LONGTEXT NOT NULL,
                failed_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
            )
        ");

        // Tablas custom del proyecto
        $this->createTableIfNotExists('administradores', "
            CREATE TABLE administradores (
                id_usuario BIGSERIAL PRIMARY KEY,
                nombres VARCHAR(255),
                apellidos VARCHAR(255),
                email VARCHAR(255),
                created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
            )
        ");

        $this->createTableIfNotExists('matriculasestudiantes', "
            CREATE TABLE matriculasestudiantes (
                id_estudiante SERIAL PRIMARY KEY,
                nombres VARCHAR(255) NOT NULL,
                primer_apellido VARCHAR(255) NOT NULL,
                segundo_apellido VARCHAR(255) NOT NULL,
                fecha_nacimiento TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                id_grado INTEGER,
                estado_matricula VARCHAR(255) DEFAULT 'Sin pagar',
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )
        ");

        $this->createTableIfNotExists('gradosacademicos', "
            CREATE TABLE gradosacademicos (
                id_grado SERIAL PRIMARY KEY,
                grado VARCHAR(255) NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )
        ");

        $this->createTableIfNotExists('periodos_academicos', "
            CREATE TABLE periodos_academicos (
                id_periodo SERIAL PRIMARY KEY,
                periodo VARCHAR(255) NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )
        ");

        $this->createTableIfNotExists('materias', "
            CREATE TABLE materias (
                id_materia SERIAL PRIMARY KEY,
                nombre_materia VARCHAR(255) NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )
        ");

        $this->createTableIfNotExists('notas', "
            CREATE TABLE notas (
                id_nota SERIAL PRIMARY KEY,
                id_estudiante INTEGER NOT NULL,
                id_materia INTEGER,
                id_periodo INTEGER,
                nota DECIMAL(4,2),
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
            )
        ");

        $this->info('✅ Tablas verificadas/creadas');
    }

    private function createTableIfNotExists(string $table, string $sql): void
    {
        if (!Schema::hasTable($table)) {
            DB::statement($sql);
            $this->info("✅ Tabla $table creada");
        }
    }

    private function info(string $message): void
    {
        echo $message . "\n";
    }
}
