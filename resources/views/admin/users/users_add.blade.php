@extends('layouts.common')

@section('js')

    <script src="{{ asset('js/admin/add_user.js') }}"></script>

@endsection
@section('title')
    <i class="subheader-icon fal fa-handshake"></i>&nbsp; Добавить сотрудника
@endsection
@section('content')

    <add-users :data="{{ json_encode([ 'status' =>Auth::user()->status ]) }} "></add-users>

@endsection


@section('modal')
@endsection
