@extends('layouts.common')
@section('css')


@endsection
@section('js')


@endsection
@section('title')

    <i class='subheader-icon fal fa-inbox-in'></i>   Обращение

@endsection
@section('content')


    <div class="col-md-12 sortable-grid ui-sortable pad_0">
        <div class="panel panel-sortable" role="widget">
            <div class="panel-hdr" role="heading">
                <h2> Обращение  от <span class="fw-300"> {!! date("d.m.Y H:i") !!} </span> </h2>
            </div>
            <div class="panel-container show" role="content">
                <div class="panel-content">


                    <div class="panel-tag">
                        Обращение создано
                    </div>

                    <hr class="my-3">
                    <div class="a_c">
                        <a href="{{url('tack_add_page')}}"><span class="btn btn-lg cur_p btn-success waves-effect waves-themed"> Создать Обращение</span></a>
                    </div>

                </div>
            </div>
        </div>
    </div>


@endsection
@section('modal')



@endsection

