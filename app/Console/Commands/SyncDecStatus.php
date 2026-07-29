<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\Gempa;

class SyncDecStatus extends Command
{
    protected $signature = 'gempa:sync-dec {source?}';

    protected $description = 'Sinkronkan seluruh status gempa menggunakan FastAPI DEC';

    public function handle()
    {
        $source = $this->argument('source');

        $query = Gempa::query();

        if ($source) {
            $query->where('source', strtoupper($source));
        }

        $total = $query->count();

        $this->info("Total data: {$total}");
        $this->info("Memulai sinkronisasi...");

        $updated = 0;

        $query->chunk(100, function ($gempas) use (&$updated) {

            foreach ($gempas as $g) {

                $depth = (float) preg_replace('/[^0-9.]/', '', $g->kedalaman);

                try {

                    $response = Http::timeout(30)->post(
                        env('DEC_API_URL') . '/predict',
                        [
                            'magnitudo' => (float) $g->magnitudo,
                            'kedalaman' => $depth
                        ]
                    );

                    if (!$response->successful()) {
                        $this->error("Gagal ID {$g->id}");
                        continue;
                    }

                    $dec = $response->json();

                    // DEBUG
                    $this->info(
                        "ID {$g->id} | M={$g->magnitudo} | K={$depth} | STATUS={$dec['status']}"
                    );

                    $g->update([
                        'status' => $dec['status'],
                        'color'  => $dec['color'],
                    ]);

                    $updated++;

                } catch (\Throwable $e) {

                    $this->error("ID {$g->id}: ".$e->getMessage());

                }
            }

        });

        $this->info("--------------------------------");
        $this->info("Selesai.");
        $this->info("Total update : {$updated}");
    }
}