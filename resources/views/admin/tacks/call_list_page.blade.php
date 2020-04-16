@extends('layouts.common')
@section('css')

  <link href="{{ asset('js/plugins/bootstrap-datepicker/css/bootstrap-datepicker.css') }}" rel="stylesheet">
  <!--<link href="{{ asset('js/plugins/datatables/css/datatables.bundle.css') }}" rel="stylesheet" media="screen, print">-->
  <link href="{{ asset('js/plugins/bootstrap-table/bootstrap-table.css') }}" rel="stylesheet" media="screen, print">

@endsection
@section('js')

  <script src="{{ asset('js/plugins/bootstrap-datepicker/bootstrap-datepicker.js') }}"></script>
  <script src="{{ asset('js/plugins/bootstrap-datepicker/bootstrap-datetimepicker.ru.js') }}"></script>
  <script src="{{ asset('js/admin/call_list.js') }}"></script>

  <!--<script src="{{ asset('js/plugins/datatables/datatables.bundle.js') }}"></script>-->
  <script src="{{ asset('js/plugins/bootstrap-table/bootstrap-table.js') }}"></script>
  <script src="{{ asset('js/plugins/bootstrap-table/locale/bootstrap-table-ru-RU.js') }}"></script>
  <script>
    $(document).ready(function () {

      $('#search-button').click(function () {
        $('#dt-basic-example').bootstrapTable('refresh', {
          query: queryParams()
        });
      })

      function calldateFormatter(value, row, index) {
        return '<h5 class="fl_l"><b>' + row.calldate + '</b></h5> <div class="fl_l"> &nbsp;' + row.calltime + '</div>';
      }
      function srcFormatter(value, row, index) {
        return "<b>" + row.src + "</b>";
      }
      function callTypeFormatter(value, row, index) {
        if(row.src.length == 4)
          return '<span class="badge badge-success fw-300">Исходящий</span>';
        else
          return '<span class="badge badge-primary fw-300">Входящий</span>';
      }
      function durationFormatter(value, row, index) {
        if(row.disposition == 'NO ANSWER')
          return '<span class="badge badge-danger fw-300">Пропущеный</span>';
        else
          return '<h5>' + row.duration + '</h5>';
      }
      function linkFormatter(value, row, index) {
        if(row.link != '')
          return '<a href="' + row.link + '" target="_blank" class="btn btn-sm btn-icon btn-outline-primary look-task rounded-circle ml-1"><i class="fal fa-play"></i></a>';
      }
      function operatorFormatter(value, row, index) {
        div = '';
        for(var i = 0; i < row.operators.length; i++){
          div += '<div> <b>' + row.operators[i].number + '</b> (' + row.operators[i].user + ', ' + row.operators[i].time + ') </div>';
        }
        return div;
      }
      /*function queryParams() {
        var params = {};
        params['date_task_start'] = $('#date_task_start').val();
        params['date_task_end'] = $('#date_task_end').val();
        params['operator'] = $('#operator').val();
        params['caller'] = $('#caller').val();
        params['type-call'] = $('#type-call').val();

        return params
      }*/
      function queryParams() {
        var params = {};
        params['date_task_start'] = $('#date_task_start').val();
        params['date_task_end'] = $('#date_task_end').val();
        params['operator'] = $('#operator').val();
        params['caller'] = $('#caller').val();
        params['type-call'] = $('#type-call').val();

        return params
      }
      $('#dt-basic-example').bootstrapTable(
        {

          ///
          processing: true,
          sidePagination: 'server',
          url: 'get_calls',
          queryParams: function (p) {
            return {
              limit: p.limit,
              offset: p.offset,
              date_task_start: $('#date_task_start').val(),
              date_task_end:$('#date_task_end').val(),
              operator:$('#operator').val(),
              caller:$('#caller').val(),
              'type-call':$('#type-call').val()
            };
          },
          ///
          sortable: true,
          responsive: false,
          pagination: true,
          //processing: false,
          stateSave:  true,
          info:       false,
          searching:  false,
          columnDefs: [{
            targets: [ -1, -2 ],
            title: '',
            orderable: false,
            }],
          columns:[
            {
              field: 'id',
              title: '#',
            },
            {
              field: '',
              title: 'Дата',
              formatter: calldateFormatter
            },
            {
              field: '',
              title: 'оператор',
              formatter: operatorFormatter
            },
            {
              field: 'src',
              title: 'От кого',
              formatter: srcFormatter
            },
            {
              field: '',
              title: 'Звонок',
              class: "a_c",
              formatter: callTypeFormatter
            },
            {
              field: 'duration',
              title: 'Время разговора (сек)',
              class: "a_c",
              formatter: durationFormatter
            },
            {
              field: 'playback',
              title: 'Оценка',
            },
            {
              field: 'link',
              title: '',
              formatter: linkFormatter
            }
            ]
        });
    });
  </script>
@endsection
@section('title')

  <i class='subheader-icon fal fa-phone'></i> Список Звонков

@endsection
@section('content')

  <span class="btn cur_p btn-outline-default filter_show"><span> <i class="fal fa-search"></i> Поиск</span></span>
  <hr>
  <div class="panel-content disp_n" id="filter">

    <form method="POST" name="statPage" id="statPage" action="{{route('call_list_page')}}">

      <input type="hidden" name="load_exel" id="load_exel" value=0>

      <div class="row">
        <div class="col-12 col-md-3">
          <div class="form-group">
            <label class="form-label" for="term_complete">Период:</label>
            <div class="input-daterange input-group" id="datepicker-5">
              <input type="text" class="form-control" id="date_task_start" name="date_task_start" value="{{ $start }}">
              <div class="input-group-append input-group-prepend">
                <span class="input-group-text fs-xl"><i class="fal fa-calendar-alt"></i></span>
              </div>
              <input type="text" class="form-control" id="date_task_end" name="date_task_end" value="{{ $end }}">
            </div>
          </div>
        </div>

        <div class="col-12 col-md-2">

          <div class="form-group">
            <label class="form-label" for="operator">Оператор:</label>
            <select class="form-control" name="operator" id="operator">
              <option value="all" @if ($operator=='' ) selected @endif >Все</option>
              @foreach ( $users AS $user )
              <option value="{!! $user->users_phone !!}" @if ($operator==$user->users_phone ) selected @endif >{!! $user->users_phone !!} ( {!! $user->name !!} )</option>
              @endforeach
            </select>
          </div>

        </div>
        <div class="col-12 col-md-2">
          <div class="form-group">
            <label class="form-label" for="caller">Входящий номер:</label>
            <input type="text" class="form-control" id="caller" name="caller" value="{{ $caller }}">
          </div>
        </div>
        <div class="col-12 col-md-2">
          <div class="form-group">
            <label class="form-label" for="type-call">Звонок:</label>
            <select class="form-control" name="type-call" id="type-call">
              <option value="1" @if ($typeCall=='' ) selected @endif >Все</option>
              <option value="2" @if ($typeCall==2 ) selected @endif >Входящий</option>
              <option value="3" @if ($typeCall==3 ) selected @endif >Исходящий</option>
              <option value="4" @if ($typeCall==4 ) selected @endif >Пропущенный</option>
            </select>
          </div>
        </div>
        <div class="col-12 col-md-5">
          <b>&nbsp;</b>
          <div class="pad_0">
            <!--<span class="btn btn-primary cur_p load-stat">Выгрузить</span>-->
            <span class="btn btn-primary cur_p" id="search-button">Выгрузить</span>
            <span class="btn cur_p btn-outline-default exel"><span>Выгрузить в Excel</span></span>
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
      <th data-sortable="true">Дата</th>
      <th data-sortable="true">Оператор</th>
      <th data-sortable="true">От кого</th>
      <th data-sortable="true">Звонок</th>
      <th width="3% no_br" data-sortable="true"><nobr>Время разговора (сек)</nobr></th>
      <th width="3% no_br">Оценка</th>
      <th width="1% no_br"></th>

    </tr>
    </thead>
  </table>

@endsection
@section('modal')



@endsection
