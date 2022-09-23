<?php

namespace App\Http\Controllers;

use App\Http\Requests\RegisterPostRequest;
use App\Models\User;
use App\Models\UserLicensee;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Routing\Route;
use Auth;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class AuthController extends Controller
{
    public function mix()
    {
        # code...

        phpinfo();
    }

    /**
     * Handle an authentication attempt.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        $email = $request->email;
        $password = $request->password;
        $data = [
            'email' => $email,
            'password' => $password
        ];
        if (Auth::attempt($data)) {
            $request->session()->regenerate();

            return redirect()->intended('home');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ]);
    }
    // public function createRoles()
    // {
    //     //return phpinfo();
    //     //Role::create(['name' => 'userF']);
    //     $usersF = User::where('role_id', 10)->get();
    //     foreach ($usersF as $userF) {
    //         $userF->assignRole('userF');
    //     }
    //     return var_dump('done');
    // }
    // public function createRoles1()
    // {

    //     $usersF = User::where('role_id', 9)->get();
    //     foreach ($usersF as $userF) {
    //         $userF->assignRole('cliente_corporativo');
    //     }
    // }

    
    public function logout(Request $request)
    {
        Auth::logout();

        session()->forget('pre_cart');
        session()->forget('cart');
        session()->flush();

        $request->session()->invalidate();
    
        $request->session()->regenerateToken();
        header("Refresh:0");
        
        return redirect('/'); 
    }
    public function register(Request $request)
    {
        return view('register');
    }
    public function create(RegisterPostRequest $request)
    {
        $licensee = NULL;
        try {
            DB::beginTransaction();
            
            if ($request->referencia){

                $licensee = User::where('email', $request->referencia)->where('role_id', 1)->first();
                if (empty($licensee)) {
                    throw new Exception('El licenciatario no existe');
                }
            }
            $user = (object)$request->validated();
            $passwordPlainText = $user->password;
            $user->password  = Hash::make($user->password);
            $userInsert = [
                'name' => $user->name,
                'email' => $user->email,
                'password_plain_text' => $passwordPlainText,
                'password' => $user->password,
                'role_id' => isset($request->referencia) ? 9 : 10,
                'wallet' => 0,
            ];
            
            $userCreated = User::create($userInsert);
            if(empty($userCreated)){
                throw new Exception('Hubo un error al crear el usuario');
            }
            $data = [
                'email' => $user->email,
                'password' => $passwordPlainText
            ];

            if(!empty($licensee)){
                $userLicensee = [
                    'user_id_licenciatario' =>  $userCreated->id,
                    'user_id_cliente' => $licensee->id
                ];
                $userLicenseeCreated = UserLicensee::create($userLicensee );
                if(empty($userLicenseeCreated)){
                    throw new Exception('Hubo un error al crear el usuario');
    
                }
                $userCreated->assignRole('cliente_corporativo');

            } else {
                $userCreated->assignRole('userF');

            }

            DB::commit(); 
            if (Auth::attempt($data)) {
                $request->session()->regenerate();
                
                return redirect()->intended('home');
            }
        } catch (\Throwable $th) {
            //throw $th;
            DB::rollBack();
            return back()->withErrors([
                'errors' => $th->getMessage(),
            ]);
    
        }
    }
}
