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
        Schema::create('ip_addresses', function (Blueprint $table) {
            $table->id();
            $table->string('label')->nullable();
            $table->ipAddress('ip_address');
            $table->timestamps();
            $table->unsignedBigInteger('created_by')
                ->foreignId('created_by')->constrained(
                    table: 'users'
                );
            $table->unsignedBigInteger('updated_by')
                ->nullable()
                ->foreignId('updated_by')->constrained(
                    table: 'users'
                );

            $table->index(['label', 'ip_address']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ip_addresses');
    }
};
