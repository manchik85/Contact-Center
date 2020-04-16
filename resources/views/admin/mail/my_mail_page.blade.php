@extends('layouts.common')
@section('css')

  <link href="{{ asset('js/plugins/datatables/css/datatables.bundle.css') }}" rel="stylesheet" media="screen, print">

@endsection
@section('js')

  <script src="{{ asset('js/admin/add_mail.js') }}"></script>
  <script src="{{ asset('js/plugins/datatables/datatables.bundle.js') }}"></script>
  <script>
    $(document).ready(function () {
      $('#dt-basic-example').dataTable(
        {
          responsive: false,
          paging: false,
          processing: false,
          stateSave: true,
          info: false,
          searching: false,
          columnDefs: [
            {
              title: '',
              orderable: false,
            },
          ]
        });
    });
  </script>

@endsection
@section('title')

  <i class='subheader-icon fal fa-envelope-open'></i> Мои рассылки

@endsection
@section('content')


  <table class="dt-basic-example table table-bordered table-hover table-striped w-100" id="dt-basic-example">
    <thead class="bg-warning-200">
    <tr>
      <th width="1% no_br">#</th>
      <th>Дата</th>
      <th>Тема</th>
      <th>Адресат</th>
      <th>Отправитель</th>
      @if ( in_array('del_mail', session('getLevels')) || in_array('unit_mail_page', session('getLevels'))  )
        <th width="1% no_br"></th>
      @endif
    </tr>
    </thead>
    <tbody>

    @foreach( $list AS $_k=>$_v)
      <tr id="row_{!! $_v->mailing_list_id !!}">
        <td>{!! $i++ !!}</td>
        <td> <h4>{!! $_v->mail_date !!}</h4> </td>
        <td><b>{!! $_v->mail_title !!}</b></td>
        <td><b>{!! $_v->client_fio !!}</b>,  {!! $_v->gov_name !!}, {!! $_v->client_spot !!}, &nbsp; ({!! $_v->mail_adress !!})</td>
        <td><b>{!! $_v->users_phone !!}</b>, {!! $_v->name !!}, &nbsp; ({!! $_v->email !!})</td>
        @if ( in_array('del_mail', session('getLevels')) || in_array('unit_mail_page', session('getLevels'))  )
          <td class="no-br">
            @if( in_array('del_mail', session('getLevels')) )
              <a href="javascript:void(0);"
                 class="btn btn-sm btn-icon btn-outline-danger delete_user rounded-circle mr-1"
                 id-mail="{!! $_v->mailing_list_id !!}"
                 title="Удалить" data-toggle="modal"
                 data-target="#delete-modal">
                <i class="fal fa-times"></i>
              </a>
            @endif
            @if( in_array('unit_mail_page', session('getLevels')) )
              <a href="javascript:void(0);"
                 class="btn btn-sm btn-icon btn-outline-primary look rounded-circle ml-1"
                 id-mail="{!! $_v->mailing_list_id !!}" title="Подробности">
                <i class="fal fa-ellipsis-v"></i>
              </a>
            @endif
          </td>
        @endif
      </tr>
    @endforeach
    </tbody>
  </table>

  <article class="col-sm-12 sortable-grid ui-sortable">
    {{$list->links()}}
  </article>

  @if ( in_array('unit_mail_page', session('getLevels')) )
    <form method="POST" name="mail_page" id="mail_page" action="{{route('unit_mail_page')}}" class="disp_n">
      <input id="mail_page_id" type="hidden" name="mail_page_id" value="">
    </form>
  @endif

@endsection
@section('modal')

  @if( in_array('del_mail', session('getLevels')) )
    <div class="modal fade" id="delete-modal" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">

              <strong>Подтвердите удаление!</strong>
              <small class="m-0 text-muted">
                <input type="hidden" id="mailing_list_id">
              </small>

            </h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true"><i class="fal fa-times"></i></span>
            </button>
          </div>
          <div class="modal-body">

            <strong>Уверены, что это необходимо?</strong>

          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Отмена</button>
            <button type="button" class="btn btn-danger del_user_confirm">Удалить</button>
          </div>
        </div>
      </div>
    </div>
  @endif

@endsection

