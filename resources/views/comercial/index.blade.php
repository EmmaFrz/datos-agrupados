@extends('layouts.app')

@section('content')
@if ($errors->any())
	<div class="alert alert-danger">
		<ul>
			@foreach ($errors->all() as $error)
			  <li>{{ $error }}</li>
			@endforeach
		</ul>
	</div>
@endif
<div class="row">
	<div class="col-md-12">
		<section class="panel">
			<header class="panel-heading">
				<h2 class="panel-title">Consultores</h2>
			</header>
			<div class="panel-body">
				<form class="form-horizontal form-bordered" method="get" action="/comercial-datos">
					<div class="form-group">
						<label class="col-md-1 control-label">Periodo</label>
						<div class="col-md-12">
								<div class="input-daterange input-group" data-plugin-datepicker="">
									<span class="input-group-addon">
									<i class="fa fa-calendar"></i>
									</span>
									<input type="text" class="form-control" name="start" required="">
									<span class="input-group-addon">to</span>
									<input type="text" class="form-control" name="end" required="">
								</div>
						</div>						
					</div>
					<div class="form-group">
						<label class="col-md-1 control-label">Lista de Consultores</label>			
						<div class="col-md-12">
							<select multiple data-plugin-selectTwo class="form-control populate" name="consultor[]" required="">
								<optgroup label="Seleciona">
									@foreach($data as $d)
									<option value="{{ $d->co_usuario }}" name="">{{$d->no_usuario}}</option>	
									@endforeach
								</optgroup>
							</select>	
						</div>
						<i class="fa fa-question-circle col-md-12" data-toggle="tooltip" data-placement="top" title="Puedes escribir directamente el consultor"></i>					
					</div>
					<div class="form-group">
						<div class="col-md-12">
							<input type="submit" class="btn btn-primary" value="Buscar">
						</div>
					</div>	
				</form>
			</div>
		</section>
	</div>
	@if($correlatives)
		@foreach($correlatives as $correlative)
			{{$correlative['nombre']}}<br>
			@foreach($correlative['data'] as $data)
			{{$data->year}} 	<br>
			{{$data->month}} <br>
			{{$data->liquido}} <br>
			{{$data->comision}} <br>
			<p>----------------------</p>
			@endforeach
		@endforeach
	@endif
</div>
@endsection
		