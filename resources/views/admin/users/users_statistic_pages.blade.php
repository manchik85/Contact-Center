@extends('layouts.common')

@section('css')

  <link href="{{ asset('js/plugins/datatables/css/datatables.bundle.css') }}" rel="stylesheet" media="screen, print">
  <link href="{{ asset('js/plugins/bootstrap-datepicker/css/bootstrap-datepicker.css') }}" rel="stylesheet">

@endsection
@section('js')

  <script src="{{ asset('js/plugins/bootstrap-datepicker/bootstrap-datepicker.js') }}"></script>
  <script src="{{ asset('js/plugins/bootstrap-datepicker/bootstrap-datetimepicker.ru.js') }}"></script>
  <script src="{{ asset('js/admin/tasks_list.js') }}"></script>

  <script src="{{ asset('js/plugins/datatables/datatables.bundle.js') }}"></script>
  <script>
    $(document).ready(function () {
      $('#dt-basic-example').dataTable(
        {
          responsive: false,
          paging: false,
          processing: false,
          stateSave: true,
          info: false,
          searching: false,
          columnDefs: [
            {
              title: '',
              orderable: false,
            },
          ]
        });
    });
  </script>

@endsection
@section('title')

	<i class="subheader-icon fal fa-handshake"></i>&nbsp; Статистика пользователя <strong>{{ $user[0]->name }}</strong>

@endsection
@section('content')


  <? $i = 1; ?>
	<div class="panel-content">
		<form method="POST" name="statPage" id="statPage" action="{{route('user_statistic_page')}}">
			<input id="users_id" type="hidden" name="users_id" value="{{ $user[0]->id }}">
			<div class="row">
				<div class="col-12 col-lg-4">
					<b>Период времени:</b>
					<div class="input-daterange input-group" id="datepicker-5">
						<input type="text" class="form-control" id="start" name="start" value="{{ $start }}">
						<div class="input-group-append input-group-prepend">
							<span class="input-group-text fs-xl"><i class="fal fa-calendar-alt"></i></span>
						</div>
						<input type="text" class="form-control" id="end" name="end" value="{{ $end }}">
					</div>
				</div>
				<div class="col-12 col-lg-8">
					<b>&nbsp;</b>
					<div class="pad_0">
						<span class="btn btn-primary cur_p load-stat">Выгрузить</span>
						&nbsp;
						<span class="btn cur_p btn-outline-default exel"><span>Выгрузить в Excel</span></span>
					</div>
				</div>
			</div>
		</form>
  </div>
		<div class="px_10"></div>

		@if( isset($data[0]) && count($data) > 0 )

      <table class="dt-basic-example table table-bordered table-hover table-striped w-100" id="dt-basic-example">
				<thead class="bg-warning-200">
				<tr>
					<th width="1%">#</th>
					<th>Дата</th>
					<th>IP</th>
					<th>Время / Продолжительность</th>
					<th>Статус</th>
				</tr>
				</thead>
				<tbody>
				@foreach( $date AS $_s => $_user)
					<tr>
						<td>{!! $i++ !!}</td>
						<td>{!! date("Y-m-d", ($_user['common_date'])) !!}</td>
						<td>{!! $_user['agent'] !!}</td>
						<td>
							{!! date("H:i:s", ($_user['common_date'])) !!}
							/ <b>
							@if ( $_user['delta']>0 )
								{!! date("H:i:s", mktime(0, 0, $_user['delta'])); !!}
								@else
								В настоящее время
							@endif</b>
						</td>
						<td>
							@if(@$_user['user_event']=='online')
								<span class="badge badge-success fw-300 ml-1">Онлайн</span>
							@elseif(@$_user['user_event']=='aut')
								<span class="badge badge-primary fw-300 ml-1">Отошёл</span>
								<span class="fw-500 ml-1">{!! $_user['out_desc'] !!}</span>

              @elseif(@$_user['user_event']=='error')
                <span class="badge badge-warning fw-300 ml-1">Ошибка входа</span>

							@else
								<span class="badge badge-danger fw-300 ml-1">Оффлайн</span>
							@endif
						</td>
					</tr>
				@endforeach

        </tbody>
        <tfoot>
				<tr>
					<td><span class="badge badge-success fw-300 ml-1">Онлайн</span></td>
					<td colspan="4"> <b>{!! $sigma_on ?? 0 !!}</b> </td>
				</tr>
				<tr>
					<td><span class="badge badge-primary fw-300 ml-1">Отошёл</span></td>
					<td colspan="4"> <b>{!! $sigma_aut ?? 0 !!}</b> </td>
				</tr>
				<tr>
					<td><span class="badge badge-danger fw-300 ml-1">Оффлайн</span></td>
					<td colspan="4"> <b>{!! $sigma_off ?? 0 !!}</b> </td>
				</tr>
        </tfoot>
			</table>
			<article class="col-sm-12 sortable-grid ui-sortable">
				{!! $data->links() !!}
			</article>
		@else

			<div class="px_10"></div>
			<h2> Ничего не найдено! Выберите другой период времени. </h2>

		@endif




@endsection
