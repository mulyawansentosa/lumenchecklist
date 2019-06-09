<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateItemattributeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('itemattributes', function (Blueprint $table) {
            $table->unsignedInteger('item_id');
            $table->string('description');
            $table->boolean('is_completed');
            $table->timestamp('completed_at')->nullable();
            $table->unsignedInteger('updated_by')->nullable();
            $table->timestamp('due')->nullable();
            $table->tinyInteger('urgency')->nullable();
            $table->unsignedInteger('assignee_id')->nullable();
            $table->unsignedInteger('task_id')->nullable();
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
        Schema::dropIfExists('itemattributes');
    }
}
