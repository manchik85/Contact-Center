@extends('layouts.common')
@section('css')



@endsection
@section('js')

    <script src="{{ asset('js/admin/add_permission.js') }}"></script>

@endsection
@section('title')

    <i class='subheader-icon fal fa-lock-alt'></i> Добавить Роль

@endsection
@section('content')

    <add-permission></add-permission>

@endsection
@section('modal')



@endsection

