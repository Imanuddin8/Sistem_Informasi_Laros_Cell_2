<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Trigger AFTER INSERT
        DB::unprepared('
            CREATE TRIGGER after_pembelian_insert
            AFTER INSERT ON pembelian
            FOR EACH ROW
            BEGIN
                UPDATE produks
                SET stok = stok + NEW.jumlah
                WHERE id = NEW.produk_id;
            END
        ');

        // Trigger AFTER UPDATE
        DB::unprepared('
            CREATE TRIGGER after_pembelian_update
            AFTER UPDATE ON pembelian
            FOR EACH ROW
            BEGIN
                DECLARE diff INT;
                SET diff = NEW.jumlah - OLD.jumlah;
                UPDATE produks
                SET stok = stok + diff
                WHERE id = NEW.produk_id;
            END
        ');

        // Trigger AFTER DELETE
        DB::unprepared('
            CREATE TRIGGER after_pembelian_delete
            AFTER DELETE ON pembelian
            FOR EACH ROW
            BEGIN
                UPDATE produks
                SET stok = stok - OLD.jumlah
                WHERE id = OLD.produk_id;
            END
        ');
    }

    public function down()
    {
        DB::unprepared('DROP TRIGGER IF EXISTS after_pembelian_insert');
        DB::unprepared('DROP TRIGGER IF EXISTS after_pembelian_update');
        DB::unprepared('DROP TRIGGER IF EXISTS after_pembelian_delete');
    }
};
