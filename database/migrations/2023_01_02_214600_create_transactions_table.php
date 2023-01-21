<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->decimal('outgoing_amount', 22, 8)->default('0');
            $table->decimal('incoming_amount', 22, 8)->nullable();
            $table->unsignedBigInteger('from_account_id');
            $table->unsignedBigInteger('to_account_id');
            $table->timestamp('time')->nullable();
            $table->foreign('from_account_id')->references('id')->on('accounts');
            $table->foreign('to_account_id')->references('id')->on('accounts');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
