<?php

namespace App\Models\Dictionaries;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Acquiring extends AbstractDictionary
{
    protected $table = 'dictionary_acquiring';

    protected $with = 'bank';
    protected $appends = ['bank_name'];

    public function bank(): HasOne
    {
        return $this->hasOne(Bank::class, 'id', 'bank_id');
    }

    public function getBankNameAttribute()
    {
            return $this->bank->name;
    }

}
