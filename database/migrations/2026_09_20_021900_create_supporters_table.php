<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('supporters', function (Blueprint $table): void {
            $table->id();
            $table->string('email')->unique();
            $table->string('status', 24)->index();
            $table->string('confirmation_token_hash', 64)->nullable()->index();
            $table->timestamp('token_expires_at')->nullable();
            $table->string('unsubscribe_token_hash', 64)->nullable()->index();
            $table->timestamp('unsubscribe_token_expires_at')->nullable();
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamp('unsubscribed_at')->nullable();
            $table->string('consent_text_version', 32);
            $table->string('locale', 12)->nullable();
            $table->string('source', 80)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('supporters');
    }
};
