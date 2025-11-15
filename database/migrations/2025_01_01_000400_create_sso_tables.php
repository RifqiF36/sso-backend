<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('sso_providers', function (Blueprint $table) {
            $table->bigIncrements('provider_id');
            $table->string('name')->unique();
            $table->string('authorize_url');
            $table->string('token_url');
            $table->string('userinfo_url')->nullable();
            $table->string('redirect_uri');
            $table->string('client_id');
            $table->string('client_secret');
            $table->json('scopes')->nullable();
            $table->boolean('enabled')->default(true);
            $table->timestamps();
        });

        Schema::create('sso_identities', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('provider_id');
            $table->string('provider_subject');
            $table->unsignedBigInteger('user_id');
            $table->json('raw')->nullable();
            $table->timestamps();

            $table->unique(['provider_id', 'provider_subject']);
            $table->foreign('provider_id')->references('provider_id')->on('sso_providers')->cascadeOnDelete();
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('sso_identities');
        Schema::dropIfExists('sso_providers');
    }
};

