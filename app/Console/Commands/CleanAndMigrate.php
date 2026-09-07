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
        $this->call('migrate', ['--force' => true]);

        $this->info('🌱 Ejecutando seeders...');
        $this->call('db:seed', ['--force' => true]);

        $this->info('✅ ¡Completado!');
    }
}
