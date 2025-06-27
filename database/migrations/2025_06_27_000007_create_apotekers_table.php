<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateApotekersTable extends Migration
{
    public function up()
    {
        Schema::create('apotekers', function (Blueprint $table) {
            $table->id();
            $table->string('nama');
            $table->string('no_sipa');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('apotekers');
    }
}
