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

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;

Route::get('/hash', function () {
  $hash = Hash::make('admin123');
  dd($hash);
});

Route::fallback(function(){
    return view('404');
});
Route::get('/', function () {
  if (Auth::check()) {
    return view('home');
  }
    return redirect('login');
});
Route::get('/home', function () {
  if (Auth::check()) {
    return view('home');
  }
    return redirect('login');
});
Route::get('/info', function () {
  if (Auth::check()) {
    return view('info');
  }
    return redirect('login');
});

Route::get('/redirect', 'SocialAuthGoogleController@redirect');
Route::get('/callback', 'SocialAuthGoogleController@callback');

Route::get('/cek_encript', function () {
  $encrypted = Crypt::encryptString('Hello world.');

$decrypted = Crypt::decryptString($encrypted);
  dd($encrypted);
});

Route::get('client/krs_mahasiswa/{nim}/{term_year}', 'Client_CetakController@export_krs');
Route::get('client/khs_mahasiswa/{nim}/{term_year}', 'Client_CetakController@export_khs');
Route::get('client/export_transcript_akhir/{nim}', 'Client_CetakController@export_trnascript_akhir');
Route::get('/getfile','GetFileController@getFile')->name('getfile');

Route::resource('cek', 'CekController');


Route::get('/request/export/{Nim}', 'User\Request_exportController@export')->name('request.export');

Auth::routes();


Route::group(['middleware' => ['auth']], function () {
  // Route::get('/home', 'HomeController@index')->name('home');

  Route::get('room_jadwal_uses','RommJadwalUsesController@get_all')->name('room_jadwal_uses');

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
      Route::post('/term_year/term_year_active', 'Term_yearController@term_year_active')->name('term_year.term_year_active');


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
      Route::resource('announcement', 'AnnouncementController')->middleware('access:CanView');
      Route::get('announcement/create/{department}', 'AnnouncementController@Create');
      Route::get('announcement/{department}/edit/{dept}', 'AnnouncementController@Edit');

  });

  Route::group(['prefix' => 'parameter'], function () {
      Route::resource('curriculum_applied', 'Curriculum_appliedController');
      Route::resource('curriculum_entry_year', 'Curriculum_entry_yearController');
      Route::get('/curriculum_entry_year/create/class_program', 'Curriculum_entry_yearController@class_program')->name('curriculum_entry_year.class_program');
      Route::get('/curriculum_entry_year/copy/semester', 'Curriculum_entry_yearController@copy_data')->name('curriculum_entry_year.copy_data');

      Route::resource('course', 'CourseController');
      Route::get('/course/exportexcel/exportexcel','CourseController@exportexcel')->name('course.exportexcel');
      Route::get('/course/export/{type?}', 'CourseController@export')->name('course.export')->middleware('access:CanExport');
      Route::get('/course/import_export/import_export','CourseController@import_export')->name('course.import_export');
      Route::post('/course/import_excel/import_excel','CourseController@import_excel')->name('course.import_excel');
      Route::resource('course_curriculum', 'Course_curriculumControllerbkp');
      Route::post('/course_curriculum/hapussilabus', 'Course_curriculumControllerbkp@hapus_silabus')->name('course_curriculum.hapussilabus');
      Route::get('course_curriculum/silabus/silabus', 'Course_curriculumControllerbkp@silabus')->name('course_curriculum.silabus')->middleware('access:CanView');
      Route::post('/course_curriculum/store_silabus', 'Course_curriculumControllerbkp@store_silabus')->name('course_curriculum.store_silabus');
      Route::get('/course_curriculum/create/copydata', 'Course_curriculumControllerbkp@copydata')->name('course_curriculum.copydata');
      Route::get('/course_curriculum/export/exportexcel', 'Course_curriculumControllerbkp@exportexcel')->name('course_curriculum.exportexcel');
      Route::post('/course_curriculum/create/storecopydata', 'Course_curriculumControllerbkp@storecopydata')->name('course_curriculum.storecopydata');


      Route::resource('prasyarat', 'Prerequisite_detailController');
      Route::resource('course_identic', 'Course_identicController');
      Route::delete('course_identic/destroy_course/{id}', 'Course_identicController@destroy_course')->name('course_identic.destroy_course');
      Route::resource('grade_department', 'Grade_departmentController');
      Route::get('/grade_department/create/copydata', 'Grade_departmentController@copydata')->name('grade_department.copydata');
      Route::get('/grade_department/create/update_department', 'Grade_departmentController@update_department')->name('grade_department.update_department');
      Route::post('/grade_department/create/storecopydata', 'Grade_departmentController@storecopydata')->name('grade_department.storecopydata');

      Route::resource('beban_mengajar', 'Beban_mengajarController');

      Route::resource('komponen_penilaian', 'Komponen_PenilaianController');
      Route::post('/komponen_penilaian/update', 'Komponen_PenilaianController@update')->name('komponen_penilaian.update');
      Route::delete('komponen_penilaian/destroy_bobot/{id}', 'Komponen_PenilaianController@destroy_bobot')->name('komponen_penilaian.destroy_bobot');
      Route::get('/komponen_penilaian/create/refresh_data', 'Komponen_PenilaianController@refresh_data')->name('komponen_penilaian.refresh_data');
      Route::get('/komponen_penilaian/create/copydata', 'Komponen_PenilaianController@copydata')->name('komponen_penilaian.copydata');
      Route::post('/komponen_penilaian/create/storecopydata', 'Komponen_PenilaianController@storecopydata')->name('komponen_penilaian.storecopydata');


  });

  Route::group(['prefix' => 'setting'], function () {
      Route::resource('student', 'StudentController');
      Route::get('/student/exportdata/exportdata/{id1}/{id2}','StudentController@exportdata')->name('student.exportdata.exportdata');

      Route::get('/student/import_export/import_export','StudentController@import_export')->name('student.import_export');
      Route::post('/student/import_excel/import_excel','StudentController@import_excel')->name('student.import_excel');

      Route::resource('rfid', 'RfidController');
      Route::get('/rfid/get_data/get_data', 'RfidController@get_data')->name('RfidController.get_data');
      Route::get('/rfid/post_data/post_data', 'RfidController@post_data')->name('RfidController.post_data');
      Route::get('/rfid/exportdata/exportdata/{id1}/{id2}','RfidController@exportdata')->name('student.exportdata.exportdata');

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
      // Route::get('/student_supervision/export', 'Student_supervisionController@export');
      Route::post('/student_supervision/update_dosen', 'Student_supervisionController@update_dosen')->name('student_supervision.update_dosen');
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
      Route::get('/offered_course_sched/create', 'Offered_course_schedController@create')->name('offered_course_sched.create');
      Route::get('/offered_course_sched/findgrubsesi/findgrubsesi','Offered_course_schedController@findgrubsesi');
      Route::get('/offered_course_sched/findtype/findtype','Offered_course_schedController@findtype');
      Route::get('/offered_course_sched/findroom/findroom','Offered_course_schedController@findroom');
      Route::get('/offered_course_sched/findjadwal/findjadwal/{id1}/{id2}/{id3}/{id4}','Offered_course_schedController@findjadwal');
      Route::post('/offered_course_sched/store_detail', 'Offered_course_schedController@store_detail')->name('offered_course_sched.store_detail');
      Route::get('offered_course_sched/exportdata/exportdata/{id1}/{id2}/{id3}','Offered_course_schedController@exportdata')->name('Offered_course_schedController.exportdata.exportdata');

      Route::resource('offered_course_schedV2', 'Offered_course_scheddropController');
      Route::get('/offered_course_schedV2/create', 'Offered_course_scheddropController@create')->name('offered_course_schedV2.create');
      Route::get('/offered_course_schedV2/findgrubsesi/findgrubsesi','Offered_course_scheddropController@findgrubsesi');
      Route::get('/offered_course_schedV2/findtype/findtype','Offered_course_scheddropController@findtype');
      Route::get('/offered_course_schedV2/findroom/findroom','Offered_course_scheddropController@findroom');
      Route::get('/offered_course_schedV2/findroom/findroomwithsched/{id1}','Offered_course_scheddropController@findroomwithsched');
      Route::get('/offered_course_schedV2/findjadwal/findjadwal/{id1}/{id2}/{id3}/{id4}','Offered_course_scheddropController@findjadwal');
      Route::post('/offered_course_schedV2/store_detail', 'Offered_course_scheddropController@store_detail')->name('offered_course_schedV2.store_detail');
      Route::get('offered_course_schedV2/exportdata/exportdata/{id1}/{id2}/{id3}','Offered_course_scheddropController@exportdata')->name('Offered_course_schedControllerV2.exportdata.exportdata');
      Route::get('offered_course_schedV2/exportdata/exportdatacsv','Offered_course_scheddropController@exportdatacsv')->name('Offered_course_schedControllerV2.exportdata.exportdatacsv');

      Route::resource('offered_course_exam', 'Offered_course_examController');
      Route::get('/offered_course_exam/{offered_course_exam}/peserta', 'Offered_course_examController@peserta')->name('offered_course_exam.peserta')->middleware('access:CanViewPeserta');
      Route::get('/offered_course_exam/peserta/create', 'Offered_course_examController@create_peserta')->name('offered_course_exam.create_peserta')->middleware('access:CanAddPeserta');
      Route::post('/offered_course_exam/store_peserta', 'Offered_course_examController@store_peserta')->name('offered_course_exam.store_peserta');
      Route::delete('/offered_course_exam/destroy_peserta/{offered_course_exam}', 'Offered_course_examController@destroy_peserta')->name('offered_course_exam.destroy_peserta')->middleware('access:CanHapusPeserta');
      Route::get('/offered_course_exam/{offered_course_exam}/export', 'Offered_course_examController@export')->name('offered_course_exam.export')->middleware('access:CanExportPresensi');
      Route::get('/offered_course_exam/{offered_course_exam}/export/all', 'Offered_course_examController@exportall')->name('offered_course_exam.exportall')->middleware('access:CanExportPresensi');
      Route::post('/offered_course_exam/store_presence', 'Offered_course_examController@store_presence')->name('offered_course_exam.store_presence');
      Route::get('offered_course_exam/exportdata/exportdata/{id1}/{id2}/{id3}','Offered_course_examController@exportdata')->name('offered_course_exam.exportdata.exportdata');
      
      Route::resource('first_sks', 'First_SksController',['except'=> ['index','create','show','edit']]);
      Route::get('/first_sks', 'First_SksController@index')->name('first_sks.index');
      Route::get('/first_sks/create', 'First_SksController@create')->name('first_sks.create');
      Route::get('/first_sks/{first_sks}/edit', 'First_SksController@edit')->name('first_sks.create');

  });

  Route::group(['prefix' => 'proses'], function () {
      Route::resource('krs_matakuliah', 'Krs_matakuliahController');
      Route::get('/krs_matakuliah/{krs_matakuliah}/export', 'Krs_matakuliahController@export')->name('krs_matakuliah.export')->middleware('access:CanExport');
      Route::resource('krs_mahasiswa', 'Krs_mahasiswaController');
      Route::get('/krs_mahasiswa/{nim}/export', 'Krs_mahasiswaController@export')->name('krs_mahasiswa.export')->middleware('access:CanExport');

      Route::resource('krs_paket', 'Krs_paketController');
      Route::get('/krs_paket/{id}/create_datapeserta', 'Krs_paketController@create_datapeserta')->name('krs_paket.create_datapeserta');
      Route::post('/krs_paket/store/update_datapeserta', 'Krs_paketController@update_datapeserta')->name('krs_paket.update_datapeserta');
      Route::resource('krs_approved', 'Approved_krsController');
      Route::get('krs_approved/approved/approved','Approved_krsstoreController@approved_store')->name('krs_approved.approved');
      Route::get('krs_approved/approved/rollback','Approved_krsstoreController@approved_rollback_store')->name('krs_approved.rollback');


      Route::get('/krs_online/create', 'KrsOnline\CreateController@index')->name('krsonline_create');
      Route::get('/krs_online/course_list/{termyearid?}', 'KrsOnline\CreateController@getCourseList')->name('krsonline_courselist');
      Route::post('/krs_online/class/{courseid?}', 'KrsOnline\CreateController@getClassList')->name('krsonline_class');
      // Route::post('/krs_online/classinfo/{courseid?}', 'KrsOnline\CreateController@getClassInfo')->name('krsonline_classinfo');
      Route::post('/krs_online/classinfo/{courseid?}/{classid?}', 'KrsOnline\CreateController@getClassInfo')->name('krsonline_classinfoo');
      Route::post('/krs_online/prerequisitecourse/{courseid?}/{classid?}', 'KrsOnline\CreateController@prerequisiteCourse')->name('krsonline_prerequisitecourse');
      Route::post('/krs_online/prerequisiteclass/{courseid?}/{classid?}', 'KrsOnline\CreateController@prerequisiteClass')->name('krsonline_prerequisiteclass');
      Route::post('/krs_online/store/', 'KrsOnline\CreateController@storeData')->name('krsonline_store');

      Route::resource('khs_matakuliah', 'Khs_matakuliahController');
      Route::get('/khs_matakuliah/bobot/{offer}','Khs_matakuliahController@getSetting')->name('khs_matakuliah.getSetting');
      Route::post('/khs_matakuliah/bobot/{offer}/simpan','Khs_matakuliahController@storeSetting')->name('khs_matakuliah.storeSetting');
      Route::get('/khs_matakuliah/getNilaiAkhir/{id}/{id2}/{id3}/{id4}/','Khs_matakuliahController@getNilaiAkhir')->name('khs_matakuliah.getNilaiAkhir');
      Route::get('/khs_matakuliah/updateNilaiAkhir/update','Khs_matakuliahController@updateNilaiAkhir')->name('khs_matakuliah.updateNilaiAkhir');
      Route::get('/khs_matakuliah/publishNilai/publish','Khs_matakuliahController@publishNilai')->name('khs_matakuliah.publishNilai');
      Route::get('/khs_matakuliah/updateKurikulum/publish','Khs_matakuliahController@updateKurikulum')->name('khs_matakuliah.updateKurikulum');
      Route::get('/khs_matakuliah/defaultnilaiuts/publish','Khs_matakuliahController@defaultnilaiuts')->name('khs_matakuliah.defaultnilaiuts');
      Route::get('/khs_matakuliah/defaultnilaiuas/publish','Khs_matakuliahController@defaultnilaiuas')->name('khs_matakuliah.defaultnilaiuas');
      Route::post('/khs_matakuliah/storenilaiprak/simpan','Khs_matakuliahController@storeNilaiPrak')->name('khs_matakuliah.storeNilaiPrak');
      Route::get('/khs_matakuliah/exportdata/{oci}','Khs_matakuliahController@exportdata')->name('khs_matakuliah.exportdata');

      Route::resource('khs_mahasiswa', 'Khs_mahasiswaController');
      Route::get('/khs_mahasiswa/{khs_mahasiswa}/export', 'Khs_mahasiswaController@export')->name('khs_mahasiswa.export');
      Route::resource('transcript_equivalensi', 'Transcript_equivalensiController');

      Route::resource('cuti', 'CutiController');
      Route::get('/kembali', 'CutiController@kembali')->name('kembali');
      Route::get('/kembali/{id}/edit', 'CutiController@editkembali')->name('kembali.edit');
      Route::put('/kembali/update/{id}', 'CutiController@updatekembali')->name('kembali.update');
      Route::get('/cuti/berkascuti/data','CutiController@berkascuti')->name('berkascuti');
      Route::get('/cuti/masterberkascuti/data','CutiController@masterberkascuti')->name('masterberkascuti');
      Route::get('/cuti/export/exportcuti', 'CutiController@exportCuti')->name('exportcuti');
      
      Route::resource('tugas_akhir','Tugas_akhirController');
      Route::get('/tugas_akhir/dosen_penguji/create','Tugas_akhirController@dosen_penguji');
      Route::post('/tugas_akhir/dosen_penguji/store','Tugas_akhirController@storedosen_penguji')->name('tugas_akhir.storedosen_penguji');
      Route::delete('/tugas_akhir/dosen_penguji/delete/{idexaminer}','Tugas_akhirController@deletedosen_penguji')->name('tugas_akhir.deletedosen_penguji');
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
      Route::get('/yudisium/export/exportyudisium', 'YudisiumController@exportYudisium')->name('exportyudisium');
      Route::get('/yudisium/import/data', 'YudisiumController@import')->name('importyudisium');
      Route::post('/yudisium/import_excel/import_excel', 'YudisiumController@storeImport')->name('storeImportYudisium');
      Route::resource('wisuda','WisudaController');
      Route::resource('periodewisuda','PeriodewisudaController');
      Route::resource('/schedreal', 'SchedrealController');
      Route::get('/schedreal/peserta/{schedreal}/{id}', 'SchedrealController@peserta')->name('schedreal.peserta');
      Route::get('/schedreal/pesertatotal/{id}', 'SchedrealController@pesertatotal')->name('schedreal.pesertatotal');
      Route::get('/schedreal/peserta/{schedreal}/{id}/detail', 'SchedrealController@detail')->name('schedreal.detail');
      Route::post('/schedreal/peserta/store', 'SchedrealController@storepeserta')->name('schedreal.storepeserta');

      Route::get('/schedreal/export/{id}', 'SchedrealController@exportall')->name('schedreal.exportall');
      Route::get('/schedreal/exportdosen/{id}', 'SchedrealController@exportdosen')->name('schedreal.exportdosen');
      
      Route::get('/krslist/index', 'KuesionerListController@index')->name('krslist.index');
      Route::get('/krslist/import', 'KuesionerListController@import')->name('krslist.import');
      Route::post('/krslist/storemhskuesioner', 'KuesionerListController@storemhskuesioner')->name('krslist.storemhskuesioner');
      Route::get('/krslist/storemhskuesioner', 'KuesionerListController@storemhskuesioner')->name('krslist.storemhskuesioner');
      Route::get('/krslist/delete', 'KuesionerListController@delete')->name('krslist.delete');
  });

  Route::group(['prefix' => 'laporan'], function () {
      route::resource('detail_pengisian_nilai','Detail_pengisiannilaiController');
      Route::get('detail_pengisian_nilai/exportdata/exportdata/{id1}/{id2}/{id3}','Detail_pengisiannilaiController@exportdata')->name('detail_pengisian_nilai.exportdata.exportdata');

      route::resource('laporan_daftar_mahasiswa_krs','Daftar_mahasiswa_krsController');
      Route::get('/laporan_daftar_mahasiswa_krs/exportdata/exportdata/{id1}/{id2}/{id3}','Daftar_mahasiswa_krsController@exportdata')->name('laporan_daftar_mahasiswa_krs.exportdata.exportdata');
      route::resource('laporan_pembayaran_mahasiswa','Daftar_pembayaran_mahasiswaController');
      Route::get('/laporan_pembayaran_mahasiswa/exportdata/exportdata/{id1}/{id2}/{id3}','Daftar_pembayaran_mahasiswaController@exportdata')->name('laporan_pembayaran_mahasiswa.exportdata.exportdata');
      Route::get('/laporan_pembayaran_mahasiswa/getdata/riwayat','RiwayatPembayaranDetailsController@index')->name("laporan_pembayaran_mahasiswa.riwayat");
      Route::get('/laporan_pembayaran_mahasiswa/getdata/pdf/{Reff_Payment_Id?}','RiwayatPembayaranDetailsController@pdf')->name("laporan_pembayaran_mahasiswa.export");
      Route::get('/laporan_pembayaran_mahasiswa/getdata/kwitansi/{Reff_Payment_Id?}','RiwayatPembayaranDetailsController@kwitansi')->name("laporan_pembayaran_mahasiswa.kwitansi");

      route::resource('laporan_dosenmengajar','Dosen_mengajarController');
      Route::get('/laporan_dosenmengajar/getdosen/{id}/{id2}/{id3}','Dosen_mengajarController@get_data')->name('dosen_mengajar.get_data');
      Route::get('/laporan_dosenmengajar/getajardosen/{id}/{id2}/{id3}/{id4}','Dosen_mengajarController@get_ajardosen')->name('dosen_mengajar.get_ajardosen');
      Route::get('/laporan_dosenmengajar/getsesikuliah/{id}/{id2}/{id3}/{id4}','Dosen_mengajarController@get_sesikuliah')->name('dosen_mengajar.get_sesikuliah');
      Route::get('laporan_dosenmengajar/exportdata/exportdata/{id1}/{id2}/{id3}','Dosen_mengajarController@exportdata')->name('dosen_mengajar.exportdata.exportdata');

      route::resource('laporan_history_nilaimhs','History_nilaimhsController');
      route::resource('laporan_mhskrs','Resume_mhs_krsController');
      Route::get('/laporan_mhskrs/showmhsnonaktif/{id}', 'Resume_mhs_krsController@showmhsnonaktif')->name('showmhsnonaktif.showmhsnonaktif')->middleware('access:CanViewnonaktif');
      Route::get('/laporan_mhskrs/showmhscuti/{id}', 'Resume_mhs_krsController@showmhscuti')->name('showmhsnonaktif.showmhscuti');
      Route::get('/laporan_mhskrs/exportexcel/exportexcel','Resume_mhs_krsController@exportexcel')->name('laporan_mhskrs.exportexcel');
      Route::get('/laporan_mhskrs/exportexcelnonaktif/exportexcelnonaktif','Resume_mhs_krsController@exportexcelnonaktif')->name('laporan_mhskrs.exportexcelnonaktif')->middleware('access:CanExport');
      Route::get('/laporan_mhskrs/showmhslulus/{id}', 'Resume_mhs_krsController@showmhslulus')->name('showmhsnonaktif.showmhslulus');
      Route::get('/laporan_mhskrs/exportexcelcuti/exportexcelcuti','Resume_mhs_krsController@exportexcelcuti')->name('laporan_mhskrs.exportexcelcuti');

      //Laporan SPP
      route::resource('laporan_spp','LaporanSppController');
      Route::get('laporan_spp/exportexcel/exportexcel/{department}/{term_year}','LaporanSppController@exportexcel')->name('laporanspp.exportexcel');

      Route::resource('laporandatamahasiswa', 'LaporandatamahasiswaController');
      Route::get('laporandatamahasiswa/getAll/getAll','LaporandatamahasiswaController@getAll')->name('laporandatamahasiswa.getAll.getAll');
      Route::get('laporandatamahasiswa/getDepartment/getDepartment','LaporandatamahasiswaController@getDepartment')->name('laporandatamahasiswa.getDepartment.getDepartment');
      Route::get('laporandatamahasiswa/getFaculty/getFaculty','LaporandatamahasiswaController@getFaculty')->name('laporandatamahasiswa.getFaculty.getFaculty');
      Route::get('laporandatamahasiswa/getEntryyear/getEntryyear','LaporandatamahasiswaController@getEntryyear')->name('laporandatamahasiswa.getEntryyear.getEntryyear');
      Route::get('laporandatamahasiswa/exportdata/exportdata/{id1}/{id2}','LaporandatamahasiswaController@exportdata')->name('laporandatamahasiswa.exportdata.exportdata')->middleware('access:CanExport');
      Route::get('laporandatamahasiswa/laporandata/laporandata/{id1}/{id2}','LaporandatamahasiswaController@laporandata')->name('laporandatamahasiswa.laporandata.laporandata')->middleware('access:CanExport');
      Route::resource('exportfeeder', 'ExportfeederController');
      Route::get('exportfeeder/exportdata/exportdata/{id1}/{id2}/{id3}','ExportfeederController@exportdata')->name('exportfeeder.exportdata.exportdata');
      Route::resource('log_aktivitas', 'Log_aktivitasController');
      Route::get('log_aktivitas/exportdata/exportdata/{id1}','Log_aktivitasController@exportdata')->name('exportfeeder.exportdata.exportdata');
      Route::get('sertifikat','LaporanSertifikatController@index')->name('sertifikat.laporan');
  });


  Route::group(['prefix' => 'cetak'], function () {
      Route::resource('ktm','Cetak_ktmController');
      Route::get('/ktm/{ktm}/export', 'Cetak_ktmController@export')->name('ktm.export')->middleware('access:CanView');

      Route::get('/peserta_matakuliah', 'Cetak_pesertamatakuliahController@index')->name('peserta_matakuliah.index');
      Route::get('/peserta_matakuliah/cetak', 'Cetak_pesertamatakuliahController@cetak')->name('peserta_matakuliah.cetak');

      Route::resource('ijazah','Cetak_ijazahController');
      Route::get('/ijazah/{ijazah}/export', 'Cetak_ijazahController@export')->name('ijazah.export')->middleware('access:CanView');

      route::resource('transcript_sementara','Cetak_transcriptsementaraController')->middleware('access:CanView');
      route::get('transcript_sementara2','Cetak_transcriptsementaraController@index')->name('index');
      route::get('update/use_transcript','Cetak_transcriptsementaraController@use_transcript')->name('use_transcript');
      Route::get('/transcript_sementara/{transcript_sementara}/export', 'Cetak_transcriptsementaraController@export')->name('transcript_sementara.export')->middleware('access:CanExport');

      route::resource('transcript_akhir','Cetak_transcriptakhirController2')->middleware('access:CanView');
      Route::get('/transcript_akhir/{transcript_akhir}/export', 'Cetak_transcriptakhirController2@export')->name('transcript_akhir.export')->middleware('access:CanExport');
      Route::get('/presensimhs/{presensimhs}/exportttd', 'Cetak_presensimhsController@exportttd')->name('presensimhs.exportttd')->middleware('access:CanExport');

      Route::resource('kartuujian', 'Cetak_KartuujianController');
      Route::get('/kartuujian/get_data/get_data', 'Cetak_KartuujianController@get_data')->name('kartuujian.get_data');
      Route::get('/kartuujian/post_data/post_data', 'Cetak_KartuujianController@post_data')->name('kartuujian.post_data');
      Route::get('/kartuujian/exportdata/exportdata/{id1}/{id2}/{id3}/{id4}','Cetak_KartuujianController@exportdata')->name('kartuujian.exportdata.exportdata');
      Route::get('/kartuujian/exportdata/exportdata/{id1}/{id2}/{id3}/{id4}/{id5}','Cetak_KartuujianController@exportdataall')->name('kartuujian.exportdata.exportdata');


      Route::resource('presensimhs','Cetak_presensimhsController')->middleware('access:CanView');
      Route::get('/presensimhs/{presensimhs}/export', 'Cetak_presensimhsController@export')->name('presensimhs.export')->middleware('access:CanExport');
      Route::resource('jadwaldanpesertaujian', 'Cetak_jadwalpesertaujianController')->middleware('access:CanView');
      Route::get('/jadwaldanpesertaujian/{jadwaldanpesertaujian}/export', 'Cetak_jadwalpesertaujianController@export')->name('jadwaldanpesertaujian.export')->middleware('access:CanExport');

  });

  Route::group(['prefix' => 'feeder'], function () {
      Route::get('conf', 'Feeder\ConfController@index')->name('feeder.conf.index');
      Route::post('conf/save', 'Feeder\ConfController@save')->name('feeder.conf.save');
      Route::resource('sync', 'Feeder\SyncController');
      Route::post('sync/new_update', 'Feeder\SyncController@newUpdate');
      Route::post('sync/syncronize', 'Feeder\SyncController@syncronize');
      Route::post('sync/insert_feeder/mahasiswa', 'Feeder\InsertFeederController@InsertMahasiswa');
      Route::post('sync/insert_feeder/mata_kuliah', 'Feeder\InsertFeederController@InsertMataKuliah');
      Route::post('sync/insert_feeder/kelas_kuliah', 'Feeder\InsertFeederController@InsertKelasKuliah');
      Route::post('sync/insert_feeder/ajar_dosen', 'Feeder\InsertFeederController@InsertAjarDosen');
      Route::post('sync/insert_feeder/bobot_nilai', 'Feeder\InsertFeederController@InsertBobotNilai');
      Route::post('sync/insert_feeder/nilai', 'Feeder\InsertFeederController@InsertNilai');
      Route::post('sync/insert_feeder/kuliah_mahasiswa', 'Feeder\InsertFeederController@InsertKuliahMahasiswa');
      Route::post('sync/insert_feeder/nilai_transfer', 'Feeder\InsertFeederController@InsertNilaiTransfer');
      Route::post('sync/insert_feeder/mahasiswa_lulus_do', 'Feeder\InsertFeederController@InsertMahasiswaLulusDo');
      Route::post('sync/insert_feeder/dosen_pembimbing', 'Feeder\InsertFeederController@InsertDosenPembimbing');
      Route::post('sync/insert_feeder/bimbing_mahasiswa', 'Feeder\InsertFeederController@InsertBimbingMahasiswa');
      Route::post('sync/insert_feeder/aktivitas_mahasiswa', 'Feeder\InsertFeederController@InsertAktivitasMahasiswa');
      Route::post('sync/insert_feeder/anggota_aktivitas_mahasiswa', 'Feeder\InsertFeederController@InsertAnggotaAktivitasMahasiswa');
      Route::post('sync/insert_feeder/prestasi', 'Feeder\InsertFeederController@InsertPrestasi');
      Route::get('sync/delete/upload', 'Feeder\SyncController@destroyUpload');
      Route::get('sync/delete/upload_update', 'Feeder\SyncController@destroyUploadUpdate');
      Route::post('sync/log_error', 'Feeder\SyncController@logError');
  });

});
// Route::group(['prefix' => 'modal'], function () {
// Route::get('faculty', 'FacultyController@modal')->name('faculty.modal');
// });
