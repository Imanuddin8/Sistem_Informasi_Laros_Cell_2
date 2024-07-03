<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ResetTransaksi extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'transaksi:reset';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Reset jumlah transaksi penjualan setiap hari';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        // Mengambil tanggal hari ini
        $today = Carbon::today();

        // Menghitung total penjualan yang terjadi hari ini
        $totalSales = DB::table('penjualan')->whereDate('tanggal', $today)->count();

        // Menampilkan total penjualan hari ini di console
        $this->info("Penjualan hari ini: $totalSales");

        // Reset jumlah transaksi
        DB::table('penjualan')->truncate();

        $this->info('Jumlah transaksi telah direset.');
    }
}
