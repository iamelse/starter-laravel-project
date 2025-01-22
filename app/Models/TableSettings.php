<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Modules\User\Models\User;

class TableSettings extends Model
{
    protected $guarded = ['id'];

    /**
     * Get the user that owns the TableSettings
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
