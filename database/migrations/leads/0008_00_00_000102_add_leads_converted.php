<?php

use App\Models\Dictionaries\LeadStatus;
use App\Models\Leads\Lead;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddLeadsConverted extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::table('leads', static function (Blueprint $table) {
            $table->dateTime('converted_at')->nullable();
        });
        $leads = Lead::get();
        foreach ($leads as $lead)
        {
            if ($lead->status_id === LeadStatus::client_created)
            {
                $lead->converted_at = $lead->subscription->client->created_at;
                $lead->save();
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::table('leads', static function (Blueprint $table) {
            $table->dropColumn('converted_at');
        });
    }
}
