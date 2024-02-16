<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\Registerst5s;
use Illuminate\Http\Request;
use Input;
use DB;
use Redirect;
use Alert;
use Storage;
use Auth;
use Image;
use File;
use Excel;
use App\GetDepartment;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

class StudentController extends Controller
{

  public function __construct()
  {
    $this->middleware('access:CanView', ['only' => ['index','show']]);
    $this->middleware('access:CanAdd', ['only' => ['create','store']]);
    $this->middleware('access:CanEdit', ['except' => ['index','create','store','show','destroy']]);
    $this->middleware('access:CanDelete', ['except' => ['index','create','store','show','edit','update']]);

  }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
     public function index(Request $request)
     {
       $this->validate($request,[
         'rowpage'=>'numeric|nullable'
       ]);
       $search = Input::get('search');
       $rowpage = Input::get('rowpage');
       $FacultyId = Auth::user()->Faculty_Id;
       $DepartmentId = Auth::user()->Department_Id;

       if ($rowpage == null || $rowpage <= 0) {
         $rowpage = 10;
       }
       $entry_year = Input::get('entry_year');
       $department = Input::get('department');
       $status = Input::get('status');

       if($status == null){
       }


       $select_entry_year = DB::table('mstr_entry_year')
       ->orderBy('mstr_entry_year.Entry_Year_Id', 'desc')
       ->get();

       $select_status = DB::table('mstr_status')
       ->orderby('Status_Id')->get();

      $select_department = GetDepartment::getDepartment();
      $dpt = [];
      $p = 0;
      foreach ($select_department as $key) {
        $dpt[$p]=$key->Department_Id;
        $p++;
      }

      if(empty($department) && empty($entry_year) && empty($search)){
        $data = [];
        $data = $this->paginate($data);
        $count_dep = 0;
      }else{
        $data = DB::table('acd_student')
          ->join('mstr_entry_year','mstr_entry_year.Entry_Year_Id','=','acd_student.Entry_Year_Id')
          ->join('mstr_department','mstr_department.Department_Id','=','acd_student.Department_Id')
          ->leftjoin('mstr_class_program','mstr_class_program.Class_Prog_Id','=','acd_student.Class_Prog_Id')
          ->leftjoin('mstr_class','mstr_class.Class_Id','=','acd_student.Class_Id')
          ->leftjoin('mstr_gender','mstr_gender.Gender_Id','=','acd_student.Gender_Id')
          ->leftjoin('mstr_status','mstr_status.Status_Id','=','acd_student.Status_Id')
          // ->where('acd_student.Entry_Year_Id', '>=' ,'2012')
          ->where('acd_student.Entry_Year_Id', 'like' ,'%'.$entry_year.'%')
          ->where('acd_student.Department_Id','like' ,'%'.$department.'%')
          // ->where('acd_student.Status_Id','like' , '%'.$status.'%')
          ->where('acd_student.Status_Id','!=' , null)
          ->where(function($query){
            $search = Input::get('search');
            $query->whereRaw("lower(Full_Name) like '%" . strtolower($search) . "%'");
            $query->orwhere('Nim', 'LIKE', '%'.$search.'%');
          })
          ->orderBy('acd_student.Nim', 'asc')
          ->groupby('acd_student.Nim');
          // ->paginate($rowpage);

          $count_dep = count($data->get());
          $data = $data->paginate($rowpage);
          $data->appends(['search'=> $search, 'rowpage'=> $rowpage, 'entry_year'=> $entry_year, 'department'=> $department,'status'=>$status]);
        }

       return view('acd_student/index')->with('select_status',$select_status)->with('status',$status)->with('count_dep',$count_dep)->with('query',$data)->with('search',$search)->with('rowpage',$rowpage)->with('select_entry_year', $select_entry_year)->with('entry_year', $entry_year)->with('select_department', $select_department)->with('department', $department);
     }
     public function paginate($items, $perPage = 5, $page = null, $options = [])
      {
          $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);
          $items = $items instanceof Collection ? $items : Collection::make($items);
          return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);
      }
     // public function modal()
     // {
     //   return view('mstr_entry_year/modal');
     // }
     /**
      * Show the form for creating a new resource.
      *
      * @return \Illuminate\Http\Response
      */
     public function create()
     {
         $search = Input::get('search');
         $page = Input::get('page');
         $rowpage = Input::get('rowpage');
         $department_id = Input::get('department');
         $entry_year_id = Input::get('entry_year');
         $FacultyId = Auth::user()->Faculty_Id;

         $entry_year = DB::table('mstr_entry_year')->where('Entry_Year_Id', $entry_year_id)->get();

        $Department = DB::table('mstr_department')
        ->where('Department_Id', $department_id)->get();


         $gender = DB::table('mstr_gender')->get();
         $city = DB::table('mstr_city')->get();
         $citizenship = DB::table('mstr_citizenship')->get();
         $religion = DB::table('mstr_religion')->get();
         $marital = DB::table('mstr_marital_status')->get();
         $blood = DB::table('mstr_blood_type')->get();
         $high_school_major = DB::table('mstr_high_school_major')->get();
         $class_program = DB::table('mstr_class_program')->get();
         $class = DB::table('mstr_class')->get();

         return view('acd_student/create')->with('entry_year_id', $entry_year_id)->with('department_id', $department_id)->with('entry_year', $entry_year)->with('department', $Department)->with('gender', $gender)->with('city', $city)->with('citizenship', $citizenship)
         ->with('religion', $religion)->with('marital', $marital)->with('blood', $blood)->with('high_school_major', $high_school_major)->with('class_program', $class_program)->with('class', $class)->with('search',$search)->with('page', $page)->with('rowpage', $rowpage);
     }


     /**
      * Store a newly created resource in storage.
      *
      * @param  \Illuminate\Http\Request  $request
      * @return \Illuminate\Http\Response
      */
     public function store(Request $request)
     {
       $this->validate($request,[
         'Entry_Year_Id'=>'required',
         'Department_Id' => 'required',
         'Nim' => 'required|numeric|unique:acd_student',
         'Register_Number' => 'required|numeric',
         'Full_Name' => 'required',
         'file'=>'max:10000'
       ],['file.max' => 'Foto harus kurang dari 8Mb']);

             $Department_Id = Input::get('Department_Id');
             $Entry_Year_Id = Input::get('Entry_Year_Id');
             $Nim = Input::get('Nim');
             $Register_Number = Input::get('Register_Number');
             $Full_Name = Input::get('Full_Name');
             $First_Title = Input::get('First_Title');
             $Last_Title = Input::get('Last_Title');
             $Gender_Id = Input::get('Gender_Id');
             $Birth_Place = Input::get('Birth_Place');
             $Birth_Place_Id = Input::get('Birth_Place_Id');
             $Birth_Date = Input::get('Birth_Date');
             $Citizenship_Id = Input::get('Citizenship_Id');
             $Religion_Id = Input::get('Religion_Id');
             $Marital_Status_Id = Input::get('Marital_Status_Id');
             $Blood_Id = Input::get('Blood_Id');
             $High_School_Major_Id = Input::get('High_School_Major_Id');
             $Phone_Mobile = Input::get('Phone_Mobile');
             $Class_Prog_Id = Input::get('Class_Prog_Id');
             $Class_Id = Input::get('Class_Id');

try {
       $u =  DB::table('acd_student')
       ->insert(
       ['Department_Id' => $Department_Id,'Entry_Year_Id' => $Entry_Year_Id,'Nim' => $Nim,'Register_Number' => $Register_Number,'Full_Name' => $Full_Name,
        'First_Title' => $First_Title, 'Last_Title' => $Last_Title, 'Gender_Id' => $Gender_Id, 'Birth_Place'=> $Birth_Place, 'Birth_Place_Id' => $Birth_Place_Id,
        'Birth_Date' => $Birth_Date, 'Citizenship_Id' => $Citizenship_Id, 'Religion_Id' => $Religion_Id, 'Marital_Status_Id' => $Marital_Status_Id, 'Blood_Id' => $Blood_Id,
        'High_School_Major_Id' => $High_School_Major_Id, 'Phone_Mobile' => $Phone_Mobile, 'Class_Prog_Id' => $Class_Prog_Id, 'Class_Id' => $Class_Id]);


        if ($u) {
          $id = DB::getPdo()->lastInsertId();
          $entry_year = DB::table('acd_student')->where('Student_Id', $id)->select('Entry_Year_Id')->first();
          $directory = public_path().'/foto_mhs/'.$entry_year->Entry_Year_Id.'';
          $uploadfile = $request->file('file');
          if ($uploadfile != null) {
            $image       = $request->file('file');
            if( $exists = File::exists('/foto_mhs/'.$entry_year->Entry_Year_Id.'')){
              //$img = Image::make($image)->resize(151.18110236, 226.77165354)->save(storage_path('app/public/foto_mhs/'.$entry_year->Entry_Year_Id.'/'.$Nim.'.jpg'));
		$img = Image::make($image)->resize(151.18110236, 226.77165354)->save('foto_mhs/'.$entry_year->Entry_Year_Id.'/'.$Nim.'.jpg');

              // $Extension = $request->file('file')->guessExtension();
              // $file = $Nim.".jpg";
              // $path = $uploadfile->storeAs('public/foto_mhs',$file);
            }
            else{
              File::makeDirectory($directory, $mode = 0777, true, true);
              //$img = Image::make($image)->resize(151.18110236, 226.77165354)->save(storage_path('app/public/foto_mhs/'.$entry_year->Entry_Year_Id.'/'.$Nim.'.jpg'));
		$img = Image::make($image)->resize(151.18110236, 226.77165354)->save('foto_mhs/'.$entry_year->Entry_Year_Id.'/'.$Nim.'.jpg');
            }
          }

        }

       return Redirect::back()->withErrors('Berhasil Menambah Data Mahasiswa');
     } catch (\Exception $e) {
       return Redirect::back()->withErrors('Gagal Menambah Data Mahasiswa');
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
       $search = Input::get('search');
       $page = Input::get('page');
       $rowpage = Input::get('rowpage');
       $department_id = Input::get('department');
       $entry_year_id = Input::get('entry_year_id');
       $FacultyId = Auth::user()->Faculty_Id;


       $entry_year = DB::table('mstr_entry_year')
       ->where('Entry_Year_Id', $entry_year_id)->get();

  $Department = DB::table('mstr_department')
  ->where('Department_Id', $department_id)->get();


       $gender = DB::table('mstr_gender')->get();
       $city = DB::table('mstr_city')->get();
       $citizenship = DB::table('mstr_citizenship')->get();
       $religion = DB::table('mstr_religion')->get();
       $marital = DB::table('mstr_marital_status')->get();
       $blood = DB::table('mstr_blood_type')->get();
       $high_school_major = DB::table('mstr_high_school_major')->get();
       $class_program = DB::table('mstr_class_program')->get();
       $class = DB::table('mstr_class')->get();

       $data = DB::table('acd_student')
       ->join('mstr_department','mstr_department.Department_Id','=','acd_student.Department_Id')
       ->leftjoin('acd_student_address','acd_student_address.Student_Id','=','acd_student.Student_Id')
       ->where('acd_student.Student_Id', $id)->where('acd_student.Department_Id', $department_id)
       ->select('acd_student.*','mstr_department.Department_Dikti_Sk_Date',
       'acd_student_address.Address',
       'acd_student_address.Dusun',
       'acd_student_address.Sub_District',
       'acd_student_address.District_Id')->get();
      //  dd($data->Department_Dikti_Sk_Date);
       if(count($data) == 0) { return view('404'); }

       $mstr_education = DB::table('mstr_education_type')->get();
       $mstr_job = DB::table('mstr_job_category')->get();
       $mstr_tinggal = DB::table('mstr_residence_type')->get();
       $mstr_transport = DB::table('mstr_transport_type')->get();

       $upload = DB::table('reg_camaru_attachment as a')
              ->join('reg_camaru as b','a.Camaru_Id','=','b.Camaru_Id')->where('b.Reg_Num', $data[0]->Register_Number)->first();

      
      if($upload != null){
        $requir=DB::table('reg_camaru_register_requirement')
                  ->join('mstr_camaru_requirement','mstr_camaru_requirement.Camaru_Requirement_Id','=','reg_camaru_register_requirement.Camaru_Requirement_Id')
                  ->where('Register_Type_Id',  $upload->Register_Type_Id)->get();

        $requir2=DB::table('reg_camaru_department_requirement')
                ->join('mstr_camaru_requirement','mstr_camaru_requirement.Camaru_Requirement_Id','=','reg_camaru_department_requirement.Camaru_Requirement_Id')
                ->where('Department_Id',  $upload->Department_Accepted_Id)->get();
      }else{
        $requir = null;
        $requir2 = null;
      }

       return view('acd_student/edit')
       ->with('query_edit', $data)
       ->with('id', $id)
       ->with('upload', $upload)
       ->with('requir', $requir)
       ->with('requir2', $requir2)
       ->with('entry_year_id', $entry_year_id)
       ->with('department_id', $department_id)
       ->with('entry_year', $entry_year)
       ->with('department', $Department)
       ->with('gender', $gender)
       ->with('city', $city)
       ->with('citizenship', $citizenship)
       ->with('mstr_education', $mstr_education)
       ->with('mstr_job', $mstr_job)
       ->with('mstr_tinggal', $mstr_tinggal)
       ->with('mstr_transport', $mstr_transport)
       ->with('religion', $religion)->with('marital', $marital)->with('blood', $blood)->with('high_school_major', $high_school_major)->with('class_program', $class_program)->with('class', $class)->with('search',$search)->with('page', $page)->with('rowpage', $rowpage);
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
       $this->validate($request,[
         'Nim' => 'required|numeric',
         'Register_Number' => 'required|numeric',
         'Full_Name' => 'required',
         'file'=>'max:10000'
       ],['file.max' => 'Foto harus kurang dari 10Mb']);

             $Nim = Input::get('Nim');
             $Register_Number = Input::get('Register_Number');
             $Full_Name = Input::get('Full_Name');
             $First_Title = Input::get('First_Title');
             $Last_Title = Input::get('Last_Title');
             $Gender_Id = Input::get('Gender_Id');
             $Birth_Place = Input::get('Birth_Place');
             $Birth_Place_Id = Input::get('Birth_Place_Id');
             $Birth = Input::get('Birth_Date');
             $Citizenship_Id = Input::get('Citizenship_Id');
             $Religion_Id = Input::get('Religion_Id');
             $Marital_Status_Id = Input::get('Marital_Status_Id');
             $Blood_Id = Input::get('Blood_Id');
             $High_School_Major_Id = Input::get('High_School_Major_Id');
             $Phone_Mobile = Input::get('Phone_Mobile');
             $Class_Prog_Id = Input::get('Class_Prog_Id');
             $Class_Id = Input::get('Class_Id');
             $image = Input::get('file');
             $nik = Input::get('nik');
             $nisn = Input::get('nisn');
             $npwp = Input::get('npwp');
             $kps = Input::get('kps');
             $email = Input::get('email');
             $jalan = Input::get('jalan');
             $dusun = Input::get('dusun');
             $District_Id = Input::get('District_Id');
             $kelurahan = Input::get('kelurahan');
             $District_Id = Input::get('District_Id');
             $jenis_tinggal = Input::get('jenis_tinggal');
             $transport = Input::get('transport');
             
             $ayah_nik = Input::get('ayah_nik');
             $ayah_name = Input::get('ayah_name');
             $ayah_pendidikan = Input::get('ayah_pendidikan');
             $ayah_pekerjaan = Input::get('ayah_pekerjaan');
             $ayah_penghasilan = Input::get('ayah_penghasilan');
            $date = strtotime($Birth);
            $Birth_Date = date('Y-m-d', $date);
            $Birth_Ayah = Input::get('ayah_birth_date');
            $date_ayah = strtotime($Birth_Ayah);
            $Birth_Date_Ayah = date('Y-m-d', $date_ayah);

             $ibu_nik = Input::get('ibu_nik');
             $ibu_name = Input::get('ibu_name');
             $ibu_pendidikan = Input::get('ibu_pendidikan');
             $ibu_pekerjaan = Input::get('ibu_pekerjaan');
             $ibu_penghasilan = Input::get('ibu_penghasilan');
            $date = strtotime($Birth);
            $Birth_Date = date('Y-m-d', $date);
            $Birth_Ibu = Input::get('ibu_birth_date');
            $date_ibu = strtotime($Birth_Ibu);
            $Birth_Date_Ibu = date('Y-m-d', $date_ibu);

try {
             $student = DB::table('acd_student')->where('Student_Id', $id)->get();
             foreach ($student as $key) {
               $oldNim = $key->Nim;
             }
             $student_detail = DB::table('acd_student')->where('Student_Id', $id)->first();

             $u =  DB::table('acd_student')
             ->where('Student_Id',$id)
             ->update(
               ['Nim' => $Nim,
               'Register_Number' => $Register_Number,
               'Full_Name' => $Full_Name,
               'First_Title' => $First_Title, 
               'Last_Title' => $Last_Title, 
               'Gender_Id' => $Gender_Id, 
               'Birth_Place'=> $Birth_Place, 
               'Birth_Place_Id' => $Birth_Place_Id,
               'Birth_Date' => $Birth_Date, 
               'Citizenship_Id' => $Citizenship_Id, 
               'Religion_Id' => $Religion_Id, 
               'Marital_Status_Id' => $Marital_Status_Id, 
               'Blood_Id' => $Blood_Id,
               'High_School_Major_Id' => $High_School_Major_Id, 
               'Phone_Mobile' => $Phone_Mobile, 
               'Class_Prog_Id' => $Class_Prog_Id, 
              //  'Photo' => 'foto_mhs/'.$student_detail->Entry_Year_Id.'/'.$student_detail->Nim.'.jpg', 
               'Nik' => $nik, 
               'Nisn' => $nisn, 
               'Npwp' => $npwp, 
               'Recieve_Kps' => $kps, 
               'Email_Corporate' => $email,
               'Residence_Type_Id' => $jenis_tinggal,
               'Transport_Type_Id' => $transport,
               'Class_Id' => $Class_Id]);

               $cek_data_ayah = DB::table('acd_student_parent')->where('Student_Id',$id)->where('Parent_Type_Id',1)->count();
               $cek_data_ibu = DB::table('acd_student_parent')->where('Student_Id',$id)->where('Parent_Type_Id',2)->count();

               //Ayah
              if($cek_data_ayah > 0){
                $us =  DB::table('acd_student_parent')
                 ->where('Student_Id',$id)
                 ->where('Parent_Type_Id',1)
                 ->update(
                   [
                     'Nik' => $ayah_nik,
                     'Full_Name' => $ayah_name,
                     'Birth_Date' => $Birth_Date_Ayah,
                     'Education_Type_Id' => $ayah_pendidikan,
                     'Job_Category_Id' => $ayah_pekerjaan,
                     'Income' => $ayah_penghasilan,
                     ]);
              }else{
                $us =  DB::table('acd_student_parent')
                 ->insert(
                   ['Student_Id' => $id,
                    'Parent_Type_Id' => 1,
                    'Nik' => $nik,
                    'Full_Name' => $ayah_name,
                    'Birth_Date' => $Birth_Date_Ayah,
                    'Education_Type_Id' => $ayah_pendidikan,
                    'Job_Category_Id' => $ayah_pekerjaan,
                    'Income' => $ayah_penghasilan,
                    ]);
              }

              //Ibu
              if($cek_data_ibu > 0){
                $us =  DB::table('acd_student_parent')
                 ->where('Student_Id',$id)
                 ->where('Parent_Type_Id',2)
                 ->update(
                   [
                     'Nik' => $ibu_nik,
                     'Full_Name' => $ibu_name,
                     'Birth_Date' => $Birth_Date_Ibu,
                     'Education_Type_Id' => $ibu_pendidikan,
                     'Job_Category_Id' => $ibu_pekerjaan,
                     'Income' => $ibu_penghasilan,
                     ]);
              }else{
                $us =  DB::table('acd_student_parent')
                 ->insert(
                   ['Student_Id' => $id,
                    'Parent_Type_Id' => 2,
                    'Nik' => $nik,
                    'Full_Name' => $ibu_name,
                    'Birth_Date' => $Birth_Date_Ibu,
                    'Education_Type_Id' => $ibu_pendidikan,
                    'Job_Category_Id' => $ibu_pekerjaan,
                    'Income' => $ibu_penghasilan,
                    ]);
              }

              $cek_data = DB::table('acd_student_address')->where('Student_Id',$id)->count();
              if($cek_data > 0){
                $us =  DB::table('acd_student_address')
                 ->where('Student_Id',$id)
                 ->update(
                   [
                     'Address' => $jalan,
                     'Dusun' => $dusun,
                     'Sub_District' => $kelurahan,
                     'District_Id' => $District_Id,
                     ]);
              }else{
                $us =  DB::table('acd_student_address')
                 ->insert(
                   ['Student_Id' => $id,
                    'Dusun' => $dusun,
                    'Sub_District' => $kelurahan,
                    'District_Id' => $District_Id,
                    'Address' => $jalan]);
              }

                $entry_year = DB::table('acd_student')->where('Student_Id', $id)->select('Entry_Year_Id')->first();
                $directory = public_path().'/foto_mhs/'.$entry_year->Entry_Year_Id.'';
                $image       = $request->file('file');

               if ($request->file('file') != null) {
                 if( $exists = File::exists('/public/foto_mhs/'.$entry_year->Entry_Year_Id.'')){
                   $img = Image::make($image)->resize(151.18110236, 226.77165354)->save('foto_mhs/'.$entry_year->Entry_Year_Id.'/'.$Nim.'.jpg');
                   $us =  DB::table('acd_student')
                    ->where('Student_Id',$id)
                    ->update(
                      [
                        'Photo' => 'foto_mhs/'.$student_detail->Entry_Year_Id.'/'.$student_detail->Nim.'.jpg'
                      ]);
                  //  $img = Image::make($image)->resize(151.18110236, 226.77165354)->save('/home/web/sttnas/www/simakad/public/foto_mhs/'.$entry_year->Entry_Year_Id.'/'.$Nim.'.jpg');

                 } else{
                   File::makeDirectory($directory, $mode = 0777, true, true);
                //   $img = Image::make($image)->resize(151.18110236, 226.77165354)->save('/home/web/sttnas/www/simakad/public/foto_mhs/'.$entry_year->Entry_Year_Id.'/'.$Nim.'.jpg');
                    $img = Image::make($image)->resize(151.18110236, 226.77165354)->save('foto_mhs/'.$entry_year->Entry_Year_Id.'/'.$Nim.'.jpg');
                    $us =  DB::table('acd_student')
                      ->where('Student_Id',$id)
                      ->update(
                        [
                          'Photo' => 'foto_mhs/'.$student_detail->Entry_Year_Id.'/'.$student_detail->Nim.'.jpg'
                        ]);
                 }
               }

             return Redirect::back()->withErrors('Berhasil Menyimpan Perubahan');




             } catch (\Exception $e) {
               return Redirect::back()->withErrors('Gagal Menyimpan Perubahan');
             }
     }

     /**
      * Remove the specified resource from storage.
      *
      * @param  int  $id
      * @return \Illuminate\Http\Response
      */
     public function destroy(Request $request,$id)
     {
        $Nim = DB::table('acd_student')->where('Student_Id', $id)->select('Nim')->first();
        $q=DB::table('acd_student')->where('Student_Id', $id)->delete();
        $q=Storage::delete('public/foto_mhs/'.$Nim->Nim.'.jpg');
        echo json_encode($q);
     }

    public function exportdata($department, $entry_year){
          Excel::create('Data Mahasiswa', function ($excel) use($department, $entry_year){       
            $items  = DB::table('acd_student')
                  ->join('mstr_entry_year','mstr_entry_year.Entry_Year_Id','=','acd_student.Entry_Year_Id')
                  ->leftjoin('mstr_class_program','mstr_class_program.Class_Prog_Id','=','acd_student.Class_Prog_Id')
                  ->leftjoin('mstr_class','mstr_class.Class_Id','=','acd_student.Class_Id')
                  ->leftjoin('mstr_register_status','mstr_register_status.Register_Status_Id','=','acd_student.Register_Status_Id')
                  ->leftjoin('mstr_gender','mstr_gender.Gender_Id','=','acd_student.Gender_Id')
                  ->leftjoin('mstr_status','mstr_status.Status_Id','=','acd_student.Status_Id')
                  ->where('acd_student.Entry_Year_Id', $entry_year)
                  ->where('acd_student.Department_Id', $department)
                  ->select('acd_student.*','mstr_class_program.Class_Program_Name','mstr_class.Class_Name','mstr_register_status.Register_Status_Name','mstr_status.Status_Name')
                  ->orderBy('acd_student.Nim', 'asc')
                  ->groupBy('acd_student.Nim')
                  ->get();

          function tanggal_indo($tanggal, $cetak_hari = false)
          {
              $hari = array ( 1 =>    'Senin',
                          'Selasa',
                          'Rabu',
                          'Kamis',
                          'Jumat',
                          'Sabtu',
                          'Minggu'
                      );

              $bulan = array (1 =>   'Januari',
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
              $split 	  = explode('-', $tanggal);
              $tgl_indo = $split[2] . ' ' . $bulan[ (int)$split[1] ] . ' ' . $split[0];

              // if ($cetak_hari) {
              //     $num = date('N', strtotime($tanggal));
              //     return $hari[$num] . ', ' . $tgl_indo;
              // }
              return $tgl_indo;
          }

            if ($items->count() == 0) {
              $data = [
                  [
                    'NO' => '',
                    'NIM' => '',
                    'Nama Mahasiswa' => '',
                    'Kelas Program' => '',
                    'Kelas' => '',
                    'Register Status' => '',
                    'Tempat Lahir' => '',
                    'Tanggal Lahir' => '',
                  ]
              ];
          }

          $i = 1;
          foreach ($items as $item) {
            if ($item->Birth_Date == null) {
                $birth = "";
              }else {
                $date = strtotime($item->Birth_Date);
                $da = Date('Y-m-d',$date);
                $birth = tanggal_indo($da,true);
              }
              if($item->Created_Date == null){
                $Created_Date = '';
              }else{
                $Created_Date = strtotime($item->Created_Date);
                $Created_Date = Date('Y-m-d',$Created_Date);
                $Created_Date = tanggal_indo($Created_Date,true);
              }
              $data[] = [
                          'NO' => $i,
                          'NIM' => $item->Nim,
                          'Nama Mahasiswa' => $item->Full_Name,
                          'Kelas Program' => $item->Class_Program_Name,
                          'Kelas' => $item->Class_Name,
                          'Register Status' => $item->Register_Status_Name,
                          'Tempat Lahir' => $item->Birth_Place,
                          'Tanggal Lahir' => $birth,
                          'Tanggal Diterima' => $Created_Date,
                          'Semester Diterima' => $item->Entry_Term_Id,
                          'Status Mahasiswa' => $item->Status_Name,
                        ];
              $i++;
          }

          $excel->sheet('Data Mahasiswa', function ($sheet) use ($data,$items) {
              $sheet->fromArray($data, null, 'A1');

              $num_rows = sizeof($data) + 1;

              for ($i = 1; $i <= $num_rows; $i++) { 
                  $rows[$i] = 18;
              }

              $rows[1] = 30;

              $sheet->setAutoSize(true);

              $sheet->setStyle([
                  'font' => [
                      'name' => 'Arial',
                      'size' => 10
                  ]
              ]);

              $sheet->setAllBorders('none');

              $sheet->setHeight($rows);

              $sheet->setWidth([
                  'A' => 6,
                  'B' => 20,
                  'C' => 40,
                  'D' => 15,
                  'E' => 10,
                  'F' => 20,
              ]);
              
              $sheet->setHorizontalCentered(true);

              for ($i = 1; $i <= $num_rows; $i++) { 
                  $sheet->row($i, function ($row) {
                      $row->setValignment('center');
                  });
              }

              for ($i = 1; $i > $num_rows; $i++) { 
                  $sheet->row($i, function ($row) {
                      $cells->setAlignment('center');
                  });
              }
              
              $sheet->setBorder('A1:J' . (sizeof($data) + 1), 'thin');

              $sheet->setHorizontalCentered(true);

              $sheet->cells('A1:J1', function ($cells) {
                  $cells->setBackground('#97D86E');
                  $cells->setFontWeight('bold');
                  $cells->setAlignment('center');
              });
              // $sheet->cells('Q1', function ($cells) {
              //     $cells->setBackground('#F0FF00');
              //     $cells->setFontWeight('bold');
              //     $cells->setAlignment('center');
              // });
              // $sheet->cells('R1:T1', function ($cells) {
              //     $cells->setBackground('#FF3939');
              //     $cells->setFontWeight('bold');
              //     $cells->setAlignment('center');
              // });
              // foreach ($data as $dt) {
              //       $no = ($dt['NO'] + 1);
              //       if ($dt['SKS'] == null || $dt['Semester'] == null || $dt['SKS Transkrip'] == null) {
              //           $sheet->cells('A' . $no . ':I' . $no, function ($cells) {
              //               $cells->setBackground('#ff0000');
              //               $cells->setFontColor('#ffffff');
              //               $cells->setAlignment('center');
              //           });
              //       }else{
              //         $sheet->cells('A' . $no . ':I' . $no, function ($cells) {
              //               $cells->setAlignment('center');
              //       });
              //     }
              //   }

              foreach ($data as $dt) {
                  $sheet->cells('D' . $i . ':E' . sizeof($data), function ($cells) {
                      $cells->setAlignment('center');
                  });
                }

              // $last = $i+1;
              // $sheet->cells('E2:E9999', function ($cells) {
              //             $cells->setAlignment('center');
              //     });
              // $sheet->setCellValue('B'.$last, 'STIKES MUHAMMADIYAH PALEMBANG');
          });
      })->export('xls');
    }

    public function import_export(Request $request)
    {
      $mstr_department = DB::table('mstr_department')->where('Department_Id',$request->department)->get();
        return view('acd_student/import_export')->with('mstr_department',$mstr_department)->with('department',$request->department)->with('entry_year',$request->entry_year);
    }

    public function import_excel(Request $request)
    {
      $data_student = DB::table('acd_student')->where([['Department_Id',$request->department],['Entry_Year_Id',$request->entry_year]])->select('Nim')->get()->toarray();
      
      $student_nim = [];
      $i = 0;
      foreach ($data_student as $item) {
        $student_nim[$i] = $item->Nim;
        $i++;
      }
      // dd($student_nim);
      if($request->hasFile('import_file'))
      {
        // dd($request->file('import_file'));
          $sukses = 0;
          Excel::load($request->file('import_file')->getRealPath(), function ($reader) use($request,$student_nim,$sukses)
          {
              $log = [];
              $q = 0;
              foreach ($reader->toArray() as $row)
              {
                $stat = 0;
                $data=[];
                if(in_array(($row['nim']), $student_nim) == false){
                  // dd(substr($row['nim'], 0, 1));
                  if(substr($row['nim'], 0, 1) == ' '){
                    
                  }else{
                    if ($row['gender'] != null && $row['full_name'] != "" && $row['class_prog_code'] != "" && $row['nim'] != "") {
                      $class_prog_code = DB::table('mstr_class_program')->where('Class_Prog_Code',$row['class_prog_code'])->orwhere('Class_Program_Name',$row['class_prog_code'])->first();
                      if(!$class_prog_code){
                        continue;
                      }else{
                        $data_student_now = DB::table('acd_student')->where([['Department_Id',$request->department],['Entry_Year_Id',$request->entry_year],['Nim',$row['nim']]])->select('Nim')->first();
    
                        if(!$data_student_now){
                          $data['Nim'] = $row['nim'];
                          $data['Register_Number'] = $row['nim'];
                          $data['Full_Name'] = $row['full_name'];
                          $data['Gender_Id'] = $row['gender'];
                          $data['Department_Id'] = $request->department;
                          $data['Entry_Year_Id'] = $request->entry_year;
                          $data['Class_Prog_Id'] = $class_prog_code->Class_Prog_Id;
                          $data['Birth_Date'] = $row['birth_date'];
                          $data['Birth_Place'] = $row['birth_place'];
                          $data['Student_Password'] = md5($row['student_password']);
                          $data['Parent_Password'] = md5($row['nim']);
                          $data['Email_Corporate'] = $row['email_corporate'];
                          $data['Email_General'] = $row['email_general'];
                          $data['Entry_Period_Id'] = 1;
                          $data['Entry_Term_Id'] = 1;
                          $data['Entry_Period_Type_Id'] = 1;
                          $data['Status_Id'] = 1;
      
                          DB::table('acd_student')->insert($data);
                          $sukses++;
                        }
                      }
                    }
                  }
                }else{
                  // $err = $err.' ada data yang tidak sesuai ';
                  // stop;
                }
                
                $q++;
              }
          });
      }

      return redirect()->to('/setting/student?department='.$request->department.'&entry_year='.$request->entry_year)->withErrors('Berhasil Memasukkan Data Ke Database');
    }

    public function message($err,$department,$entry_year){
      return redirect()->to('/setting/student?department='.$department.'&entry_year='.$entry_year)->withErrors($err);
    }
 }
