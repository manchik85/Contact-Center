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
			<th>Пароль</th>
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
				<td>{!! $_v->client_pass !!}</td>
				<td>{!! $_v->client_phone !!}</td>
				<td>{!! $_v->client_mail !!}</td>
				<td>{!! $_v->task_name !!}</td>
				<td> {!! $_v->operator !!} </td>
				<td> {!! @$_v->developer !!} </td>
				<td>{!! $_v->created_at !!}</td>
				<td>@if( $_v->task_type == 'request_tack'){!! $_v->task_off !!}@endif</td>
				<td>
					@if(@$_v->task_priority=='1' && $_v->task_type == 'request_tack')
						Высокий
					@elseif(@$_v->task_priority=='2' && $_v->task_type == 'request_tack')
						Средний
					@elseif(@$_v->task_priority=='3' && $_v->task_type == 'request_tack')
						Низкий
					@endif
				</td>
				<td>
					@if(@$_v->complete=='1'  && $_v->task_type == 'request_tack')
						Назначен Исполнитель
					@elseif(@$_v->complete=='2'  && $_v->task_type == 'request_tack')
						Принято в работу
					@elseif(@$_v->complete=='3'  && $_v->task_type == 'request_tack')
						Решено
					@elseif(@$_v->complete=='4'  && $_v->task_type == 'request_tack')
						Не начат
          			@elseif(@$_v->complete=='5' && $_v->task_type == 'request_tack')
						Повторное исполнение
          			@elseif(@$_v->complete=='6' && $_v->task_type == 'request_tack')
            			На доработке у оператора
					@elseif(@$_v->complete=='7' && $_v->task_type == 'request_tack')
						Консультация
					@elseif(@$_v->complete=='8' && $_v->task_type == 'request_tack')
						Не повторилась
					@elseif(@$_v->complete=='9' && $_v->task_type == 'request_tack')
						Не актуальная
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

