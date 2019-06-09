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
            $table->unsignedInteger('checklist_id');
            $table->string('object_domain');
            $table->unsignedInteger('object_id');
            $table->string('description');
            $table->boolean('is_completed');
            $table->timestamp('completed_at')->nullable();
            $table->unsignedInteger('updated_by')->nullable();
            $table->timestamp('due')->nullable();
            $table->tinyInteger('urgency');
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
        Schema::dropIfExists('checklistattributes');
    }
}
