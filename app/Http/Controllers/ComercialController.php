<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Carbon\Carbon;
class ComercialController extends Controller
{
  public function index()
  {
  	$data = DB::table('cao_usuario as user')
      ->select('user.*','perm.*')
      ->join('permissao_sistema as perm','perm.co_usuario','user.co_usuario')
      ->where('perm.co_sistema',1)
      ->where('perm.in_ativo','S')
      ->whereIn('perm.co_tipo_usuario',[0,1,2])
      ->get();

  	return view('comercial.index',[
  		'data' => $data,
  	]);
  }

  public function datos(Request $request)
  {
      $data = DB::table('cao_fatura as invoice')
      ->select('invoice.*','os.*','salary.*')
      ->join('cao_os as os','os.co_os','invoice.co_os')
      ->join('cao_salario as salary','salary.co_usuario','.os.co_usuario')
      ->whereIn('os.co_usuario',$request->pero)
      ->orderBy('invoice.data_emissao','desc')
      ->get()
      ->groupBy(function($val) {
            return Carbon::parse($val->data_emissao)->format('Y-m');
      });

      dd($data);
  }

}
