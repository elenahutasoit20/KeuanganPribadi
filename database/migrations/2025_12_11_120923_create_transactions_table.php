<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // ✅ ENUM sesuai dengan nilai yang dikirim dari form
            $table->enum('type', ['pemasukan', 'pengeluaran']);

            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->string('description')->nullable();
            $table->integer('amount');

            // ✅ Ganti dari date() → datetime() agar bisa simpan jam juga
            $table->datetime('date');

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('transactions');
    }
};

