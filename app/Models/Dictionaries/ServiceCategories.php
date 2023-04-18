<?php

namespace App\Models\Dictionaries;

/**
 * @property int $id
 * @property string $name
 * @property bool $enabled
 * @property int $order
 */
class ServiceCategories extends AbstractDictionary
{
    /** @var int Платная */
    public const paid = 1;

    /** @var int Бесплатная */
    public const free = 2;

    /** @var string Referenced table name. */
    protected $table = 'dictionary_service_categories';
}
