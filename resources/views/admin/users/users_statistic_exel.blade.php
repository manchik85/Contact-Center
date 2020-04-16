<? $i = 1; ?>
@if( isset($data[0]) && count($data) > 0 )
	<table>
		<thead>
		<tr>
			<th>#</th>
			<th>Дата</th>
			<th>Время / Продолжительность</th>
			<th>Статус</th>
		</tr>
		</thead>
		<tbody>
		@foreach( $date AS $_s => $_user)
			<tr>
				<td>{!! $i++ !!}</td>
				<td>{!! date("d.m.Y", ($_user['common_date'])) !!}</td>
				<td>
					{!! date("H:i:s", ($_user['common_date'])) !!}
					/
						@if ( $_user['delta']>0 )
							{!! date("H:i:s", mktime(0, 0, $_user['delta'])); !!}
						@else
							В настоящее время
						@endif
				</td>
				<td>
					@if(@$_user['user_event']=='online')
						Онлайн
					@elseif(@$_user['user_event']=='aut')
						Отошёл: {!! $_user['out_desc'] !!}
					@else
						Оффлайн
					@endif
				</td>
			</tr>
		@endforeach				<tr>
			<td> Онлайн </td>
			<td colspan="3"> <b>{!! $sigma_on ?? 0 !!}</b> </td>
		</tr>
		<tr>
			<td> Отошёл </td>
			<td colspan="3"> <b>{!! $sigma_aut ?? 0 !!}</b> </td>
		</tr>
		<tr>
			<td> Оффлайн </td>
			<td colspan="3"> <b>{!! $sigma_off ?? 0 !!}</b> </td>
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

