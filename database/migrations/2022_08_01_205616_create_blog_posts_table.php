<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBlogPostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('blog_posts', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique()->index();
            $table->text('intro')->nullable();
            $table->longText('text');
            $table->string('image')->default('')->nullable();
            $table->string('meta_title')->default('')->nullable();
            $table->string('meta_description')->default('')->nullable();
            $table->string('meta_keywords')->default('')->nullable();
            $table->string('tags')->default('')->nullable();
            $table->integer('views')->default(0)->nullable();
            $table->integer('status')->default(0)->nullable();
            $table->timestamp('publish_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('blog_posts');
    }
}
