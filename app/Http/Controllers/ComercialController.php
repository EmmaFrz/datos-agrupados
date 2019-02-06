<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use DB;
use Carbon\Carbon;
class ComercialController extends Controller
{
  public function index()
  {
    $data = ComercialController::consultores();

  	return view('comercial.index',[
  		'data' => $data,
      'correlatives' => false,
      'pizza' => false,
      'graphic' => false
  	]);
  }

  public function datos(Request $request)
  {
      $data = ComercialController::consultores();

      $validatedData = $request->validate([
        'start' => 'required',
        'end' => 'required',
        'consultor' => 'required',
      ]);

      $arrayTotal = [];
      $arrayNombre = [];
      foreach ($request->consultor as $consultor) 
      {
        
      $correlatives = DB::table('cao_fatura as invoice')
      ->select(DB::raw('YEAR(invoice.data_emissao) as year,
       MONTHNAME(invoice.data_emissao) as month, 
       ROUND(SUM(invoice.valor - (invoice.valor*(invoice.total_imp_inc/100))),2) as liquido, 
       ROUND(SUM(invoice.valor - (invoice.valor*invoice.total_imp_inc)*invoice.comissao_cn),2) as comision, 
       salary.brut_salario as salario, 
       ROUND(SUM(invoice.valor - (invoice.valor*(invoice.total_imp_inc/100))),2) - ( salary.brut_salario + ROUND(SUM(invoice.valor - (invoice.valor*invoice.total_imp_inc)*invoice.comissao_cn),2)) as total'))
     ->join('cao_os as os','os.co_os','invoice.co_os')
     ->join('cao_salario as salary','salary.co_usuario','os.co_usuario')
     ->whereMonth('invoice.data_emissao','>=',Carbon::parse($request->start)->format('m'))
     ->whereMonth('invoice.data_emissao','<=',Carbon::parse($request->end)->format('m')) 
     ->whereYear('invoice.data_emissao','>=',Carbon::parse($request->start)->format('Y'))
     ->whereYear('invoice.data_emissao','<=',Carbon::parse($request->end)->format('Y'))     
     ->orderBy('invoice.data_emissao','ASC')
     ->where('os.co_usuario',$consultor)
     ->groupBy(DB::raw("YEAR(data_emissao),MONTHNAME(data_emissao),salary.brut_salario"))
     ->get();
      
      $name =  DB::table('cao_usuario as user')
      ->select('user.no_usuario')
      ->where('user.co_usuario',$consultor)
      ->first();    

      $arrayNombre = ['nombre' => $name->no_usuario, 'data' => $correlatives];
      array_push($arrayTotal, $arrayNombre);
      }

      $arrayTotal = collect($arrayTotal);

      //dd($arrayTotal);

     return view('comercial.index',[
        'data' => $data,
        'correlatives' => $arrayTotal,
        'chart' => json_encode($arrayTotal),

      ]);
  }

  private function consultores()
  {
      $data = DB::table('cao_usuario as user')
      ->select('user.*','perm.*')
      ->join('permissao_sistema as perm','perm.co_usuario','user.co_usuario')
      ->where('perm.co_sistema',1)
      ->where('perm.in_ativo','S')
      ->whereIn('perm.co_tipo_usuario',[0,1,2])
      ->get();

      return $data;
  }

}
