<?php

use App\Models\Dictionaries\LeadStatus;
use App\Models\Leads\Lead;
use Illuminate\Database\Migrations\Migration;

class ChangeLeadsStatuses extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Lead::query()
            ->whereDate('created_at', '>=', '2022-08-31')
            ->whereDate('created_at', '<=', '2023-08-31')
            ->where('status_id', LeadStatus::new)
            ->update([
                'status_id' => LeadStatus::deleted
            ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
