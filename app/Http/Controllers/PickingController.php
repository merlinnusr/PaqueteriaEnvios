<?php

namespace App\Http\Controllers;

use App\Http\Requests\PickingCreatePost;
use App\Jobs\SendPickingUpdatedJob;
use App\Models\BranchOffice;
use App\Models\Cupon;
use App\Models\CuponUsed;
use App\Models\Picking;
use App\Models\User;
use App\Notifications\PickingUpdatedNotification;
use App\Services\CuponService;
use App\Services\PickingService;
use App\Services\WalletService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PickingController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('users.picking.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('users.picking.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PickingCreatePost $request)
    {
        $validatedRequest = (object)$request->validated();

        $nombre = $validatedRequest->nombre;
        $celular = $validatedRequest->celular;
        $contenido = $validatedRequest->contenido;
        $correo = $validatedRequest->correo;

        $cupon = $request->cupon;
        $for = $request->for;
        $photo =  $validatedRequest->foto;

        $price = (new PickingService())->getPrice($validatedRequest->paquete);
        $dimensions = (new PickingService())->getDimensiones($validatedRequest->paquete);
        if (!$price) {
            return back()->withErrors('Paquete inexistente');
        }




        $imagePath = (new PickingService())->uploadImage($photo);
        DB::beginTransaction();
        if (isset($cupon) && !empty($cupon)) {
            $validCupon = (object)(new CuponService())->checkCupon($cupon, $for, $price);
            $price = $validCupon->price; 
        }
        $dataSave = [
            'cliente_id' => auth()->id(),
            'nombre' => $nombre,
            'celular' => $celular,
            'correo' => $correo,
            'contenido' => $contenido,
            'foto_paquete' => $imagePath,
            'dimensiones' => $dimensions,
            'costo' => $price,
            'codigo' => uniqid(),
        ];
        $pickingCreated = Picking::create($dataSave);


        if (!empty($validCupon->cantidad)) {
            $cupon = Cupon::find($validCupon->id);
            if ($cupon->cantitad != null) {
                $cantidad = $cupon->cantidad - 1;
                $cuponData = [
                    'cantitad' => $cantidad
                ];
                Cupon::find($validCupon->id)->update($cuponData);
            }
            CuponUsed::create([
                'cupon_id' => $validCupon->id,
                'usuario_id' => auth()->id(),
                'pedido_id' => $pickingCreated->id,
                'usado_en' => 'picking',
                'fecha' => date('Y-m-d H:i:s'),
            ]);
        }
        if (!$pickingCreated) {
            DB::rollBack();

            return back()->withErrors(['error' => 'No se guardo el paquete']);
        }
        $wallet = (new WalletService())->walletUpdate(-floatval($price));

        if (!$wallet) {
            DB::rollBack();
        }

        DB::commit();

        return redirect('/pickup')->with('status', 'Profile updated!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        $picking = Picking::where('id', $id)->where('cliente_id', auth()->id())->first();
        if ($picking->sucursal_id != null){
            dd($picking->sucursal_id);
            $data['sucursal'] = [
                'sucursal' => BranchOffice::find($picking->sucursal_id)
            ];
        }
        $data['picking'] = $picking;
        $ahora = strtotime(date('Y-m-d H:i:s'));
        $fecha_clave = strtotime($picking->fecha_clave);
        
        $diasVencido = diffDays($fecha_clave,$ahora);
        $precioDia=0; 
        if($picking->dimensiones == 'carta') { $precioDia=10;}
        if($picking->dimensiones == '20x20x20') { $precioDia=10;}
        if($picking->dimensiones == '30x30x30') { $precioDia=13;}
        if($picking->dimensiones == '60x60x60') { $precioDia=20;}
        
        $total = $diasVencido * $precioDia;
        $data['extra_data'] = [
            'dias_vencidos' => $diasVencido,
            'precio_dia' => $precioDia,
            'total' => $total
        ];
        return view('users.picking.show', $data);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $picking = Picking::find($id)->where(auth()->id());
        $fecha_clave = strtotime($picking->fecha_clave);
        $diasVencido = diffDays(now(), $fecha_clave );
        $precioDia = 0;
        if ($picking->dimensiones == 'carta') {
            $precioDia = 10;
        }
        if ($picking->dimensiones == '20x20x20') {
            $precioDia = 10;
        }
        if ($picking->dimensiones == '30x30x30') {
            $precioDia = 13;
        }
        if ($picking->dimensiones == '60x60x60') {
            $precioDia = 20;
        }

        $totalApagar = $diasVencido * $precioDia;

        //$this->comprobarSaldo($totalApagar);

        //$saldo = $this->wallet_model->obtener_wallet_usuario(return_user()['id'])[0]['wallet'];
        $saldo = User::find(auth()->id())->getWalletBalance();
        if ($saldo < $totalApagar){
            return back()->withErrors('No tienes saldo');
        }
        $nuevoSaldo = $saldo - $totalApagar;

        //$this->wallet_model->actualizar_wallet(return_user()['id'], $nuevoSaldo);
        $user = [
            'wallet' => $nuevoSaldo
        ];
        User::find(auth()->id())->update($user);

        $precioAnterior = $picking->costo;
        $precioTotal = $precioAnterior + $totalApagar;


        Picking::find($picking->id)->update($pickingUpdate);
        $user = User::find(auth()->id());
        $picking = Picking::find($picking->id);
        dispatch(new SendPickingUpdatedJob($picking));

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
