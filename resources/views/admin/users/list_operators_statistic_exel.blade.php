<? $i = 1; ?>
@if( count($date) > 0 )
	<table>
		<thead>
		<tr>
			<th width="1%">#</th>
			<th>Группа</th>
			<th>Оператор</th>
			<th>Номер</th>
			<th>Контакт</th>
      <th>e-mail</th>

			<th>Звонки</th>
			<th>Отвеченные</th>
			<th>Не отвеченные</th>

			<th>Консультации</th>
			<th>Неисправности</th>

			<th>Онлайн</th>
			<th>Отошёл</th>
			<th>Оффлайн</th>
		</tr>
		</thead>
		<tbody>

		@foreach( $date AS $_k=>$_user)
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

				<td> <b>{!! $_user['calls'] !!}</b> </td>
				<td> <b>{!! $_user['answered'] !!}</b> </td>
				<td> <b>{!! $_user['no_answered'] !!}</b> </td>

				<td> <b>{!! $_user['advice_tack'] !!}</b> </td>
				<td> <b>{!! $_user['request_tack'] !!}</b> </td>

				<td><b>{!! $_user['sigma_on'] !!}</b></td>
				<td><b>{!! $_user['sigma_aut'] !!}</b></td>
				<td><b>{!! $_user['sigma_off'] !!}</b></td>
			</tr>
		@endforeach
    <tr>
      <td colspan="6"><h3>Всего:</h3></td>

      <td><b>{{ $calls }}</b></td>
      <td><b>{{ $answered }}</b></td>
      <td><b>{{ $no_answered }}</b></td>

      <td><b>{{ $advice_tack }}</b> </td>
      <td><b>{{ $request_tack }}</b></td>

      <td><b>{{ $sigma_on }}</b></td>
      <td><b>{{ $sigma_aut }}</b></td>
      <td><b>{{ $sigma_off }}</b></td>
    </tr>
    <tr></tr>
    <tr></tr>
    <tr>
      <td></td>
      <td>Количество звонков</td>
      <td>Принятые</td>
      <td>Пропущенные</td>
    </tr>
    <tr>
      <td></td>
      <td><b>{{ $call_st_quantity }}</b></td>
      <td><b>{{ $call_st_answered }}</b></td>
      <td><b>{{ $call_st_missed }}</b></td>
    </tr>
    <tr></tr>
    <tr>
      <td></td>
      <td>Среднее время ожидания</td>
      <td><b>{{ $call_st_waitAverageTime }}</b></td>
    </tr>
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

