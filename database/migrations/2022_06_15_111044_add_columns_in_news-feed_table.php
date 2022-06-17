<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddColumnsInNewsFeedTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('news_feed', function (Blueprint $table) {
            $table->text('meta_title')->nullable()->after('file');
            $table->longText('meta_description')->nullable()->after('meta_title');
            $table->text('meta_image')->nullable()->after('meta_description');
            $table->text('content_url')->nullable()->after('meta_image');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('news_feed', function (Blueprint $table) {
            $table->dropColumn('meta_title');
            $table->dropColumn('meta_description');
            $table->dropColumn('meta_image');
            $table->dropColumn('content_url');
        });
    }
}
