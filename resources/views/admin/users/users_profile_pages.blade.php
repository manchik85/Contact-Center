@extends('layouts.common')

@section('js')

    <script src="{{ asset('js/admin/profile.js') }}"></script>

@endsection
@section('title')

    <i class="subheader-icon fal fa-handshake"></i>&nbsp; Настройка профиля

@endsection
@section('content')
    <section id="widget-grid">
        <div class="row">
            <div class="col-md-12 ui-sortable">
                <div class="tabbable">
                    <ul class="nav nav-pills" role="tablist">
                        <li class="nav-item">
                            <a href="#commonInfo" data-toggle="tab" rel="tooltip" data-placement="top" class="nav-link active">
                                <i class="fal fa-list-alt" aria-hidden="true"></i> Общая Информация
                            </a>
                        </li>
                        @if (in_array('chenge_users_passw', session('getLevels')))
                        <li class="nav-item">
                            <a href="#password" data-toggle="tab" rel="tooltip" data-placement="top" class="nav-link">
                                <i class="fal fa-key" aria-hidden="true"></i> Изменить пароль
                            </a>
                        </li>
                        @endif
                        @if ( in_array('chenge_self_descr', session('getLevels')) )
                        <li class="nav-item">
                            <a href="#description" data-toggle="tab" rel="tooltip" data-placement="top" class="nav-link">
                                <i class="fal fa-pencil-alt" aria-hidden="true"></i> Комментарий
                            </a>
                        </li>
                        @endif
                    </ul>
                    <div class="tab-content py-3">
                        <div class="tab-pane padding-10 in active" id="commonInfo">
                            <form class="smart-form client-form" id="profile_common_info" name="profile_common_info"
                                  method="post" action="{{route('users_profile')}}">
                                <input type="hidden" name="mod" value=10>
                                <input type="hidden" name="users_id" value={{$dataUser->id}}>
                                <edit-common-info :data="{{ json_encode([
                                                                            'id'   =>$dataUser->id,
                                                                            'name' =>$dataUser->name,
                                                                            'email'=>$dataUser->email,
                                                                            'users_phone'=>@$dataUser->users_phone,
                                                                             'users_cont_phone'=>@$dataUser->users_cont_phone,
                                                                             'gov_group'=>@$dataUser->gov_group
                                                                         ]) }} "></edit-common-info>
                            </form>
                            <div class="clb"></div>
                        </div>

                        @if (in_array('chenge_users_passw', session('getLevels')))
                        <div class="tab-pane padding-10 smart-form client-form" id="password">
                            <edit-password-adm :data="{{ json_encode(['id' =>$dataUser->id]) }}"></edit-password-adm>
                            <div class="div px_10 float_n clb"></div>
                            <div class="clb"></div>
                        </div>
                        @endif
                        @if ( in_array('chenge_self_descr', session('getLevels')) )
                        <div class="tab-pane padding-10" id="description">
                            <edit-description
                                :data="{{ json_encode(['id' =>$dataUser->id, 'deck' => @$dataUser->users_deck ]) }}"></edit-description>
                            <div class="clb"></div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
