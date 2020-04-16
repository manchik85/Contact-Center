@extends('layouts.common')

@section('css')

  <link href="{{ asset('js/plugins/datatables/css/datatables.bundle.css') }}" rel="stylesheet" media="screen, print">
	<link href="{{ asset('js/plugins/bootstrap-datepicker/css/bootstrap-datepicker.css') }}" rel="stylesheet">

@endsection
@section('js')

	<script src="{{ asset('js/plugins/bootstrap-datepicker/bootstrap-datepicker.js') }}"></script>
	<script src="{{ asset('js/plugins/bootstrap-datepicker/bootstrap-datetimepicker.ru.js') }}"></script>
	<script src="{{ asset('js/admin/kpi.js') }}"></script>

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
              @if ( in_array('task_del', session('getLevels')) || in_array('tack_cons_page', session('getLevels')) )
              // targets: [-1],
              @endif
              title: '',
              orderable: false,
            },
          ]
        });
    });
  </script>

@endsection
@section('title')

	<i class="subheader-icon fal fa-handshake"></i>&nbsp; KPI операторов

@endsection
@section('content')


  <? $i = 1; ?>
	<div class="panel-content">

		<form method="POST" name="statPage" id="statPage" action="{{route('kpi_page')}}">
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
				<div class="col-12 col-lg-5">

					<b>Группа (отдел)</b>
					<div class="panel-content">

						<select name="gov_group" id="gov_group" class="form-control">

							<option value="">Все</option>
              @foreach($roles_office AS $role)
                <option value="{!! $role->gov_group_name !!}">
                  {!! $role->gov_group_name !!}
                </option>
              @endforeach

							<optgroup label="------------------------------------------------"></optgroup>
							<option value="Местные исполнительные органы">
								Местные исполнительные органы
							</option>
							<optgroup label="------------------------------------------------"></optgroup>

							<option value="Аппарат акима Акмолинской области">
								Аппарат акима Акмолинской области
							</option>
							<option value="Аппарат акима Карагандинской области">
								Аппарат акима Карагандинской области
							</option>
							<option value="Аппарат акима Восточно-Казахстанской области">
								Аппарат акима Восточно-Казахстанской области
							</option>
							<option value="Аппарат акима Жамбылской области">
								Аппарат акима Жамбылской области
							</option>
							<option value="Аппарат акима Западно-Казахстанской области">
								Аппарат акима Западно-Казахстанской области
							</option>
							<option value="Аппарат акима Кызылординской области">
								Аппарат акима Кызылординской области
							</option>
							<option value="Аппарат акима Павлодарской области">
								Аппарат акима Павлодарской области
							</option>
							<option value="Аппарат акима Костанайской области">
								Аппарат акима Костанайской области
							</option>
							<option value="Аппарат акима Мангистауской области">
								Аппарат акима Мангистауской области
							</option>
							<option value="Аппарат акима Атырауской области">
								Аппарат акима Атырауской области
							</option>
							<option value="Аппарат акима Северо-Казахстанской области">
								Аппарат акима Северо-Казахстанской области
							</option>
							<option value="Аппарат акима Алматинской области">
								Аппарат акима Алматинской области
							</option>
							<option value="Аппарат акима Актюбинской области">
								Аппарат акима Актюбинской области
							</option>
							<option value="Аппарат акима Туркестанской области">
								Аппарат акима Туркестанской области
							</option>
							<option value="Аппарат акима города Алматы">
								Аппарат акима города Алматы
							</option>
							<option value="Аппарат акима города Нур-Султан">
								Аппарат акима города Нур-Султан
							</option>
							<option value="Аппарат акима города Шымкента">
								Аппарат акима города Шымкента
							</option>
							<optgroup label="------------------------------------------------"></optgroup>
							<option value="Центральные государственные органы">
								Центральные государственные органы
							</option>
							<optgroup label="------------------------------------------------"></optgroup>
							<option value="Агентство Республики Казахстан по делам государственной службы">
								Агентство Республики Казахстан по делам государственной службы
							</option>
							<option value="Агентство Республики Казахстан по противодействию коррупции">
								Агентство Республики Казахстан по противодействию коррупции
							</option>
							<optgroup label="------------------------------------------------"></optgroup>
							<option value="Центральные исполнительные органы">
								Центральные исполнительные органы
							</option>
							<optgroup label="------------------------------------------------"></optgroup>
							<option value="Министерство культуры и спорта РК">
								Министерство культуры и спорта РК
							</option>
							<option value="Министерство национальной экономики РК">
								Министерство национальной экономики РК
							</option>
							<option value="Министерство индустрии и инфраструктурного развития Республики Казахстан">
								Министерство индустрии и инфраструктурного развития Республики Казахстан
							</option>
							<option value="Министерство образования и науки Республики Казахстан">
								Министерство образования и науки Республики Казахстан
							</option>
							<option value="Министерство энергетики Республики Казахстан">
								Министерство энергетики Республики Казахстан
							</option>
							<option value="Министерство внутренних дел Республики Казахстан">
								Министерство внутренних дел Республики Казахстан
							</option>
							<option value="Министерство сельского хозяйства Республики Казахстан">
								Министерство сельского хозяйства Республики Казахстан
							</option>
							<option value="Министерство здравоохранения Республики Казахстан">
								Министерство здравоохранения Республики Казахстан
							</option>
							<option value="Министерство юстиции Республики Казахстан">
								Министерство юстиции Республики Казахстан
							</option>
							<option value="Министерство обороны Республики Казахстан">
								Министерство обороны Республики Казахстан
							</option>
							<option value="Министерство иностранных дел Республики Казахстан">
								Министерство иностранных дел Республики Казахстан
							</option>
							<option value="Министерство финансов Республики Казахстан">
								Министерство финансов Республики Казахстан
							</option>
							<option value="Министерство цифрового развития, инноваций и аэрокосмической промышленности Республики Казахстан">
								Министерство цифрового развития, инноваций и аэрокосмической промышленности Республики Казахстан
							</option>
							<option value="Министерство информации и общественного развития Республики Казахстан">
								Министерство информации и общественного развития Республики Казахстан
							</option>
							<option value="Министерство труда и социальной защиты населения Республики Казахстан">
								Министерство труда и социальной защиты населения Республики Казахстан
							</option>
							<option value="Министерство экологии, геологии и природных ресурсов Республики Казахстан">
								Министерство экологии, геологии и природных ресурсов Республики Казахстан
							</option>

						</select>
					</div>

				</div>
				<div class="col-12 col-lg-3">
					<b>&nbsp;</b>
					<div class="pad_0">
						<span class="btn btn-primary cur_p load-stat">Выгрузить</span>
						&nbsp;
						<span class="btn cur_p btn-outline-default exel"><span>Выгрузить в Excel</span></span>
					</div>
				</div>
			</div>
		</form>
    <div class="px_10"></div>
    @if(  count($date) > 0 )
      <table class="dt-basic table table-bordered table-hover table-striped w-100" id="dt-basic-example">
        <thead class="bg-warning-200">
        <tr>
          <th width="1%">#</th>
          <th>Группа</th>
          <th>Оператор</th>
          <th>Номер</th>
          <th>Контакт</th>
          <th>e-mail</th>
          <th>KPI</th>
        </tr>
        </thead>
        <tbody>
        @foreach( $date AS $_s => $_user)
          <tr>
            <td>{!! $i++ !!}</td>
            <td>
              @if($_user['gov_group_root']) <b>{!! $_user['gov_group_root'] !!}</b><br>@endif
              @if($_user['gov_group_master']) <b>{!! $_user['gov_group_master'] !!}</b>, &nbsp;  @endif
              {!! $_user['gov_group'] !!} </td>
            <td>{!! $_user['name'] !!}</td>
            <td>{!! $_user['users_phone'] !!}</td>
            <td>{!! $_user['users_cont_phone'] !!}</td>
            <td>{!! $_user['email'] !!}</td>
            <td> <b>{!! $_user['kpi'] !!}</b> </td>
          </tr>
        @endforeach
        </tbody>
      </table>

    @else

      <div class="px_10"></div>
      <h2> Ничего не найдено! Выберите другой период времени. </h2>

    @endif


	</div>


@endsection
