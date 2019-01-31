<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
class ComercialController extends Controller
{
    public function index()
    {
    	$data = DB::table('cao_usuario')->select('*')->get();
    	$num = 0;

    	return view('comercial.index',[
    		'data' => $data,
    		'num' => $num
    	]);
    }

    public function datos(Request $request)
    {
    	dd($request->query('pero'));
    }

}
