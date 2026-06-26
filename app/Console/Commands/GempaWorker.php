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
        $this->info('===== Gempa Worker Started =====');

        while (true) {

            try {

                Artisan::call('gempa:fetch');

                $this->info(now() . ' - ' . trim(Artisan::output()));

            } catch (\Throwable $e) {

                $this->error(now() . ' - ' . $e->getMessage());

            }

            sleep(15);
        }
    }
}