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
            $table->string("name")->default("");
            $table->text("description")->default("");
            $table->bigInteger("assigned_to")->unsigned()->default(0);
            $table->boolean('is_assigned_to_owner')->default(false);
            $table->foreign("assigned_to")->references("id")->on("users")->onDelete("cascade");
            $table->bigInteger("column_id")->unsigned()->default(0);
            $table->foreign("column_id")->references("id")->on("columns")->onDelete("cascade");
            $table->timestamp("deadline");
            $table->enum('priority', ['Low', 'Medium', 'High']);
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
