<?php

namespace App\Http\Models;

use Illuminate\Support\Facades\DB;

class RiwayatPembayaranData extends BaseModel
{

    static function getRiwayatData($registernumber)
    {
        return $result = DB::table('fnc_reff_payment')
            ->select(DB::raw('Reff_Payment_Id, Payment_Date, fnc_bank.Bank_Name, sum(fnc_reff_payment.Total_Amount) as Total_Amount'))
            ->join('fnc_bank', 'fnc_reff_payment.Bank_Id', '=', 'fnc_bank.Bank_Id')
            ->where('fnc_reff_payment.Register_Number', $registernumber)
            ->groupBy('Reff_Payment_Id')
            ->get();

    }

}