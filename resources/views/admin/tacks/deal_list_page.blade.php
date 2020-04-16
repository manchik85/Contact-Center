@extends('layouts.common')
@section('css')

  <link href="{{ asset('js/plugins/datatables/css/datatables.bundle.css') }}" rel="stylesheet" media="screen, print">
  <link href="{{ asset('js/plugins/bootstrap-datepicker/css/bootstrap-datepicker.css') }}" rel="stylesheet">

@endsection
@section('js')

  <script src="{{ asset('js/plugins/bootstrap-datepicker/bootstrap-datepicker.js') }}"></script>
  <script src="{{ asset('js/plugins/bootstrap-datepicker/bootstrap-datetimepicker.ru.js') }}"></script>
  <script src="{{ asset('js/admin/dial_list.js') }}"></script>

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

  <i class='subheader-icon fal fa-inbox-in'></i> Общий список Звонков

@endsection
@section('content')

  <form method="POST" name="statPage" id="statPage" action="{{route('deal_list_page')}}">
  <span class="btn cur_p btn-outline-default filter_show"><span> <i class="fal fa-search"></i> Поиск</span></span>
    <input class="btn cur_p btn-outline-default filter_clear" type="reset" value="Очистить">
  <hr>
  <div class="panel-content disp_n" id="filter">


      <input type="hidden" name="load_exel" id="load_exel" value=0>

      <div class="row">
        <div class="col-12 col-md-3">
          <div class="form-group">
            <label class="form-label" for="term_complete">Обращения:</label>
            <div class="input-daterange input-group" id="datepicker-5">
              <input type="text" class="form-control" id="date_task_start" name="date_task_start" value="{{ $start }}">
              <div class="input-group-append input-group-prepend">
                <span class="input-group-text fs-xl"><i class="fal fa-calendar-alt"></i></span>
              </div>
              <input type="text" class="form-control" id="date_task_end" name="date_task_end" value="{{ $end }}">
            </div>
          </div>

        </div>
        <div class="col-12 col-md-3">
          <div class="form-group">
            <label class="form-label" for="term_complete">Решение:</label>
            <div class="input-daterange input-group" id="datepicker-6">
              <input type="text" class="form-control" id="date_complete_start" name="date_complete_start"
                     value="{{ $off_start }}">
              <div class="input-group-append input-group-prepend">
                <span class="input-group-text fs-xl"><i class="fal fa-calendar-alt"></i></span>
              </div>
              <input type="text" class="form-control" id="date_complete_end" name="date_complete_end"
                     value="{{ $off_end }}">
            </div>
          </div>
        </div>
        <div class="col-12 col-md-2">
          <div class="form-group">
            <label class="form-label" for="term_complete">Наименование:</label>
            <select class="form-control" name="process_name" id="process_name">
              <option value="">---------- Все ----------</option>
              @foreach($process_names as $proc)
                <option value="{{ $proc->process }}" @if($proc->process==$process_name) selected @endif>{{ $proc->process }}</option>
              @endforeach
            </select>
          </div>
        </div>
        <div class="col-12 col-md-2">

          <div class="form-group">
            <label class="form-label" for="priority">Приоритет:</label>
            <select class="form-control" name="priority" id="priority">
              <option value="" @if ($priority=='' ) selected @endif >Все</option>
              <option value="1" @if ($priority==1 ) selected @endif >Высокий</option>
              <option value="2" @if ($priority==2 ) selected @endif >Средний</option>
              <option value="3" @if ($priority==3 ) selected @endif >Низкий</option>
            </select>
          </div>


        </div>
        <div class="col-12 col-md-2">

          <div class="form-group">
            <label class="form-label" for="complete">Стадии:</label>
            <select class="form-control" name="complete" id="complete">
              <option value="" @if ($complete=='' ) selected @endif >Все</option>
              <option value="4" @if ($complete=='4' ) selected @endif >Не начат</option>
              <option value="6" @if ($complete=='6' ) selected @endif >На доработке у Оператора</option>
              <option value="1" @if ($complete=='1' ) selected @endif >Назначен Исполнитель</option>
              <option value="2" @if ($complete=='2' ) selected @endif >Принято в работу</option>
              <option value="3" @if ($complete=='3' ) selected @endif >Найдено решение</option>
              <option value="5" @if ($complete=='5' ) selected @endif >Решено</option>
            </select>
          </div>

        </div>

      </div>
      <hr>
      <div class="row">

        <div class="col-12 col-md-2">

          <div class="form-group">
            <label class="form-label" for="term_complete">Должность:</label>
            <input type="text" class="form-control" id="client_spot" name="client_spot" value="{{ $client_spot }}">
          </div>

        </div>
        <div class="col-12 col-md-2">

          <div class="form-group">
            <label class="form-label" for="term_complete">Гос. орган:</label>
            <input type="text" class="form-control" id="gov_name" name="gov_name" value="{{ $gov_name }}">
          </div>

        </div>
        <div class="col-12 col-md-2">

          <div class="form-group">
            <label class="form-label" for="term_complete">Фио:</label>
            <input type="text" class="form-control" id="client_fio" name="client_fio" value="{{ $client_fio }}">
          </div>

        </div>
        <div class="col-12 col-md-2">

          <div class="form-group">
            <label class="form-label" for="term_complete">Логин:</label>
            <input type="text" class="form-control" id="client_login" name="client_login" value="{{ $client_login }}">
          </div>

        </div>
        <div class="col-12 col-md-2">

          <div class="form-group">
            <label class="form-label" for="term_complete">Телефон:</label>
            <input type="text" class="form-control" id="client_phone" name="client_phone" value="{{ $client_phone }}">
          </div>

        </div>
        <div class="col-12 col-md-2">

          <div class="form-group">
            <label class="form-label" for="term_complete">Эл. почта:</label>
            <input type="text" class="form-control" id="client_mail" name="client_mail" value="{{ $client_mail }}">
          </div>

        </div>

      </div>
      <hr>
      <div class="row">

        <div class="col-12 col-md-2">

          <div class="form-group">
            <label class="form-label" for="term_complete">Оператор:</label>
            <select class="form-control" name="operator" id="operator">
              <option value="">---------- Все ----------</option>
              @foreach($operators as $oper)
                <option value="{{ $oper->name }}" @if($oper->name==$operator) selected @endif>{{ $oper->name }}</option>
              @endforeach
            </select>
          </div>


        </div>
        <div class="col-12 col-md-2">

          <div class="form-group">
            <label class="form-label" for="developer">Исполнитель:</label>
            <input type="text" class="form-control" id="developer" name="developer" value="{{ $developer }}">
          </div>

        </div>
        <div class="col-12 col-md-2">

          <div class="form-group">
            <label class="form-label" for="task_id">Номер Обращения:</label>
            <input type="text" class="form-control" id="task_id" name="task_id" value="{{ $task_id }}">
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
            <span class="btn btn-primary cur_p load-stat">Выгрузить</span>
            &nbsp;
            <span class="btn cur_p btn-outline-default exel"><span>Выгрузить в Excel</span></span>

          </div>
        </div>
      </div>
      <hr>


    <input type="hidden" id="page_linc" value="{{ $page_linc }}">
    <div class="px_10"></div>
    <div class="px_10"></div>
  </div>

  </form>




  <table class="dt-basic-example table table-bordered table-hover table-striped w-100" id="dt-basic-example">
    <thead class="bg-warning-200">
    <tr>
      <th width="1% no_br">№</th>
      <th>Регион, Гос. орган</th>
      <th>Фио, Логин/Пароль</th>
      <th>Контакты</th>
      <th>Наименование</th>
      <th>Оператор</th>
      <th>Исполнитель</th>
      <th>Обращения</th>
      <th>Решение</th>
      <th>Приоритет</th>
      <th>Стадия</th>
      @if ( in_array('task_del', session('getLevels')) || in_array('tack_cons_page', session('getLevels')) )
        <th width="1% no_br"></th>
      @endif
    </tr>
    </thead>
    <tbody>

    @foreach( $list AS $_k=>$_v)
      <tr id="row_{!! $_v->id !!}">
        <td>{!! $_v->id !!}</td>
        <td> <h4>{!! $_v->task_district !!}</h4> {!! $_v->gov_name !!} <b>{!! $_v->client_spot !!}</b></td>
        <td><b>{!! $_v->client_fio !!}</b> <br> {!! $_v->client_login !!} / {!! $_v->client_pass !!}</td>
        <td>{!! $_v->client_phone !!}, {!! $_v->client_mail !!}</td>
        <td>{!! $_v->task_name !!}</td>
        <td> {!! $_v->operator !!} </td>
        <td> {!! @$_v->developer !!} </td>
        <td>{!! $_v->created_at !!}</td>
        <td>@if( $_v->task_type == 'request_tack'){!! $_v->task_off !!}@endif</td>
        <td>
          @if(@$_v->task_priority=='1' && $_v->task_type == 'request_tack')
            <span class="badge badge-danger fw-300">Высокий</span>
          @elseif(@$_v->task_priority=='2' && $_v->task_type == 'request_tack')
            <span class="badge badge-success fw-300">Средний</span>
          @elseif(@$_v->task_priority=='3' && $_v->task_type == 'request_tack')
            <span class="badge badge-primary fw-300">Низкий</span>
          @endif
        </td>
        <td>
          @if(@$_v->complete=='1'  && $_v->task_type == 'request_tack')
            <span class="badge badge-danger fw-300">Назначен Исполнитель</span>
          @elseif(@$_v->complete=='2'  && $_v->task_type == 'request_tack')
            <span class="badge badge-primary fw-300">Принято в работу</span>
          @elseif(@$_v->complete=='3'  && $_v->task_type == 'request_tack')
            <span class="badge badge-info fw-300">Решено</span>
          @elseif(@$_v->complete=='4'  && $_v->task_type == 'request_tack')
            <span class="badge badge-warning fw-300">Не начат</span>
          @elseif(@$_v->complete=='5' && $_v->task_type == 'request_tack')
            <span class="badge badge-success fw-300">Повторное исполнение</span>
          @elseif(@$_v->complete=='6' && $_v->task_type == 'request_tack')
            <span class="badge badge-secondary fw-300">На доработке у Оператора</span>
          @elseif(@$_v->complete=='7'  && $_v->task_type == 'request_tack')
            <span class="badge badge-warning fw-300">Консультация</span>
          @elseif(@$_v->complete=='8'  && $_v->task_type == 'request_tack')
            <span class="badge badge-warning fw-300">Не повторилась</span>
          @elseif(@$_v->complete=='9'  && $_v->task_type == 'request_tack')
            <span class="badge badge-warning fw-300">Не актуальная</span>
          @endif
        </td>
        @if ( in_array('task_del', session('getLevels')) || in_array('tack_cons_page', session('getLevels')) )
          <td class="no-br">
            @if( in_array('task_del', session('getLevels')) )
              <a href="javascript:void(0);"
                 class="btn btn-sm btn-icon btn-outline-danger delete_user rounded-circle mr-1"
                 name-task="{!! $_v->task_name !!}" id-client="{!! $_v->client_id !!}"
                 id-task="{!! $_v->id !!}" title="Удалить" data-toggle="modal"
                 data-target="#delete-modal">
                <i class="fal fa-times"></i>
              </a>
            @endif
            @if( in_array('tack_cons_page', session('getLevels')) && $_v->task_type == 'request_tack')
              <a href="javascript:void(0);"
                 class="btn btn-sm btn-icon btn-outline-primary look-task rounded-circle ml-1"
                 id-task="{!! $_v->id !!}" title="Подробности">
                <i class="fal fa-ellipsis-v"></i>
              </a>
            @elseif( in_array('tack_cons_page', session('getLevels')) && $_v->task_type == 'advice_tack')
              <a href="javascript:void(0);"
                 class="btn btn-sm btn-icon btn-outline-primary look rounded-circle ml-1"
                 id-task="{!! $_v->id !!}" title="Подробности">
                <i class="fal fa-ellipsis-v"></i>
              </a>
            @endif
          </td>
        @endif

      </tr>
    @endforeach

    </tbody>
  </table>

  <article class="col-sm-12 sortable-grid ui-sortable">
    {{$list->links()}}
  </article>

  @if ( in_array('my_tack_page_operator', session('getLevels')) )
    <form method="POST" name="task_operator_page" id="task_operator_page" action="{{route('my_tack_page_operator')}}"
          class="disp_n">
      <input id="task_operator_page_id" type="hidden" name="task_operator_page_id" value="">
    </form>
  @endif

  @if ( in_array('tack_cons_page', session('getLevels')) )
    <form method="POST" name="task_page" id="task_page" action="{{route('tack_cons_page')}}" class="disp_n">
      <input id="task_page_id" type="hidden" name="task_page_id" value="">
    </form>
  @endif

@endsection
@section('modal')

  @if( in_array('task_del', session('getLevels')) )
    <div class="modal fade" id="delete-modal" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">

              <strong>Подтвердите удаление!</strong>
              <small class="m-0 text-muted">
                Заявка: <strong><span id="name_task_modal"></span></strong>
                <input type="hidden" id="task_delete_id">
                <input type="hidden" id="client_id">
              </small>

            </h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true"><i class="fal fa-times"></i></span>
            </button>
          </div>
          <div class="modal-body">

            Заявка и все, связанные с ней файлы, будут удалены! <br>
            <strong>Уверены, что это необходимо?</strong>

          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Отмена</button>
            <button type="button" class="btn btn-danger del_user_confirm">Удалить</button>
          </div>
        </div>
      </div>
    </div>
  @endif

@endsection
