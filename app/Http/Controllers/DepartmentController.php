<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Input;
use DB;
use Redirect;
use Alert;
use Auth;

class DepartmentController extends Controller
{
  public function __construct()
  {
    $this->middleware('access:CanView', ['only' => ['index']]);
  }

  public function index(Request $request)
  {
    return view('mstr_department/home');
  }
}
