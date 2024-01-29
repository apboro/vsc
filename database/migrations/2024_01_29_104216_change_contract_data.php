<?php

use App\Models\Subscriptions\SubscriptionContract;
use Illuminate\Database\Migrations\Migration;

class ChangeContractData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        try {
            SubscriptionContract::query()->where('id',4815)->update(['start_at' => '2023-10-12']);
        } catch (Exception $e) {

        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        try {
            SubscriptionContract::query()->where('id',4815)->update(['start_at' => '2023-11-09']);
        } catch (Exception $e) {

        }
    }
}
