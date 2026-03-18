<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Config;

class MigrateData extends Command
{
    protected $signature = 'app:migrate-data';
    protected $description = 'Migrate data from SQLite to PostgreSQL';

    public function handle()
    {
        $this->info('Starting migration...');

        $tables = [
            'users',
            'genres',
            'sucursales',
            'salas',
            'peliculas',
            'funciones',
            'asientos',
            'funcion_asiento',
        ];

        try {
            DB::statement("SET session_replication_role = 'replica';");

            foreach ($tables as $table) {
                if (!Schema::connection('sqlite_legacy')->hasTable($table)) {
                    $this->warn("Table $table does not exist in the legacy SQLite database, skipping.");
                    continue;
                }

                $this->info("Processing table: $table");
                
                $data = DB::connection('sqlite_legacy')->table($table)->get();
                
                if ($data->isEmpty()) {
                    $this->line("Table $table has no records.");
                    continue;
                }

                // Delete all to avoid duplicates from previous failed runs
                DB::table($table)->delete();

                $rows = $data->map(fn($row) => (array)$row)->toArray();
                
                $count = 0;
                foreach ($rows as $rowData) {
                    try {
                        DB::table($table)->insert($rowData);
                        $count++;
                    } catch (\Exception $e) {
                        $this->warn("Skipping row in $table (already exists or error): " . $e->getMessage());
                    }
                }

                $this->info("Successfully migrated $count records for '$table'.");
            }

            DB::statement("SET session_replication_role = 'origin';");
            $this->info('Migration finished successfully!');

        } catch (\Exception $e) {
            DB::statement("SET session_replication_role = 'origin';");
            $this->error("Migration failed: " . $e->getMessage());
        }
    }
}
