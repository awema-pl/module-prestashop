
<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePrestashopShopsTable extends Migration
{
    public function up()
    {
        Schema::create(config('prestashop.database.tables.prestashop_shops'), function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('url');
            $table->string('api_key');
            $table->integer('shop_language_id');
            $table->string('shop_language_name');
            $table->timestamps();
        });

        Schema::table(config('prestashop.database.tables.prestashop_shops'), function (Blueprint $table) {
            $table->foreignId('user_id')
                ->constrained(config('prestashop.database.tables.users'))
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table(config('prestashop.database.tables.prestashop_shops'), function (Blueprint $table) {
            $table->dropForeign(['user_id']);
        });

        Schema::drop(config('prestashop.database.tables.prestashop_shops'));
    }
}
