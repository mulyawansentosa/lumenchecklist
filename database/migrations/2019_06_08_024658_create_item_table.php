<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItemTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('items', function (Blueprint $table){
            $table->bigIncrements('id');
            $table->bigInteger('checklist_id')->require();
            $table->string('type')->require();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('itemattributes', function (Blueprint $table){
            $table->dropForeign('itemattributes_item_id_foreign');
        });
        Schema::table('itemlinks', function (Blueprint $table){
            $table->dropForeign('itemlinks_item_id_foreign');
        });
        Schema::dropIfExists('items');
    }
}
