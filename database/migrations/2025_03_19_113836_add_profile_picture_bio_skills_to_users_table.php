<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddProfilePictureBioSkillsToUsersTable extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('profile_picture')->nullable()->after('email'); 
            $table->text('bio')->nullable()->after('profile_picture'); 
            $table->text('skills')->nullable()->after('bio'); 
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['profile_picture', 'bio', 'skills']); 
        });
    }
}
