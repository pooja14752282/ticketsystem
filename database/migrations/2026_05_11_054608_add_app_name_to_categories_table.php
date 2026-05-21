<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ticket_categories', function (Blueprint $table) {  // ✅ fixed table name
            $table->enum('app_name', [
                'seelinfinity',
                'examinfinity',
                'mockinfinity',
                'dasohainfinity',
                'interninfinity'
            ])->nullable();
        });
    }

    public function down(): void
    {
        Schema::table('ticket_categories', function (Blueprint $table) {  // ✅ fixed table name
            $table->dropColumn('app_name');
        });
    }
};