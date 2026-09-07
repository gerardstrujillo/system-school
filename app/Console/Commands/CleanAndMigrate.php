<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CleanAndMigrate extends Command
{
    protected $signature = 'clean:migrate';
    protected $description = 'Drop all tables and run migrations cleanly';

    public function handle()
    {
        $this->info('🗑️  Limpiando base de datos completamente...');

        try {
            // Deshabilitar todos los constraints antes de dropar
            DB::statement('SET session_replication_role = replica');

            // Dropear todas las tablas
            DB::statement('DROP SCHEMA public CASCADE');
            DB::statement('CREATE SCHEMA public');
            DB::statement('GRANT ALL ON SCHEMA public TO postgres');
            DB::statement('GRANT ALL ON SCHEMA public TO public');

            // Reabilitar constraints
            DB::statement('SET session_replication_role = default');

            $this->info('✅ Schema limpiado');
        } catch (\Exception $e) {
            $this->warn('⚠️  Error al limpiar schema: ' . $e->getMessage());
            DB::statement('SET session_replication_role = default');
        }

        $this->info('📦 Ejecutando migraciones...');

        try {
            $this->call('migrate', [
                '--force' => true,
                '--no-interaction' => true,
            ]);
            $this->info('✅ Migraciones completadas');
        } catch (\Exception $e) {
            $this->error('❌ Error en migraciones: ' . $e->getMessage());
            throw $e;
        }

        $this->info('🌱 Ejecutando seeders...');
        try {
            $this->call('db:seed', [
                '--force' => true,
                '--no-interaction' => true,
            ]);
            $this->info('✅ Seeders completados');
        } catch (\Exception $e) {
            $this->warn('⚠️  Error en seeders: ' . $e->getMessage());
        }

        $this->info('✅ ¡Completado!');
    }
}
