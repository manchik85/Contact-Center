<!DOCTYPE html>
<html lang="ru">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <!-- CSRF Token -->
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>{{ config('app.name', 'Контакт Центр') }}</title>

  <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('img/favicon/favicon-32x32.png') }}">

  <!-- Styles -->
  <link href="{{ asset('css/vendors.bundle.css') }}" rel="stylesheet">
  <link href="{{ asset('css/app.core.css') }}" rel="stylesheet">
  <link href="{{ asset('css/fa-regular.css') }}" rel="stylesheet">
  @yield('css')
</head>
<body class="mod-bg-1 desktop chrome webkit pace-done nav-function-minify nav-function-fixed blur">
<div class="page-wrapper">
  <div class="page-inner">
    <aside class="page-sidebar">
      <div class="page-logo nav-menu">
        @if ( in_array('dashboard', session('getLevels')) )
            <a href="{{url('dashboard')}}" style="font-size: 1.75rem; color: rgba(255, 255, 255, 0.5)"> <i class="fal fa-home"></i></a>
        @endif
      </div>
      <!-- BEGIN PRIMARY NAVIGATION -->
      <nav id="js-primary-nav" class="primary-nav" role="navigation">
        <ul id="js-nav-menu" class="nav-menu">

          <li class="nav-title">Меню рабочего стола</li>
          @if (
          in_array('tack_form_edit_page', session('getLevels')) ||
          in_array('tack_add_page', session('getLevels')) ||
          in_array('cons_list_page', session('getLevels')) ||
          in_array('tack_cons_page', session('getLevels')) ||
          in_array('tack_list_page', session('getLevels')) ||
          in_array('my_tack_page_operator', session('getLevels')) ||
          in_array('my_tack_page_developer', session('getLevels')) )
            <li {{ ( Route::currentRouteName() == 'tack_form_edit_page'
                              || Route::currentRouteName() == 'tack_add_page'
                              || Route::currentRouteName() == 'cons_list_page'
                              || Route::currentRouteName() == 'tack_cons_page'
                              || Route::currentRouteName() == 'tack_list_page'
                              || Route::currentRouteName() == 'my_tack_page_operator'
                              || Route::currentRouteName() == 'my_tack_page_developer' ) ? ' class=active' : '' }} >
              <a href="#"> <i class="fal fa-inbox-in"></i> <span class="nav-link-text">Обращения</span></a>
              <ul>
                @if ( in_array('tack_form_edit_page', session('getLevels')) )
                  <li{{ (Route::currentRouteName()=='tack_form_edit_page' ? ' class=active' : '') }}>
                    <a href="{{url('tack_form_edit_page')}}">
                      <span class="nav-link-text">Редактировать форму</span>
                    </a>
                  </li>
                @endif
                @if ( in_array('tack_add_page', session('getLevels')) )
                  <li{{ (Route::currentRouteName()=='tack_add_page' ? ' class=active' : '') }}>
                    <a href="{{url('tack_add_page')}}">
                      <span class="nav-link-text">Создать Обращение</span>
                    </a>
                  </li>
                @endif
                @if ( in_array('deal_list_page', session('getLevels')) )
                  <li{{ (Route::currentRouteName()=='deal_list_page' ? ' class=active' : '') }}>
                    <a href="{{url('deal_list_page')}}">
                      <span class="nav-link-text">Общий список Звонков</span>
                    </a>
                  </li>
                @endif
                @if ( in_array('cons_list_page', session('getLevels')) )
                  <li{{ (Route::currentRouteName()=='cons_list_page' ? ' class=active' : '') }}>
                    <a href="{{url('cons_list_page')}}">
                      <span class="nav-link-text">Список Консультаций</span>
                    </a>
                  </li>
                @endif
                @if ( in_array('tack_list_page', session('getLevels')) )
                  <li{{ (Route::currentRouteName()=='tack_list_page' ? ' class=active' : '') }}>
                    <a href="{{url('tack_list_page')}}">
                      <span class="nav-link-text">Список Неисправностей</span>
                    </a>
                  </li>
                @endif
                {{--                                @if ( in_array('my_tack_page_operator', session('getLevels')) )--}}
                {{--                                <li{{ (Route::currentRouteName()=='my_tack_page_operator' ? ' class=active' : '') }}>--}}
                {{--                                    <a href="{{url('my_tack_page_operator')}}">--}}
                {{--                                        <span class="nav-link-text">Мои Заявки</span>--}}
                {{--                                    </a>--}}
                {{--                                </li>--}}
                {{--                                @endif--}}
                @if ( in_array('my_tack_page_developer', session('getLevels')) )
                  <li{{ (Route::currentRouteName()=='my_tack_page_developer' ? ' class=active' : '') }}>
                    <a href="{{url('my_tack_page_developer')}}">
                      <span class="nav-link-text">Мои Обращения</span>
                    </a>
                  </li>
                @endif
              </ul>
            </li>
          @endif


          @if (
          in_array('kpi_page', session('getLevels')) ||
          in_array('add_users', session('getLevels')) ||
          in_array('list_pages', session('getLevels')) ||
          in_array('list_operators_statistic', session('getLevels')) ||
          in_array('white_ip_page', session('getLevels')) ||
          in_array('user_statistic_page', session('getLevels')) ||
          in_array('access_pages', session('getLevels')) )
            <li {{ ( Route::currentRouteName() == 'list_users'
                              || Route::currentRouteName() == 'kpi_page'
                              || Route::currentRouteName() == 'add_users'
                              || Route::currentRouteName() == 'white_ip_page'
                              || Route::currentRouteName() == 'user_statistic_page'
                              || Route::currentRouteName() == 'list_operators_statistic'
                              || Route::currentRouteName() == 'access_pages' ) ? ' class=active' : '' }} >
              <a href="#"> <i class="fal fa-handshake"></i> <span class="nav-link-text">Сотрудники</span></a>
              <ul>
                @if ( in_array('add_users', session('getLevels')) )
                  <li{{ (Route::currentRouteName()=='add_users' ? ' class=active' : '') }}>
                    <a href="{{url('add_users')}}">
                      <span class="nav-link-text">Добавить сотрудника</span>
                    </a>
                  </li>
                @endif
                @if ( in_array('list_users', session('getLevels')) )
                  <li{{ (Route::currentRouteName()=='list_users' ? ' class=active' : '') }}>
                    <a href="{{url('list_users')}}">
                      <span class="nav-link-text">Список сотрудников</span>
                    </a>
                  </li>
                @endif
                @if ( in_array('list_operators_statistic', session('getLevels')) )
                  <li{{ (Route::currentRouteName()=='list_operators_statistic' ? ' class=active' : '') }}>
                    <a href="{{url('list_operators_statistic')}}">
                      <span class="nav-link-text">Статистика работы Операторов</span>
                    </a>
                  </li>
                @endif
                @if ( in_array('kpi_page', session('getLevels')) )
                  <li{{ (Route::currentRouteName()=='kpi_page' ? ' class=active' : '') }}>
                    <a href="{{url('kpi_page')}}">
                      <span class="nav-link-text">KPI операторов</span>
                    </a>
                  </li>
                @endif
                @if ( in_array('access_pages', session('getLevels')) )
                  <li{{ (Route::currentRouteName()=='access_pages' ? ' class=active' : '') }}>
                    <a href="{{url('access_pages')}}">
                      <span class="nav-link-text">Права доступа</span>
                    </a>
                  </li>
                @endif
                @if ( in_array('white_ip_page', session('getLevels')) )
                  <li{{ (Route::currentRouteName()=='white_ip_page' ? ' class=active' : '') }}>
                    <a href="{{url('white_ip_page')}}">
                      <span class="nav-link-text">Разрешённые IP-адреса</span>
                    </a>
                  </li>
                @endif
              </ul>
            </li>
          @endif

          @if (
          in_array('permissions_add', session('getLevels')) ||
          in_array('permissions_list', session('getLevels')) )
            <li {{ ( Route::currentRouteName() == 'permissions_add'
                              || Route::currentRouteName() == 'permissions_list' ) ? ' class=active' : '' }} >
              <a href="#"> <i class="fal fa-lock-alt"></i> <span class="nav-link-text">Роли</span></a>
              <ul>
                @if ( in_array('permissions_add', session('getLevels')) )
                  <li{{ (Route::currentRouteName()=='permissions_add' ? ' class=active' : '') }}>
                    <a href="{{url('permissions_add')}}">
                      <span class="nav-link-text">Добавить Роль</span>
                    </a>
                  </li>
                @endif
                @if ( in_array('permissions_list', session('getLevels')) )
                  <li{{ (Route::currentRouteName()=='permissions_list' ? ' class=active' : '') }}>
                    <a href="{{url('permissions_list')}}">
                      <span class="nav-link-text">Список Ролей</span>
                    </a>
                  </li>
                @endif
              </ul>
            </li>
          @endif

          @if (
          in_array('call_list_page', session('getLevels')) ||
          in_array('call_user_page', session('getLevels')) )
            <li {{ ( Route::currentRouteName() == 'call_list_page' || Route::currentRouteName() == 'call_user_page' ) ? ' class=active' : '' }} >
              <a href="#"> <i class="fal fa-phone"></i> <span class="nav-link-text">Звонки</span></a>
              <ul>
                @if ( in_array('call_list_page', session('getLevels')) )
                  <li{{ (Route::currentRouteName()=='call_list_page' ? ' class=active' : '') }}>
                    <a href="{{url('call_list_page')}}">
                      <span class="nav-link-text">Список звонков</span>
                    </a>
                  </li>
                @endif
                @if ( in_array('call_user_page', session('getLevels')) )
                  <li{{ (Route::currentRouteName()=='call_user_page' ? ' class=active' : '') }}>
                    <a href="{{url('call_user_page')}}">
                      <span class="nav-link-text">Мои звонки</span>
                    </a>
                  </li>
                @endif
              </ul>
            </li>
          @endif

          @if (
          in_array('mail_page', session('getLevels')) ||
          in_array('my_mail_page', session('getLevels')) ||
          in_array('add_mail_page', session('getLevels')) )
            <li {{ ( Route::currentRouteName() == 'mail_page'
                  || Route::currentRouteName() == 'my_mail_page'
                  || Route::currentRouteName() == 'add_mail_page' ) ? ' class=active' : '' }} >
              <a href="#"> <i class="fal fa-envelope-open"></i> <span class="nav-link-text">Рассылки</span></a>
              <ul>
                @if ( in_array('add_mail_page', session('getLevels')) )
                  <li{{ (Route::currentRouteName()=='add_mail_page' ? ' class=active' : '') }}>
                    <a href="{{url('add_mail_page')}}">
                      <span class="nav-link-text">Создать рассылку</span>
                    </a>
                  </li>
                @endif
                @if ( in_array('mail_page', session('getLevels')) )
                  <li{{ (Route::currentRouteName()=='mail_page' ? ' class=active' : '') }}>
                    <a href="{{url('mail_page')}}">
                      <span class="nav-link-text">Список рассылок</span>
                    </a>
                  </li>
                @endif
                @if ( in_array('my_mail_page', session('getLevels')) )
                  <li{{ (Route::currentRouteName()=='my_mail_page' ? ' class=active' : '') }}>
                    <a href="{{url('my_mail_page')}}">
                      <span class="nav-link-text">Мои рассылки</span>
                    </a>
                  </li>
                @endif
              </ul>
            </li>
          @endif

          @if ( in_array('notice_user_page', session('getLevels')) )
            <li {{ (Route::currentRouteName() == 'notice_user_page') ? ' class=active' : '' }} >
              <a href="{{url('notice_user_page')}}"> <i class="fal fa-bell"></i></a>
            </li>
          @endif

        </ul>
      </nav>
    </aside>
    <div class="page-content-wrapper">
      <header class="page-header" role="banner">

        <div class="subheader">
          <h1 class="subheader-title">
            @yield('title')
            <small>
              @yield('small_title')
            </small>
          </h1>
        </div>

        <div class="subheader left_logo">
          <img src="img/logo_s.png">
          <h1 class="subheader-title">
            Служба поддержки ИИС «Е-кызмет»
          </h1>
        </div>

        <div class="ml-auto d-flex">

          <div>
            <h4 class="header-icon">{!! Auth::user()->name !!} &nbsp; </h4>
          </div>

          <div>
            <a href="#" class="header-icon" data-toggle="dropdown" title="Уведомления">
              <i class="fal fa-bell"></i>
              <span class="label label-warning">{!! count(\App\Models\Adm\Notice::getNoticeUser(Auth::user()->id)) !!}</span>
            </a>

            <div class="dropdown-menu dropdown-menu-animated dropdown-xl">
              <div class="dropdown-header bg-trans-gradient d-flex justify-content-center align-items-center rounded-top mb-2">
                <h4 class="m-0 text-center color-white">
                  Список уведомлений!
                </h4>
              </div>
              <div class="tab-content tab-notification">
                <div class="tab-pane active p-3 text-center">
                <?php
                  $breakCount = 0;

                  foreach (\App\Models\Adm\Notice::getNoticeUser(Auth::user()->id) AS $arrResult) {
                      //увеличивываем счетчик
                      $breakCount++;
                      //формируем адрес
                      $url = action('Adm\Tacks\PagesController@tackOperator', ['notice_id' => $arrResult->id, 'task_operator_page_id' => $arrResult->task]);

                      echo '<div class="pt-3">
                        <a href="'.$url.'">
                          <span class="nav-link-text">'. $arrResult->description .'</span>
                        </a>
                      </div>';

                      //показываем не более 5 уведомления, причем показываем те что давно уже пришли
                      if ($breakCount == 5) {
                          break;
                      }
                  }
                ?>
                </div>
              </div>
            </div>
          </div>

          <div>
            <a href="#" class="header-icon" data-toggle="modal" data-target="#out_modal" title="Отошёл">
              <i class="fal fa-clock"></i>
            </a>
          </div>
          <div>
            <a href="#" class="header-icon" data-toggle="dropdown" title="Выйти из программы">
              <i class="fal fa-sign-out-alt"></i>
            </a>
            <div class="dropdown-menu dropdown-menu-animated dropdown-xl">
              <div
                  class="dropdown-header bg-trans-gradient d-flex justify-content-center align-items-center rounded-top mb-2">
                <h4 class="m-0 text-center color-white">
                  Подтвердите действие!
                  <!--small class="mb-0 opacity-80"> </small-->
                </h4>
              </div>
              <div class="tab-content tab-notification">
                <div class="tab-pane active p-3 text-center">
                  <h5 class="mt-4 fw-500">
                                            <span class="d-block fa-4x text-muted">
                                                <i class="fal fa-sign-out-alt text-gradient opacity-70"></i>
                                            </span>
                    <div class="pt-3">&nbsp;</div>
                    Вы собираетесь выйти из программы и закончить работу
                    <div class="px-6">&nbsp;</div>
                    <small class="mt-3 fs-b" style="color: red">
                      <strong>Рекомендуем сохранить все несохранённые данные!</strong>

                      <div class="px-6">&nbsp;</div>
                      <div class="px-6">&nbsp;</div>
                      <button type="button" class="btn btn-outline-secondary waves-effect waves-themed"
                              data-toggle="close">Продолжить работу
                      </button>
                      <a href="{{ url('/logout') }}">
                        <button type="button" class="btn btn-danger waves-effect waves-themed">Выйти</button>
                      </a>

                    </small>
                  </h5>
                </div>
              </div>
            </div>
          </div>


          <span id="task_mess_bat" data-toggle="modal" data-target="#task_mess"></span>

          @if ( in_array('my_profile', session('getLevels')) )
            <a href='#'
               class="header-icon cur_p"
               data-toggle="dropdown"
               onclick="javascript:document.location.href='{{url('my_profile')}}'"
               title="Мои данные">
              <i class="fal fa-user"></i>
            </a>

          @endif


        </div>
      </header>
      <main id="js-page-content" role="main" class="page-content">

        <div id="app">
          <input type="hidden" id="id_comm" value="{!! Auth::user()->id !!}">
          @yield('content')
        </div>

      </main>
      <footer class="page-footer" role="contentinfo">
      <b>&#169; ТОО "Radix Group"</b> &nbsp; &nbsp; Служба технической поддержки: +77024607944 (ватсап, телеграмм), электронная почта: &nbsp; <a href="mailto:info@rcompany.kz">info@rcompany.kz</a>
      </footer>
    </div>
  </div>
</div>

<div class="modal fade" id="task_mess" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">
          Неисправность (ошибка) № <span id="task_id"></span>
          <small class="m-0 text-muted">
            <span id="task_name"></span></strong>
          </small>
        </h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true"><i class="fal fa-times"></i></span>
        </button>
      </div>
      <div class="modal-body">
        Статус заявки: <b><span id="task_message"></b></span>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрыть</button>
      </div>
    </div>
  </div>
</div>

@yield('modal')

<div class="modal fade" id="out_modal" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">
          <strong>Подтвердите действие!</strong>
          <small class="m-0 text-muted">
            Вы собираетесь прервать работу.
          </small>
        </h4>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true"><i class="fal fa-times"></i></span>
        </button>
      </div>
      <div class="modal-body">
        <div class="form-group">
          <label class="form-label" for="justification_out">Обоснование:</label>
          <textarea class="form-control" id="justification_out" name="justification_out"></textarea>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" data-dismiss="modal" aria-label="Close"
                class="btn btn-outline-secondary waves-effect waves-themed"
                data-toggle="close">Продолжить работу
        </button>

        <button type="button" id="operator-aut" user-id="{{Auth::user()->id}}" class="btn btn-info"
                data-toggle="dropdown">Отойти
        </button>
      </div>
    </div>
  </div>
</div>

<script src="{{ asset('js/plugins/jquery.min.js') }}"></script>
<script src="{{ asset('js/operator.js') }}"></script>
<script src="{{ asset('js/vendors.bundle.js') }}"></script>
<script src="{{ asset('js/dashboard.js') }}"></script>
@yield('js')
</body>
</html>

