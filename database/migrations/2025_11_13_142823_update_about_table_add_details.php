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
        Schema::table('about', function (Blueprint $table) {
            $table->text('history')->nullable()->after('bio');
            $table->string('bank_account')->nullable()->after('history');
            $table->string('phone_number')->nullable()->after('bank_account');
            $table->string('owner_name')->nullable()->after('phone_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('about', function (Blueprint $table) {
            $table->dropColumn([
                'history',
                'bank_account',
                'phone_number',
                'owner_name',
            ]);
        });
    }
};
