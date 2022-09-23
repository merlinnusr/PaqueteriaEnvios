<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class LogPurchaseController extends Controller
{
    public function __invoke()
    {
        return view('users.home.movements');
    }
    public function overweight(){
        // $listado = DB::select('select * from overweight_labels where id = ? ', [1]);
        $id = Auth::id();
        $listado = DB::table('overweight_labels')
            ->select('*', 'overweight_labels.deleted_at as estatus')
            ->join('paquetes_envio', 'paquetes_envio.object_id_quote', '=', 'overweight_labels.packet_id')
            ->join('users','users.id','=','paquetes_envio.user')
            // ->where('overweight_labels.deleted_at','!=', NULL)
            ->where('paquetes_envio.user', '=', $id)
            ->orderBy('overweight_labels.id', 'desc')
            ->get();
        return view('users.home.sobrepeso', compact('listado', 'id'));
    }

    public function cancelados(){
        $id = Auth::id();
        $listado = DB::table('paquetes_envio')
            ->join('pedidos_envio','pedidos_envio.id_paquete','=','paquetes_envio.id')
            ->join('paquetes_cancelados','pedidos_envio.paquetes_cancelados_id','=','paquetes_cancelados.id')                        
            ->where('user',$id)
            ->where('paquetes_envio.activo','<=',0)
            ->orderBy('paquetes_envio.id','desc')
            ->get();

        return view('users.home.cancelados', compact('listado','id'));
    }
    public function manuales(){
        $id = Auth::id();
        $listado = DB::table('paquetes_manuales')            
            ->where('user',$id)
            ->where('activo','=',1)
            ->orderBy('fecha_envio','desc')
            ->get();

        return view('users.home.manuales', compact('listado','id'));
    }
}
