<?php

use App\Models\Account\AccountTransaction;
use App\Models\Clients\ClientComment;
use App\Models\Positions\Position;
use App\Models\PositionService;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class DeleteDuplicatePositions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $duplicateUserIds = DB::select('select distinct user_id from positions group by user_id having count(*) > 1');
        $duplicateUserIds = array_map(function ($el) {
            return $el->user_id;
        }, $duplicateUserIds);
        foreach ($duplicateUserIds as $duplicateUserId) {
            DB::transaction(function () use ($duplicateUserId) {
                $positions = Position::query()->where('user_id', $duplicateUserId)->orderBy('created_at', 'ASC')->get();

                $positionIdToStay = $positions->shift()->id;
                $positionIdsToRemove = $positions->map(function ($el) {
                    return $el->id;
                });

                ClientComment::query()
                    ->whereIn('position_id', $positionIdsToRemove)
                    ->update(['position_id' => $positionIdToStay]);

                AccountTransaction::query()
                    ->whereIn('committer_id', $positionIdsToRemove)
                    ->update(['committer_id' => $positionIdToStay]);

                PositionService::query()
                    ->whereIn('position_id', $positionIdsToRemove)
                    ->update(['position_id' => $positionIdToStay]);

                Position::query()
                    ->whereIn('id', $positionIdsToRemove)
                    ->delete();
            });
        }
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
