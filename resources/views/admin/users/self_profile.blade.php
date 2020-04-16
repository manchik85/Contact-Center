@extends('layouts.common')

@section('js')
    {{--    <script src="//ajax.googleapis.com/ajax/libs/jquery/3.1.0/jquery.min.js"></script>--}}
    {{--    <script src="{{ asset('control/plugins/masked-input/jquery.maskedinput.min.js') }}"></script>--}}
    <script src="{{ asset('js/admin/profile.js') }}"></script>
@endsection
@section('title')

    <i class="subheader-icon fal fa-user"></i>&nbsp; Настройка своего профиля

@endsection

@section('content')


    <section id="widget-grid">
        <div class="row">
            <div class="col-md-12 ui-sortable">
                <div class="tabbable">
                    <ul class="nav nav-pills" role="tablist">
                        <li class="nav-item">
                            <a href="#commonInfo" data-toggle="tab" rel="tooltip" data-placement="top"
                               class="nav-link active">
                                <i class="fal fa-list-alt" aria-hidden="true"></i> Общая Информация
                            </a>
                        </li>
                        @if (in_array('chenge_self_passw', session('getLevels')))
                        <li class="nav-item">
                            <a href="#password" data-toggle="tab" rel="tooltip" data-placement="top"
                               class="nav-link">
                                <i class="fal fa-key" aria-hidden="true"></i> Изменить пароль
                            </a>
                        </li>
                        @endif
                        @if ( in_array('chenge_self_descr', session('getLevels')) )
                        <li class="nav-item">
                            <a href="#description" data-toggle="tab" rel="tooltip" data-placement="top"
                               class="nav-link">
                                <i class="fal fa-pencil-alt" aria-hidden="true"></i> Комментарий
                            </a>
                        </li>
                        @endif
                    </ul>
                    <div class="tab-content py-3">
                        <div class="tab-pane padding-10 in active" id="commonInfo">
                            <form class="smart-form client-form" id="profile_common_info" name="profile_common_info"
                                  method="post" action="{{route('profile.update')}}">
                                <edit-common-info :data="{{ json_encode([
                                                                        'id'   =>Auth::user()->id,
                                                                        '_token'   => csrf_token() ,
                                                                        'name' =>Auth::user()->name,
                                                                        'email'=>Auth::user()->email,
                                                                        'users_phone'=>@$data_user[0]->users_phone,
                                                                        'users_cont_phone'=>@$data_user[0]->users_cont_phone,
                                                                        'gov_group'=>@$data_user[0]->gov_group,
                                                                        'gov_group_root'=>@$data_user[0]->gov_group_root,
                                                                        'gov_group_master'=>@$data_user[0]->gov_group_master
                                                                     ]) }}"></edit-common-info>
                            </form>
                            <div class="clb"></div>
                        </div>
                        @if (in_array('chenge_self_passw', session('getLevels')))
                            <div class="tab-pane padding-10 smart-form client-form" id="password">
                                <edit-password></edit-password>
                            </div>
                        @endif
                        @if ( in_array('chenge_self_descr', session('getLevels')) )
                        <div class="tab-pane padding-10" id="description">
                            <edit-description :data="{{ json_encode(['id' =>Auth::user()->id,'deck'=>@$data_user[0]->users_deck]) }} "></edit-description>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection


