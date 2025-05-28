<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFileToArsipTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
{
    Schema::table('arsip', function (Blueprint $table) {
        $table->string('file')->nullable();
    });
}

public function down()
{
    Schema::table('arsip', function (Blueprint $table) {
        $table->dropColumn('file');
    });
}
}
