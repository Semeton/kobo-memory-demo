<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Expand phase: add `display_name` alongside the legacy `name` column.
 * `name` stays (and keeps being written) until the contract release.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->string('display_name')->nullable()->after('name');
        });

        DB::table('customers')->whereNull('display_name')->update(['display_name' => DB::raw('name')]);
    }

    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn('display_name');
        });
    }
};
