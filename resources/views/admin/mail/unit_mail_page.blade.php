@extends('layouts.common')
@section('css')



@endsection
@section('js')


  <script src="{{ asset('js/admin/add_mail.js') }}"></script>


@endsection
@section('title')

  <i class='subheader-icon fal fa-envelope-open'></i> {!! $mail[0]->mail_title !!}

@endsection
@section('content')

  <h2>{!! date('d.m.Y H:i:s', strtotime($mail[0]->mail_date)) !!}</h2>
  <hr>
  <div><span class="fs-xl"><b>Адресат</b></span>: &nbsp;
    <b>{!! $mail[0]->client_fio !!}</b>,
    {!! $mail[0]->gov_name !!},
    {!! $mail[0]->client_spot !!}, &nbsp;
    ({!! $mail[0]->mail_adress !!})
  </div>
  <hr>
  <div><span class="fs-xl"><b>Отправитель</b></span>: &nbsp;
    <b>{!! $mail[0]->users_phone !!}</b>,
    {!! $mail[0]->name !!}, &nbsp;
    ({!! $mail[0]->email !!})
  </div>
  <hr>
  <div class="fs-lg">
    {!! str_replace("\n", '<br>', $mail[0]->mail_body) !!}
  </div>

@endsection
@section('modal')



@endsection

