@extends('layouts.common')
@section('css')

  <link href="{{ asset('js/plugins/bootstrap-datepicker/css/bootstrap-datepicker.css') }}" rel="stylesheet">

@endsection
@section('js')

  <script src="{{ asset('js/app.bundle.js') }}"></script>
  <script src="{{ asset('js/plugins/statistics/flot/flot.bundle.js') }}"></script>

  <script src="{{ asset('js/plugins/bootstrap-datepicker/bootstrap-datepicker.js') }}"></script>
  <script src="{{ asset('js/plugins/bootstrap-datepicker/bootstrap-datetimepicker.ru.js') }}"></script>
  <script src="{{ asset('js/admin/tasks_list_dash.js') }}"></script>

  <script>
    $(document).ready(function () {

      var dataSetPie = [
        {
          label: "Высокий",
          data: {{$proir['str_p']}},
          color: myapp_get_color.danger_500
        },
        {
          label: "Средний",
          data: {{$proir['med_p']}},
          color: myapp_get_color.success_500
        },
        {
          label: "Низкий",
          data: {{$proir['low_p']}},
          color: myapp_get_color.primary_500
        }];

      var dataSetPie1 = [
        {
          label: "Не начат",
          data: {{$compl['not_work_p']}},
          color: myapp_get_color.danger_500
        },
        {
          label: "На доработке",
          data: {{$compl['return_p']}},
          color: myapp_get_color.fusion_500
        },
        {
          label: "Назначен Исполнитель",
          data: {{$compl['devel_p']}},
          color: myapp_get_color.primary_500
        },
        {
          label: "Принято в работу",
          data: {{$compl['in_work_p']}},
          color: myapp_get_color.info_500
        },
        {
          label: "На проверке",
          data: {{$compl['complete_p']}},
          color: myapp_get_color.success_500
        },
        {
          label: "Повторное исполнение",
          data: {{$compl['confirmed_p']}},
          color: myapp_get_color.warning_500
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
    });

  </script>

@endsection
@section('title')
{{--  Рабочий Стол--}}
@endsection
@section('content')

  <form method="POST" name="statPage-all" id="statPage-all" action="{{route('users.common.dashboard')}}">
  <div class="row">
    <div class="col-12 col-md-6">
      <div class="form-group">
        <div class="input-daterange input-group" id="datepicker-0">
          <input type="text" class="form-control" id="date_task_start-0" name="start" value="{{ $start }}">
          <div class="input-group-append input-group-prepend">
            <span class="input-group-text fs-xl"><i class="fal fa-calendar-alt"></i></span>
          </div>
          <input type="text" class="form-control" id="date_task_end-0" name="end" value="{{ $end }}">
        </div>
      </div>
    </div>
    <div class="col-12 col-md-6">
      <span class="btn btn-primary cur_p load-stat-all">Выгрузить</span>
    </div>
  </div>
  </form>
  &nbsp;
  <div class="row">
    <div class="col-md-6">

      <div id="panel-6" class="panel">
        <div class="panel-hdr">
          <h2>Заявки по Статусу обращения</h2>
        </div>
        <div class="panel-container show">
          <div class="px_10"></div>
          <div class="px_10"></div>
          <div class="panel-content">
            <div class="row  mb-g">
              <div class="col-md-7 d-flex align-items-center">
                <div id="flotPie" class="w-100" style="height:300px"></div>
              </div>
              <div class="col-md-5 col-lg-4 mr-lg-auto">

                <div class="px_10"></div>
                <div class="px_10"></div>
                <div class="d-flex mt-2 mb-1 fs-xs text-danger load-stat-dash-priority" data-priority="1" style="cursor: pointer">
                  Высокий: &nbsp; <b>{{$proir['str']}}</b>
                </div>
                <div class="progress progress-xs mb-3">
                  <div class="progress-bar bg-danger-500" role="progressbar" style="width: {{$proir['str_p']}}%;"
                       aria-valuenow="70"
                       aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                <div class="d-flex mt-2 mb-1 fs-xs text-success load-stat-dash-priority" data-priority="2" style="cursor: pointer">
                  Средний: &nbsp; <b>{{$proir['med']}}</b>
                </div>
                <div class="progress progress-xs mb-3">
                  <div class="progress-bar bg-success-500" role="progressbar" style="width: {{$proir['med_p']}}%;"
                       aria-valuenow="30" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                <div class="d-flex mt-2 mb-1 fs-xs text-primary load-stat-dash-priority" data-priority="3" style="cursor: pointer">
                  Низкий: &nbsp; <b>{{$proir['low']}}</b>
                </div>
                <div class="progress progress-xs mb-3">
                  <div class="progress-bar bg-primary-500" role="progressbar" style="width: {{$proir['low_p']}}%;"
                       aria-valuenow="40" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                <div class="px_10"></div>
                <div class="px_1"></div>
                <hr>
                <h3>Всего: &nbsp; <b>{{ $proir['sigma'] }}</b></h3>
              </div>
            </div>
          </div>

          <div class="px_10"></div>
          <div class="px_10"></div>
          <div class="px_10"></div>
          <div class="px_10"></div>
          <div class="px_5"></div>
          <div class="px_1"></div>
        </div>
      </div>

    </div>
    <div class="col-md-6">

      <div id="panel-6" class="panel">
        <div class="panel-hdr">
          <h2>Заявки по Готовности</h2>
        </div>
        <div class="panel-container show">
          <div class="panel-content">
            <div class="row  mb-g">
              <div class="col-md-7 d-flex align-items-center">
                <div id="flotPie1" class="w-100" style="height:300px"></div>
              </div>
              <div class="col-md-5 col-lg-4 mr-lg-auto">

                <div class="d-flex mt-2 mb-1 fs-xs text-danger load-stat-dash-complete" data-complete="4" style="cursor: pointer">
                  Не начат: &nbsp; <b>{{$compl['not_work']}}</b>
                </div>
                <div class="progress progress-xs mb-3">
                  <div class="progress-bar bg-danger-500" role="progressbar" style="width: {{$compl['not_work_p']}}%;"
                       aria-valuenow="70"
                       aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                <div class="d-flex mt-2 mb-1 fs-xs text-secondary load-stat-dash-complete" data-complete="6" style="cursor: pointer">
                  На доработке у Оператора: &nbsp; <b>{{$compl['return']}}</b>
                </div>
                <div class="progress progress-xs mb-3">
                  <div class="progress-bar bg-fusion-500" role="progressbar" style="width: {{$compl['return_p']}}%;"
                       aria-valuenow="70"
                       aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                <div class="d-flex mt-2 mb-1 fs-xs text-primary load-stat-dash-complete" data-complete="1" style="cursor: pointer">
                  Назначен Исполнитель: &nbsp; <b>{{$compl['devel']}}</b>
                </div>
                <div class="progress progress-xs mb-3">
                  <div class="progress-bar bg-primary-500" role="progressbar" style="width: {{$compl['devel_p']}}%;"
                       aria-valuenow="30" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                <div class="d-flex mt-2 mb-1 fs-xs text-info load-stat-dash-complete" data-complete="2" style="cursor: pointer">
                  Принято в работу: &nbsp; <b>{{$compl['in_work']}}</b>
                </div>
                <div class="progress progress-xs mb-3">
                  <div class="progress-bar bg-info-500" role="progressbar" style="width: {{$compl['in_work_p']}}%;"
                       aria-valuenow="40" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                <div class="d-flex mt-2 mb-1 fs-xs text-success load-stat-dash-complete" data-complete="3" style="cursor: pointer">
                  На проверке у Оператора: &nbsp; <b>{{$compl['complete']}}</b>
                </div>
                <div class="progress progress-xs mb-3">
                  <div class="progress-bar bg-success-500" role="progressbar"
                       style="width: {{$compl['complete_p']}}%;"
                       aria-valuenow="40" aria-valuemin="0" aria-valuemax="100"></div>
                </div>

                <div class="d-flex mt-2 mb-1 fs-xs text-warning load-stat-dash-complete" data-complete="5" style="cursor: pointer">
                  Повторное исполнение: &nbsp; <b>{{$compl['confirmed']}}</b>
                </div>
                <div class="progress progress-xs mb-3">
                  <div class="progress-bar bg-warning-500" role="progressbar"
                       style="width: {{$compl['confirmed_p']}}%;"
                       aria-valuenow="40" aria-valuemin="0" aria-valuemax="100"></div>
                </div>
                <div class="px_1"></div>
                <hr>
                <h3>Всего: &nbsp; <b>{{ $proir['sigma'] }}</b></h3>
              </div>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>

  @if ( in_array('cons_list_page', session('getLevels')) )
    <div class="row">
      <div class="col-md-12">

        <div id="panel-6" class="panel">
          <div class="panel-hdr">
            <h2>Список Консультаций</h2>
          </div>
          <div class="panel-container show">
            <div class="panel-content">
              <div class="pad_10">


                <form method="POST" name="statPage" id="statPage" action="{{route('cons_list_page')}}">
                  <input type="hidden" name="load_exel" id="load_exel" value=0>
                  <div class="row">
                    <div class="col-12 col-md-2">
                      <div class="form-group">
                        <label class="form-label" for="term_complete">Обращения:</label>
                        <div class="input-daterange input-group" id="datepicker-5">
                          <input type="text" class="form-control" id="date_task_start" name="date_task_start"
                                 value="">
                          <div class="input-group-append input-group-prepend">
                            <span class="input-group-text fs-xl"><i class="fal fa-calendar-alt"></i></span>
                          </div>
                          <input type="text" class="form-control" id="date_task_end" name="date_task_end" value="">
                        </div>
                      </div>
                    </div>
                    <div class="col-12 col-md-2">

                      <div class="form-group">
                        <label class="form-label" for="term_complete">Должность:</label>
                        <input type="text" class="form-control" id="client_spot" name="client_spot" value="">
                      </div>

                    </div>
                    <div class="col-12 col-md-2">

                      <div class="form-group">
                        <label class="form-label" for="term_complete">Фио:</label>
                        <input type="text" class="form-control" id="client_fio" name="client_fio" value="">
                      </div>

                    </div>
                    <div class="col-12 col-md-2">

                      <div class="form-group">
                        <label class="form-label" for="term_complete">Логин:</label>
                        <input type="text" class="form-control" id="client_login" name="client_login" value="">
                      </div>

                    </div>
                    <div class="col-12 col-md-2">

                      <div class="form-group">
                        <label class="form-label" for="term_complete">Телефон:</label>
                        <input type="text" class="form-control" id="client_phone" name="client_phone" value="">
                      </div>

                    </div>
                    <div class="col-12 col-md-2">

                      <div class="form-group">
                        <label class="form-label" for="term_complete">Эл. почта:</label>
                        <input type="text" class="form-control" id="client_mail" name="client_mail" value="">
                      </div>

                    </div>
                  </div>
                  <hr>
                  <div class="row">
                    <div class="col-12 col-md-2">
                      <div class="form-group">
                        <label class="form-label" for="term_complete">Решение:</label>
                        <div class="input-daterange input-group" id="datepicker-6">
                          <input type="text" class="form-control" id="date_complete_start" name="date_complete_start"
                                 value="">
                          <div class="input-group-append input-group-prepend">
                            <span class="input-group-text fs-xl"><i class="fal fa-calendar-alt"></i></span>
                          </div>
                          <input type="text" class="form-control" id="date_complete_end" name="date_complete_end"
                                 value="">
                        </div>
                      </div>
                    </div>
                    <div class="col-12 col-md-2">

                      <div class="form-group">
                        <label class="form-label" for="term_complete">Гос. орган:</label>
                        <input type="text" class="form-control" id="gov_name" name="gov_name" value="">
                      </div>

                    </div>
                    <div class="col-12 col-md-2">

                      <div class="form-group">
                        <label class="form-label" for="term_complete">Наименование:</label>
                        <input type="text" class="form-control" id="process_name" name="process_name" value="">
                      </div>

                    </div>
                    <div class="col-12 col-md-2">
                      <div class="form-group">
                        <label class="form-label" for="task_id">Номер Консультации:</label>
                        <input type="text" class="form-control" id="task_id" name="task_id" value="">
                      </div>
                    </div>
                    <div class="col-12 col-md-2">
                      <div class="form-group">
                        <label class="form-label" for="district">Регион:</label>
                        <select class="form-control" name="task_district" id="district">
                          <option value="">Все</option>
                          <option value="Акмолинская область">Акмолинская область</option>
                          <option value="Карагандинская область">Карагандинская область</option>
                          <option value="Восточно-Казахстанская область">Восточно-Казахстанская область</option>
                          <option value="Жамбылская область">Жамбылская область</option>
                          <option value="Западно-Казахстанская область">Западно-Казахстанская область</option>
                          <option value="Кызылординская область">Кызылординская область</option>
                          <option value="Павлодарская область">Павлодарская область</option>
                          <option value="Костанайская область">Костанайская область</option>
                          <option value="Мангистауская область">Мангистауская область</option>
                          <option value="Атырауская область">Атырауская область</option>
                          <option value="Северо-Казахстанская область">Северо-Казахстанская область</option>
                          <option value="Алматинская область">Алматинская область</option>
                          <option value="Актюбинская область">Актюбинская область</option>
                          <option value="Туркестанская область">Туркестанская область</option>
                          <option value="город Алматы">город Алматы</option>
                          <option value="город Нур-Султан">город Нур-Султан</option>
                          <option value="город Шымкент">город Шымкент</option>
                        </select>
                      </div>
                    </div>
                    <div class="col-12 col-md-2 no-br">
                      <b>&nbsp;</b>
                      <div class="pad_0">
                        <span class="btn btn-primary cur_p load-stat">Выгрузить</span>
                      </div>
                    </div>
                  </div>
                </form>

                <div class="px_10"></div>
              </div>
            </div>
          </div>
        </div>

      </div>
    </div>

  @endif
  @if ( in_array('tack_list_page', session('getLevels')) )
    <div class="row">
      <div class="col-md-12">

        <div id="panel-6" class="panel">
          <div class="panel-hdr">
            <h2>Список Неисправностей</h2>
          </div>
          <div class="panel-container show">
            <div class="panel-content">
              <div class="pad_10">

                <form method="POST" name="statPageTask" id="statPageTask" action="{{route('tack_list_page')}}">
                  <input type="hidden" name="load_exel" id="load_exel" value=0>
                  <input type="hidden" name="is_dashboard" id="is_dashboard" value=0>
                  @if ( Auth::user()->status == 5 )
                    <input type="hidden" class="form-control" id="operator" name="operator" value="{{ Auth::user()->name }}">
                  @endif

                  <div class="row">
                    <div class="col-12 col-md-3">
                      <div class="form-group">
                        <label class="form-label" for="term_complete">Обращения:</label>
                        <div class="input-daterange input-group" id="datepicker-51 task">
                          <input type="text" class="form-control" id="date_task_start" name="date_task_start"
                                 value="">
                          <div class="input-group-append input-group-prepend">
                            <span class="input-group-text fs-xl"><i class="fal fa-calendar-alt"></i></span>
                          </div>
                          <input type="text" class="form-control" id="date_task_end" name="date_task_end" value="">
                        </div>
                      </div>

                    </div>
                    <div class="col-12 col-md-3">
                      <div class="form-group">
                        <label class="form-label" for="term_complete">Решение:</label>
                        <div class="input-daterange input-group" id="datepicker-61">
                          <input type="text" class="form-control" id="date_complete_start" name="date_complete_start"
                                 value="">
                          <div class="input-group-append input-group-prepend">
                            <span class="input-group-text fs-xl"><i class="fal fa-calendar-alt"></i></span>
                          </div>
                          <input type="text" class="form-control" id="date_complete_end" name="date_complete_end"
                                 value="">
                        </div>
                      </div>
                    </div>
                    <div class="col-12 col-md-2">
                      <div class="form-group">
                        <label class="form-label" for="term_complete">Наименование:</label>
                        <input type="text" class="form-control" id="process_name" name="process_name" value="">
                      </div>
                    </div>
                    <div class="col-12 col-md-2">

                      <div class="form-group">
                        <label class="form-label" for="priority">Приоритет:</label>
                        <select class="form-control" name="priority" id="priority">
                          <option value="">Все</option>
                          <option value="1">Высокий</option>
                          <option value="2">Средний</option>
                          <option value="3">Низкий</option>
                        </select>
                      </div>


                    </div>
                    <div class="col-12 col-md-2">

                      <div class="form-group">
                        <label class="form-label" for="complete">Стадии:</label>
                        <select class="form-control" name="complete" id="complete">
                          <option value="">Все</option>
                          <option value="4">Не начат</option>
                          <option value="1">Назначен Исполнитель</option>
                          <option value="2">Принято в работу</option>
                          <option value="3">Решено</option>
                          <option value="5">Повторное исполнение</option>
                          <option value="6">На доработке у Оператора</option>
                          <option value="7">Консультация</option>
                          <option value="8">Не повторилась</option>
                          <option value="9">Не актуальная</option>
                        </select>
                      </div>

                    </div>

                  </div>
                  <hr>
                  <div class="row">

                    <div class="col-12 col-md-2">

                      <div class="form-group">
                        <label class="form-label" for="term_complete">Должность:</label>
                        <input type="text" class="form-control" id="client_spot" name="client_spot" value="">
                      </div>

                    </div>
                    <div class="col-12 col-md-2">

                      <div class="form-group">
                        <label class="form-label" for="term_complete">Гос. орган:</label>
                        <input type="text" class="form-control" id="gov_name" name="gov_name" value="">
                      </div>

                    </div>
                    <div class="col-12 col-md-2">

                      <div class="form-group">
                        <label class="form-label" for="term_complete">Фио:</label>
                        <input type="text" class="form-control" id="client_fio" name="client_fio" value="">
                      </div>

                    </div>
                    <div class="col-12 col-md-2">

                      <div class="form-group">
                        <label class="form-label" for="term_complete">Логин:</label>
                        <input type="text" class="form-control" id="client_login" name="client_login" value="">
                      </div>

                    </div>
                    <div class="col-12 col-md-2">

                      <div class="form-group">
                        <label class="form-label" for="term_complete">Телефон:</label>
                        <input type="text" class="form-control" id="client_phone" name="client_phone" value="">
                      </div>

                    </div>
                    <div class="col-12 col-md-2">

                      <div class="form-group">
                        <label class="form-label" for="term_complete">Эл. почта:</label>
                        <input type="text" class="form-control" id="client_mail" name="client_mail" value="">
                      </div>

                    </div>

                  </div>
                  <hr>

                  <div class="row">
                    <div class="col-12 col-md-2">
                      <div class="form-group">
                        <label class="form-label" for="developer">Исполнитель:</label>
                        <input type="text" class="form-control" id="developer" name="developer" value="">
                      </div>
                    </div>
                    <div class="col-12 col-md-2">

                      <div class="form-group">
                        <label class="form-label" for="task_id">Номер Неисправности:</label>
                        <input type="text" class="form-control" id="task_id" name="task_id" value="">
                      </div>

                    </div>
                    <div class="col-12 col-md-2">
                      <div class="form-group">
                        <label class="form-label" for="district">Регион:</label>
                        <select class="form-control" name="task_district" id="district">
                          <option value="">Все</option>
                          <option value="Акмолинская область">Акмолинская область</option>
                          <option value="Карагандинская область">Карагандинская область</option>
                          <option value="Восточно-Казахстанская область">Восточно-Казахстанская область</option>
                          <option value="Жамбылская область">Жамбылская область</option>
                          <option value="Западно-Казахстанская область">Западно-Казахстанская область</option>
                          <option value="Кызылординская область">Кызылординская область</option>
                          <option value="Павлодарская область">Павлодарская область</option>
                          <option value="Костанайская область">Костанайская область</option>
                          <option value="Мангистауская область">Мангистауская область</option>
                          <option value="Атырауская область">Атырауская область</option>
                          <option value="Северо-Казахстанская область">Северо-Казахстанская область</option>
                          <option value="Алматинская область">Алматинская область</option>
                          <option value="Актюбинская область">Актюбинская область</option>
                          <option value="Туркестанская область">Туркестанская область</option>
                          <option value="город Алматы">город Алматы</option>
                          <option value="город Нур-Султан">город Нур-Султан</option>
                          <option value="город Шымкент">город Шымкент</option>
                        </select>
                      </div>
                    </div>
                    <div class="col-12 col-md-2 no-br">
                      <b>&nbsp;</b>
                      <div class="px_1"></div>
                      <div class="pad_0">
                        <span class="btn btn-primary cur_p load-stat-task">Выгрузить</span>
                      </div>
                    </div>
                  </div>
                  <hr>


                </form>

                <div class="px_10"></div>
              </div>
            </div>
          </div>
        </div>

      </div>
    </div>
  @endif

@endsection
@section('modal')



@endsection

