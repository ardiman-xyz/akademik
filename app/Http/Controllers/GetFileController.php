<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use File;
use Response;

class GetFileController extends Controller
{
    public function getFile(Request $request){
        $pathToFile = storage_path('app/public/'.$request->name);
        $extension = File::extension($pathToFile);
        if ($extension != 'pdf' && $extension != 'jpg' && $extension != 'jpeg' && $extension != 'png' && $extension != 'docx'&& $extension != 'xlsx') {
            abort(404, 'Not Found');
        }

        $file = File::get($pathToFile); 
        $type = File::mimeType($pathToFile); 
        $response = Response::make($file, 200); 
        $response->header("Content-Type", $type);
        //name file
        $explodes = explode('/', $request->name);
        $name = end($explodes);
        $response->header("Content-Disposition", "attachment; filename=$name");
        return $response;
    }
}
