<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('whois_servers', function (Blueprint $table) {
            $table->id();
            $table->string('domain')->unique();
            $table->string('whois_server');
            $table->timestamps();
        });

        // Prepopulating from https://www.iana.org/domains/root/db
        DB::table('whois_servers')->insert([
            [
                'domain' => 'ua',
                'whois_server' => 'whois.ua',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'domain' => 'net',
                'whois_server' => 'whois.verisign-grs.com',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'domain' => 'com',
                'whois_server' => 'whois.verisign-grs.com',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('whois_servers');
    }
};
