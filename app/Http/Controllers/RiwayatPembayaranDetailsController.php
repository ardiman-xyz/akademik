<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
// use aipt\Http\Requests;
// use aipt\Http\Controllers\Controller;
use Input;
use DB;
use Redirect;
use Validator;
use PDO;
use Notifiable;
use Alert;
use PDF;

class RiwayatPembayaranDetailsController extends Controller
{
  public function __construct()
    {
          // $this->middleware('auth');
    }

    // index -----------------------------------------------------------------------------------------\\
    public function index()
    {
      $ReffPaymentId = Input::get('Reff_Payment_Id');
      $Fnc_Student_Payment = DB::table('fnc_student_payment')
      ->Where('Reff_Payment_Id','=',$ReffPaymentId)
      ->leftJoin('fnc_cost_item','fnc_student_payment.Cost_Item_Id','=','fnc_cost_item.Cost_Item_Id')
      ->join('mstr_term_year','fnc_student_payment.Term_Year_Id','=','mstr_term_year.Term_Year_Id')
      ->join('mstr_term','mstr_term_year.Term_Id','=','mstr_term.Term_Id')
      ->get();
      return View("riwayat_pembayaran_detail/partial")
            ->with('Fnc_Student_Payment',$Fnc_Student_Payment)
            ->with('ReffPaymentId',$ReffPaymentId);
    }
    //akhir index ===========================================================================================

    //cetak pdf ----------------------------------------------------------------------------------------------
    public function pdf($ReffPaymentId)
    {
      $Fnc_Student_Payment = DB::table('fnc_student_payment')
      ->Where('fnc_student_payment.Reff_Payment_Id','=',$ReffPaymentId)
      ->leftjoin('fnc_reff_payment','fnc_student_payment.Reff_Payment_Id','=','fnc_reff_payment.Reff_Payment_Id')
      ->leftJoin('fnc_cost_item','fnc_student_payment.Cost_Item_Id','=','fnc_cost_item.Cost_Item_Id')
      ->leftjoin('mstr_term_year','fnc_student_payment.Term_Year_Id','=','mstr_term_year.Term_Year_Id')
      ->leftjoin('mstr_term','mstr_term_year.Term_Id','=','mstr_term.Term_Id')
      ->leftjoin('acd_student','fnc_student_payment.Register_Number','=','acd_student.Register_Number')
      ->leftjoin('mstr_department','acd_student.Department_Id','=','mstr_department.Department_Id')
      ->leftjoin('mstr_faculty','mstr_department.Faculty_Id','=','mstr_faculty.Faculty_Id')
      ->leftjoin('fnc_bank','fnc_reff_payment.Bank_Id','=','fnc_bank.Bank_Id')
      ->get();
      $Student_Payment = $Fnc_Student_Payment->first();
      View()->share('Fnc_Student_Payment',$Fnc_Student_Payment);
      $pdf = PDF::loadView('riwayat_pembayaran_detail/pdf');
      return $pdf->stream('KuitansiPengganti_'.$Student_Payment->Reff_Payment_Code.'.pdf');
      return view('riwayat_pembayaran_detail/pdf');
    }
    // akhir cetak =============================================================================================
    

    public function kwitansi($ReffPaymentId)
    {
      $Fnc_Student_Payment = DB::table('fnc_student_payment')
      ->Where('fnc_student_payment.Reff_Payment_Id','=',$ReffPaymentId)
      ->leftjoin('fnc_reff_payment','fnc_student_payment.Reff_Payment_Id','=','fnc_reff_payment.Reff_Payment_Id')
      ->leftJoin('fnc_cost_item','fnc_student_payment.Cost_Item_Id','=','fnc_cost_item.Cost_Item_Id')
      ->leftjoin('mstr_term_year','fnc_student_payment.Term_Year_Id','=','mstr_term_year.Term_Year_Id')
      ->leftjoin('mstr_term','mstr_term_year.Term_Id','=','mstr_term.Term_Id')
      ->leftjoin('acd_student','fnc_student_payment.Register_Number','=','acd_student.Register_Number')
      ->leftjoin('mstr_department','acd_student.Department_Id','=','mstr_department.Department_Id')
      ->leftjoin('mstr_faculty','mstr_department.Faculty_Id','=','mstr_faculty.Faculty_Id')
      ->leftjoin('fnc_bank','fnc_reff_payment.Bank_Id','=','fnc_bank.Bank_Id')
      ->get();
      // dd($Fnc_Student_Payment);

      $Student_Payment = $Fnc_Student_Payment->first();
      
      View()->share('Fnc_Student_Payment',$Fnc_Student_Payment);
      $customPaper = array(0,0,680.315,396.85);
      $pdf = PDF::loadView('riwayat_pembayaran_detail/kwitansi')->setPaper($customPaper);
      return $pdf->stream('KuitansiPengganti_'.$Student_Payment->Reff_Payment_Code.'.pdf');
      return view('riwayat_pembayaran_detail/kwitansi');
    }
    
  }

  
