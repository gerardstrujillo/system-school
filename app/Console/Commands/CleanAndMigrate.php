<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CleanAndMigrate extends Command
{
    protected $signature = 'clean:migrate';
    protected $description = 'Limpiar BD y ejecutar seeders para crear tablas';

    public function handle()
    {
        $this->info('🗑️  Limpiando base de datos...');

        try {
            DB::statement('SET session_replication_role = replica');
            DB::statement('DROP SCHEMA public CASCADE');
            DB::statement('CREATE SCHEMA public');
            DB::statement('GRANT ALL ON SCHEMA public TO postgres');
            DB::statement('GRANT ALL ON SCHEMA public TO public');
            DB::statement('SET session_replication_role = default');
            $this->info('✅ Schema limpiado');
        } catch (\Exception $e) {
            $this->warn('⚠️  Error: ' . $e->getMessage());
        }

        $this->info('🌱 Creando tablas via seeders...');
        try {
            $this->call('db:seed', [
                '--class' => 'Database\\Seeders\\CreateTablesIfNotExists',
                '--force' => true,
            ]);
            $this->info('✅ Tablas creadas');
        } catch (\Exception $e) {
            $this->error('❌ Error: ' . $e->getMessage());
        }

        $this->info('✅ ¡Completado!');
    }
}
