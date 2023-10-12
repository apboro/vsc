<?php

namespace App\Models\Dictionaries;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $name
 * @property bool $enabled
 * @property int $order
 */
class ClientCommentActionType extends Model
{
    /** @var int */
    public const close_contract = 1;

    /** @var int */
    public const change_subscription = 2;

    /** @var int */
    public const add_subscription = 3;

    /** @var int */
    public const lead_card = 4;

    /** @var int */
    public const lead_convert = 5;

    /** @var string Referenced table name. */
    protected $table = 'dictionary_client_comment_action_types';
}
