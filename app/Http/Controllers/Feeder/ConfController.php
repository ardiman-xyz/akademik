<?php

namespace App\Http\Controllers\Feeder;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\FeederConf;

class ConfController extends Controller
{
    public function index()
    {
        $data = FeederConf::first();
        return view('feeder.conf.index')->with(['data'=> $data]);
    }
    public function save(Request $request)
    {
        // dd($request->all());
        try {
            FeederConf::query()->update(
            ['url_wsdl' => $request->url_wsdl, 
            'user_wsdl' => $request->user_wsdl,
            'pass_wsdl' => $request->pass_wsdl,
            'activate_synchronization' => (int)$request->activate_synchronization,
            'default_term_year' => $request->default_term_year,
            'realization_term_year' => $request->realization_term_year,
            'separator_export' => $request->separator_export]);
            return redirect()->back()->with('status', 'Konfigurasi Sudah di Update');
        } catch (\Throwable $th) {
            return redirect()->back()->with('status', '!!Error Menupdate Konfigurasi!!');
        }
    }
}
