<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;
use League\Csv\Reader;

class Ticket extends Model
{
    use HasFactory;
    protected $table = "tickets";

    protected $fillable = [
        'student',
        'date',
        'pack_id',
        'payment',
        'payment_date',
        'customer_id',
        'place_id',
    ];

    public $timestamps = false;
    protected $primaryKey = "id_ticket";
    public $incrementing = false;

    public static function createTicket(array $attributes = [])
    {
        $defaultAttributes = [
            'date' => Carbon::now(),
            'state' => 'default',
        ];
        $mergedAttributes = array_merge($defaultAttributes, $attributes);
        return static::create($mergedAttributes);
    }

    public static function updateTickets($idTicket1, $idTicket2, $payment)
    {
        try {
            $ticket1 = self::findOrFail($idTicket1);
            $ticket2 = self::findOrFail($idTicket2);
        } catch (ModelNotFoundException $e) {
            throw new \Exception('Un ou plusieurs numéros de ticket spécifiés n\'existent pas.');
        }

        $currentDate = Carbon::now()->toDateString();

        $ticketsWithState10 = self::whereBetween('id_ticket', [$idTicket1, $idTicket2])
            ->where('state', 10)
            ->pluck('id_ticket')
            ->toArray();

        if (!empty($ticketsWithState10)) {
            $message = 'Les tickets suivants sont déjà payés : ' . implode(', ', $ticketsWithState10);
            throw new \Exception($message);
        }

        $remainingPayments = DB::table('v_tickets_complet')
            ->selectRaw('(price - payment) as remaining_payment, price, id_ticket')
            ->whereBetween('id_ticket', [$idTicket1, $idTicket2])
            ->get();

        foreach ($remainingPayments as $remainingPayment) {
            $newPayment = $payment + $remainingPayment->remaining_payment;

            $ticketState = ($remainingPayment->remaining_payment == 0) ? 10 : 5;

            self::where('id_ticket', $remainingPayment->id_ticket)
                ->update([
                    'payment' => $newPayment,
                    'payment_date' => $currentDate,
                    'state' => $ticketState
                ]);

        }
    }

    public static function updateTicket($idTicket, $payment, $customer_id)
    {

        try {
            $ticket = self::findOrFail($idTicket);
        } catch (ModelNotFoundException $e) {
            throw new \Exception('Le numéro de ticket spécifié n\'existe pas.');
        }

        $ticketsWithState10 = self::where('id_ticket', '=', $idTicket)
            ->where('state', 10)
            ->pluck('id_ticket')
            ->toArray();

        if (!empty($ticketsWithState10)) {
            $message = 'Le ticket de numero '. implode(', ', $ticketsWithState10) .' déjà payé : ' ;
            throw new \Exception($message);
        }

        $remainingPayment = DB::table('v_tickets_complet')
            ->selectRaw('(price - payment) as remaining_payment, price')
            ->where('id_ticket', $idTicket)
            ->first();
        $newPayment = $ticket->payment + $payment;
        $currentDate = Carbon::now();
        try {
            $ticketState = 5;

            if ($newPayment >= $remainingPayment->price) {
                $newPayment = $remainingPayment->price;
                $ticketState = 10;
            }

           if($customer_id == "0"){
                self::where('id_ticket', $idTicket)
                ->update([
                    'payment' => $newPayment,
                    'payment_date' => $currentDate,
                    'state' => $ticketState
                ]);
           } else{
            self::where('id_ticket', $idTicket)
                ->update([
                    'payment' => $newPayment,
                    'payment_date' => $currentDate,
                    'state' => $ticketState,
                    'customer_id' => $customer_id
                ]);
           }


        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public static function importCsv($csvFilePath ){
        $csv = Reader::createFromPath(storage_path('app/' . $csvFilePath), 'r');
        $csv->setHeaderOffset(0);
        $data = $csv->getRecords();
        foreach ($data as $row) {
            if ($row['Quantite'] <= 0) {
                throw new Exception('La quantité doit etre positif');
            }
            self::checkPack($row['Code_pack']);
            self::checkUser($row['Code_vendeur']);
            $place_id = self::checkPlace($row['Axe_livraison']);
            $dateString = $row['Date'];
            $date = \DateTime::createFromFormat('d/m/Y', $dateString);
            $formattedDate = $date->format('Y-m-d');
            for($i = 0; $i < $row['Quantite']; $i++) {
                Ticket::create([
                    'student' => $row['Code_vendeur'],
                    'pack_id' => $row['Code_pack'],
                    'state' => 0,
                    'date' => $formattedDate,
                    'place_id' => $place_id,
                ]);
            }
        }
    }

    public static function checkPlace($place){
        $check =  Place::where('place', $place)->exists();
        $placeRecord = Place::where('place', $place)->first();
        if ($placeRecord) {
            return $placeRecord->id_place;
        }else{
            Place::create([
                'place'=> $place
            ]);
            $placeRecord = Place::where('place', $place)->first();
            return $placeRecord->id_place;
        }

    }

    public static function checkUser($code){
        $check =  User::where('id_user', $code)->exists();
        if( $check == false ){
            User::create([
                'id_user'=> $code,
                 'name' => $code,
                 'first_name' =>$code,
                 'email' =>$code,
                 'passwords' => $code,
                 'role'=> 0
            ]);

            User::where('name', $code)->update([
                'id_user'=> $code,
            ]);
        }
    }

    public static function checkPack($code){
        $checkPack =  Pack::where('id_pack', $code)->exists();
        if($checkPack == false){
            Pack::create([
                'id_pack'=> $code,
                'name'=> $code,
                'price'=> 40000,
                'picture'=> "Not found",
                'state'=>10,
            ]);
            Pack::where('name', $code)
                ->update([
                'id_pack' => $code
            ]);
        }
    }
}
