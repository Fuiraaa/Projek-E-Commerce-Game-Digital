<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['admin', 'developer', 'player'])->default('player')->after('email');
            $table->boolean('is_verified')->default(false)->after('role');
            $table->decimal('wallet_balance', 10, 2)->default(0.00)->after('is_verified');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['role', 'is_verified', 'wallet_balance']);
        });
    }
};
