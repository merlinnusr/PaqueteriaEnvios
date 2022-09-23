<?php

namespace App\Http\Controllers\Datatables;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LogPurchase;
use Yajra\DataTables\Facades\DataTables as DT;
class LogPurchaseController extends Controller
{
    public function __invoke(Request $request)
    {
        try {
            if ($request->ajax()) {
                $picking = LogPurchase::where('user_id', auth()->id())->orderBy('created_at', 'desc')->get();
                return DT::of($picking)
                    ->make(true);
            }
        } catch (\Throwable $th) {
            //throw $th;
            return response()->json(['status' => 'error', 'response' => $th->getMessage()]);
        }
    }
}
