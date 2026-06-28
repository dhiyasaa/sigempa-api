<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class GempaWorker extends Command
{
    protected $signature = 'gempa:worker';
    protected $description = 'Worker fetch BMKG setiap 15 detik';

    public function handle()
    {
        $this->info("========================================");
        $this->info(" Auto Fetch Gempa ON");
        $this->info(" Tekan CTRL + C untuk menghentikan.");
        $this->info("========================================");

        while (true) {

            try {

                Artisan::call('gempa:fetch');

$output = trim(Artisan::output());

switch ($output) {

    case 'NEW_DATA':
        $this->info("[" . now()->format('H:i:s') . "] ✅ Gempa baru ditemukan & disimpan.");
        break;

    case 'NO_NEW_DATA':
        $this->line("[" . now()->format('H:i:s') . "] ℹ️ Tidak ada gempa baru.");
        break;

    default:
        $this->error("[" . now()->format('H:i:s') . "] ❌ Gagal mengambil data BMKG.");
        break;
}

            } catch (\Throwable $e) {

                $this->error(
                    "[" . now()->format('H:i:s') . "] ERROR : " . $e->getMessage()
                );

            }

            sleep(15);
        }
    }
}