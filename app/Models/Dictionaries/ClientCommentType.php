<?php

namespace App\Models\Dictionaries;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $name
 * @property bool $enabled
 * @property int $order
 */
class ClientCommentType extends Model
{
    /** @var int The ID of inner status */
    public const inner = 1;

    /** @var int The ID of outer status */
    public const outer = 2;

    /** @var string Referenced table name. */
    protected $table = 'dictionary_client_comments_type';
}
