<?php

use App\Enums\VoucherTypes;
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
        Schema::create('vouchers', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('code', 8)->unique();
            $table->enum('type', VoucherTypes::cases())->default('charge');
            $table->unsignedBigInteger('amount');
            $table->unsignedInteger('max_uses')->default(1)->index();
            $table->unsignedInteger('current_uses')->default(0)->index();
            $table->timestamp('starts_at')->nullable()->index();
            $table->timestamp('expires_at')->nullable()->index();
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vouchers');
    }
};
