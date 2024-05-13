<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {

        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('title')->required();
            $table->unsignedBigInteger('todo_list_id');
            $table->foreign('todo_list_id')->references('id')->on('todo_lists')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
