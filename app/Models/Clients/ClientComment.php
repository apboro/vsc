<?php

namespace App\Models\Clients;

use App\Models\Dictionaries\ClientCommentActionType;
use App\Models\Dictionaries\ClientCommentType;
use App\Models\Positions\Position;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property int $id
 * @property string $text
 *
 * @property int $type_id
 * @property int|null $action_id
 * @property int|null $position_id
 * @property int $client_id
 *
 * @property Carbon $created_at
 * @property Carbon $updated_at
 *
 * @property-read ClientCommentType $type
 * @property-read ClientCommentActionType|null $actionType
 * @property-read Position|null $position
 * @property-read Client $client
 */
class ClientComment extends Model
{
    protected $fillable = [
        'text', 'type_id',
        'action_id', 'position_id',
    ];

    public function type(): BelongsTo
    {
        return $this->belongsTo(ClientCommentType::class, 'type_id', 'id');
    }

    public function actionType(): BelongsTo
    {
        return $this->belongsTo(ClientCommentActionType::class, 'action_id', 'id');
    }

    public function position(): BelongsTo
    {
        return $this->belongsTo(Position::class, 'position_id', 'id');
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'client_id', 'id');
    }

    public function isInner(): bool
    {
        return $this->type_id === ClientCommentType::inner;
    }
}
