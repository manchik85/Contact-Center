@extends('layouts.common')

@section('css')

  <link rel="stylesheet" media="screen, print" href="{{ asset('js/plugins/datatables/css/datatables.bundle.css') }}">

@endsection
@section('js')

  <script src="{{ asset('js/admin/list_user.js') }}"></script>

  <script src="{{ asset('js/plugins/datatables/datatables.bundle.js') }}"></script>
  <script>
    $(document).ready(function () {
      $('#dt-basic-example').dataTable(
        {
          responsive: false,
          paging: false,
          processing: false,
          stateSave: true,
          info: false,
          searching: false,
          columnDefs: [
            {
              @if ( in_array('task_del', session('getLevels')) || in_array('tack_cons_page', session('getLevels')) )
              targets: [-1],
              @endif
              title: '',
              orderable: false,
            },
          ]
        });
    });
  </script>

@endsection
@section('title')

  <i class="subheader-icon fal fa-handshake"></i>&nbsp; Список сотрудников

@endsection
@section('content')

  <ul class="nav nav-pills" role="tablist">
    <?php $i = 0; ?>
    @foreach( $usersList AS $_k=>$_v)
      <li class="nav-item"><a class="nav-link @if($i==0) active @endif" data-toggle="pill" href="#tab_{{ $i }}">
          {{ $_v['group'] }}</a></li>
      <?php $i++; ?>
    @endforeach
  </ul>
  <div class="tab-content py-3">
    <?php $i = 0; ?>
    @foreach( $usersList AS $_k=>$_v)
      <div class="tab-pane @if($i==0)in active @endif" id="tab_{{ $i }}">

        <div class="panel-container show">
          <div class="panel-content">
            <table class="dt-basic-example table table-bordered table-hover table-striped w-100" id="dt-basic-example">
              <thead class="bg-warning-200">
              <tr>
                <th width="1%">ID</th>
                <th width="1%">Создан</th>
                <th>Имя</th>
                @if($_k == 5)
                  <th width="1%">Статус</th>
                @endif
                <th width="15%">Логин</th>
                <th width="10%">Телефон</th>
                <th width="1%"></th>
              </tr>
              </thead>
              <tbody>
              @foreach( $_v AS $_s => $_user)
                @if($_s!='group' && $_s!='active')
                  <tr id="user_{!! $_s !!}">
                    <td class="no-br"><b>{{ $_s }}</td>
                    <td class="no-br"><b>{{ @$_user['date'] }}</b> {{ @$_user['time'] }}</td>
                    <td>{{ @$_user['name'] }}</td>
                    @if($_user['status'] == 5)
                      <td class="no-br">
                        @if(@$_user['online']==1)
                          <span class="badge badge-success fw-300 ml-1">Онлайн</span>
                          &nbsp;

                          @if($_user['online_in_phone'] ?? '')
                            <span class="badge badge-warning fw-300 ml-1">Звонок</span>
                          @endif

                        @elseif(@$_user['online']==2)
                          <span class="badge badge-primary fw-300 ml-1">Отошёл</span>
                        @else
                          <span class="badge badge-danger fw-300 ml-1">Оффлайн</span>
                        @endif
                      </td>
                    @endif
                    <td class="no-br">{{ @$_user['email'] }}</td>
                    <td class="no-br">{{ @$_user['phone'] }}</td>
                    <td class="no-br">
                      @if( in_array('delete_user', session('getLevels')) )
                        <a href="javascript:void(0);"
                           class="btn btn-sm btn-icon btn-outline-danger delete_user rounded-circle mr-1"
                           id-user="{{ $_s }}"
                           name-user="{{ @$_user['name'] }}"
                           title="Удалить"
                           data-toggle="modal" data-target="#delete-modal">
                          <i class="fal fa-times"></i>
                        </a>
                      @endif
                      <div class="dropdown d-inline-block dropleft">&nbsp;
                        <a href="#" class="btn btn-sm btn-icon btn-outline-primary rounded-circle shadow-0"
                           data-toggle="dropdown" aria-expanded="true" title="Действия">
                          <i class="fal fa-ellipsis-v"></i>
                        </a>
                        <div class="dropdown-menu">
                          @if( in_array('users_profile', session('getLevels')) )
                            <a class="dropdown-item users_profile" idUser="{!! $_s !!}">Профиль</a>
                          @endif
                          @if($_user['status'] == 5 &&  in_array('user_statistic_page', session('getLevels')) )
                            <a class="dropdown-item statistic_user" id-user="{{ $_s }}">Статистика</a>
                          @endif
                          @if( $_k > 1 &&  in_array('users_ban', session('getLevels')) )
                            <a class="dropdown-item bann_user" id-user="{{ $_s }}" name-user="{{ @$_user['name'] }}"
                               data-toggle="modal" data-target="#bann-modal">Заблокировать</a>
                          @endif
                        </div>
                      </div>
                    </td>
                  </tr>
                @endif
              @endforeach
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <?php $i++; ?>
    @endforeach
  </div>
  @if (in_array('users_profile', session('getLevels')) && Auth::user()->status>= $_k )
    <form method="POST" name="admChenUserPage" id="admChenUserPage" action="{{route('users_profile')}}" class="disp_n">
      <input id="prifileUserId" type="hidden" name="users_id" value="">
    </form>
  @endif

  @if (in_array('user_statistic_page', session('getLevels')) && Auth::user()->status>= $_k )
    <form method="POST" name="statPage" id="statPage" action="{{route('user_statistic_page')}}" class="disp_n">
      <input id="statUserId" type="hidden" name="users_id" value="">
    </form>
  @endif

@endsection
@section('modal')

  <div class="modal fade" id="delete-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">
            <strong>Подтвердите удаление!</strong>
            <small class="m-0 text-muted">
              Cотрудник: <strong><span id="name_user_modal"></span></strong>
              <input type="hidden" id="user_delete_id">
            </small>
          </h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true"><i class="fal fa-times"></i></span>
          </button>
        </div>
        <div class="modal-body">

          Все данные, связаные с ним, также будут безвозвратно стёрты! <br>
          <strong>Уверены, что это необходимо?</strong>

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Отмена</button>
          <button type="button" class="btn btn-danger del_user_confirm">Удалить</button>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="bann-modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">
            <strong>Подтвердите действие!</strong>
            <small class="m-0 text-muted">
              Cотрудник: <strong><span id="name_user_modal_bann"></span></strong>
              <input type="hidden" id="user_bann_id">
            </small>
          </h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true"><i class="fal fa-times"></i></span>
          </button>
        </div>
        <div class="modal-body">
          Сотрудник не сможет работать с системой;
          все данные, связаные с ним, также будут доступны другим сотрудникам! <br>

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Отмена</button>
          <button type="button" class="btn btn-warning bann_user_confirm">Заблокировать</button>
        </div>
      </div>
    </div>
  </div>

@endsection

