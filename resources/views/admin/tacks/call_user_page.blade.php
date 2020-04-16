@extends('layouts.common')
@section('css')

  <link href="{{ asset('js/plugins/bootstrap-datepicker/css/bootstrap-datepicker.css') }}" rel="stylesheet">
  <link href="{{ asset('js/plugins/bootstrap-table/bootstrap-table.css') }}" rel="stylesheet" media="screen, print">

@endsection
@section('js')

  <script src="{{ asset('js/plugins/bootstrap-datepicker/bootstrap-datepicker.js') }}"></script>
  <script src="{{ asset('js/plugins/bootstrap-datepicker/bootstrap-datetimepicker.ru.js') }}"></script>
  <script src="{{ asset('js/admin/call_list.js') }}"></script>

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

  <i class='subheader-icon fal fa-phone'></i> Мои Звонки

@endsection
@section('content')

  <span class="btn cur_p btn-outline-default filter_show"><span> <i class="fal fa-search"></i> Поиск</span></span>
  <hr>
  <div class="panel-content disp_n" id="filter">
    <form method="POST" name="statPage" id="statPage" action="{{route('call_user_page')}}">
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
      <th data-sortable="true">Дата</th>
      <th data-sortable="true">От кого</th>
      <th data-sortable="true">Кому</th>
      <th data-sortable="true">Звонок</th>
      <th width="3% no_br" data-sortable="true"><nobr>Время разговора (сек)</nobr></th>
      <th width="1% no_br" data-sortable="true"></th>
      <th width="1% no_br" data-sortable="true"></th>
    </tr>
    </thead>
    <tbody>
    @foreach( $calls_arr AS $_k=>$_v)
      <tr>
        <td><h5>{!! $i++ !!}</h5></td>
        <td><h5 class="fl_l"><b>{!! $_v['calldate']  !!}</b></h5> <div class="fl_l"> &nbsp; {!! $_v['calltime']  !!}</div></td>
        <td><b>{!! $_v['src']  !!}</b></td>
        <td><b>{!! $_v['dst'] ?? '' !!}</b></td>
        <td class="a_c">
          @if($_v['src'] == $operator)
            <span class="badge badge-success fw-300">Исходящий</span>
          @else
            <span class="badge badge-primary fw-300">Входящий</span>
          @endif
        </td>
        <td class="a_c">
          @if($_v['disposition'] =='NO ANSWER')
            <span class="badge badge-danger fw-300">Пропущеный</span>
          @else
            <h5>{!! $_v['duration'] !!}</h5>
          @endif
        </td>
        <td>
          @if($_v['link']!='')
            <audio src="{!! $_v['link'] !!}" preload="auto" controls></audio>
          @endif
        </td>
        <td>
          @if($_v['link']!='')
            <a href="{!! $_v['link'] !!}" target="_blank" class="btn btn-sm btn-icon btn-outline-primary look-task rounded-circle ml-1">
              <i class="fal fa-download"></i>
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
