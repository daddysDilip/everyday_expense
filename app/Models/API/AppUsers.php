<?php

namespace App\Models\API;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Laravel\Passport\HasApiTokens;
use Illuminate\Foundation\Auth\User as Authenticatable;

class AppUsers extends Authenticatable implements MustVerifyEmail
{
	protected $table = "app_users";
    use HasApiTokens, Notifiable;

}
