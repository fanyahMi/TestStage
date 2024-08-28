<?php

namespace App\Http\Controllers;

use App\Models\Pack;
use App\Models\Ticket;
use App\Models\Customer;
use App\Models\Place;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

class TicketController extends Controller
{

    public function showTicketSold()
    {
        // Paginate data with 10 items per page
        $viewData = DB::table('v_tickets_sold')->paginate(2);
        $priceMaterial = DB::table('v_price_material_student')->paginate(2);
        $total_amount = DB::table('v_ticket_total_amount')->paginate(2);

        return view("template.Layout", [
            'title' => 'Vente de billets',
            'page' => "ticket.TicketSold",
            'datas' => $viewData,
            'priceMaterials' =>  $priceMaterial,
            'total_amounts' =>  $total_amount,
        ]);
    }

    public function showWindTicket(){
        return view("template.Layout", [
            'title' => 'Vente de billets',
            'page' => "ticket.WindTicket",
            'packs' => Pack::where('state', 10)->get(),
            'places'=> Place::all(),
        ]);
    }

    public function showTicketPayment(){
        return view("template.Layout", [
            'title' => 'Payment des Billets',
            'page' => "ticket.TicketPayment",
            'customers' => Customer::all(),
        ]);
    }


    public function createTicket(Request $request){
        $rules = [
            'student' => 'required|string|max:8',
            'pack_id' => 'required',
            'number' => 'required|numeric|min:0',
            'place_id' => 'required',
        ];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()
                        ->withErrors($validator)
                        ->withInput();
        }

        $pack_id = $request->input('pack_id');
        $student = $request->input('student');
        $number = $request->input('number');
        $place_id =  $request->input('place_id');
        for ($i=0; $i < $number ; $i++) {
            Ticket::createTicket([
                'student' => $student,
                'pack_id' => $pack_id,
                'place_id' => $place_id,
            ]);
        }

        return redirect('/windTicket')->with('success', 'Vente billets avec sucess');

    }

    public function ticketPayment(Request $request)
    {
        $rules = [
            'ticketNumber1' => 'required|string|max:8',
            'ticketNumber2' => 'max:8',
            'paiment2' => 'required|numeric|min:0'
        ];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $ticket1 = $request->input('ticketNumber1');
        $ticket2 = $request->input('ticketNumber2');
        $paymentUnitaire = $request->input('paiment2');
        $customer_id = $request->input('customer_id');
        try {
            if ($ticket2 == null) {
                Ticket::updateTicket($ticket1 , $paymentUnitaire, $customer_id);
            } else {
                Ticket::updateTickets($ticket1, $ticket2, $paymentUnitaire);
            }
        } catch (\Throwable $th) {
            Session::flash('error', $th->getMessage());
        }
         return redirect()->back();
    }

    public function ticketPriceTotal(Request $request){
        $ticketNumber1 = $request->input('ticketNumber1');
        $ticketNumber2 = $request->input('ticketNumber2');

        $ticket1Exists = Ticket::where('id_ticket', $ticketNumber1)->exists();
        $ticket2Exists = false;

        if ($ticketNumber2 != null) {
            $ticket2Exists = Ticket::where('id_ticket', $ticketNumber2)->exists();
        }

        $errors = [];

        if (!$ticket1Exists) {
            $errors[] = 'Le ticket avec le numéro ' . $ticketNumber1 . ' n\'existe pas.';
        }
        if ($ticket2Exists == false &&   $ticketNumber2 != null){
            $errors[] = 'Le ticket avec le numéro ' . $ticketNumber2 . ' n\'existe pas.';
        }

        if ($ticket2Exists == false && $ticket1Exists == false) {
            $errors[] = 'Le ticket avec le numéro ' . $ticketNumber2 . ' n\'existe pas.';
        }

        if (!empty($errors)) {
            return response()->json([
                'success' => false,
                'errors' => $errors
            ]);
        }

        $query = DB::table('v_tickets_complet');
        $check = false;

        if ($ticketNumber2 == null) {
            $query->selectRaw('(price - payment) as reste_paye')
                ->where('id_ticket', '=', $ticketNumber1);
        } else {
            $query->selectRaw('SUM(price - payment) as reste_paye, COUNT(*) as nombre_de_tickets')
                ->where('id_ticket', '>=', $ticketNumber1)
                ->where('id_ticket', '<=', $ticketNumber2);
            $check = true;
        }

        $result = $query->first();
        $reste_paye = $result->reste_paye;
        $total = $result->reste_paye;
        if ($check) {
            $nombre_de_tickets = $result->nombre_de_tickets;
            $reste_paye = $reste_paye / $nombre_de_tickets;
        }

        return response()->json([
            'success' => true,
            'reste_paye' =>  $reste_paye,
            'total_paye'  =>   $total
        ]);
    }

    public function checkCustomer(Request $request){
        $ticketNumber1 = $request->input('ticketNumber1');

        $ticket1Exists = Ticket::where('id_ticket', $ticketNumber1)->exists();


        $errors = [];

        if (!$ticket1Exists) {
            $errors[] = 'Le ticket avec le numéro ' . $ticketNumber1 . ' n\'existe pas.';
        }

        if (!empty($errors)) {
            return response()->json([
                'success' => false,
                'errors' => $errors
            ]);
        }

        $ticket = Ticket::where('id_ticket', $ticketNumber1)->first();

        if ($ticket->customer_id != "not") {
            return response()->json([
                'success' => true,
                'customer_id' => true
            ]);
        } else {
            return response()->json([
                'success' => true,
                'customer_id' => false
            ]);
        }


    }


    public function importVente(Request $request){
        $rules = [
             'csv_file' => 'file|mimes:csv,txt',
            ];
            $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return redirect()->back()
                        ->withErrors($validator)
                        ->withInput();
        }
        $csvFile = $request->file('csv_file');
        $csvFilePath = $csvFile->store('csv_files');

        try {
            Ticket::importCsv($csvFilePath);
        } catch (\Throwable $th) {
            Session::flash('error', $th->getMessage());
        }
        return redirect()->back();

    }




}
