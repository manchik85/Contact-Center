@extends('layouts.common')
@section('css')

    <link href="{{ asset('js/plugins/bootstrap-datepicker/css/bootstrap-datepicker.css') }}" rel="stylesheet">

@endsection
@section('js')

    <script src="{{ asset('js/plugins/bootstrap-datepicker/bootstrap-datepicker.js') }}"></script>
    <script src="{{ asset('js/plugins/bootstrap-datepicker/bootstrap-datetimepicker.ru.js') }}"></script>
    <script src="{{ asset('js/admin/add_tack.js') }}"></script>

@endsection
@section('title')

    <i class='subheader-icon fal fa-inbox-in'></i> Создать Обращение

@endsection
@section('content')

    <div class="row">
        <div class="col-md-12 sortable-grid ui-sortable pad_0">
            <div class="panel panel-sortable" role="widget">
                <div class="panel-hdr" role="heading">
                    <h2> Обращение  от <span class="fw-300"> {!! date("d.m.Y H:i") !!} </span> </h2>
                </div>
                <div class="panel-container show" role="content">
                    <div class="panel-content">
                        <add-tack :data="{{ json_encode([
                            'form' => $form,
                            'names' => $names,
                            'prior' => $prior,
                            'client_phone' => $client_phone,
                            'client_login' => $client_login,
                            'task_district' => $region_name
                        ]) }}"></add-tack>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('modal')



@endsection

