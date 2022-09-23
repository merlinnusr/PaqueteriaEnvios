<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class SyncRoleController extends Controller
{
    //
    public function index($id)
    {
        $user = User::find($id);

        $user->assignRole('cliente_corporativo');

    }
}
