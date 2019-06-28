<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTemplateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('templates', function (Blueprint $table) {
            $table->bigIncrements('id');
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
        Schema::table('templateattributes', function (Blueprint $table){
            $table->dropForeign('templateattributes_template_id_foreign');
        });
        Schema::table('templatelinks', function (Blueprint $table){
            $table->dropForeign('templatelinks_template_id_foreign');
        });
        Schema::dropIfExists('templates');
    }
}
