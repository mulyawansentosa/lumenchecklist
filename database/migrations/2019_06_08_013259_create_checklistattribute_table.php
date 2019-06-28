<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChecklistattributeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('checklistattributes', function (Blueprint $table) {
            $table->bigInteger('checklist_id')->unsigned();
            $table->string('object_domain')->require();
            $table->unsignedInteger('object_id')->require();
            $table->string('description')->require();
            $table->boolean('is_completed');
            $table->timestamp('completed_at')->nullable();
            $table->unsignedInteger('updated_by')->nullable();
            $table->timestamp('due')->nullable();
            $table->tinyInteger('urgency');
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent();
            $table->foreign('checklist_id')->references('id')->on('checklists')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('checklistattributes');
    }
}
