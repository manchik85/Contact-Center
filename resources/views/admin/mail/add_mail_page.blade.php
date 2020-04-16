@extends('layouts.common')
@section('css')



@endsection
@section('js')


  <script src="{{ asset('js/admin/add_mail.js') }}"></script>


@endsection
@section('title')

  <i class='subheader-icon fal fa-envelope-open'></i> Создать рассылку

@endsection
@section('content')

  <add-mail :data="{{ json_encode([ 'users_id' => Auth::user()->id ]) }} "></add-mail>



@endsection
@section('modal')



@endsection


