<?php

namespace App\Http\Controllers;

use App\Events\PaymentReport as EventsPaymentReport;
use App\Models\PaymentPlace;
use App\Models\PaymentReport;
use App\Models\ServiceCategory;
use Illuminate\Http\Request;
use DataTables;
use Exception;

class PaymentReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('payment_report.index');
    }

    public function list()
    {
        $data = PaymentReport::where('user_id',  auth()->id())->get();

        return Datatables::of($data)
            ->addColumn('action', function ($row) {

                $btn = "<button 
                            type='button' 
                            class='btn btn-danger btn-sm cancelBtn' 
                            data-mdb-toggle='modal'
                            data-mdb-target='#exampleModal'
                            data-id='{$row->id}'
                            >
                            Cancelar
                        </button>";

                return $btn;
            })
            ->rawColumns(['action'])
            ->make(true);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $paymentPlaces = PaymentPlace::all();
        $serviceCategories = ServiceCategory::all();
        return view('payment_report.create', ['paymentPlaces' => $paymentPlaces, 'serviceCategories' => $serviceCategories]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try {
            $image = $request->file('file');
            $imageName = uniqid('img_') . '.' . $image->getClientOriginalExtension();
            $uploadResponse = $image->move(public_path('images'), $imageName);
            if (!$uploadResponse) {
                throw new Exception('Error al guardar la imagen');
            }
            $data = $request->post();
            $paymentReport = [
                'user_id' => auth()->id(),
                'folio' => $data['folio'],
                'amount' => $data['amount'],
                'date' => $data['paymentDate'],
                'folio' => $data['folio'],
                'receipt_img' => 'https://envios.dagpacket.org/images/' . $imageName,
                'service_category_id' => $data['paymentServiceCategories'],
                'payment_places_id' => $data['paymentPlaces'],
                'created_at' => now()
            ];
            $paymentReportReply = PaymentReport::create($paymentReport);
            if (empty($paymentReportReply)) {
                throw new Exception('Error al registro');
            }
            event(new  \App\Events\PaymentReport('Se registro un reporte de pago'));
            return response()->json(['status' => 'success', 'response' => 'Se registro satisfactoriamente tu reporte']);
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json(['status' => 'error', 'response' => $th->getMessage()]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        try {
            $id = $request->id;
            $paymentDeleted = PaymentReport::find($id)->delete();
            if (empty($paymentDeleted)) {
                throw new Exception("El id de ese pago no exite");
            }
            return response()->json(['status' => 'success', 'response' => 'Se borro exitosamente el registo']);
            //code...
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json(['status' => 'error', 'response' => $th->getMessage()]);
        }
    }
}
