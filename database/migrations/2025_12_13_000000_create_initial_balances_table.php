<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('initial_balances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->decimal('amount', 15, 2)->default(0);
            $table->date('effective_date');
            $table->string('notes')->nullable();
            $table->timestamps();
            
            // Satu saldo awal per user per tanggal (untuk fleksibilitas update)
            $table->unique(['user_id', 'effective_date']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('initial_balances');
    }
};
