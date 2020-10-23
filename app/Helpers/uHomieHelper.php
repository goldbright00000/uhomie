<?php

namespace App\Helpers;
use DateTime;
use App\Role;

class uHomieHelper
{
    public static function getAvaliableRoles(){
        return Role::where('hidden', false)->get();
    }
}
