<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class AutoFetchGempa extends Command
{
    protected $signature = 'gempa:auto-fetch';

    protected $description = 'Auto fetch data BMKG setiap 15 detik';

    public function handle()
    {
        $this->info('Auto Fetch Gempa ON');
        $this->info('Tekan CTRL + C untuk menghentikan.');

        while (true) {

            Artisan::call('gempa:fetch');

            $this->info(
                'Fetch berhasil : ' . now()
            );

            sleep(15);
        }
    }
}