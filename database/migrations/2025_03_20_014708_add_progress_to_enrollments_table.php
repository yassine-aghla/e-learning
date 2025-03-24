<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddProgressToEnrollmentsTable extends Migration
{
    public function up()
    {
        Schema::table('enrollments', function (Blueprint $table) {
            $table->integer('progress')->default(0); 
        });
    }

    public function down()
    {
        Schema::table('enrollments', function (Blueprint $table) {
            $table->dropColumn('progress'); // Supprimez la colonne en cas de rollback
        });
    }
}