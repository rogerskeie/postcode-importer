<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePostcodesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create(
            'postcodes',
            function (Blueprint $table) {
                $table
                    ->mediumInteger('id')
                    ->unique();
                $table->string('postcode', 6);
                $table->mediumInteger('lowest');
                $table->mediumInteger('highest');
                $table->enum(
                    'type',
                    [
                        'odd',
                        'even',
                        'houseboats',
                        'trailers',
                        'vacant',
                    ]
                );
                $table->string('street', 80);
                $table->string('city', 80);
                $table->string('municipality', 80);
                $table->string('province', 20);
                $table->timestamps();
            }
        );
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('postcodes');
    }
}
