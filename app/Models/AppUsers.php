<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Model;

class AppUsers extends Model
{
	protected $table = "app_users";
    use HasFactory, Notifiable;

}
