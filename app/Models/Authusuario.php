<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Laravel\Lumen\Auth\Authorizable;
use Illuminate\Database\Eloquent\Model;
use Laravel\Passport\HasApiTokens;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Danjdewhurst\PassportFacebookLogin\FacebookLoginTrait;

class Authusuario extends Model implements AuthenticatableContract, AuthorizableContract
{
    use HasApiTokens, Authenticatable, Authorizable, FacebookLoginTrait;

    protected $primaryKey = 'usuarioid';
    protected $guarded = ['usuarioid'];
    protected $hidden = ['password'];
}
