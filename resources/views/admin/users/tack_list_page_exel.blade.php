<? $i = 1; ?>
@if( isset($data[0]) && count($data) > 0 )
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
			<th>Исполнитель</th>
			<th>Обращения</th>
			<th>Решение</th>
			<th>Приоритет</th>
			<th>Стадия</th>
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
				<td> {!! @$_v->developer !!} </td>
				<td>{!! $_v->created_at !!}</td>
				<td>{!! $_v->task_off !!}</td>
				<td>
					
					@if(@$_v->task_priority=='1')
						Высокий
					@elseif(@$_v->task_priority=='2')
						Средний
					@else
						Низкий
					@endif
				
				</td>
				<td>
					
					@if(@$_v->complete=='4')
						Не начат
					@elseif(@$_v->complete=='1')
						Назначен Исполнитель
					@elseif(@$_v->complete=='2')
						На рассмотрении
					@else
						 Решена
					@endif
				
				</td>
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

