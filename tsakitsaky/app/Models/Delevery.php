<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Delevery extends Model
{
    use HasFactory;

    public static function genererPDF($date, $axe, $datas)
    {


        $imageData = file_get_contents('assets/static/images/logo.png');
        $imageDataEncoded = base64_encode($imageData);

        $html = '
        <!-- Styles CSS -->
        <style>
        @page {
            margin: 10mm;
          }
            body {
                font-family: Arial, sans-serif;
            }
            table {
                width: 100%;
                border-collapse: collapse;
            }
            th, td {
                border: 1px solid #ddd;
                padding: 8px;
                text-align: left;
            }
            th {
                background-color: #f2f2f2;
            }
            img {
                width: 100px;
                height: 100px;
            }
            .header {
                margin-bottom: 20px;
            }
            .header p {
                margin: 0;
            }
            .header p b {
                font-size: 16px;
            }
            .invoice-details {
                margin-bottom: 20px;
            }
            .invoice-details p {
                margin: 0;
            }
            .total {
                font-weight: bold;
            }
        </style>

        <!-- Contenu de la facture -->
        <img src="data:image/png;base64,' . $imageDataEncoded . '" alt="Logo">

        <div class="header">
            <p><b>MIANDRISON Hasinjo </b></p>
            <p><b>Toavina</b></p>
            <p><b>TsakiTsaky</b></p>
            <p>Lot IEC 09 Ambatofotsy GAra</p>
            <p>ATSIMONDRANO</p>
            <p>Telephone: 034 90 133 58 </p>
            <p>Mail: toavina@gmail.com</p>
        </div>


        <div class="invoice-details">
            <p>Date: ' . $date . '</p>
            <p>Axe : ' . $axe . '</p>

        </div>

        <table>
            <tr>
                <th>Vendeur</th>
                <th>Lieu</th>
                <th>Client</th>
                <th>Phone</th>
                <th>Nombre des packs</th>
                <th>Montant</th>
            </tr>';

            foreach($datas as $data){
            $html .= '
            <tr>
                <td>' .$data->seller. '</td>
                <td>' .$data->place. '</td>
                <td>' . $data->name . '</td>
                <td>' . $data->phone . '</td>
                <td style="text-align:right;">' .  number_format($data->number_pack,2,',',' ')   . '</td>
                <td style="text-align:right;">' . number_format($data->montant ,2,',',' '). '</td>

                </tr>';
        }

        $html .= '

        </table>

        <p class="total"><strong>Montant en Ariaryy:</strong>  </p>';

        return $html;
    }



}
