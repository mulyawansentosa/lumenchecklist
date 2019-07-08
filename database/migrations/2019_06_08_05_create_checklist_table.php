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
            $table->bigInteger('template_id')->require()->unsigned();
            $table->string('object_domain')->nullable();
            $table->unsignedInteger('object_id')->nullable();
            $table->string('description')->require();
            $table->boolean('is_completed')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('due')->nullable();
            $table->integer('due_interval')->unsigned()->nullable();
            $table->enum('due_unit',['minute','hour','day','week','month'])->nullable();
            $table->tinyInteger('urgency')->nullable();
            $table->bigInteger('created_by')->unsigned()->nullable();
            $table->bigInteger('updated_by')->unsigned()->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
            $table->foreign('template_id')->references('id')->on('templates')->onDelete('cascade')->onUpdate('cascade');
            $table->softDeletes();            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('items', function (Blueprint $table){
            $table->dropForeign('items_checklist_id_foreign');
        });
        Schema::dropIfExists('checklists');
    }
}
