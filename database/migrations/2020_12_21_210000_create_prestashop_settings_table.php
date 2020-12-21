
<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePrestashopSettingsTable extends Migration
{
    public function up()
    {
        Schema::create(config('prestashop.database.tables.prestashop_settings'), function (Blueprint $table) {
            $table->id();
            $table->string('key');
            $table->text('value')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::drop(config('prestashop.database.tables.prestashop_settings'));
    }
}
