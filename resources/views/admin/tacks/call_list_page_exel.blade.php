@if( isset($calls_arr) && count($calls_arr) > 0 )
  <table>
    <thead>
    <tr>
      <th>Дата</th>
      <th>Оператор</th>
      <th>От кого</th>
      <th> Звонок (сек)</th>
      <th width="3% no_br">Оценка</th>
    </tr>
    </thead>
    <tbody>
    @foreach( $calls_arr AS $_k=>$_v)
      <tr>
        <td><h5 class="fl_l"><b>{!! $_v['calldate']  !!}</b></h5>
          <div class="fl_l"> &nbsp; {!! $_v['calltime']  !!}</div>
        </td>
        <td>
          @foreach( $_v['operators'] AS $_k=>$operator)
            <div><b>{!! $_k  !!}</b> ({!! $operator['user'] ?? '' !!})</div>
          @endforeach
        </td>
        <td><b>{!! $_v['src']  !!}</b></td>
        <td class="a_c">
          @if($_v['disposition'] =='NO ANSWER')
            <span class="badge badge-danger fw-300">Пропущеный</span>
          @else
            <h5>{!! $_v['duration'] !!}</h5>
          @endif
        </td>
      </tr>
      <td>
        <h5>{!! $_v['playback'] !!}</h5>
      </td>
    @endforeach

    </tbody>
  </table>
@else
  <table>
    <thead>
    <tr>
      <th>
        Ничего не найдено! Выберите другой период времени.
      </th>
    </tr>
    </thead>
  </table>
@endif

