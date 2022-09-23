<?php

namespace App\Http\Controllers;

use App\Jobs\SendPickingUpdatedJob;
use App\Mail\TestAmazonSes;
use App\Models\Picking;
use App\Models\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Mail;

class HomeController extends Controller
{
    public function index()
    {
  
        return view('users.home.index');
    }

    public function per()
    {
        //$role = Role::create(['name' => 'cliente_corporativo']);
        
        $users = User::where('tipo_usuario',2)->get();
        foreach ($users as $user) {
            # code...

            $user->assignRole('cliente_corporativo');
        }
        

    }

    public function send()
    {
        $picking = Picking::find(5);
        dispatch(new SendPickingUpdatedJob($picking));

    }
}
