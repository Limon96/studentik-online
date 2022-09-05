<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterFaqCategoryIdToFaq extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('faq', function (Blueprint $table) {
            $table->foreignId('faq_category_id')->constrained('f_a_q_categories')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('faq', function (Blueprint $table) {
            $table->dropForeign('faq_category_id');
        });
    }
}
