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
      <th>KPI</th>
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
        <td> <b>{!! $_user['kpi'] !!}</b> </td>
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

