<? $i = 1; ?>
@if( isset($list[0]) && count($list) > 0 )
	<table>
		<thead>
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
		</tr>
		</thead>
		<tbody>
		
		@foreach( $list AS $_k=>$_v)
			<tr>
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
			</tr>
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

