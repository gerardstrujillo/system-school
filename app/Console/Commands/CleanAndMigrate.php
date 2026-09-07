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
        $this->info('🗑️  Limpiando base de datos...');

        try {
            DB::statement('DROP SCHEMA public CASCADE');
            DB::statement('CREATE SCHEMA public');
            $this->info('✅ Schema limpiado');
        } catch (\Exception $e) {
            $this->warn('⚠️  Error al limpiar schema: ' . $e->getMessage());
        }

        $this->info('📦 Ejecutando migraciones...');

        try {
            // Deshabilitar foreign keys temporalmente
            DB::statement('SET session_replication_role = replica');

            $this->call('migrate', ['--force' => true]);

            // Reabilitar foreign keys
            DB::statement('SET session_replication_role = default');

            $this->info('✅ Migraciones completadas');
        } catch (\Exception $e) {
            DB::statement('SET session_replication_role = default');
            $this->error('❌ Error en migraciones: ' . $e->getMessage());
            throw $e;
        }

        $this->info('🌱 Ejecutando seeders...');
        $this->call('db:seed', ['--force' => true]);

        $this->info('✅ ¡Completado!');
    }
}
