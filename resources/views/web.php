<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
  if (Auth::check()) {
    return redirect('home');
  }
    return view('welcome');
});


Route::get('test_pdf', 'TestPdfController@testpdf')->name('testpdf');

Route::get('/krsdibuka', function () {
  $Term_Year_Id = 20171;
  $Class_Prog_Id = 1;
  $result = DB::table('mstr_department')
              ->select(DB::Raw('
                mstr_department.Department_Name as Prodi,
                (SELECT COUNT(distinct(acd_student.Student_Id)) FROM acd_student LEFT JOIN acd_student_krs ON acd_student.Student_Id = acd_student_krs.Student_Id WHERE acd_student_krs.Term_Year_Id = '.$Term_Year_Id.' AND acd_student.Department_Id = mstr_department.Department_Id AND acd_student.Class_Prog_Id = '.$Class_Prog_Id.'  ) as JumlahMhsAktif
              '))
              ->get();
  dd($result);
});

Route::get('/clear', function () {
  Artisan::call('config:clear'); 
  Artisan::call('cache:clear'); 
  Artisan::call('view:clear'); 
  return 'ok';
});

Route::get('/cek_mhsaktif', function () {
  $term_year = 20171;
  $prog_kelas = 1;
  $id=1;
  $entry_year=2016;
  $std_krs = DB::table('acd_student_krs')->select('Student_Id');
  $std_out = DB::table('acd_student_out')->select('Student_Id');
  $std_gradfinal = DB::table('acd_graduation_final')->select('Student_Id');

  $result = DB::table('acd_student')
  ->join('acd_student_krs','acd_student.Student_Id','=','acd_student_krs.Student_Id')
  ->join('mstr_class_program','acd_student.Class_Prog_Id','=','mstr_class_program.Class_Prog_Id')
  ->where('acd_student_krs.Term_Year_Id', $term_year)
  ->where('acd_student.Class_Prog_Id', $prog_kelas)
  ->where('acd_student.Entry_Year_Id', $entry_year)
  ->where('acd_student.Department_Id', $id)
  ->groupBy('acd_student.Student_Id')->get();
  dd($result);
});

Auth::routes();


Route::group(['middleware' => ['auth']], function () {
  Route::get('/home', 'HomeController@index')->name('home');

  Route::group(['prefix' => 'administrator'], function () {
      Route::resource('user', 'UserController');
      Route::delete('user/destroy_role_user/{id}', 'UserController@destroy_role_user')->name('user.destroy_role_user');
      Route::post('/user/update_role', 'UserController@update_role')->name('user.update_role');
      Route::resource('ubahpassword', 'UbahPassController');
      Route::resource('ubahpasswordsaya', 'UbahPasssayaController');
      Route::resource('role', 'RoleController');
  });
  Route::group(['prefix' => 'master'], function () {
      Route::resource('faculty', 'FacultyController');
      Route::resource('department', 'DepartmentController',['except'=> ['index','create','show','edit']]);
      Route::get('/department/{fakultas?}', 'DepartmentController@index')->name('department.index')->middleware('faculty');
      Route::get('/department/create/{fakultas?}', 'DepartmentController@create')->name('department.create')->middleware('faculty');
      Route::get('/department/{department}/edit/{fakultas?}', 'DepartmentController@edit')->name('department.create')->middleware('faculty');
      Route::resource('class_program', 'Class_programController');
      Route::resource('department_class_program', 'Department_class_progController',['except'=> ['index','create','show','edit']]);
      Route::get('/department_class_program/{fakultas?}', 'Department_class_progController@index')->name('department_class_program.index');
      Route::get('/department_class_program/{department}/edit/{fakultas?}', 'Department_class_progController@edit')->name('department.create');
      Route::resource('concentration', 'ConcentrationController',['except'=> ['index','create','show','edit']]);
      Route::get('/concentration/{fakultas?}', 'ConcentrationController@index')->name('concentration.index')->middleware('faculty');
      Route::get('/concentration/create/{fakultas?}', 'ConcentrationController@create')->name('concentration.create')->middleware('faculty');
      Route::get('/concentration/{department}/edit/{fakultas?}', 'ConcentrationController@edit')->name('concentration.create')->middleware('faculty');
      Route::resource('education_type', 'Education_typeController');
      Route::resource('entry_year', 'Entry_yearController');
      Route::resource('term_year', 'Term_yearController');
      Route::resource('sched_session_group', 'Sched_session_groupController');
      Route::resource('employee', 'EmployeeController');
      Route::resource('education_program_type', 'Education_prog_typeController');
      Route::resource('grade_letter', 'Grade_letterController');
      Route::resource('course_type', 'Course_typeController');
      Route::resource('course_group', 'Course_groupController');
      Route::resource('graduate_predicate', 'Graduate_predicateController');
      Route::resource('religion', 'ReligionController');
      Route::resource('citizenship', 'CitizenshipController');
      Route::resource('blood_type', 'Blodd_typeController');
      Route::resource('register_status', 'Register_statusController');
      Route::resource('high_school_major', 'High_school_majorController');
      Route::resource('building', 'BuildingController');
      Route::resource('room', 'RoomController');
      Route::resource('jabatan', 'JabatanController');
      Route::resource('akreditasiuniv', 'AkreditasiunivController');
      Route::resource('functional_position_term_year', 'Functional_position_term_yearController');
      Route::get('/functional_position_term_year/create/faculty', 'Functional_position_term_yearController@Faculty')->name('functional_position_term_year.Faculty');
      Route::get('/functional_position_term_year/create/department', 'Functional_position_term_yearController@Department')->name('functional_position_term_year.Department');
      Route::post('/functional_position_term_year/store_kopidata', 'Functional_position_term_yearController@store_kopidata')->name('functional_position_term_year.store_kopidata');

      Route::resource('curriculum_type', 'Curriculum_typeController');
      Route::resource('curriculum', 'CurriculumController');
      Route::resource('country', 'CountryController');
      Route::resource('province', 'ProvinceController');
      Route::resource('city', 'CityController');
      Route::resource('status', 'Student_statusController');

  });

  Route::group(['prefix' => 'parameter'], function () {
      Route::resource('curriculum_applied', 'Curriculum_appliedController');
      Route::resource('curriculum_entry_year', 'Curriculum_entry_yearController');
      Route::get('/curriculum_entry_year/create/class_program', 'Curriculum_entry_yearController@class_program')->name('curriculum_entry_year.class_program');
      Route::resource('course', 'CourseController');
      Route::get('/course/exportexcel/exportexcel','CourseController@exportexcel')->name('course.exportexcel');
      Route::get('/course/export/{type?}', 'CourseController@export')->name('course.export')->middleware('access:CanExport');
      Route::resource('course_curriculum', 'Course_curriculumController');
	  Route::get('course_curriculum/silabus/silabus', 'Course_curriculumController@silabus')->name('course_curriculum.silabus')->middleware('access:CanView');
      Route::post('/course_curriculum/store_silabus', 'Course_curriculumController@store_silabus')->name('course_curriculum.store_silabus');
            Route::get('/course_curriculum/create/copydata', 'Course_curriculumController@copydata')->name('course_curriculum.copydata');
      Route::post('/course_curriculum/create/storecopydata', 'Course_curriculumController@storecopydata')->name('course_curriculum.storecopydata');
      Route::resource('prasyarat', 'Prerequisite_detailController');
      Route::resource('course_identic', 'Course_identicController');
      Route::delete('course_identic/destroy_course/{id}', 'Course_identicController@destroy_course')->name('course_identic.destroy_course');
      Route::resource('grade_department', 'Grade_departmentController');

      Route::resource('komponen_penilaian', 'Komponen_penilaianController');
      Route::post('/komponen_penilaian/update', 'Komponen_penilaianController@update')->name('komponen_penilaian.update');
      Route::delete('komponen_penilaian/destroy_bobot/{id}', 'Komponen_penilaianController@destroy_bobot')->name('komponen_penilaian.destroy_bobot');
      Route::get('/komponen_penilaian/create/copydata', 'Komponen_penilaianController@copydata')->name('komponen_penilaian.copydata');
      Route::post('/komponen_penilaian/create/storecopydata', 'Komponen_penilaianController@storecopydata')->name('komponen_penilaian.storecopydata');


  });

  Route::group(['prefix' => 'setting'], function () {
      Route::resource('student', 'StudentController');
      Route::post('/student/set_kelas', 'StudentController@set_kelas')->name('student.set_kelas');
      Route::post('/student/set_kelasmahasiswa', 'StudentController@set_kelasmahasiswa')->name('student.set_kelasmahasiswa');
      Route::post('/student/clear_kelas', 'StudentController@clear_kelas')->name('student.clear_kelas');
      Route::get('/student/exportdata/exportdata/{id1}/{id2}','StudentController@exportdata')->name('student.exportdata.exportdata');
      Route::get('/student/get_data_student/get_data_student','StudentController@get_data_student')->name('student.get_data_student');
      Route::get('/student/import_export/import_export','StudentController@import_export')->name('student.import_export');
      Route::post('/student/import_excel/import_excel','StudentController@import_excel')->name('student.import_excel');

      Route::resource('studentmundurkeluardo', 'StudentmundurkeluardoController');
      Route::get('/studentmundurkeluardo/create/student', 'StudentmundurkeluardoController@create_student')->name('studentmundurkeluardo.create_student');
      Route::get('/studentmundurkeluardo/create/student_mengundurkandiri', 'StudentmundurkeluardoController@create_student_mengundurkandiri')->name('studentmundurkeluardo.create_student_mengundurkandiri');
      Route::post('/studentmundurkeluardo/create/store_student_mengundurkandiri', 'StudentmundurkeluardoController@store_student_mengundurkandiri')->name('studentmundurkeluardo.store_student_mengundurkandiri');
      Route::get('/studentmundurkeluardo/student_mengundurkandiri/{status}/{student_id}','StudentmundurkeluardoController@edit_student_mengundurkandiri')->name('studentmundurkeluardo.edit_student_mengundurkandiri');
      Route::put('/studentmundurkeluardo/student_mengundurkandiri/{student_id}', 'StudentmundurkeluardoController@update_student_mengundurkandiri')->name('studentmundurkeluardo.update_student_mengundurkandiri');

      Route::get('/studentmundurkeluardo/create/student_do', 'StudentmundurkeluardoController@create_student_do')->name('studentmundurkeluardo.create_student_do');
      Route::post('/studentmundurkeluardo/create/store_student_do', 'StudentmundurkeluardoController@store_student_do')->name('studentmundurkeluardo.store_student_do');
      Route::get('/studentmundurkeluardo/student_do/{status}/{student_id}','StudentmundurkeluardoController@edit_student_do')->name('studentmundurkeluardo.edit_student_do');
      Route::put('/studentmundurkeluardo/student_do/{student_id}', 'StudentmundurkeluardoController@update_student_do')->name('studentmundurkeluardo.update_student_do');

      Route::get('/studentmundurkeluardo/create/student_pindah', 'StudentmundurkeluardoController@create_student_pindah')->name('studentmundurkeluardo.create_student_pindah');
      Route::get('/studentmundurkeluardo/create/student_pindah/findnim/findnim','StudentmundurkeluardoController@findnim')->name('studentmundurkeluardo.create_student_pindah_findnim');
      Route::post('/studentmundurkeluardo/create/store_student_pindah', 'StudentmundurkeluardoController@store_student_pindah')->name('studentmundurkeluardo.store_student_pindah');
      Route::get('/studentmundurkeluardo/student_pindah/{status}/{student_id}','StudentmundurkeluardoController@edit_student_pindah')->name('studentmundurkeluardo.edit_student_pindah');
      Route::put('/studentmundurkeluardo/student_pindah/{student_id}', 'StudentmundurkeluardoController@update_student_pindah')->name('studentmundurkeluardo.update_student_pindah');

      Route::get('/studentmundurkeluardo/create/student_meninggal', 'StudentmundurkeluardoController@create_student_meninggal')->name('studentmundurkeluardo.create_student_meninggal');
      Route::post('/studentmundurkeluardo/create/store_student_meninggal', 'StudentmundurkeluardoController@store_student_meninggal')->name('studentmundurkeluardo.store_student_meninggal');
      Route::get('/studentmundurkeluardo/student_meninggal/{status}/{student_id}','StudentmundurkeluardoController@edit_student_meninggal')->name('studentmundurkeluardo.edit_student_meninggal');
      Route::put('/studentmundurkeluardo/student_meninggal/{student_id}', 'StudentmundurkeluardoController@update_student_meninggal')->name('studentmundurkeluardo.update_student_meninggal');

      // Route::resource('student', 'StudentController');
      Route::resource('student_supervision', 'Student_supervisionController');
      Route::get('/student_supervision/create/dpa', 'Student_supervisionController@create_dpa')->name('student_supervision.create_dpa');
      Route::resource('student_password', 'Student_passwordController');
      Route::resource('event_sched', 'Event_schedController');
      Route::resource('short_term', 'Short_TermController');
      Route::resource('offered_course', 'Offered_courseController');
      Route::get('/offered_course/{offered_course}/edit_capacity', 'Offered_courseController@edit_capacity')->name('offered_course.edit_capacity')->middleware('access:CanEditCapacity');
      Route::put('/offered_course/update_capacity/{offered_course}','Offered_courseController@update_capacity')->name('offered_course.update_capacity');
      Route::get('/offered_course/{offered_course}/edit_employee', 'Offered_courseController@edit_employee')->name('offered_course.edit_employee')->middleware('access:CanEditEmployee');
      Route::put('/offered_course/update_employee/{offered_course}','Offered_courseController@update_employee')->name('offered_course.update_employee');
      Route::delete('/offered_course/{offered_course}/destroy_employee', 'Offered_courseController@destroy_employee')->name('offered_course.destroy_employee');
      Route::get('/offered_course/create/copydata', 'Offered_courseController@copydata')->name('offered_course.copydata');
      Route::post('/offered_course/create/storecopydata', 'Offered_courseController@storecopydata')->name('offered_course.storecopydata');
      Route::post('/offered_course/store/update_datacourse', 'Offered_courseController@update_datacourse')->name('offered_course.update_datacourse');


      Route::resource('department_lecturer', 'Department_lecturerController');
      Route::resource('allowed_sks', 'Allowed_sksController');
      Route::resource('sched_session', 'Sched_sessionController');
      Route::get('/sched_session/create/session', 'Sched_sessionController@session')->name('sched_session.session');
      
      Route::resource('offered_course_sched', 'Offered_course_schedController');
      Route::get('/offered_course_sched/findgrubsesi/findgrubsesi','Offered_course_schedController@findgrubsesi');
      Route::get('/offered_course_sched/findtype/findtype','Offered_course_schedController@findtype');
      Route::get('/offered_course_sched/findroom/findroom','Offered_course_schedController@findroom');
      Route::get('/offered_course_sched/findjadwal/findjadwal/{id1}/{id2}/{id3}/{id4}','Offered_course_schedController@findjadwal');
      Route::post('/offered_course_sched/store_detail', 'Offered_course_schedController@store_detail')->name('offered_course_sched.store_detail');
      Route::get('offered_course_sched/exportdata/exportdata/{id1}/{id2}/{id3}','Offered_course_schedController@exportdata')->name('Offered_course_schedController.exportdata.exportdata');

      Route::resource('offered_course_schedV2', 'Offered_course_scheddropController');
      Route::get('/offered_course_schedV2/create', 'Offered_course_scheddropController@create')->name('offered_course_schedV2.create');
      Route::get('/offered_course_schedV2/findgrubsesi/findgrubsesi', 'Offered_course_scheddropController@findgrubsesi');
      Route::get('/offered_course_schedV2/findtype/findtype', 'Offered_course_scheddropController@findtype');
      Route::get('/offered_course_schedV2/findroom/findroom', 'Offered_course_scheddropController@findroom');
      Route::get('/offered_course_schedV2/findjadwal/findjadwal/{id1}/{id2}/{id3}/{id4}', 'Offered_course_scheddropController@findjadwal');
      Route::post('/offered_course_schedV2/store_detail', 'Offered_course_scheddropController@store_detail')->name('offered_course_schedV2.store_detail');
      Route::get('offered_course_schedV2/exportdata/exportdata/{id1}/{id2}/{id3}', 'Offered_course_scheddropController@exportdata')->name('Offered_course_schedControllerV2.exportdata.exportdata');
      Route::get('offered_course_schedV2tester/exportdata/exportdata/{id1}/{id2}/{id3}', 'Offered_course_scheddropController@exportdatatester')->name('Offered_course_schedControllerV2tester.exportdata.exportdata');
      Route::get('offered_course_schedV2/exportdata/exportdatacsv', 'Offered_course_scheddropController@exportdatacsv')->name('Offered_course_schedControllerV2.exportdata.exportdatacsv');


      Route::resource('offered_course_exam', 'Offered_course_examController');
      Route::get('/offered_course_exam/{offered_course_exam}/peserta', 'Offered_course_examController@peserta')->name('offered_course_exam.peserta')->middleware('access:CanViewPeserta');
      Route::get('/offered_course_exam/peserta/create', 'Offered_course_examController@create_peserta')->name('offered_course_exam.create_peserta')->middleware('access:CanAddPeserta');
      Route::post('/offered_course_exam/store_peserta', 'Offered_course_examController@store_peserta')->name('offered_course_exam.store_peserta');
      Route::delete('/offered_course_exam/destroy_peserta/{offered_course_exam}', 'Offered_course_examController@destroy_peserta')->name('offered_course_exam.destroy_peserta')->middleware('access:CanHapusPeserta');
      Route::get('/offered_course_exam/{offered_course_exam}/export', 'Offered_course_examController@export')->name('offered_course_exam.export')->middleware('access:CanExportPresensi');

  });

  Route::group(['prefix' => 'proses'], function () {
      Route::resource('krs_matakuliah', 'Krs_matakuliahController');
      Route::get('/krs_matakuliah/{krs_matakuliah}/export', 'Krs_matakuliahController@export')->name('krs_matakuliah.export')->middleware('access:CanExport');
      Route::resource('krs_mahasiswa', 'Krs_mahasiswaController');
      Route::get('/krs_mahasiswa/{nim}/export', 'Krs_mahasiswaController@export')->name('krs_mahasiswa.export')->middleware('access:CanExport');


      Route::get('/krs_online/create', 'KrsOnline\CreateController@index')->name('krsonline_create');
      
      Route::resource('krs_paket', 'Krs_paketController');
      Route::get('/krs_paket/{id}/create_datapeserta', 'Krs_paketController@create_datapeserta')->name('krs_paket.create_datapeserta');
      Route::post('/krs_paket/store/update_datapeserta', 'Krs_paketController@update_datapeserta')->name('krs_paket.update_datapeserta');


      Route::resource('khs_matakuliah', 'Khs_matakuliahController');
      Route::get('/khs_matakuliah/bobot/{offer}','Khs_matakuliahController@getSetting')->name('khs_matakuliah.getSetting');
      Route::post('/khs_matakuliah/bobot/{offer}/simpan','Khs_matakuliahController@storeSetting')->name('khs_matakuliah.storeSetting');
      Route::get('/khs_matakuliah/getNilaiAkhir/{id}/{id2}/{id3}/{id4}/','Khs_matakuliahController@getNilaiAkhir')->name('khs_matakuliah.getNilaiAkhir');
      Route::get('/khs_matakuliah/updateNilaiAkhir/update','Khs_matakuliahController@updateNilaiAkhir')->name('khs_matakuliah.updateNilaiAkhir');
      Route::get('/khs_matakuliah/publishNilai/publish','Khs_matakuliahController@publishNilai')->name('khs_matakuliah.publishNilai');
      Route::get('/khs_matakuliah/defaultnilaiuts/publish','Khs_matakuliahController@defaultnilaiuts')->name('khs_matakuliah.defaultnilaiuts');
      Route::get('/khs_matakuliah/defaultnilaiuas/publish','Khs_matakuliahController@defaultnilaiuas')->name('khs_matakuliah.defaultnilaiuas');
      Route::post('/khs_matakuliah/storenilaiprak/simpan','Khs_matakuliahController@storeNilaiPrak')->name('khs_matakuliah.storeNilaiPrak');
      Route::get('khs_matakuliah/exportdata/exportdata/{id1}/{id2}/{id3}/{id4}','Khs_matakuliahController@exportdata')->name('Khs_matakuliahController.exportdata.exportdata');

      Route::resource('khs_matakuliahdetail', 'Khs_matakuliahdetailController');
      Route::get('/khs_matakuliahdetail/bobot/{offer}','Khs_matakuliahdetailController@getSetting')->name('khs_matakuliahdetail.getSetting');
      Route::post('/khs_matakuliahdetail/bobot/{offer}/simpan','Khs_matakuliahdetailController@storeSetting')->name('khs_matakuliahdetail.storeSetting');
      Route::get('/khs_matakuliahdetail/getNilaiAkhir/{id}/{id2}/{id3}/{id4}/','Khs_matakuliahdetailController@getNilaiAkhir')->name('khs_matakuliahdetail.getNilaiAkhir');
      Route::get('/khs_matakuliahdetail/updateNilaiAkhir/update','Khs_matakuliahdetailController@updateNilaiAkhir')->name('khs_matakuliahdetail.updateNilaiAkhir');
      Route::get('/khs_matakuliahdetail/publishNilai/publish','Khs_matakuliahdetailController@publishNilai')->name('khs_matakuliahdetail.publishNilai');
      Route::get('/khs_matakuliahdetail/defaultnilaiuts/publish','Khs_matakuliahdetailController@defaultnilaiuts')->name('khs_matakuliahdetail.defaultnilaiuts');
      Route::get('/khs_matakuliahdetail/defaultnilaiuas/publish','Khs_matakuliahdetailController@defaultnilaiuas')->name('khs_matakuliahdetail.defaultnilaiuas');
      Route::post('/khs_matakuliahdetail/storenilaiprak/simpan','Khs_matakuliahdetailController@storeNilaiPrak')->name('khs_matakuliahdetail.storeNilaiPrak');
      Route::get('khs_matakuliahdetail/exportdata/exportdata/{id1}/{id2}/{id3}/{id4}','Khs_matakuliahdetailController@exportdata')->name('Khs_matakuliahdetailController.exportdata.exportdata');
      Route::get('khs_matakuliahdetail/exportdatapdf/exportdatapdf/{id1}/{id2}/{id3}/{id4}/{id5}/{id6}','Khs_matakuliahdetailController@exportdatapdf')->name('Khs_matakuliahdetailController.exportdatapdf.exportdatapdf');
      
      Route::resource('khs_mahasiswa', 'Khs_mahasiswaController');
      Route::get('/khs_mahasiswa/index/test', 'Khs_mahasiswaController@indextest')->name('khs_mahasiswa.indextest');
      Route::get('/khs_mahasiswa/select/course/get', 'dataKhsController@selectCourse')->name('khs_mahasiswa.selectCourse');
      Route::get('/khs_mahasiswa/select/class/get', 'dataKhsController@selectClass')->name('khs_mahasiswa.selectClass');
      Route::get('/khs_mahasiswa/select/nilai/get', 'dataKhsController@selectNilai')->name('khs_mahasiswa.selectNilai');
      Route::get('/khs_mahasiswa/select/semester/get', 'dataKhsController@selectSemester')->name('khs_mahasiswa.selectSemester');
      Route::get('/khs_mahasiswa/select/update/nilai', 'dataKhsController@insertkhsstudent')->name('khs_mahasiswa.insertkhsstudent');
      Route::delete('/khs_mahasiswa/delete/update/nilai', 'dataKhsController@deletekhsstudent')->name('khs_mahasiswa.deletekhsstudent');
      Route::get('/khs_mahasiswa/getkhsstudent/khs', 'dataKhsController@getkhsstudent')->name('khs_mahasiswa.getkhsstudent');
      Route::get('/khs_mahasiswa/updatekhsstudent/khs', 'dataKhsController@updatekhsstudent')->name('khs_mahasiswa.updatekhsstudent');
      Route::get('/khs_mahasiswa/{khs_mahasiswa}/export', 'Khs_mahasiswaController@export')->name('khs_mahasiswa.export');

      Route::resource('transcript_equivalensi', 'Transcript_equivalensiController');
      Route::get('/transcript_equivalensi/export/data', 'Transcript_equivalensiController@export')->name('transcript_equivalensi.export');

      Route::resource('cuti', 'CutiController');
      Route::get('/kembali', 'CutiController@kembali')->name('kembali');
      Route::get('/kembali/{id}/edit', 'CutiController@editkembali')->name('kembali.edit');
      Route::put('/kembali/update/{id}', 'CutiController@updatekembali')->name('kembali.update');
      Route::get('/cuti/berkascuti/data','CutiController@berkascuti')->name('berkascuti');
      Route::get('/cuti/masterberkascuti/data','CutiController@masterberkascuti')->name('masterberkascuti');
      

      Route::resource('tugas_akhir','Tugas_akhirController');
      Route::get('/tugas_akhir/finddata/finddata','Tugas_akhirController@finddata');
      Route::get('/tugas_akhir/findgrade/findgrade','Tugas_akhirController@findgrade');
      Route::post('/tugas_akhir/store_srtijinta/store_srtijinta','Tugas_akhirController@store_srtijinta')->name('tugas_akhir.store_srtijinta');
      Route::post('/tugas_akhir/store_srtmohonseminarta/store_srtmohonseminarta','Tugas_akhirController@store_srtmohonseminarta')->name('tugas_akhir.store_srtmohonseminarta');
      Route::post('/tugas_akhir/store_undanganseminar/store_undanganseminar','Tugas_akhirController@store_undanganseminar')->name('tugas_akhir.store_undanganseminar');
      Route::post('/tugas_akhir/store_permohonan_pendadaran/store_permohonan_pendadaran','Tugas_akhirController@store_permohonan_pendadaran')->name('tugas_akhir.store_permohonan_pendadaran');
      Route::post('/tugas_akhir/store_undangan_pendadaran/store_undangan_pendadaran','Tugas_akhirController@store_undangan_pendadaran')->name('tugas_akhir.store_undangan_pendadaran');
      Route::get('/tugas_akhir/{tugas_akhir}/export', 'Tugas_akhirController@export')->name('tugas_akhir.export');

      Route::resource('yudisium','YudisiumController')->middleware('access:CanView');
      Route::get('/yudisium/create','YudisiumController@create')->name('create_yudisium.create')->middleware('access:CanAdd');
      Route::get('/yudisium/berkasyudisium/data','YudisiumController@berkasyudisium')->name('berkasyudisium');
      Route::get('/yudisium/masterberkasyudisium/data','YudisiumController@masterberkasyudisium')->name('masterberkasyudisium');
      Route::get('/yudisium/finddata/finddata','YudisiumController@finddata');
      Route::get('/yudisium/{yudisium}/beritaacara_yudisium','YudisiumController@beritaacara_yudisium')->name('beritaacara_yudisium.beritaacara_yudisium');
      //Route::get('/yudisium/{yudisium}/storeberitaacara_yudisium','YudisiumController@storeberitaacara_yudisium');
      Route::post('/yudisium/storeberitaacara_yudisium', 'YudisiumController@storeberitaacara_yudisium')->name('beritaacara_yudisium.storeberitaacara_yudisium');
      Route::get('/yudisium/{yudisium}/skl','YudisiumController@skl')->name('skl.skl');
      Route::post('/yudisium/store_skl', 'YudisiumController@store_skl')->name('skl.store_skl');
      Route::get('/yudisium/{yudisium}/export', 'YudisiumController@export')->name('yudisium.export')->middleware('access:CanExport');
      Route::get('/yudisium/findnik/findnik','YudisiumController@findnik');
      Route::delete('/yudisium/delete/{id}','YudisiumController@destroy')->name('create_yudisium.delete');
      Route::get('/yudisium/dataupdate/{id}','YudisiumController@update')->name('yudisium.dataupdate');


      Route::get('/skpi/skpi/data','SkpiController@skpi')->name('skpi');
      Route::get('/skpi/masterskpi/data','SkpiController@masterskpi')->name('masterskpi');

      Route::get('portofolio','PortofolioController@index')->name('portofolio.index');

      //get student
      Route::get('portofolio/getstudent','PortofolioController@getstudent')->name('portofolio.getstudent');
      //workshop
      Route::get('portofolio/workshop','PortofolioController@getworkshop')->name('portofolio.getworkshop');
      Route::put('portofolio/updateworkshop','PortofolioController@updateworkshop')->name('portofolio.updateworkshop');
      Route::post('portofolio/uploadworkshop','PortofolioController@uploadworkshop')->name('portofolio.uploadworkshop');
      //prestasi
      Route::get('portofolio/prestasi','PortofolioController@getprestasi')->name('portofolio.getprestasi');
      Route::put('portofolio/updateprestasi','PortofolioController@updateprestasi')->name('portofolio.updateprestasi');
      Route::post('portofolio/uploadprestasi','PortofolioController@uploadprestasi')->name('portofolio.uploadprestasi');
      //penelitian
      Route::get('portofolio/penelitian','PortofolioController@getpenelitian')->name('portofolio.getpenelitian');
      Route::put('portofolio/updatepenelitian','PortofolioController@updatepenelitian')->name('portofolio.updatepenelitian');
      Route::post('portofolio/uploadpenelitian','PortofolioController@uploadpenelitian')->name('portofolio.uploadpenelitian');
      //organisasi
      Route::get('portofolio/organisasi','PortofolioController@getorganisasi')->name('portofolio.getorganisasi');
      Route::put('portofolio/updateorganisasi','PortofolioController@updateorganisasi')->name('portofolio.updateorganisasi');
      Route::post('portofolio/uploadorganisasi','PortofolioController@uploadorganisasi')->name('portofolio.uploadorganisasi');
      //magang
      Route::get('portofolio/magang','PortofolioController@getmagang')->name('portofolio.getmagang');
      Route::put('portofolio/updatemagang','PortofolioController@updatemagang')->name('portofolio.updatemagang');
      Route::post('portofolio/uploadmagang','PortofolioController@uploadmagang')->name('portofolio.uploadmagang');

      Route::delete('portofolio/deletecertificate','PortofolioController@deletecertificate')->name('portofolio.deletecertificate');

      Route::resource('wisuda','WisudaController');
      Route::resource('periodewisuda','PeriodewisudaController');

      Route::resource('/schedreal', 'SchedrealController');
      Route::get('/schedreal/peserta/{schedreal}/{id}', 'SchedrealController@peserta')->name('schedreal.peserta');
      Route::get('/schedreal/pesertatotal/{id}', 'SchedrealController@pesertatotal')->name('schedreal.pesertatotal');
      Route::get('/schedreal/peserta/{schedreal}/{id}/detail', 'SchedrealController@detail')->name('schedreal.detail');
      Route::post('/schedreal/peserta/store', 'SchedrealController@storepeserta')->name('schedreal.storepeserta');
  });

  Route::group(['prefix' => 'laporan'], function () {
      route::resource('laporan_daftar_mahasiswa_krs','Daftar_mahasiswa_krsController');

      route::resource('laporan_aktifitasmahasiswa','AktifitasperkuliahanController');
      route::get('laporan_aktifitasmahasiswa/get_data/all','AktifitasperkuliahanController@get_alldata')->name('laporan_aktifitasmahasiswa.get_data');

      route::resource('laporan_peringkatipk','Laporan_peringkatipkController');
      route::get('laporan_peringkatipk/get_data/all','Laporan_peringkatipkController@get_alldata')->name('laporan_peringkatipk.get_data');

      route::resource('laporan_history_nilaimhs','History_nilaimhsController');
      route::resource('laporan_mhskrs','Resume_mhs_krsController');
      Route::get('/laporan_mhskrs/showmhsnonaktif/{id}', 'Resume_mhs_krsController@showmhsnonaktif')->name('showmhsnonaktif.showmhsnonaktif')->middleware('access:CanViewnonaktif');
      // Route::get('/laporan_mhskrs/showmhscuti/{id}', 'Resume_mhs_krsController@showmhscuti')->name('showmhsnonaktif.showmhscuti');
      Route::get('/laporan_mhskrs/showmhscuti/{id}', 'Resume_mhs_krsController@showmhscuti2')->name('showmhsnonaktif.showmhscuti');
      Route::get('/laporan_mhskrs/showmhsmengundurkandiri/{id}', 'Resume_mhs_krsController@showmhsmengundurkandiri')->name('showmhsnonaktif.showmhsmengundurkandiri');
      Route::get('/laporan_mhskrs/showmhstidakdiketahui/{id}', 'Resume_mhs_krsController@showmhstidakdiketahui')->name('showmhsnonaktif.showmhstidakdiketahui');
      Route::get('/laporan_mhskrs/showmhsdo/{id}', 'Resume_mhs_krsController@showmhsdo')->name('showmhsnonaktif.showmhsdo');
      Route::get('/laporan_mhskrs/showmhslulus/{id}', 'Resume_mhs_krsController@showmhslulus')->name('showmhsnonaktif.showmhslulus');
      Route::get('/laporan_mhskrs/exportexcel/exportexcel','Resume_mhs_krsController@exportexcel')->name('laporan_mhskrs.exportexcel');
      Route::get('/laporan_mhskrs/exportexcelnonaktif/exportexcelnonaktif','Resume_mhs_krsController@exportexcelnonaktif')->name('laporan_mhskrs.exportexcelnonaktif')->middleware('access:CanExport');
      Route::get('/laporan_mhskrs/exportexcellulus/exportexcellulus','Resume_mhs_krsController@exportexcellulus')->name('laporan_mhskrs.exportexcellulus')->middleware('access:CanExport');
      Route::get('/laporan_mhskrs/exportexcelcuti/exportexcelcuti','Resume_mhs_krsController@exportexcelcuti')->name('laporan_mhskrs.exportexcelcuti');      
      Route::resource('laporandatamahasiswa', 'LaporandatamahasiswaController');
      Route::get('laporandatamahasiswa/getAll/getAll','LaporandatamahasiswaController@getAll')->name('laporandatamahasiswa.getAll.getAll');
      Route::get('laporandatamahasiswa/getDepartment/getDepartment','LaporandatamahasiswaController@getDepartment')->name('laporandatamahasiswa.getDepartment.getDepartment');
      Route::get('laporandatamahasiswa/getFaculty/getFaculty','LaporandatamahasiswaController@getFaculty')->name('laporandatamahasiswa.getFaculty.getFaculty');
      Route::get('laporandatamahasiswa/getEntryyear/getEntryyear','LaporandatamahasiswaController@getEntryyear')->name('laporandatamahasiswa.getEntryyear.getEntryyear');
      Route::get('laporandatamahasiswa/exportdata/exportdata/{id1}/{id2}','LaporandatamahasiswaController@exportdata')->name('laporandatamahasiswa.exportdata.exportdata')->middleware('access:CanExport');
      Route::get('laporandatamahasiswa/laporandata/laporandata/{id1}/{id2}','LaporandatamahasiswaController@laporandata')->name('laporandatamahasiswa.laporandata.laporandata')->middleware('access:CanExport');
      Route::resource('exportfeeder', 'ExportfeederController');
      Route::get('exportfeeder/exportdata/exportdata/{id1}/{id2}/{id3}','ExportfeederController@exportdata')->name('exportfeeder.exportdata.exportdata');


      Route::resource('laporan_mahasiswa', 'laporan_mahasiswaController');

      Route::get('sertifikat', 'LaporanSertifikatController@index')->name('sertifikat.laporan');
  });
  

  Route::group(['prefix' => 'cetak'], function () {
      Route::resource('kartuujian', 'Cetak_KartuujianController');
      Route::get('/kartuujian/get_data/get_data', 'Cetak_KartuujianController@get_data')->name('kartuujian.get_data');
      Route::get('/kartuujian/post_data/post_data', 'Cetak_KartuujianController@post_data')->name('kartuujian.post_data');
      Route::get('/kartuujian/exportdata/exportdata/{id1}/{id2}/{id3}/{id4}','Cetak_KartuujianController@exportdata')->name('kartuujian.exportdata.exportdata');
      Route::get('/kartuujian/exportdata/exportdata/{id1}/{id2}/{id3}/{id4}/{id5}','Cetak_KartuujianController@exportdataall')->name('kartuujian.exportdata.exportdata');
      
      Route::resource('ktm','Cetak_ktmController');
      Route::get('/ktm/{ktm}/export', 'Cetak_ktmController@export')->name('ktm.export')->middleware('access:CanView');
      Route::resource('krs_khs_transkrip','Cetak_krs_khs_transkripController');
      Route::get('/krs_khs_transkrip/{krs_khs_transkrip}/export', 'Cetak_krs_khs_transkripController@export')->name('krs_khs_transkrip.export');
      Route::resource('ijazah','Cetak_ijazahController');
      Route::get('/ijazah/{ijazah}/export', 'Cetak_ijazahController@export')->name('ijazah.export')->middleware('access:CanView');
      route::resource('transcript_sementara','Cetak_transcriptsementaraController')->middleware('access:CanView');
      Route::get('/transcript_sementara/{transcript_sementara}/export', 'Cetak_transcriptsementaraController@export')->name('transcript_sementara.export')->middleware('access:CanExport');
      route::resource('transcript_akhir','Cetak_transcriptakhirController2')->middleware('access:CanView');
      Route::get('/transcript_akhir/{transcript_akhir}/export', 'Cetak_transcriptakhirController2@export')->name('transcript_akhir.export')->middleware('access:CanExport');
      Route::get('/transcript_akhirmulti/{nim}/export', 'Cetak_transcriptakhirController2@exportmulti')->name('transcript_akhir.exportmulti')->middleware('access:CanExport');
      Route::get('/transcript_akhir2/{transcript_akhir}/export', 'Cetak_transcriptakhirController2@export')->name('transcript_akhir.export')->middleware('access:CanExport');
      Route::resource('presensimhs','Cetak_presensimhsController')->middleware('access:CanView');
      Route::get('/presensimhs/{presensimhs}/export', 'Cetak_presensimhsController@export')->name('presensimhs.export')->middleware('access:CanExport');
      Route::resource('jadwaldanpesertaujian', 'Cetak_jadwalpesertaujianController')->middleware('access:CanView');
      Route::get('/jadwaldanpesertaujian/{jadwaldanpesertaujian}/export', 'Cetak_jadwalpesertaujianController@export')->name('jadwaldanpesertaujian.export')->middleware('access:CanExport');

  });

  Route::group(['prefix' => 'data'], function () {
    Route::get('/student/all','data\dataController@getAllStudent');
    Route::get('/student/onestudent/{std_id}','data\dataController@getOneStudent');
  });


});
// Route::group(['prefix' => 'modal'], function () {
// Route::get('faculty', 'FacultyController@modal')->name('faculty.modal');
// });
