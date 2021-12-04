
<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeApiKeyColumnPrestashopShopsTable extends Migration
{
    public function up()
    {
        Schema::table(config('prestashop.database.tables.prestashop_shops'), function (Blueprint $table) {
            $table->text('api_key')->change();
        });

    }

    public function down()
    {
    
    }
}
