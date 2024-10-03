<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOrderIdToRatingsTable extends Migration
{
    public function up()
    {
        Schema::table('ratings', function (Blueprint $table) {
            // Menambahkan kolom order_id dan menghubungkannya dengan tabel orders
            $table->foreignId('order_id')->constrained('orders')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('ratings', function (Blueprint $table) {
            // Menghapus kolom order_id jika rollback migrasi
            $table->dropForeign(['order_id']);
            $table->dropColumn('order_id');
        });
    }
}
