<?php

use App\Models\Positions\Position;
use App\Models\Services\Service;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPositionServices extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('position_services', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Position::class);
            $table->foreignIdFor(Service::class);
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
        Schema::dropIfExists('positions_services');
    }
}
