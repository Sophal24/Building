<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoomInformation extends Model
{
    use HasFactory;

    protected $table = 'room_information';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'room_type',
        'floor',
        'section',
        'timestamp',
        'additional_info'
    ];

    public function building()
    {
        return $this->hasOne(Building::class, 'id', 'room_id');
    }
}
