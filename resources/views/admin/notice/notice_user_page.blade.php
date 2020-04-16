@extends('layouts.common')
@section('css')

  <link href="{{ asset('js/plugins/bootstrap-datepicker/css/bootstrap-datepicker.css') }}" rel="stylesheet">
  <link href="{{ asset('js/plugins/bootstrap-table/bootstrap-table.css') }}" rel="stylesheet" media="screen, print">

@endsection
@section('js')

  <script src="{{ asset('js/plugins/bootstrap-datepicker/bootstrap-datepicker.js') }}"></script>
  <script src="{{ asset('js/plugins/bootstrap-datepicker/bootstrap-datetimepicker.ru.js') }}"></script>
  <script src="{{ asset('js/admin/notice_list.js') }}"></script>
  <script src="{{ asset('js/plugins/bootstrap-table/bootstrap-table.js') }}"></script>
  <script src="{{ asset('js/plugins/bootstrap-table/locale/bootstrap-table-ru-RU.js') }}"></script>

  <script>
      $(document).ready(function () {
          $('#dt-basic-example').bootstrapTable(
              {
                  responsive: false,
                  pagination: true,
                  processing: false,
                  stateSave:  true,
                  info:       false,
                  searching:  false,
                  columnDefs: [{
                      targets: [ -1, -2 ],
                      title: '',
                      orderable: false,
                  }]
              });
      });
  </script>

@endsection
@section('title')

  <i class='subheader-icon fal fa-bell'></i> Уведомления

@endsection
@section('content')

    <span class="btn cur_p btn-outline-default filter_show"><span> <i class="fal fa-search"></i> Поиск</span></span>
    <input class="btn cur_p btn-outline-default filter_clear" type="reset" value="Очистить">
    <hr>
    <div class="panel-content disp_n" id="filter">
        <form method="POST" name="statPage" id="statPage" action="{{route('notice_user_page')}}">
            <input type="hidden" name="load_exel" id="load_exel" value=0>
            <div class="row">
                <div class="col-12 col-md-3">
                    <div class="form-group">
                        <label class="form-label" for="term_complete">Период:</label>
                        <div class="input-daterange input-group" id="datepicker-5">
                            <input type="text" class="form-control" id="date_task_start" name="date_notice_start" value="{{ $start }}">
                            <div class="input-group-append input-group-prepend">
                                <span class="input-group-text fs-xl"><i class="fal fa-calendar-alt"></i></span>
                            </div>
                            <input type="text" class="form-control" id="date_task_end" name="date_notice_end" value="{{ $end }}">
                        </div>
                    </div>
                </div>

                <div class="col-12 col-md-2">
                    <div class="form-group">
                        <label class="form-label" for="type-call">Ознакомлен?:</label>
                        <select class="form-control" name="read-notice" id="read-notice">
                            <option value="2" @if ($readNotice==2 ) selected @endif >Все</option>
                            <option value="1" @if ($readNotice==1 ) selected @endif >Да</option>
                            <option value="0" @if ($readNotice==0 ) selected @endif >Нет</option>
                        </select>
                    </div>
                </div>
                <div class="col-12 col-md-5">
                    <b>&nbsp;</b>
                    <div class="pad_0">
                        <span class="btn btn-primary cur_p load-stat">Выгрузить</span>
                    </div>
                </div>
            </div>
            <hr>
        </form>
    </div>
    <table class="dt-basic-example table table-bordered table-hover table-striped w-100" id="dt-basic-example">
        <thead class="bg-warning-200">
        <tr>
            <th width="1% no_br" data-sortable="true">#</th>
            <th data-sortable="true">№ заявки</th>
            <th data-sortable="true">Описание</th>
            <th data-sortable="true">Дата</th>
            <th data-sortable="true">От кого</th>
            <th width="1% no_br"></th>
        </tr>
        </thead>
        <tbody>
        @foreach($notice_arr AS $_k=>$_v)
            <tr>
                <td><h5>{!! $i++ !!}</h5></td>
                <td><b>{!! $_v->task  !!}</b></td>
                <td><b>{!! $_v->description  !!}</b></td>
                <td><h5 class="fl_l"><b>{!! $_v->created_date  !!}</b></h5> <div class="fl_l"> &nbsp; {!! $_v->created_date  !!}</div></td>
                <td><b>{!! $_v->avtor_name  !!}</b></td>
                <td>
                    @if($_v->read != 1)
                        <?php
                            $url = action('Adm\Tacks\PagesController@tackOperator', ['notice_id' => $_v->id, 'task_operator_page_id' => $_v->task]);
                        ?>

                        <a href="{!! $url !!}" class="btn btn-sm btn-icon btn-outline-primary look-task rounded-circle ml-1">
                            <i class="fal fa-ellipsis-v"></i>
                        </a>
                    @endif
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

@endsection
@section('modal')

@endsection
