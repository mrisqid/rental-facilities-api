<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rental extends Model
{
    use HasFactory;

    protected $fillable = [
      'name',
      'identity_card',
      'phone_number',
      'organization_name',
      'organization_address',
      'organization_image',
      'facilities',
      'date_start',
      'date_end',
      'message',
      'file'
    ];

    protected $casts = [
      'date_start' => 'date',
      'date_end' => 'date'
    ];
}
