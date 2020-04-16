@extends('layouts.common')
@section('css')


@endsection
@section('js')

  <script src="{{ asset('js/admin/edit_form.js') }}"></script>

@endsection
@section('title')

  <i class='subheader-icon fal fa-inbox-in'></i> Редактировать форму

@endsection
@section('content')


  <div class="row">
    <div class="col-md-6 sortable-grid ui-sortable">

      <div class="panel panel-sortable" role="widget">
        <div class="panel-hdr" role="heading">
          <h2>Гос. органы</h2>
        </div>
        <div class="panel-container show" role="content">
          <div class="panel-content pad_0">

            <table class="table m-0 table-striped table-hover  " id="table-example">
              <tbody>

              @foreach($list AS $unit)
                <tr id="gov_{{ $unit->id }}">
                  <td>{{ $unit->gov_name }}</td>
                  <td>{{ $unit->gov_mail }}</td>
                  <td class="a_r">

                    @if ( in_array('gov_del', session('getLevels')) )
                      <a href="javascript:void(0);"
                         class="btn btn-sm btn-icon btn-outline-danger delete_gov rounded-circle mr-1"
                         id-gov="{{ $unit->id }}" data-toggle="modal" data-target="#delete-modal">
                        <i class="fal fa-times"></i>
                      </a>
                    @endif

                  </td>
                </tr>
              @endforeach
              </tbody>
            </table>
            <div class="px_10"></div>
            <article class="col-sm-12 sortable-grid ui-sortable">
              {{$list->links()}}
            </article>
            @if ( in_array('gov_add', session('getLevels')) )
              <add-gov></add-gov>
            @endif

          </div>
        </div>
      </div>
    </div>
    <div class="col-md-6 sortable-grid ui-sortable">

      <div class="panel panel-sortable" role="widget">
        <div class="panel-hdr" role="heading">
          <h2>Дополнительные поля в форме заявки</h2>
        </div>
        <div class="panel-container show" role="content">
          <div class="panel-content pad_0">

            <table class="table m-0 table-striped table-hover  " id="table-example">
              <tbody>

              @foreach($form AS $unit_f)
                <tr id="form_{{ $unit_f->id }}">
                  <td>{{ $unit_f->form_name }}</td>
                  <td>{{ $unit_f->form_alias }}</td>
                  <td class="a_r">

                    @if ( in_array('form_del', session('getLevels')) )
                      <a href="javascript:void(0);"
                         class="btn btn-sm btn-icon btn-outline-danger delete_form rounded-circle mr-1"
                         id-form="{{ $unit_f->id }}" data-toggle="modal" data-target="#delete-modal-form">
                        <i class="fal fa-times"></i>
                      </a>
                    @endif

                  </td>
                </tr>
              @endforeach
              </tbody>
            </table>

            @if ( in_array('form_add', session('getLevels')) )
              <add-form></add-form>
            @endif

          </div>
        </div>
      </div>

      <div class="panel panel-sortable" role="widget">
        <div class="panel-hdr" role="heading">
          <h2>Основные группы пользователей</h2>
        </div>
        <div class="panel-container show" role="content">
          <div class="panel-content pad_0">

            <table class="table m-0 table-striped table-hover  " id="table-example">
              <tbody>

              @foreach($gov AS $unit_f)
                <tr id="role_{{ $unit_f->gov_group_id }}">
                  <td>{{ $unit_f->gov_group_name }}</td>
                  <td class="a_r">

                    @if ( in_array('gov_group_del', session('getLevels')) )
                      <a href="javascript:void(0);"
                         class="btn btn-sm btn-icon btn-outline-danger delete_gov_role_form rounded-circle mr-1"
                         id-form="{{ $unit_f->gov_group_id }}" data-toggle="modal" data-target="#delete_gov_group_del">
                        <i class="fal fa-times"></i>
                      </a>
                    @endif

                  </td>
                </tr>
              @endforeach
              </tbody>
            </table>

            @if ( in_array('gov_group_add', session('getLevels')) )
              <add-gov-role-form></add-gov-role-form>
            @endif


          </div>
        </div>
      </div>

      <div class="panel panel-sortable" role="widget">
        <div class="panel-hdr" role="heading">
          <h2>Наименование процесса</h2>
        </div>
        <div class="panel-container show" role="content">
          <div class="panel-content pad_0">

            <table class="table m-0 table-striped table-hover  " id="table-example">
              <tbody>

              @foreach($p_name AS $unit_f)
                <tr id="name_{{ $unit_f->id }}">
                  <td>{{ $unit_f->process }}</td>
                  <td class="a_r">

                    @if ( in_array('name_group_del', session('getLevels')) )
                      <a href="javascript:void(0);"
                         class="btn btn-sm btn-icon btn-outline-danger name_group_del rounded-circle mr-1"
                         id-name="{{ $unit_f->id }}" data-toggle="modal" data-target="#name_group_del">
                        <i class="fal fa-times"></i>
                      </a>
                    @endif

                  </td>
                </tr>
              @endforeach
              </tbody>
            </table>

            <div class="px_10"></div>
            <article class="col-sm-12 sortable-grid ui-sortable">
              {{$p_name->links()}}
            </article>

          @if ( in_array('gov_group_add', session('getLevels')) )
              <add-name></add-name>
            @endif


          </div>
        </div>
      </div>



      <div class="panel panel-sortable" role="widget">
        <div class="panel-hdr" role="heading">
          <h2>Приоритет - дни</h2>
        </div>
        <div class="panel-container show" role="content">
          <div class="panel-content pad_0">

            <prior-days :data="{{ json_encode($prior) }}"></prior-days>

          </div>
        </div>
      </div>


    </div>


  </div>


@endsection
@section('modal')

  @if ( in_array('gov_group_del', session('getLevels')) )
    <div class="modal fade" id="delete_gov_group_del" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">
              <strong>Подтвердите удаление!</strong>
              <small class="m-0 text-muted">
                <span>Группа пользователей будет удалена.</span>
                <input type="hidden" id="gov_group_delete_id">
              </small>
            </h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true"><i class="fal fa-times"></i></span>
            </button>
          </div>
          <div class="modal-body">
            Все Обращения, в коготых фигурирует Группа пользователей, сохранятся!
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Отмена</button>
            <button type="button" class="btn btn-danger del_gov_group_confirm">Удалить</button>
          </div>
        </div>
      </div>
    </div>
  @endif


  @if ( in_array('gov_del', session('getLevels')) )
    <div class="modal fade" id="delete-modal" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">
              <strong>Подтвердите удаление!</strong>
              <small class="m-0 text-muted">
                <span>Название Гос. органа будет удалено.</span>
                <input type="hidden" id="gov_delete_id">
              </small>
            </h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true"><i class="fal fa-times"></i></span>
            </button>
          </div>
          <div class="modal-body">
            Все Обращения, в коготых фигурирует Гос. орган, сохранятся!
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Отмена</button>
            <button type="button" class="btn btn-danger del_gov_confirm">Удалить</button>
          </div>
        </div>
      </div>
    </div>
  @endif


  @if ( in_array('form_del', session('getLevels')) )
    <div class="modal fade" id="delete-modal-form" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">
              <strong>Подтвердите удаление!</strong>
              <small class="m-0 text-muted">

                <input type="hidden" id="form_delete_id">
              </small>
            </h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true"><i class="fal fa-times"></i></span>
            </button>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Отмена</button>
            <button type="button" class="btn btn-danger del_form_confirm">Удалить</button>
          </div>
        </div>
      </div>
    </div>
  @endif


  @if ( in_array('name_group_del', session('getLevels')) )
    <div class="modal fade" id="name_group_del" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">
              <strong>Подтвердите удаление!</strong>
              <small class="m-0 text-muted">
                <input type="hidden" id="name_group_del_id">
              </small>
            </h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true"><i class="fal fa-times"></i></span>
            </button>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Отмена</button>
            <button type="button" class="btn btn-danger name_group_del_confirm">Удалить</button>
          </div>
        </div>
      </div>
    </div>
  @endif



@endsection

