<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Model;

#[Fillable(['session_id', 'ip_hash', 'path', 'referrer', 'country', 'device', 'browser', 'platform', 'user_agent', 'is_bot'])]
class Visit extends Model
{
    protected function casts(): array
    {
        return ['is_bot' => 'boolean'];
    }
}
