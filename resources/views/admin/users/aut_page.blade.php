@extends('layouts.app')
@section('css')
    <link href="{{ asset('css/page-login.css') }}" rel="stylesheet">
@endsection
@section('js')
{{--    <script src="{{ asset('js/dashboard.js') }}"></script>--}}
    <script src="{!! asset('/js/aut_page.js') !!}"></script>
@endsection
@section('content')
    <input type="hidden" id="id_comm" value="{!! Auth::user()->id !!}">
    <aut-page :data="{{ json_encode( [ 'name' => config('app.name', 'Laravel'), 'email' =>  Auth::user()->email, 'id' =>  Auth::user()->id ] ) }}"></aut-page>
@endsection
