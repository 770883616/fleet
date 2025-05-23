<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
       Schema::create('invoices', function (Blueprint $table) {
    $table->id();
    $table->string('title');
    $table->decimal('amount', 10, 2);
    $table->dateTime('issued_date');
    $table->dateTime('due_date');
    $table->unsignedBigInteger('maintenance_id')->nullable(); // عمود فقط بدون قيد مفتاح أجنبي
    $table->timestamps();
});

    }

    public function down(): void
    {
        Schema::dropIfExists('invoices');
    }
};

