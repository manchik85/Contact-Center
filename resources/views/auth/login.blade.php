@extends('layouts.app')
@section('css')
    <link href="{{ asset('css/page-login.css') }}" rel="stylesheet">
@endsection
@section('js')
    <script src="{!! asset('/js/login.js') !!}"></script>
@endsection
@section('content')
    <login :data="{{ json_encode( [ 'name' => config('app.name', 'Laravel') ] ) }}"></login>
@endsection



