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
        Schema::create('game_requests', function (Blueprint $table) {
            $table->id();
            $table->string('user');
            $table->boolean('is_winner')->nullable();
            $table->timestamp('played_at')->nullable();
            $table->timestamps();
        });
    }
};
