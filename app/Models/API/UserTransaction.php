<?php

namespace App\Models\API;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;

class UserTransaction extends Authenticatable
{
	protected $table = "user_transactions";
    use HasApiTokens, Notifiable;
}
