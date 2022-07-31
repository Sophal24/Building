<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dump extends Model
{
    use HasFactory;
    
    protected $table = 'dump';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'col1',
        'col2',
        'col3',
        'col4',
        'col5',
        'col6',
        'col7'
    ];
}
