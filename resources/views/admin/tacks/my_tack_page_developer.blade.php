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

    <i class='subheader-icon fal fa-inbox-in'></i> Мои Обращения

@endsection
@section('content')

  <span class="btn cur_p btn-outline-default filter_show"><span> <i class="fal fa-search"></i> Поиск</span></span>
  <hr>
  <div class="panel-content disp_n" id="filter">

        <form method="POST" name="statPage" id="statPage" action="{{route('my_tack_page_developer')}}">

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
                            <input type="text" class="form-control" id="date_complete_start" name="date_complete_start" value="{{ $off_start }}">
                            <div class="input-group-append input-group-prepend">
                                <span class="input-group-text fs-xl"><i class="fal fa-calendar-alt"></i></span>
                            </div>
                            <input type="text" class="form-control" id="date_complete_end" name="date_complete_end" value="{{ $off_end }}">
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-2">
                    <div class="form-group">
                        <label class="form-label" for="term_complete">Наименование:</label>
                        <input type="text" class="form-control" id="process_name" name="process_name" value="{{ $process_name }}">
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
                            <option value="1" @if ($complete=='1' ) selected @endif >Назначен Исполнитель</option>
                            <option value="2" @if ($complete=='2' ) selected @endif >В работе</option>
                            <option value="3" @if ($complete=='3' ) selected @endif >Завершён</option>
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
                        <label class="form-label" for="operator">Оператор:</label>
                        <input type="text" class="form-control" id="operator" name="operator" value="{{ $operator }}">
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
                <div class="col-12 col-md-2">

                    <div class="form-group">
                        <label class="form-label" for="task_id">Номер Неисправности:</label>
                        <input type="text" class="form-control" id="task_id" name="task_id" value="{{ $task_id }}">
                    </div>

                </div>

                <div class="col-12 col-md-2 no-br">
                    <b>&nbsp;</b>
                    <div class="pad_0">
                        <span class="btn btn-primary cur_p load-stat">Выгрузить</span>
                    </div>
                </div>
                <div class="col-12 col-md-2">



                </div>
            </div>
            <hr>


        </form>
    </div>
    <div class="px_10"></div>
    <div class="px_10"></div>
    <table class="dt-basic-example table table-bordered table-hover table-striped w-100" id="dt-basic-example">
        <thead class="bg-warning-200">
        <tr>
            <th width="1% no_br">№</th>
            <th>Гос. орган</th>
            <th>Должность</th>
            <th>Фио</th>
            <th>Логин</th>
            <th>Телефон</th>
            <th>Эл. почта</th>
            <th>Наименование</th>
            <th>Оператор</th>
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
                <td>{!! $_v->gov_name !!}</td>
                <td>{!! $_v->client_spot !!}</td>
                <td>{!! $_v->client_fio !!}</td>
                <td>{!! $_v->client_login !!}</td>
                <td>{!! $_v->client_phone !!}</td>
                <td>{!! $_v->client_mail !!}</td>
                <td>{!! $_v->task_name !!}</td>
                <td> {!! $_v->operator !!} </td>
                <td>{!! $_v->created_at !!}</td>
                <td>{!! $_v->task_off !!}</td>
                <td>

                    @if(@$_v->task_priority=='1')
                        <span class="badge badge-danger fw-300">Высокий</span>
                    @elseif(@$_v->task_priority=='2')
                        <span class="badge badge-success fw-300">Средний</span>
                    @else
                        <span class="badge badge-primary fw-300">Низкий</span>
                    @endif

                </td>
                <td>

                    @if(@$_v->complete=='4')
                        <span class="badge badge-danger fw-300">Не начат</span>
                    @elseif(@$_v->complete=='1')
                        <span class="badge badge-primary fw-300">Назначен Исполнитель</span>
                    @elseif(@$_v->complete=='2')
                        <span class="badge badge-info fw-300">В работе</span>
                    @else
                        <span class="badge badge-success fw-300">Завершён</span>
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
                        @if( in_array('tack_cons_page', session('getLevels')) )
                            <a href="javascript:void(0);"
                               class="btn btn-sm btn-icon btn-outline-primary look-task rounded-circle ml-1"
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
        <form method="POST" name="task_operator_page" id="task_operator_page" action="{{route('my_tack_page_operator')}}" class="disp_n">
            <input id="task_operator_page_id" type="hidden" name="task_operator_page_id" value="">
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
                                Консультация: <strong><span id="name_task_modal"></span></strong>
                                <input type="hidden" id="task_delete_id">
                                <input type="hidden" id="client_id">
                            </small>

                        </h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true"><i class="fal fa-times"></i></span>
                        </button>
                    </div>
                    <div class="modal-body">

                        Консультация и все, связанные с ней файлы, будут удалены! <br>
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
