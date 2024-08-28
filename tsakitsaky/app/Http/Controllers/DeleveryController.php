<?php

namespace App\Http\Controllers;

use App\Models\Axe;
use App\Models\Delevery;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use PDF;
use Rap2hpoutre\FastExcel\FastExcel;


class DeleveryController extends Controller
{
    public function index(){
        return view("template.Layout", [
            'title' => 'Clients',
            'page' => "delevery.DeleveryDate",
            'axes' => Axe::all(),
        ]);
    }

    public function store(Request $request){
        $rules = [
            'date' => 'required|date',
            'axe_id' => 'required',
        ];

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()
                        ->withErrors($validator)
                        ->withInput();
        }

        $date = $request->input('date');
        $axe_id = $request->input('axe_id');
        $viewData = DB::table('v_delevery')
                                ->where('date', $date)
                                ->where('axe_id', $axe_id)
                                ->get();

                                return view("template.Layout", [
                                    'title' => 'Livraison',
                                    'page' => "delevery.DeleveryDetail",
                                    'datas' => $viewData,
                                    'date'=> $date,
                                    'axe_id'=> $axe_id,
                                ]);

    }

    public function exportpdf(Request $request){
        $date = $request->input('date');
        $axe = $request->input('axe_id');
        $datas = DB::table('v_delevery')
            ->where('date', $date)
            ->where('axe_id', $axe)
            ->get();
            $html = Delevery::genererPDF($date, $axe, $datas);
            $pdf = PDF::loadHTML($html);
            return $pdf->download('livraison.pdf');
    }

    public function exportToExcelStateTickets()
    {
        $datas = DB::table('v_ticket_packs')
                                    ->get();
        $filename = 'ticketState_' . date('Y_m_d_H_i_s') . '.xlsx';
        return (new FastExcel($datas))->download($filename);
    }


}
