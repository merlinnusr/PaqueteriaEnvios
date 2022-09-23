<?php

namespace App\Http\Controllers\Datatables;

use App\Http\Controllers\Controller;
use App\Models\Picking;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables as DT;

class PickingController extends Controller
{
    public function index(Request $request)
    {

        if ($request->ajax()) {
            $picking = Picking::where('cliente_id', auth()->id())->orderBy('fecha_creacion', 'desc')->get();
            $csrf = csrf_field();
            $pdfInvoiceUrl = route('invoice.pdf');
            return DT::of($picking)
                ->addIndexColumn()
                ->addColumn('status', function ($row) {
                    $status = NULL;
                    if (isset($row->fecha_entrega)) {
                        $status =  "Entregado";
                    } else if (null == $row->fecha_recepcion) {
                        $status =  "No recepcionado aun";
                    } else if (null == $row->fecha_entrega && null != $row->fecha_recepcion && date('Y-m-d H:i:s') <= $row->fecha_clave) {
                        $status =  "Listo para entregar>";
                    } else if (null == $row->fecha_entrega && null != $row->fecha_recepcion && date('Y-m-d H:i:s') > $row->fecha_clave) {
                        $vencido = true;
                        $status =  "BLOQUEADO";
                    }




                    return $status;
                })

                ->addColumn('invoices', function ($row) use ($pdfInvoiceUrl, $csrf) {
                    $pickingDetalles = route('picking.show', ['id' => $row->id]);

                    $forms = "
                            <form method='post' action='{$pdfInvoiceUrl}'>
                                {$csrf}
                                <input type='text' hidden name='idPedido' value='{$row->id}' readonly>
                                <button type='submit'  onClick='this.form.submit(); this.disabled=true; this.innerHtml='Espere'; '  value='pdf' name='pdf' class='' style='background: transparent; color: white; border-color:transparent;'>
                                    <i class='fas fa-file-pdf fa-2x' style='color:red'></i>                                
                                </button>
                            </form>

                            <a class='btn btn-success' href='{$pickingDetalles}'>
                                    Ver
                            </a>";
                    return $forms;
                })
                ->addColumn('price', function ($row){
                    $price = number_local_format($row->costo);

                    return $price;
                })

                ->rawColumns(['price', 'invoice', 'invoices'])
                ->make(true);
        }
    }
    public function delete($index)
    {
        $cart = session('cart');
        unset($cart[$index]);
        session(['cart' => $cart]);
        return redirect()->route('checkout.show');
    }
}
