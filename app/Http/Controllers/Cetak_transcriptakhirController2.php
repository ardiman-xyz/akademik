<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\Registerst5s;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Input;
use DB;
use Redirect;
use Alert;
use PDF;
use Auth;

class Cetak_transcriptakhirController2 extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    $nim_ = Input::get('nim');
    $FacultyId = Auth::user()->Faculty_Id;

    if($FacultyId==""){
      $student = DB::table('acd_student')
      ->where('Nim', $nim_)->first();
    }else{
      $student = DB::table('acd_student')
      ->join('mstr_department','mstr_department.Department_Id','=','acd_student.Department_Id')
      ->join('mstr_faculty','mstr_faculty.Faculty_Id','mstr_department.Faculty_Id')
      ->where('mstr_faculty.Faculty_Id', $FacultyId)
      ->where('Nim', $nim_)->first();
    }
    if($FacultyId==""){
    $data = DB::table('acd_transcript')
    ->join('acd_course','acd_course.Course_Id','=','acd_transcript.Course_Id')
    ->join('acd_grade_letter','acd_grade_letter.Grade_Letter_Id','=','acd_transcript.Grade_Letter_Id')
    ->join('acd_student','acd_student.Student_Id','=','acd_transcript.Student_Id')
    ->where('acd_student.Nim',$nim_)
    ->select('acd_student.Full_Name','acd_transcript.*','acd_grade_letter.Grade_Letter','acd_course.*',
    DB::raw('round((acd_transcript.Sks*acd_transcript.Weight_Value),2) as weightvalue'))
    ->get();
  } else{
    $data = DB::table('acd_transcript')
    ->join('acd_course','acd_course.Course_Id','=','acd_transcript.Course_Id')
    ->join('acd_grade_letter','acd_grade_letter.Grade_Letter_Id','=','acd_transcript.Grade_Letter_Id')
    ->join('acd_student','acd_student.Student_Id','=','acd_transcript.Student_Id')
    ->join('mstr_department','mstr_department.Department_Id','=','acd_student.Department_Id')
    ->join('mstr_faculty','mstr_faculty.Faculty_Id','mstr_department.Faculty_Id')
    ->where('mstr_faculty.Faculty_Id', $FacultyId)
    ->where('acd_student.Nim',$nim_)
    ->select('acd_student.Full_Name','acd_transcript.*','acd_grade_letter.Grade_Letter','acd_course.*',
    DB::raw('round((acd_transcript.Sks*acd_transcript.Weight_Value),2) as weightvalue'))
    ->get();
  }

if($FacultyId==""){
    $query=DB::table('acd_transcript')
    ->select(DB::raw('SUM(acd_transcript.Sks) as jml_sks'),
    DB::raw('round(sum(acd_transcript.Sks*acd_transcript.Weight_Value),2) as jml_mutu'),
    DB::raw('round((sum(acd_transcript.Sks*acd_transcript.Weight_Value))/(SUM(acd_transcript.Sks)),2) as ipk'))
    ->join('acd_student','acd_student.Student_Id','=','acd_transcript.Student_Id')->where('acd_student.Nim',$nim_)->first();
} else{
  $query=DB::table('acd_transcript')
  ->select(DB::raw('SUM(acd_transcript.Sks) as jml_sks'),
  DB::raw('round(sum(acd_transcript.Sks*acd_transcript.Weight_Value),2) as jml_mutu'),
  DB::raw('round((sum(acd_transcript.Sks*acd_transcript.Weight_Value))/(SUM(acd_transcript.Sks)),2) as ipk'))
  ->join('acd_student','acd_student.Student_Id','=','acd_transcript.Student_Id')
  ->join('mstr_department','mstr_department.Department_Id','=','acd_student.Department_Id')
  ->join('mstr_faculty','mstr_faculty.Faculty_Id','mstr_department.Faculty_Id')
  ->where('mstr_faculty.Faculty_Id', $FacultyId)
  ->where('acd_student.Nim',$nim_)->first();
}
    $jumlahdata=DB::table('acd_transcript')->select(
    DB::raw('count(acd_transcript.Transcript_Id) as jmldata'))->join('acd_student','acd_student.Student_Id','=','acd_transcript.Student_Id')
    ->where('acd_student.Nim',$nim_)->first();



    return view('cetak/index_transcriptakhir')->with('jmldata', $jumlahdata)->with('student',$student)->with('query_', $query)->with('query',$data)->with('nim',$nim_);
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create()
  {
      //
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {
      //
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

  public function export(Request $request, $id)
  {
    $type = $request->type;

    $nim = $request->nim;
    $student=DB::table('acd_student')
    ->join('mstr_department','mstr_department.Department_Id','=','acd_student.Department_Id')
    ->join('mstr_education_program_type','mstr_education_program_type.Education_Prog_Type_Id','=','mstr_department.Education_Prog_Type_Id')
    ->join('mstr_faculty','mstr_faculty.Faculty_Id','=','mstr_department.Faculty_Id')
    ->leftjoin('acd_yudisium','acd_yudisium.Student_Id','=','acd_student.Student_Id')
    ->leftjoin('acd_thesis','acd_thesis.Student_Id','=','acd_student.Student_Id')
    ->leftjoin('mstr_graduate_predicate','acd_yudisium.Graduate_Predicate_Id','=','mstr_graduate_predicate.Graduate_Predicate_Id')
    ->where('Nim',$id)
    ->select('mstr_department.*','mstr_department.First_Title as Department_First_Title','mstr_department.Last_Title as Department_Last_Title','acd_student.*','acd_yudisium.*','mstr_education_program_type.*','mstr_faculty.*','mstr_graduate_predicate.Predicate_Name','mstr_graduate_predicate.Predicate_Name_Eng','acd_thesis.*',
      DB::raw('DATE_FORMAT(acd_student.Birth_Date, "%d-%m-%Y") as Birth_Date'))
    ->first();

    $transcript_nilai=DB::table('acd_transcript')
    ->select(DB::raw('SUM(acd_transcript.Sks) as jml_sks'),
    DB::raw('round(sum(acd_transcript.Sks*acd_transcript.Weight_Value),2) as jml_mutu'),
    DB::raw('round((sum(acd_transcript.Sks*acd_transcript.Weight_Value))/(SUM(acd_transcript.Sks)),2) as ipk'))
    ->join('acd_student','acd_student.Student_Id','=','acd_transcript.Student_Id')->where('acd_student.Nim',$id)
    ->first();

    $data_transcripts = DB::table('acd_transcript')
    ->select('acd_student.Full_Name','acd_transcript.*','acd_grade_letter.Grade_Letter','acd_course.*',
      DB::raw('round((acd_transcript.Sks*acd_transcript.Weight_Value),2) as weightvalue'))
    ->join('acd_course','acd_course.Course_Id','=','acd_transcript.Course_Id')
    ->join('acd_grade_letter','acd_grade_letter.Grade_Letter_Id','=','acd_transcript.Grade_Letter_Id')
    ->join('acd_student','acd_student.Student_Id','=','acd_transcript.Student_Id')
    ->where('acd_student.Nim',$id)
    ->get();

    $date = date('Y-m-d H:i:s');
    $term_yearcount=DB::table('mstr_term_year')
    ->where('Start_Date','<=',$date)
    ->where('End_Date','>=',$date)
    ->select('Start_Date','End_Date')
    ->count();

    $term_year1 ="";
    if($term_yearcount > 0){
      $term_year1=DB::table('mstr_term_year')
      ->where('Start_Date','<=',$date)
      ->where('End_Date','>=',$date)
      ->select('Term_Year_Id')
      ->first();
      $term_year1=$term_year1->Term_Year_Id;
    }

    $namadekan = '';
    $nidn = '';
    $functional_dekan = DB::table('acd_functional_position_term_year as func')
      ->join('emp_functional_position as efp','func.Functional_Position_Id','=','efp.Functional_Position_Id')
      ->join('emp_employee as ee','func.Employee_Id','=','ee.Employee_Id')
      // ->where([['efp.Functional_Position_Code','KP'],['func.Term_Year_Id',20201]])
      ->where([['efp.Functional_Position_Code','D'],['func.Term_Year_Id',$term_year1]])
      ->first();
    if($functional_dekan){
      $namadekan = ($functional_dekan->First_Title == null ? '':$functional_dekan->First_Title.', ').$functional_dekan->Name.($functional_dekan->Last_Title == null ? '':', '.$functional_dekan->Last_Title);
      $nidn = $functional_dekan->Nip;
    }

    $bagian = ceil(count($data_transcripts)/2);
    $sum_sks = 0;
    $sum_bnk = 0;
    $data = [];
    $q = 0;
    foreach (array_chunk($data_transcripts->toArray(), $bagian) as $x => $val) {
      $data_in = [];
      $z = 0;
      foreach ($val as $i => $key) {
        $data_in[$z]['Course_Code'] = $key->Course_Code;
        $data_in[$z]['Course_Name'] = $key->Course_Name;
        $data_in[$z]['Sks'] = $key->Sks;
        $data_in[$z]['Grade_Letter'] = $key->Grade_Letter;
        $data_in[$z]['Weight_Value'] = $key->Weight_Value;
        $data_in[$z]['Bnk_Value'] = ($key->Sks * $key->Weight_Value);
        $sum_sks = $sum_sks + $key->Sks;
        $sum_bnk = $sum_bnk + ($key->Sks * $key->Weight_Value);
        $z++;
      }
      $data[$q] = $data_in;
      $q++;
    }

    $date_cetak = strtotime($date);
    $date_cetak = Date('d-m-Y',$date_cetak);
    $date_cetak = $this->tgl_indo($date_cetak);

    $Graduate_Date = $date_cetak;
    if(isset($student->Graduate_Date)){
      $Graduate_Date = strtotime($student->Graduate_Date);
      $Graduate_Date = Date('d-m-Y',$Graduate_Date);
      $Graduate_Date = $this->tgl_indo($Graduate_Date);
    }

    $print['Transcript'] = $data;
    $print['Transcript_Number'] = $student->Transcript_Num;
    $print['Full_Name'] = $student->Full_Name;
    $print['National_Certificate_Number'] = $student->National_Certificate_Number;
    $print['Nim'] = $student->Nim;
    $print['Register_Number'] = $student->Register_Number;
    if($student->Birth_Date != null){
      $print['TTL'] = $student->Birth_Place.', '. ($student->Birth_Date == "00-00-0000" ? $date_cetak:$this->tgl_indo($student->Birth_Date));
    }else{
      $print['TTL'] = $student->Birth_Place.', '. $date_cetak;
    }
    $print['Faculty_Name'] = $student->Faculty_Name;
    $print['Department_Name'] = $student->Department_Name;
    $print['Title'] = ($student->Department_First_Title ? $student->Department_First_Title.', ':'').$student->Department_Last_Title;
    $print['Graduate_Date'] = $Graduate_Date;
    $print['sum_sks'] = $sum_sks;
    $print['sum_bnk'] = $sum_bnk;
    $print['Thesis_Title'] = $student->Thesis_Title;
    $print['Thesis_Title_Eng'] = $student->Thesis_Title_Eng;
    $print['ipk'] =  (number_format($sum_bnk / $sum_sks,2));
    // $print['ipk_terbilang'] = ucwords($this->terbilang('2.01'));
    $print['ipk_terbilang'] = ucwords($this->terbilang((number_format($sum_bnk / $sum_sks,2))));
    $print['Thesis_Title_Eng'] = $student->Thesis_Title_Eng;
    $print['Date_Cetak'] = $date_cetak;
    $print['namadekan'] = $namadekan;
    $print['nidn'] = $nidn;
    $print['predikat_lulus'] = 'predikat_lulus';


    View()->share([
      'print'=>$print
      ]);
    if ($request->to == "download") {
      $pdf = PDF::loadView('cetak/export_transcriptakhir2');
      return $pdf->download('Transkrip_akhir.pdf');
    }else{
      $pdf = PDF::loadView('cetak/export_transcriptakhir2');
      return $pdf->stream('Transkrip_akhir.pdf');
    }

  }

  function tgl_indo($tanggal){
    $bulan = array (
      1 =>   'Januari',
      'Februari',
      'Maret',
      'April',
      'Mei',
      'Juni',
      'Juli',
      'Agustus',
      'September',
      'Oktober',
      'November',
      'Desember'
    );
    $pecahkan = explode('-', $tanggal);

    // variabel pecahkan 0 = tanggal
    // variabel pecahkan 1 = bulan
    // variabel pecahkan 2 = tahun

    return $pecahkan[0] . ' ' . $bulan[ (int)$pecahkan[1] ] . ' ' . $pecahkan[2];
  }

  function konversi($x){
    $x = abs($x);
    $angka = array ("nol","satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan", "sembilan", "sepuluh", "sebelas");
    $temp = "";
    
    if($x < 12){
      $temp = " ".$angka[$x];
    }else if($x<20){
      $temp = $this->konversi($x - 10)." ";
    }else if ($x<100){
      $temp = $this->konversi($x/10)." ". $this->konversi($x%10);
    }else if($x<200){
      $temp = " ".$this->konversi($x-100);
    }else if($x<1000){
      $temp = $this->konversi($x/100)." ".$this->konversi($x%100);   
    }else if($x<2000){
      $temp = " ".$this->konversi($x-1000);
    }else if($x<1000000){
      $temp = $this->konversi($x/1000)." ".$this->konversi($x%1000);   
    }else if($x<1000000000){
      $temp = $this->konversi($x/1000000)." ".$this->konversi($x%1000000);
    }else if($x<1000000000000){
      $temp = $this->konversi($x/1000000000)." ".$this->konversi($x%1000000000);
    }
    
    return $temp;
  }
    
  function tkoma($x){
    $str = stristr($x,".");
    $ex = explode('.',$x);
    
    if(($ex[1]/10) >= 0.1){
      $a = abs($ex[1]);
    }
    $string = array("nol", "satu", "dua", "tiga", "empat", "lima", "enam", "tujuh", "delapan",   "sembilan","sepuluh", "sebelas");
    $temp = "";
  
    $a2 = $ex[1]/10;
    $pjg = strlen($str);
    $i =1;
      
    if($ex[1] == 00){ 
      $temp .= "Nol";
    }else if($a>=1 && $a< 12){  
      $temp .= "Nol ".$string[$a];
    }else if($a>=12 && $a < 20){   
      $temp .= $this->konversi($a - 10)." ";
    }else if ($a>20 && $a<100){   
      $temp .= $this->konversi($a / 10)." ". $this->konversi($a % 10);
    }else{
      if($a2<1){
        while ($i<$pjg){     
          $char = substr($str,$i,1);     
          $i++;
          $temp .= " ".$string[$char];
        }
      }
    }
    return $temp;
  }
  
  function terbilang($x){
    if($x<0){
      $hasil = "minus ".trim($this->konversi(x));
    }else{
      $poin = trim($this->tkoma($x));
      $hasil = trim($this->konversi($x));
    }
    
    if($poin){
      $hasil = $hasil." koma ".$poin;
    }else{
      $hasil = $hasil;
    }
    return $hasil;  
  }


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
  public function destroy($id)
  {
      //
  }

  public function exportBKP($id){
      // try {        
        $domPdfPath = base_path( 'vendor/dompdf/dompdf');
        \PhpOffice\PhpWord\Settings::setPdfRendererPath($domPdfPath);
        \PhpOffice\PhpWord\Settings::setPdfRendererName('DomPDF');
        \PhpOffice\PhpWord\Style\Cell::TEXT_DIR_BTLR;
        \PhpOffice\PhpWord\Style\Cell::TEXT_DIR_TBRL;
        $mhs = DB::table('acd_student')->where('Nim',$id)->orderby('Nim','asc')->get();
        // $my_template = new \PhpOffice\PhpWord\TemplateProcessor(public_path('templateTranskrip.docx'));

        $my_template = new \PhpOffice\PhpWord\PhpWord();
        $my_template = new \PhpOffice\PhpWord\TemplateProcessor(public_path('templateTranskrip.docx'));
        $data  = [];
        $q = 1;
        foreach ($mhs as $key) {
          $data[$q]['no'] = $q;
          $data[$q]['nim_row'] = $key->Nim;
          $data[$q]['email_row'] = $key->Email_Corporate;
          $data[$q]['name'] = $key->Full_Name;
          $data[$q]['email'] = $key->Email_Corporate;
          $q++;
        }
        
        $my_template->cloneRow('no', count($mhs));
        foreach ($data as $key) {
          $my_template->setValue('no#'. $key['no'], $key['no']);
          $my_template->setValue('nim_row#'. $key['no'], $key['nim_row']);
          $my_template->setValue('email_row#'. $key['no'], $key['email_row']);
        }
      //   $templateProcessor->setValues([
      //     'number' => '212/SKD/VII/2019',
      //     'name' => 'Alfa',
      //     'birthplace' => 'Bandung',
      //     'birthdate' => '4 Mei 1991',
      //     'gender' => 'Laki-Laki',
      //     'religion' => 'Islam',
      //     'address' => 'Jln. ABC no 12',
      //     'date' => date('Y-m-d'),
      // ]);
        
        $my_template->cloneBlock('CLONEBLOCK', 0, true, false, $data);

          // return response()->download(storage_path($mhs[0]->Full_Name.'.docx'));
        $my_template->saveAs(storage_path('trans.docx'));

        //Load temp file
        $objReader = \PhpOffice\PhpWord\IOFactory::load(storage_path('trans.docx'));

        //Save it
        $xmlWriter = \PhpOffice\PhpWord\IOFactory::createWriter($objReader , 'PDF');
        $xmlWriter->save(storage_path('trans.pdf'));

        return true;
        // $directory = public_path().'/foto_mhs/'.$entry_year->Entry_Year_Id.'';
      // } catch (\Exception $e) {
      //   return Redirect::back()->withErrors('Maximum execution time of 30 seconds exceeded');
      // }
    }
}
