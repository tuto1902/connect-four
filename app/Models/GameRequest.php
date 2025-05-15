<?php

namespace App\Models;

use DateTimeInterface;
use DateTimeZone;
use Illuminate\Database\Eloquent\Model;

class GameRequest extends Model
{
    protected $fillable = [
        'user'
    ];

    protected function casts(): array
    {
        return [
            'created_at' => 'datetime:Y-m-d h:i A',
            'played_at' => 'datetime:Y-m-d h:i A'
        ];
    }

    protected function serializeDate(DateTimeInterface $date): string
    {
        return $date->setTimezone(new DateTimeZone('America/Denver'))->format('Y-m-d h:i A');
    }
}
