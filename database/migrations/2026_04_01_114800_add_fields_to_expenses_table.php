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
        Schema::table('expenses', function (Blueprint $table) {
            $table->foreignId('expense_category_id')->nullable()->constrained('expense_categories')->nullOnDelete()->after('category');
            $table->string('supplier')->nullable()->after('note');         // tedarikçi / mağaza
            $table->boolean('is_recurring')->default(false)->after('supplier'); // tekrarlayan mı
            $table->string('recurring_period')->nullable()->after('is_recurring'); // monthly | yearly
        });
    }

    public function down(): void
    {
        Schema::table('expenses', function (Blueprint $table) {
            $table->dropForeign(['expense_category_id']);
            $table->dropColumn(['expense_category_id','supplier','is_recurring','recurring_period']);
        });
    }
};
