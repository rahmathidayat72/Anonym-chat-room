<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->string('code', 8)->unique();
            $table->boolean('is_private')->default(false);
            $table->timestamp('expired_at');
            $table->timestamps();

            $table->index('code');
            $table->index('expired_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
};
