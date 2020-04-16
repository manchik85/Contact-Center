@extends('layouts.common')

@section('css')
	
	<link rel="stylesheet" media="screen, print" href="{{ asset('js/plugins/datatables/css/datatables.bundle.css') }}">

@endsection
@section('js')
	
	<script src="{{ asset('js/admin/white_ip.js') }}"></script>

@endsection
@section('title')
	
	<i class="subheader-icon fal fa-handshake"></i>&nbsp; Разрешённые IP-адреса

@endsection
@section('content')
	
	
	
	<div class="row">
		<div class="col-md-8 col-lg-6 sortable-grid ui-sortable">
			
			
			
			<div class="panel panel-sortable" role="widget">
				<div class="panel-hdr" role="heading">
					<h2 >Список разрешённых IP-адресов</h2>
				</div>
				<div class="panel-container show" role="content">
					<div class="panel-content pad_0">
						
						<table class="table m-0 table-striped table-hover  " id="table-example">
							<tbody>
							@foreach($list AS $unit)
								<tr id="gov_{{ $unit->ip_id }}">
									<td>{{ $unit->white_ip }}</td>
									<td class="a_r">
										
										@if ( in_array('white_ip_del', session('getLevels')) )
											<a href="javascript:void(0);" class="btn btn-sm btn-icon btn-outline-danger delete_gov rounded-circle mr-1" id-gov="{{ $unit->ip_id }}" data-toggle="modal" data-target="#delete-modal">
												<i class="fal fa-times"></i>
											</a>
										@endif
									
									</td>
								</tr>
							@endforeach
							</tbody>
						</table>
						@if ( in_array('white_ip_add', session('getLevels')) )
							<white_ip_add></white_ip_add>
						@endif
					
					</div>
				</div>
			</div>
			
		</div>
	</div>

@endsection
@section('modal')
	
	<div class="modal fade" id="delete-modal" tabindex="-1" role="dialog" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered" role="document">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title">
						<strong>Подтвердите удаление!</strong>
					</h4>
					<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						<span aria-hidden="true"><i class="fal fa-times"></i></span>
					</button>
				</div>
				<div class="modal-body">
					
					<input type="hidden" id="gov_delete_id">
					Пользователи с этим IP-адресом не смогут пользоваться системой! <br>
					<strong>Уверены, что это необходимо?</strong>
				
				</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-secondary" data-dismiss="modal">Отмена</button>
					<button type="button" class="btn btn-danger del_gov_confirm">Удалить</button>
				</div>
			</div>
		</div>
	</div>
	
@endsection

