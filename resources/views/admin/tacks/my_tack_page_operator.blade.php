@extends('layouts.common')
@section('css')

  <link href="{{ asset('js/plugins/bootstrap-datepicker/css/bootstrap-datepicker.css') }}" rel="stylesheet">
  <link href="{{ asset('/js/plugins/dropzone/css/dropzone.css') }}" rel="stylesheet">

@endsection
@section('js')

  <script src="{{ asset('js/plugins/bootstrap-datepicker/bootstrap-datepicker.js') }}"></script>
  <script src="{{ asset('js/plugins/bootstrap-datepicker/bootstrap-datetimepicker.ru.js') }}"></script>
  <script src="{{ asset('/js/plugins/dropzone/dropzone.js') }}"></script>
  <script src="{{ asset('js/admin/unit_tack.js') }}"></script>

@endsection
@section('title')

  <i class='subheader-icon fal fa-inbox-in'></i> Заявка №{{$task[0]->id}}

@endsection
@section('content')


  @if ( in_array('task_edit', session('getLevels')) )
    <form method="post" action="{{route('task_edit')}}" id="task_edit" name="task_edit">
      <input type="hidden" id="task_id" name="task_id" value="{{$task[0]->id}}">
      <input type="hidden" id="is_close" name="is_close" value="{{$task[0]->is_close}}">
      <input type="hidden" id="complete_id" name="complete_id" value="{{$task[0]->complete}}">
      <input type="hidden" id="client_id" name="client_id" value="{{$task[0]->client_id}}">
      <input type="hidden" id="task_priority" name="task_priority" value="{{$task[0]->task_priority}}">
      <input type="hidden" id="developer_id" name="developer_id" value="{{$task[0]->developer_id}}">
      <div class="row">
        <div class="col-md-12 sortable-grid ui-sortable">
          <div class="panel panel-sortable" role="widget">
            <div class="panel-container show" role="content">
              <div class="panel-content">
                <div class="row margin-0">
                  <div class="col-md-2">
                    <div class="form-group">
                      <label class="form-label" for="task_priority">Приоритет: &nbsp;
                        @if(@$task[0]->task_priority=='1')
                          <span class="badge badge-danger fw-300">Высокий</span>
                        @elseif(@$task[0]->task_priority=='2')
                          <span class="badge badge-success fw-300">Средний</span>
                        @else
                          <span class="badge badge-primary fw-300">Низкий</span>
                        @endif
                      </label>
                      @if(@$task[0]->users_id == Auth::user()->id && $task[0]->is_close != 1)
                        <select class="form-control" name="task_priority" id="task_priority">
                          <option value="1" @if ($task[0]->task_priority==1 ) selected @endif >Высокий</option>
                          <option value="2" @if ($task[0]->task_priority==2 ) selected @endif >Средний</option>
                          <option value="3" @if ($task[0]->task_priority==3 ) selected @endif >Низкий</option>
                        </select>
                      @endif
                    </div>
                  </div>

                  <div class="col-md-2">
                    <div class="form-group">
                      <label class="form-label" for="task_off">Дата решения: <b><span id="old_date">{!! date("d.m.Y", strtotime($task[0]->task_off)) !!}</span></b></label>
                      @if($task[0]->is_close != 1)
                        <div>
                          <span class="btn btn-primary cur_p ch_data" data-toggle="modal" data-target="#name_group_del">Изменить дату решения</span>
                        </div>
                      @endif
                      <input type="hidden" id="task_off" name="task_off" value="{!! date("d.m.Y", strtotime($task[0]->task_off)) !!}">
                    </div>
                  </div>

                  <div class="col-md-2">
                    <div class="form-group">
                      @php
                      if($task[0]->developer_id !== '') {
                        $user = \App\User::find($task[0]->developer_id);
                        if ($user !== null) {
                          $devName = $user->name;
                        } else {
                          $devName = 'Не назначен';
                        }
                      } else {
                        $devName = 'Не назначен';
                      }
                      @endphp
                      <label class="form-label" for="developer_id">Исполнитель: <b><span id="old_date">{!! $devName !!}</span></b></label>
                    </div>
                  </div>

                  <div class="col-md-offset-2">
                    <div class="form-group">
                      <label class="form-label" for="complete">Стадия работы: &nbsp;
                        @if(@$task[0]->complete=='1')
                          <span class="badge badge-primary fw-300">Назначен Исполнитель</span>
                        @elseif(@$task[0]->complete=='2')
                          <span class="badge badge-primary fw-300">Принято в работу</span>
                        @elseif(@$task[0]->complete=='3')
                          <span class="badge badge-primary fw-300">Решено</span>
                        @elseif(@$task[0]->complete=='4')
                          <span class="badge badge-primary fw-300">Не начат</span>
                        @elseif(@$task[0]->complete=='5')
                          <span class="badge badge-primary fw-300">Повторное исполнение</span>
                        @elseif(@$task[0]->complete=='6')
                          <span class="badge badge-primary fw-300">На доработке у Оператора</span>
                        @elseif(@$task[0]->complete=='7')
                          <span class="badge badge-primary fw-300">Консультация</span>
                        @elseif(@$task[0]->complete=='8')
                          <span class="badge badge-primary fw-300">Не повторилась</span>
                        @elseif(@$task[0]->complete=='9')
                          <span class="badge badge-primary fw-300">Не актуальная</span>
                        @endif
                      </label>
                      @if($task[0]->is_close != 1)
                        @if(@$task[0]->users_id == Auth::user()->id && $task[0]->complete == 6)
                          <div>
                            <span class="btn btn-primary cur_p ch_data" data-toggle="modal" data-target="#response_modal">Ответить и вернуть на исполнение</span>
                          </div>
                        @elseif(@$task[0]->users_id == Auth::user()->id && ($task[0]->complete == 3 || $task[0]->complete == 7 ||
                            $task[0]->complete == 8 || $task[0]->complete == 9))
                          <div>
                            <span class="btn btn-primary cur_p ch_data" data-toggle="modal" data-target="#complete_modal">Изменить стадию работы</span>
                          </div>
                        @elseif(@$task[0]->users_id != Auth::user()->id)
                          <div>
                            <span class="btn btn-primary cur_p ch_data" data-toggle="modal" data-target="#complete_modal">Изменить стадию работы</span>
                          </div>
                        @endif
                      @endif

                      <div class="col-md-2">
                        <div class="form-group">
                          <label class="form-label"> &nbsp; </label>
                          <div>
                            <button style="display: none" class="btn btn-primary cur_p task-edit-but">Отправить</button>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>

                  @if(@$task[0]->users_id == Auth::user()->id)
                  <div class="col-md-offset-1" style="position: absolute; right: 12px">
                    <div>
                      @if($task[0]->is_close != 1)
                        <label class="form-label"> &nbsp; </label>
                        <div>
                          <button type="button" class="btn btn-danger task-close-but">Закрыть заявку</button>
                        </div>
                      @else
                        <span class="badge badge-info fw-600">Заявка закрыта</span>
                      @endif
                    </div>
                  </div>
                  @endif
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </form>
  @endif

  @if(in_array('task_edit_developer', session('getLevels')))
    @if(@$task[0]->developer_id == Auth::user()->id)
      <form method="post" action="{{route('task_edit_developer')}}" id="task_edit_developer" name="task_edit_developer">
        <input type="hidden" id="task_id"   name="task_id"   value="{{$task[0]->id}}">
        <input type="hidden" id="complete_id" name="complete_id" value="{{$task[0]->complete}}">
        <input type="hidden" id="client_id" name="client_id" value="{{$task[0]->client_id}}">
        <input type="hidden" id="task_priority" name="task_priority" value="{{$task[0]->task_priority}}">
        <div class="row">
          <div class="col-md-12 sortable-grid ui-sortable">
            <div class="panel panel-sortable" role="widget">
              <div class="panel-container show" role="content">
                <div class="panel-content">
                  <div class="row margin-0">
                    <div class="col-md-2">
                      <div class="form-group">
                        <label class="form-label" for="task_priority">Приоритет: &nbsp;
                          @if(@$task[0]->task_priority=='1')
                            <span class="badge badge-danger fw-300">Высокий</span>
                          @elseif(@$task[0]->task_priority=='2')
                            <span class="badge badge-success fw-300">Средний</span>
                          @else
                            <span class="badge badge-primary fw-300">Низкий</span>
                          @endif
                        </label>
                      </div>
                    </div>
                    <div class="col-md-2">
                      <div class="form-group">
                        <label class="form-label" for="task_off">Дата решения: <b><span id="old_date">{!! date("d.m.Y", strtotime($task[0]->task_off)) !!}</span></b></label>
                        @if($task[0]->is_close != 1)
                        <div>
                          <span class="btn btn-primary cur_p ch_data" data-toggle="modal" data-target="#name_group_del">Изменить дату решения</span>
                        </div>
                        @endif
                        <input type="hidden" id="task_off" name="task_off" value="{!! date("d.m.Y", strtotime($task[0]->task_off)) !!}">
                      </div>
                    </div>
                    <div class="col-md-offset-2">
                      <div class="form-group">
                        <label class="form-label" for="task_priority">Стадия работы: &nbsp;
                          @if(@$task[0]->complete=='1')
                            <span class="badge badge-primary fw-300">Назначен Исполнитель</span>
                          @elseif(@$task[0]->complete=='2')
                            <span class="badge badge-primary fw-300">Принято в работу</span>
                          @elseif(@$task[0]->complete=='3')
                            <span class="badge badge-primary fw-300">Решено</span>
                          @elseif(@$task[0]->complete=='4')
                            <span class="badge badge-primary fw-300">Не начат</span>
                          @elseif(@$task[0]->complete=='5')
                            <span class="badge badge-primary fw-300">Повторное исполнение</span>
                          @elseif(@$task[0]->complete=='6')
                            <span class="badge badge-primary fw-300">На доработке у Оператора</span>
                          @elseif(@$task[0]->complete=='7')
                            <span class="badge badge-primary fw-300">Консультация</span>
                          @elseif(@$task[0]->complete=='8')
                            <span class="badge badge-primary fw-300">Не повторилась</span>
                          @elseif(@$task[0]->complete=='9')
                            <span class="badge badge-primary fw-300">Не актуальная</span>
                          @endif
                        </label>
                        @if($task[0]->is_close != 1)
                        <div>
                          <span class="btn btn-primary cur_p ch_data" data-toggle="modal" data-target="#complete_modal">Изменить стадию работы</span>
                        </div>
                        @endif
                      </div>
                    </div>

                    <div class="col-md-2">
                      <div class="form-group">
                        <label class="form-label"> &nbsp; </label>
                        <div>
                          <button style="display: none" class="btn btn-primary cur_p task-edit-but">Отправить</button>
                        </div>
                      </div>
                    </div>

                    <div class="col-md-offset-1" style="position: absolute; right: 12px">
                      <div>
                        @if($task[0]->is_close == 1)
                          <span class="badge badge-info fw-600">Заявка закрыта</span>
                        @endif
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </form>
    @endif
  @endif

  <div class="row">
    <div class="col-md-8 sortable-grid ui-sortable">
      <div class="panel panel-sortable" role="widget">
        <div class="panel-hdr" role="heading">
          <h2><span class="fw-300">Обращение:</span> &nbsp; {!! $task[0]->task_name !!} </h2>
        </div>
        <div class="panel-container show" role="content">
          <div class="panel-content">
            <div class="row">
              <div class="col-md-2 a_r"><span class="fw-300">Создано:</span> &nbsp;</div>
              <div class="col-md-10"><b>{!! date("d.m.Y", strtotime($task[0]->created_at)) !!}</b> в
                <b>{!! date("H:i:s", strtotime($task[0]->created_at)) !!}</b>
              </div>
            </div>
            @if( count($add)>0 )
              @foreach( $add AS $k => $unit)
                @if( $unit!=null )
                  <hr>
                  <div class="row">
                    <div class="col-md-2 a_r"><b>{!! $unit['name'] !!}:</b> &nbsp;</div>
                    <div class="col-md-10">{!! $unit['val'] !!} </div>
                  </div>
                @endif
              @endforeach
            @endif
            <div class="px_10"></div>
          </div>
        </div>
      </div>
    </div>
    <div class="col-md-4 sortable-grid ui-sortable pad_0">
      <div class="panel panel-sortable" role="widget">
        <div class="panel-hdr" role="heading">
          <h2><span class="fw-300">Клиент:</span> &nbsp; {!! $task[0]->client_fio !!} </h2>
        </div>
        <div class="panel-container show" role="content">
          <div class="panel-content">
            <div class="row">
              <div class="col-md-4 a_r"><span class="fw-300">Гос. орган:</span> &nbsp;</div>
              <div class="col-md-8"><b>{!! $task[0]->gov_name !!}</b></div>
            </div>
            <div class="px_10"></div>
            <div class="row">
              <div class="col-md-4 a_r"><span class="fw-300">Должность:</span> &nbsp;</div>
              <div class="col-md-8"><b>{!! $task[0]->client_spot !!}</b></div>
            </div>
            <hr>

            <div class="row">
              <div class="col-md-4 a_r"><span class="fw-300">Логин:</span> &nbsp;</div>
              <div class="col-md-8"><b>{!! $task[0]->client_login !!}</b></div>
            </div>
            <div class="px_10"></div>
            <div class="row">
              <div class="col-md-4 a_r"><span class="fw-300">Пароль:</span> &nbsp;</div>
              <div class="col-md-8"><b>{!! $task[0]->client_pass !!}</b></div>
            </div>
            <div class="px_10"></div>
            <div class="row">
              <div class="col-md-4 a_r"><span class="fw-300">Эл. почта:</span> &nbsp;</div>
              <div class="col-md-8"><b>{!! $task[0]->client_mail !!}</b></div>
            </div>
            <div class="px_10"></div>
            <div class="row">
              <div class="col-md-4 a_r"><span class="fw-300">Телефон:</span> &nbsp;</div>
              <div class="col-md-8"><b>{!! $task[0]->client_phone !!}</b></div>
            </div>

          </div>
        </div>
      </div>
    </div>
  </div>

  <task-comments :data="{{ json_encode([
    'users_id' => Auth::user()->id ,
    'task_id'  => $task[0]->id,
    'username'  => Auth::user()->name,
    'status'  => Auth::user()->status,
    'date'  => Date('Y-d-m H:i:s')
    ]) }} "></task-comments>

  @if(count($task_files)>0)
    <hr>
    <h3>Файлы:</h3>
    <div class="row margin-0">
      @foreach( $task_files AS $docs )

        <div class="clietn_doks col-xl-1 col-lg-2 col-md-3 col-sm-6" id="{{$docs->id}}">

          <div class="del_user_file a_l" id="del_{{$docs->id}}" id_file="{{$docs->id}}" title="Удалить файл"
               data-toggle="modal" data-target="#remove_doc_file" rel="tooltip">
            <i aria-hidden="true" class="glyphicon glyphicon-trash"></i>
          </div>

          @php
            if (mb_strlen($docs->origin_name, 'UTF-8') > 16) {
                $originName = mb_substr($docs->origin_name, 0, 15, 'UTF-8').'...';
            } else{
                $originName = $docs->origin_name;
            }
          @endphp

          <a href="/clientsdata/{{$task[0]->client_id}}/{{$task[0]->id}}/{{$docs->file_name}}" target="_blank">
            @if( $docs->resol == 'docx' || $docs->resol == 'doc' || $docs->resol == 'rtf' )
              <div class="clb pad_10"><img src="/clientsdata/doc.svg" alt="doc"></div>
            @elseif( $docs->resol == 'jpg' || $docs->resol == 'jpeg' || $docs->resol == 'JPEG'|| $docs->resol == 'JPG' )
              <div class="clb pad_10"><img src="/clientsdata/jpg.svg" alt="doc"></div>
            @elseif( $docs->resol == 'png' || $docs->resol == 'PNG' )
              <div class="clb pad_10"><img src="/clientsdata/png.svg" alt="doc"></div>
            @elseif( $docs->resol == 'xls' || $docs->resol == 'xlsx' || $docs->resol == 'XLS'|| $docs->resol == 'XLSX' )
              <div class="clb pad_10"><img src="/clientsdata/xls.svg" alt="doc"></div>
            @elseif( $docs->resol == 'pdf' || $docs->resol == 'PDF' )
              <div class="clb pad_10"><img src="/clientsdata/pdf.svg" alt="doc"></div>
            @elseif( $docs->resol == 'ppt' || $docs->resol == 'pptx' || $docs->resol == 'PPT'|| $docs->resol == 'PPTX' )
              <div class="clb pad_10"><img src="/clientsdata/ppt.svg" alt="doc"></div>
            @else
              <div class="clb pad_10"><img src="/clientsdata/uni.svg" alt="doc"></div>
            @endif
            {!! $originName !!}</a>

        </div>

      @endforeach
    </div>
  @endif

  @if ( in_array('upload_additions', session('getLevels')) )
    <hr>
    <form action="{{route('upload_additions')}}" method="POST" name="upload_additions" id="upload_additions"
          class="dropzone needsclick">
      <input type="hidden" name="id_client" value="{{ $task[0]->client_id }}">
      <input type="hidden" name="id_task" value="{{ $task[0]->id }}">
      <div class="fallback"><input name="file" type="file" multiple></div>
      <div class="dz-message needsclick">
        <i class="fal fa-cloud-upload text-muted mb-3"></i> <br>
        <span class="text-uppercase">Загрузка Вложений.</span>
        <br>
        <span class="fs-sm text-muted">Документы и/или рисунки </span>
      </div>
    </form>
  @endif

  @if ( in_array('my_tack_page_operator', session('getLevels')) )
    <hr>
    <form action="{{route('my_tack_page_operator')}}" method="POST" name="task-response" id="task-response">
      <input type="hidden" name="task_operator_page_id" value="{{ $task[0]->id }}">

      <button style="display: none" class="btn btn-primary cur_p task-response-but">Отправить</button>
    </form>
  @endif

  @if ( in_array('close_tack_operator', session('getLevels')) )
    <hr>
    <form method="post" action="{{route('close_tack_operator')}}" name="close_tack_operator" id="close_tack_operator">
      <input type="hidden" id="task_close_id" name="task_close_id" value="{{ $task[0]->id }}">
      <input type="hidden" id="task_is_close" name="task_is_close" value="{{$task[0]->is_close}}">

      <button style="display: none" class="btn btn-primary cur_p task-complete-close-but">Закрыть</button>
    </form>
  @endif


@endsection
@section('modal')

  <div class="modal fade" id="name_group_del" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">
            <strong>Изменение даты</strong>
            <small class="m-0 text-muted">
              <input type="hidden" id="name_group_del_id">
            </small>
          </h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true"><i class="fal fa-times"></i></span>
          </button>
        </div>
        <div class="modal-body">

          <div class="form-group">
            <label class="form-label" for="task_off_form">Предположительная дата решения*:</label>
            <input type="text" class="form-control" id="task_off_form" value="{!! date("d.m.Y", strtotime($task[0]->task_off)) !!}">
          </div>
          <div class="form-group">
            <label class="form-label" for="justification">Обоснование*:</label>
            <textarea class="form-control" id="justification" name="justification" ></textarea>
          </div>

          <input type="hidden" id="users_id" name="users_id" value="{!! Auth::user()->id !!}">
          <input type="hidden" id="users_name" name="users_name" value="{!! Auth::user()->name !!}">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary ch_date_confirm">Изменить</button>
        </div>
      </div>
    </div>
  </div>


  <div class="modal fade" id="complete_modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h4 class="modal-title">
            <strong>Изменение стадии работы</strong>
            <small class="m-0 text-muted">
              <input type="hidden" id="complete_modal_id">
            </small>
          </h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true"><i class="fal fa-times"></i></span>
          </button>
        </div>
        <div class="modal-body">

          <div class="form-group">
            <label class="form-label" for="complete">Стадия работы<span class="red">*</span>:</label>
            <select class="form-control" name="complete" id="complete">
              @if(@$task[0]->users_id == Auth::user()->id)
                  {{--<option value="4" @if ($task[0]->complete==4 ) selected @endif >Не начат</option>--}}
                  <option value="5" @if ($task[0]->complete==5 ) selected @endif >Повторное исполнение</option>
              @elseif(Auth::user()->status == 11)
                  <option value="6" @if ($task[0]->complete==6 ) selected @endif >На доработке у Оператора</option>
                  <option value="1" @if ($task[0]->complete==1 ) selected @endif >Назначен Исполнитель</option>
                  <option value="3" @if ($task[0]->complete==3 ) selected @endif >Решено</option>
                  <option value="7" @if ($task[0]->complete==7 ) selected @endif >Консультация</option>
                  <option value="8" @if ($task[0]->complete==8 ) selected @endif >Не повторилась</option>
                  <option value="9" @if ($task[0]->complete==9 ) selected @endif >Не актуальная</option>
              @elseif(@$task[0]->developer_id == Auth::user()->id)
                  <option value="6" @if ($task[0]->complete==6 ) selected @endif >На доработке у Оператора</option>
                  <option value="2" @if ($task[0]->complete==2 ) selected @endif >Принято в работу</option>
                  <option value="3" @if ($task[0]->complete==3 ) selected @endif >Решено</option>
                  <option value="7" @if ($task[0]->complete==7 ) selected @endif >Консультация</option>
                  <option value="8" @if ($task[0]->complete==8 ) selected @endif >Не повторилась</option>
                  <option value="9" @if ($task[0]->complete==9 ) selected @endif >Не актуальная</option>
              @endif
            </select>
          </div>

          <div class="form-group" id="developer_div" style="display: none">
            <label class="form-label" for="developer">Исполнитель<span class="red">*</span>:</label>
            <select class="form-control" name="developer" id="developer" @if(Auth::user()->status < 6) disabled @endif>
              <option value=""></option>
              @if( count($developers)>0 )
                @foreach($developers AS $developer)
                  <option value="{{ $developer->id }}"
                          @if($task[0]->developer_id==$developer->id) selected @endif>
                    {{$developer->name}}
                  </option>
                @endforeach
              @endif
            </select>
          </div>

          <div class="form-group" id="comment_div">
            <label class="form-label" for="comment_complete">Комментарий<span class="red">*</span>:</label>
            <textarea class="form-control" id="comment_complete" name="comment_complete" ></textarea>
          </div>

          <input type="hidden" id="users_id" name="users_id" value="{!! Auth::user()->id !!}">
          <input type="hidden" id="users_name" name="users_name" value="{!! Auth::user()->name !!}">
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-primary ch_complete_confirm">Изменить</button>
        </div>
      </div>
    </div>
  </div>

  <div class="modal fade" id="response_modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <input type="hidden" id="response_users_id" name="response_users_id" value="{!! Auth::user()->id !!}">
        <input type="hidden" id="response_users_name" name="response_users_name" value="{!! Auth::user()->name !!}">

        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title">
              <strong>Ответ</strong>
              <small class="m-0 text-muted">
                <input type="hidden" id="response_modal_id">
              </small>
            </h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true"><i class="fal fa-times"></i></span>
            </button>
          </div>
          <div class="modal-body">
            <div class="form-group" id="comment_div">
              <label class="form-label" for="comment_response">Комментарий<span class="red">*</span>:</label>
              <textarea class="form-control" id="comment_response" name="comment_response" ></textarea>
            </div>
          </div>

          <div class="modal-footer">
            <button type="button" class="btn btn-primary ch_response_confirm">Изменить</button>
          </div>
        </div>
    </div>
  </div>

@endsection
