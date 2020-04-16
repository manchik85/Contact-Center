@extends('layouts.common')

@section('css')

  <link href="{{ asset('js/plugins/datatables/css/datatables.bundle.css') }}" rel="stylesheet" media="screen, print">
	<link href="{{ asset('js/plugins/bootstrap-datepicker/css/bootstrap-datepicker.css') }}" rel="stylesheet">

@endsection
@section('js')

  <script src="{{ asset('js/app.bundle.js') }}"></script>
  <script src="{{ asset('js/plugins/statistics/flot/flot.bundle.js') }}"></script>

	<script src="{{ asset('js/plugins/bootstrap-datepicker/bootstrap-datepicker.js') }}"></script>
	<script src="{{ asset('js/plugins/bootstrap-datepicker/bootstrap-datetimepicker.ru.js') }}"></script>
	<script src="{{ asset('js/admin/all_statistic.js') }}"></script>

  <script src="{{ asset('js/plugins/datatables/datatables.bundle.js') }}"></script>
  <script>
    $(document).ready(function () {

      var dataSetPie = [
        {
          label: "Отвеченные",
          data: {{$call_st_answered}},
          color: myapp_get_color.danger_500
        },
        {
          label: "Не отвеченные",
          data: {{$call_st_missed}},
          color: myapp_get_color.success_500
        }];

      var dataSetPie1 = [
        {
          label: "Консультации",
          data: {{$advice_tack}},
          color: myapp_get_color.success_500
        },
        {
          label: "Неисправности",
          data: {{$request_tack}},
          color: myapp_get_color.danger_500
        }];

      var dataSetPie2 = [
        {
          label: "Онлайн",
          data: {{$sigma_on_sou}},
          color: myapp_get_color.success_500
        },
        {
          label: "Отошёл",
          data: {{$sigma_aut_sou}},
          color: myapp_get_color.primary_500
        },
        {
          label: "Оффлайн",
          data: {{$sigma_off_sou}},
          color: myapp_get_color.danger_500
        }];


      $.plot($("#flotPie"), dataSetPie,
        {
          series:
            {
              pie:
                {
                  innerRadius: 0.75,
                  show: true,
                  radius: 1,
                  label:
                    {
                      show: true,
                      radius: 2 / 3,
                      threshold: 0.1
                    }
                }
            },
          legend:
            {
              show: false
            }
        });

      $.plot($("#flotPie1"), dataSetPie1,
        {
          series:
            {
              pie:
                {
                  innerRadius: 0.75,
                  show: true,
                  radius: 1,
                  label:
                    {
                      show: true,
                      radius: 2 / 3,
                      threshold: 0.1
                    }
                }
            },
          legend:
            {
              show: false
            }
        });
      $.plot($("#flotPie2"), dataSetPie2,
        {
          series:
            {
              pie:
                {
                  innerRadius: 0.75,
                  show: true,
                  radius: 1,
                  label:
                    {
                      show: true,
                      radius: 2 / 3,
                      threshold: 0.1
                    }
                }
            },
          legend:
            {
              show: false
            }
        });




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
              targets: [-1],
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

	<i class="subheader-icon fal fa-handshake"></i>&nbsp; Статистика работы Операторов

@endsection
@section('content')

  <? $i = 1; ?>
	<div class="panel-content">

		<form method="POST" name="statPage" id="statPage" action="{{route('list_operators_statistic')}}">
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
                <option value='{!! $role->gov_group_name !!}'>
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

					<th>Звонки</th>
					<th>Отвеченные</th>
					<th>Не отвеченные</th>

					<th>Консультации</th>
					<th>Неисправности</th>

					<th>Онлайн</th>
					<th>Отошёл</th>
					<th>Оффлайн</th>
					<th width="1%"></th>
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
						<td>
              <b>{!! $_user['name'] !!}</b>
              <div></div>
              {!! $_user['users_cont_phone'] !!} {!! $_user['email'] !!}
            </td>
						<td>{!! $_user['users_phone'] !!}</td>

						<td> <b>{!! $_user['calls'] !!}</b> </td>
						<td> <b>{!! $_user['answered'] !!}</b> </td>
						<td> <b>{!! $_user['no_answered'] !!}</b> </td>

						<td> <b>{!! $_user['advice_tack'] !!}</b> </td>
						<td> <b>{!! $_user['request_tack'] !!}</b> </td>

						<td><b>{!! $_user['sigma_on'] !!}</b></td>
						<td><b>{!! $_user['sigma_aut'] !!}</b></td>
						<td><b>{!! $_user['sigma_off'] !!}</b></td>
						<td id-user="{{ $_s }}" class="statistic_user">

							@if( in_array('user_statistic_page', session('getLevels')) )
								<a href="#" class="btn btn-sm btn-icon btn-outline-primary rounded-circle shadow-0"
								   data-toggle="dropdown" aria-expanded="true" title="Действия">
									<i class="fal fa-ellipsis-v"></i>
								</a>
							@endif

						</td>
					</tr>
				@endforeach
				</tbody>
				<tfooter>
					<tr>
						<td colspan="4"><h3>Всего:</h3></td>

						<td> <h4><b>{{ $calls }}</b></h4> </td>
						<td> <h4><b>{{ $answered }}</b></h4> </td>
						<td> <h4><b>{{ $no_answered }}</b></h4> </td>

						<td> <h4><b>{{ $advice_tack }}</b></h4> </td>
						<td> <h4><b>{{ $request_tack }}</b></h4> </td>

						<td> <h4>{{ $sigma_on }}</h4> </td>
						<td> <h4>{{ $sigma_aut }}</h4> </td>
						<td colspan="2"> <h4>{{ $sigma_off }}</h4> </td>
					</tr>
				</tfooter>
			</table>
		@else
			<div class="px_10"></div>
			<h2> Ничего не найдено! Выберите другой период времени. </h2>
		@endif
    <hr>

    <div class="row">

      <div class="col-md-4">

        <div class="panel">
          <div class="panel-hdr">
            <h2>Статистика по Звонкам</h2>
          </div>
          <div class="panel-container show">
            <div class="px_10"></div>
            <div class="px_10"></div>
            <div class="panel-content">
              <div class="row  mb-g">
                <div class="col-md-7 d-flex align-items-center">
                  <div id="flotPie" class="w-100" style="height:200px"></div>
                </div>
                <div class="col-md-5 col-lg-4 mr-lg-auto">

                  <div class="px_10"></div>
                  <div class="d-flex mt-2 mb-1 fs-xs text-danger">
                    Отвеченные: &nbsp; <b>{{ $call_st_answered  }}</b>
                  </div>
                  <div class="progress progress-xs mb-3">
                    @if($calls>0)
                    <div class="progress-bar bg-danger-500" role="progressbar" style="width: {!! $answered/$calls*100 !!}%;"
                         aria-valuenow="70"
                         aria-valuemin="0" aria-valuemax="100"></div>
                    @endif
                  </div>
                  <div class="px_10"></div>
                  <div class="d-flex mt-2 mb-1 fs-xs text-success">
                    Не отвеченные: &nbsp; <b>{{ $call_st_missed }}</b>
                  </div>
                  <div class="progress progress-xs mb-3">
                    @if($calls>0)
                    <div class="progress-bar bg-success-500" role="progressbar" style="width: {!! $no_answered/$calls*100 !!}%;"
                         aria-valuenow="30" aria-valuemin="0" aria-valuemax="100"></div>
                    @endif
                  </div>
                  <div class="px_1"></div>
                  <hr>
                  <h3>Всего звонков: &nbsp; <b>{{ $call_st_quantity }}</b></h3>
                  <h3>Ср.время ожидания на линии: &nbsp; <b>{{ $call_st_waitAverageTime }}</b></h3>

                </div>
              </div>
            </div>

            <div class="px_10"></div>
            <div class="px_1"></div>
          </div>
        </div>

      </div>

      <div class="col-md-4">

        <div class="panel">
          <div class="panel-hdr">
            <h2>Статистика по Обращениям</h2>
          </div>
          <div class="panel-container show">
            <div class="px_10"></div>
            <div class="px_10"></div>
            <div class="panel-content">
              <div class="row  mb-g">
                <div class="col-md-7 d-flex align-items-center">
                  <div id="flotPie1" class="w-100" style="height:200px"></div>
                </div>
                <div class="col-md-5 col-lg-4 mr-lg-auto">

                  <div class="px_10"></div>
                  <div class="d-flex mt-2 mb-1 fs-xs text-success">
                    Консультации: &nbsp; <b>{{ $advice_tack  }}</b>
                  </div>
                  <div class="progress progress-xs mb-3">
                    @if(($request_tack+$advice_tack)>0)
                    <div class="progress-bar bg-success-500" role="progressbar" style="width: {!! $advice_tack/($request_tack+$advice_tack)*100 !!}%;"
                         aria-valuenow="70"
                         aria-valuemin="0" aria-valuemax="100"></div>
                    @endif
                  </div>
                  <div class="px_10"></div>
                  <div class="d-flex mt-2 mb-1 fs-xs text-danger">
                    Неисправности: &nbsp; <b>{{ $request_tack }}</b>
                  </div>
                  <div class="progress progress-xs mb-3">
                    @if(($request_tack+$advice_tack)>0)
                    <div class="progress-bar bg-danger-500" role="progressbar" style="width: {!! $request_tack/($request_tack+$advice_tack)*100 !!}%;"
                         aria-valuenow="30" aria-valuemin="0" aria-valuemax="100"></div>
                    @endif
                  </div>
                  <div class="px_1"></div>
                  <hr>
                  <h3>Всего обращений: &nbsp; <b>{{ $request_tack+$advice_tack }}</b></h3>
                </div>
              </div>
            </div>

            <div class="px_10"></div>
            <div class="px_1"></div>
          </div>
        </div>

      </div>

      <div class="col-md-4">

        <div class="panel">
          <div class="panel-hdr">
            <h2>Статистика рабочему времени</h2>
          </div>
          <div class="panel-container show">
            <div class="px_10"></div>
            <div class="px_10"></div>
            <div class="panel-content">
              <div class="row">
                <div class="col-md-7 d-flex align-items-center">
                  <div id="flotPie2" class="w-100" style="height:200px"></div>
                </div>
                <div class="col-md-5 col-lg-4 mr-lg-auto">

                  <div class="d-flex mt-2 mb-1 fs-xs text-success">
                    Онлайн: &nbsp; <b>{{ $sigma_on  }}</b>
                  </div>
                  <div class="progress progress-xs mb-3">
                    @if(($sigma_aut_sou+$sigma_on_sou+$sigma_off_sou)>0)
                    <div class="progress-bar bg-success-500" role="progressbar" style="width: {!!  $sigma_on_sou/($sigma_aut_sou+$sigma_on_sou+$sigma_off_sou)*100 !!}%;"
                         aria-valuenow="70"
                         aria-valuemin="0" aria-valuemax="100"></div>
                    @endif
                  </div>

                  <div class="px_1"></div>
                  <div class="px_1"></div>
                  <div class="px_1"></div>
                  <div class="d-flex mt-2 mb-1 fs-xs text-primary">
                    Отошёл: &nbsp; <b>{{ $sigma_aut }}</b>
                  </div>
                  <div class="progress progress-xs mb-3">
                    @if(($sigma_aut_sou+$sigma_on_sou+$sigma_off_sou)>0)
                    <div class="progress-bar bg-primary-500" role="progressbar" style="width: {!!  $sigma_aut_sou/($sigma_aut_sou+$sigma_on_sou+$sigma_off_sou)*100 !!}%;"
                         aria-valuenow="30" aria-valuemin="0" aria-valuemax="100"></div>
                    @endif
                  </div>


                  <div class="px_1"></div>
                  <div class="px_1"></div>
                  <div class="px_1"></div>
                  <div class="d-flex mt-2 mb-1 fs-xs text-danger">
                    Оффлайн: &nbsp; <b>{{ $sigma_off }}</b>
                  </div>
                  <div class="progress progress-xs mb-3">
                    @if(($sigma_aut_sou+$sigma_on_sou+$sigma_off_sou)>0)
                    <div class="progress-bar bg-danger-500" role="progressbar" style="width: {!! $sigma_off_sou/($sigma_aut_sou+$sigma_on_sou+$sigma_off_sou)*100 !!}%;"
                         aria-valuenow="30" aria-valuemin="0" aria-valuemax="100"></div>
                    @endif
                  </div>

                  <hr>
                  <h3>Всего: &nbsp; <b>{{ $sigma_all }}</b></h3>
                </div>
              </div>
            </div>
            <div class="px_1"></div>
            <div class="px_1"></div>

          </div>
        </div>

      </div>

    </div>

	</div>
	@if (in_array('user_statistic_page', session('getLevels'))  )
		<form method="POST" name="statPageUnit" id="statPageUnit" action="{{route('user_statistic_page')}}" class="disp_n">
			<input id="statUserId" type="hidden" name="users_id" value="">
		</form>
	@endif


@endsection
