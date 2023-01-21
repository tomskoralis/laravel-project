<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('accounts', function (Blueprint $table) {
            $table->id();
            $table->string('type', 16)->default('regular');
            $table->string('number', 16)->unique();
            $table->string('label', 64)->nullable();
            $table->decimal('balance', 24, 8)->default('0');
            $table->string('currency', 16);
            $table->unsignedBigInteger('user_id');
            $table->timestamps();
            $table->timestamp('closed_at')->nullable();
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
