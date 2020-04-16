@extends('layouts.common')
@section('css')


@endsection
@section('js')

    <script src="{{ asset('js/admin/list_permission.js') }}"></script>

@endsection
@section('title')

    <i class='subheader-icon fal fa-lock-alt'></i> Список Ролей

@endsection
@section('content')


    <? $i = 1; ?>
    <div class="tab-content py-3">
        <div class="panel-container show">
            <div class="panel-content">
                <table class="dt-basic-example table table-bordered table-hover table-striped w-100">
                    <thead class="bg-warning-200">
                    <tr>
                        <th width="1%">#</th>
                        <th>Роль</th>
                        <th width="1%"></th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach( $list AS $_s => $_role)
                        <tr id="role_{!! @$_role->group_id !!}">
                            <td class="no-br"><b>{{ $i++ }}</td>
                            <td class="no-br"><b>{{ @$_role->group }}</td>
                            <td class="no-br">
                                @if( in_array('delete_user', session('getLevels')) )
                                    <a href="javascript:void(0);"
                                       class="btn btn-sm btn-icon btn-outline-danger delete_user rounded-circle mr-1"
                                       id-role="{{ @$_role->group_id}}"
                                       role="{{ @$_role->group}}"
                                       title="Удалить"
                                       data-toggle="modal" data-target="#delete-modal">
                                        <i class="fal fa-times"></i>
                                    </a>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>

@endsection
@section('modal')

    <div class="modal fade" id="delete-modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">
                        <strong>Подтвердите удаление!</strong>
                        <small class="m-0 text-muted">
                            <input type="hidden" id="role_delete_id">
                        </small>
                    </h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true"><i class="fal fa-times"></i></span>
                    </button>
                </div>
                <div class="modal-body">

                    Все пользователи с ролью <strong><span id="name_role_modal"></span></strong>
                    и все данные, связаные с ними, будут безвозвратно стёрты! <br> &nbsp;
                    <div class="a_c">
                        <strong>Уверены, что это необходимо?</strong>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Отмена</button>
                    <button type="button" class="btn btn-danger del_role_confirm">Удалить</button>
                </div>
            </div>
        </div>
    </div>

@endsection

