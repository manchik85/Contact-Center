@extends('layouts.common')

@section('js')


    <script src="{{ asset('control/ajax_common.js') }}" defer></script>
    <script src="{{ asset('js/admin/access.js') }}" defer></script>
@endsection
@section('title')

     <i class="subheader-icon fal fa-handshake"></i>&nbsp; Права доступа

@endsection
@section('content')

    <article class="col-xs-12 col-sm-12 col-md-12 col-lg-12">
        <div class="widget-body-access widget-body">
            <table class="table smart-form">
                <thead>
                <tr>
                    <th colspan="2" class="a_r">
                    </th>
                    @foreach( $access['users'] AS $_user)
                        <th>
                            <div class="padding-5 a_r">{!! $_user['group'] !!}</div>
                        </th>
                    @endforeach
                </tr>
                </thead>
                <tbody>
                @foreach( $access['permissionss'] AS $k => $_permissionss_group)
                    <? $user_permissions_group_name = $k; sort($_permissionss_group);?>
                    @foreach( $_permissionss_group AS  $_permissionss)
                        <tr>
                            @if($user_permissions_group_name!='')
                                <td rowspan="{!! count($_permissionss_group) !!}" style="line-height: 1.9em;">
                                    <b>{!! $user_permissions_group_name !!}</b>
                                    <? $user_permissions_group_name = ''; ?>
                                </td>
                            @endif
                            <td style="line-height: 1.9em;">
									<span rel="popover-hover" data-placement="top"
                                          data-content="{!! $_permissionss['permissions_comment'] !!}">
									{!! $_permissionss['permissions_title'] !!}
									</span>
                            </td>
                            @foreach( $access['users'] AS $_user)
                                <td class="a_r">
                                    <div class="px_4"></div>

                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" name="checkbox-toggle"
                                               class="custom-control-input accessSelect"
                                               id="customSwitch{!! @$_permissionss['permissions_id_c'] !!}{!! $_user['id'] !!}"
                                               level="{!! @$_permissionss['permissions_id_c'] !!}"
                                               group="{!! $_user['id'] !!}"
                                               @if ( !in_array('users_change_access', session('getLevels')) ) disabled="" @endif


                                               @if( isset($_user['permissions'][@$_permissionss['permissions_id']]) ) checked @endif >
                                        <label class="custom-control-label" for="customSwitch{!! @$_permissionss['permissions_id_c'] !!}{!! $_user['id'] !!}"></label>
                                    </div>
                                </td>
                            @endforeach
                            @endforeach
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </article>
    <div class="clb"></div>

@endsection
