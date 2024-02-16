<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::group(['middleware' => ['ApiCheck']], function () {
    Route::group(['prefix' => 'master'], function () {
        Route::get('/entry_year', 'Api\ApiMasterController@getEntryYearController')->name('api.get.entryyear');
        Route::get('/echievementlevel', 'Api\ApiMasterController@getAchievementLevel')->name('api.get.echievementlevel');

        Route::group(['prefix' => 'akademik'], function () {
            Route::group(['prefix' => 'strata_pendidikan'], function () {
                Route::get('/all', 'Api\ApiEducationProgTypeController@getEducationProgTypeController')->name('api.get.strata_pendidikan');
                Route::post('/post', 'Api\ApiEducationProgTypeController@postEducationProgTypeController')->name('api.post.strata_pendidikan');
                Route::delete('/delete', 'Api\ApiEducationProgTypeController@deleteEducationProgTypeController')->name('api.delete.strata_pendidikan');
            });
            Route::group(['prefix' => 'faculty'], function () {
                //get semua fakultas
                Route::get('/all', 'Api\ApiFacultyController@getFacultyController')->name('api.get.faculty');
                Route::post('/post', 'Api\ApiFacultyController@postFacultyController')->name('api.post.faculty');
                Route::delete('/delete', 'Api\ApiFacultyController@deleteFacultyController')->name('api.delete.faculty');
            });
            Route::group(['prefix' => 'department'], function () {
                //get semua prodi
                Route::get('/all', 'Api\ApiDepartmentController@getDepartmentController')->name('api.get.department');
                Route::post('/post', 'Api\ApiDepartmentController@postDepartmentController')->name('api.post.department');
                Route::delete('/delete', 'Api\ApiDepartmentController@deleteDepartmentController')->name('api.delete.department');
            });
            Route::group(['prefix' => 'laporan'], function () {
                Route::get('/getstudentsertifikat', 'Api\ApiLaporanController@getStudentSertifikat')->name('api.get.getstudentsertifikat');
                Route::get('/sertifikat', 'Api\ApiLaporanController@getSertifikat')->name('api.get.sertifikat');
                Route::get('/uploadsertifikat', 'Api\ApiLaporanController@getUploadSertifikat')->name('api.get.uploadsertifikat');
            });
        });
    });
    Route::group(['prefix' => 'proses'], function () {
        Route::group(['prefix' => 'cuti'], function () {
            //get semua Cuti
            Route::get('/master_berkas_all', 'Api\ApiCutiController@getMasterBerkasCutiController')->name('api.get.master_berkas_cuti');
            Route::post('/master_berkas_post', 'Api\ApiCutiController@postMasterBerkasCutiController')->name('api.post.master_berkas_cuti');
            Route::delete('/master_berkas_delete', 'Api\ApiCutiController@deleteMasterBerkasCutiController')->name('api.delete.master_berkas_cuti');
            //master berkas prodi
            Route::get('/master_berkas_prodi_all', 'Api\ApiCutiController@getMasterBerkasProdiCutiController')->name('api.get.master_berkas_prodi_cuti');
            Route::get('/master_berkas_prodi_notin_all', 'Api\ApiCutiController@getBerkasNotinCutiController')->name('api.get.master_berkas_notin_cuti');
            Route::post('/master_berkas_prodi_post', 'Api\ApiCutiController@postMasterBerkasProdiCutiController')->name('api.post.master_berkas_prodi_cuti');
            Route::delete('/master_berkas_prodi_delete', 'Api\ApiCutiController@deleteMasterBerkasProdiCutiController')->name('api.delete.master_berkas_prodi_cuti');
            //master berkas aktifkembali
            Route::get('/master_berkas_kembali_all', 'Api\ApiCutiController@getMasterBerkasProdiKembaliController')->name('api.get.master_berkas_prodi_kembali');
            Route::get('/master_berkas_kembali_notin_all', 'Api\ApiCutiController@getBerkasNotinKembaliController')->name('api.get.master_berkas_notin_kembali');
            Route::post('/master_berkas_kembali_post', 'Api\ApiCutiController@postMasterBerkasProdiKembaliController')->name('api.post.master_berkas_prodi_kembali');
            Route::delete('/master_berkas_kembali_delete', 'Api\ApiCutiController@deleteMasterBerkasProdiKembaliController')->name('api.delete.master_berkas_prodi_kembali');
            //master berkas perpanjangan
            Route::get('/master_berkas_perpanjangan_all', 'Api\ApiCutiController@getMasterBerkasProdiPerpanjanganController')->name('api.get.master_berkas_prodi_perpanjangan');
            Route::get('/master_berkas_perpanjangan_notin_all', 'Api\ApiCutiController@getBerkasNotinPerpanjanganController')->name('api.get.master_berkas_notin_perpanjangan');
            Route::post('/master_berkas_perpanjangan_post', 'Api\ApiCutiController@postMasterBerkasProdiPerpanjanganController')->name('api.post.master_berkas_prodi_perpanjangan');
            Route::delete('/master_berkas_perpanjangan_delete', 'Api\ApiCutiController@deleteMasterBerkasProdiPerpanjanganController')->name('api.delete.master_berkas_prodi_perpanjangan');
            //get all student Cuti
            Route::get('/getstudentCuti', 'Api\ApiCutiController@GetStudentCuti')->name('api.get.getstudentCuti');
            Route::get('/GetPredicate', 'Api\ApiCutiController@GetPredicate')->name('api.get.GetPredicate');
            //berkas per siswa
            Route::get('/master_berkas_siswa_all', 'Api\ApiCutiController@getMasterBerkasSiswaCutiController')->name('api.get.master_berkas_siswa_cuti');
            Route::post('/postberkassiswaCuticontroller', 'Api\ApiCutiController@postBerkasSiswaCutiController')->name('api.post.postberkassiswaCuticontroller');
            //berkas per siswa kembali
            Route::get('/master_berkas_siswa_kembali', 'Api\ApiCutiController@getMasterBerkasSiswaKembaliController')->name('api.get.master_berkas_siswa_kembali');
            Route::post('/postmaster_berkas_siswa_kembali', 'Api\ApiCutiController@postBerkasSiswaKembaliController')->name('api.post.master_berkas_siswa_kembali');
            //isi Cuti
            Route::post('/postdatasiswaCuticontroller', 'Api\ApiCutiController@postDataSiswaCutiController')->name('api.post.postdatasiswacuti');
            //delete Cuti siswa
            Route::delete('/deletesiswaCuticontroller', 'Api\ApiCutiController@deleteSiswaCutiController')->name('api.delete.deletesiswacuti');
            //allowed cuti
            Route::get('/cek_allowed_vacation', 'Api\ApiCutiController@getAllowedCuti')->name('api.get.cek_allowed_vacation');
            
            Route::post('/upload/{Vacation_Document_Id}/upload_dokumen', 'Api\ApiCutiController@uploadMasterBerkasProdiCutiController')->name('api.upload.master_berkas_prodi_cuti');
        });
        Route::group(['prefix' => 'yudisium'], function () {
            //get semua Yudisium
            Route::get('/master_berkas_all', 'Api\ApiYudisiumController@getMasterBerkasYudisiumController')->name('api.get.master_berkas_yudisium');
            Route::post('/master_berkas_post', 'Api\ApiYudisiumController@postMasterBerkasYudisiumController')->name('api.post.master_berkas_yudisium');
            Route::delete('/master_berkas_delete', 'Api\ApiYudisiumController@deleteMasterBerkasYudisiumController')->name('api.delete.master_berkas_yudisium');
            //master berkas prodi
            Route::get('/master_berkas_prodi_all', 'Api\ApiYudisiumController@getMasterBerkasProdiYudisiumController')->name('api.get.master_berkas_prodi_yudisium');
            Route::get('/master_berkas_prodi_notin_all', 'Api\ApiYudisiumController@getBerkasNotinYudisiumController')->name('api.get.master_berkas_notin_yudisium');
            Route::post('/master_berkas_prodi_post', 'Api\ApiYudisiumController@postMasterBerkasProdiYudisiumController')->name('api.post.master_berkas_prodi_yudisium');
            Route::delete('/master_berkas_prodi_delete', 'Api\ApiYudisiumController@deleteMasterBerkasProdiYudisiumController')->name('api.delete.master_berkas_prodi_yudisium');
            //get all student yudisium
            Route::get('/getstudentyudisium', 'Api\ApiYudisiumController@GetStudentYudisium')->name('api.get.getstudentyudisium');
            Route::get('/GetPredicate', 'Api\ApiYudisiumController@GetPredicate')->name('api.get.GetPredicate');
            //berkas per siswa
            Route::get('/master_berkas_siswa_all', 'Api\ApiYudisiumController@getMasterBerkasSiswaYudisiumController')->name('api.get.master_berkas_siswa_yudisium');
            Route::post('/postberkassiswayudisiumcontroller', 'Api\ApiYudisiumController@postBerkasSiswaYudisiumController')->name('api.post.postberkassiswayudisiumcontroller');
            //isi yudisium
            Route::post('/postdatasiswayudisiumcontroller', 'Api\ApiYudisiumController@postDataSiswaYudisiumController')->name('api.post.postdatasiswayudisiumcontroller');
            //delete yudisium siswa
            Route::delete('/deletesiswayudisiumcontroller', 'Api\ApiYudisiumController@deleteSiswaYudisiumController')->name('api.delete.deletesiswayudisiumcontroller');
        });
    });
});
Route::get('/krs_online/course_list/{termyearid?}', 'KrsOnline\CreateController@getCourseList')->name('krsonline_courselist');
Route::get('/krs_online/class/{courseid?}', 'KrsOnline\CreateController@getClassList')->name('krsonline_class');
Route::get('/krs_online/coursecost/{courseid?}', 'KrsOnline\CreateController@getCourseCost')->name('krsonline_coursecost');
Route::get('/krs_online/classinfo/{courseid?}', 'KrsOnline\CreateController@getClassInfo')->name('krsonline_classinfo');
Route::get('/krs_online/classinfo/{courseid?}/{classid?}', 'KrsOnline\CreateController@getClassInfo')->name('krsonline_classinfoo');
Route::get('/krs_online/prerequisitecourse/{courseid?}/{classid?}', 'KrsOnline\CreateController@prerequisiteCourse')->name('krsonline_prerequisitecourse');
Route::get('/krs_online/semesterpendekcourse/{courseid?}/{classid?}', 'KrsOnline\CreateController@semesterpendekCourse')->name('krsonline_semesterpendekcourse');
Route::get('/krs_online/prerequisiteclass/{courseid?}/{classid?}', 'KrsOnline\CreateController@prerequisiteClass')->name('krsonline_prerequisiteclass');
Route::get('/krs_online/store/', 'KrsOnline\CreateController@storeData')->name('krsonline_store');
Route::get('/krs_online/gettoken/', 'KrsOnline\CreateController@getToken')->name('getToken');
Route::get('/krs_online/posttoken/', 'KrsOnline\CreateController@postToken')->name('postToken');
