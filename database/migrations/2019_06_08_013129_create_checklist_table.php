<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChecklistTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('checklists', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('template_id')->require();
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
        Schema::table('checklistattributes', function (Blueprint $table){
            $table->dropForeign('checklistattributes_checklist_id_foreign');
        });
        Schema::table('checklistlinks', function (Blueprint $table){
            $table->dropForeign('checklistlinks_checklist_id_foreign');
        });
        Schema::dropIfExists('checklists');
    }
}
