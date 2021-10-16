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
      'date',
      'message',
      'file'
    ];

    protected $casts = [
      'facilities' => 'array',
      'date' => 'datetime'
    ];
}
