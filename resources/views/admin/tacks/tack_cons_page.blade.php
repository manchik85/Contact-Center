@extends('layouts.common')
@section('css')

    <link href="{{ asset('/js/plugins/dropzone/css/dropzone.css') }}" rel="stylesheet">

@endsection
@section('js')

    <script src="{{ asset('/js/plugins/dropzone/dropzone.js') }}"></script>

@endsection
@section('title')

    <i class='subheader-icon fal fa-inbox-in'></i> Консультация

@endsection
@section('content')

    <div class="row">

        <div class="col-md-8 sortable-grid ui-sortable">
            <div class="panel panel-sortable" role="widget">
                <div class="panel-hdr" role="heading">
                    <h2><span class="fw-300">Обращение:</span> &nbsp; {!! $task[0]->task_name !!} </h2>
                </div>
                <div class="panel-container show" role="content">
                    <div class="panel-content">

                        {{--                        <div class="row">--}}
                        {{--                            <div class="col-md-2 a_r"> <span class="fw-300">Приоритет:</span> &nbsp; </div>--}}
                        {{--                            <div class="col-md-10">--}}

                        {{--                                @if(@$task[0]->task_priority=='1')--}}
                        {{--                                    <span class="badge badge-danger fw-300">Высокий</span>--}}
                        {{--                                @elseif(@$task[0]->task_priority=='2')--}}
                        {{--                                    <span class="badge badge-success fw-300">Средний</span>--}}
                        {{--                                @else--}}
                        {{--                                    <span class="badge badge-primary fw-300">Низкий</span>--}}
                        {{--                                @endif--}}

                        {{--                            </div>--}}
                        {{--                        </div>--}}
                        {{--                        <div class="px_10"></div>--}}
                        <div class="row">
                            <div class="col-md-2 a_r"><span class="fw-300">Создано:</span> &nbsp;</div>
                            <div class="col-md-10"><b>{!! date("d.m.Y", strtotime($task[0]->created_at)) !!}</b> в
                                <b>{!! date("H:i:s", strtotime($task[0]->created_at)) !!}</b></div>
                        </div>

                        {{--                        <div class="px_10"></div>--}}
                        {{--                        <div class="row">--}}
                        {{--                            <div class="col-md-2 a_r"><span class="fw-300">Решение до:</span> &nbsp;</div>--}}
                        {{--                            <div class="col-md-10"><b>{!! date("d.m.Y", strtotime($task[0]->task_off)) !!}</b></div>--}}
                        {{--                        </div>--}}
                        {{--                        <div class="px_10"></div>--}}
                        {{--                        <div class="row">--}}
                        {{--                            <div class="col-md-2 a_r"><span class="fw-300">Решение:</span> &nbsp;</div>--}}
                        {{--                            <div class="col-md-10">{!! $task[0]->task_term !!} </div>--}}
                        {{--                        </div>--}}


                        @if( count($add)>0 )
                            <hr>
                            @foreach( $add AS $k => $unit)
                                @if( $unit!=null )
                                    <div class="row">
                                        <div class="col-md-2 a_r"><b>{!! $unit['name'] !!}:</b> &nbsp;</div>
                                        <div class="col-md-10">{!! $unit['val'] !!} </div>
                                    </div>
                                    <hr>
                                @endif
                            @endforeach
                        @endif


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

    @if(count($task_files)>0)
        <hr>
        <h3>Файлы:</h3>
        <div class="row margin-0">
            @foreach( $task_files AS $docs )

                <div class="clietn_doks col-xl-1 col-lg-2 col-md-3 col-sm-6" id="{{$docs->id}}">

                    <div class="del_user_file a_l" id="del_{{$docs->id}}" id_file="{{$docs->id}}" title="Удалить файл"
                         data-toggle="modal" data-target="#remove_doc_file" rel="tooltip" >
                        <i aria-hidden="true" class="glyphicon glyphicon-trash"></i>
                    </div>

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
                        {!! $docs->origin_name !!}</a>

                </div>

            @endforeach
        </div>
    @endif

    @if ( in_array('upload_additions', session('getLevels')) )
        <hr>
    <form action="{{route('upload_additions')}}" method="POST"  name="upload_additions" id="upload_additions" class="dropzone needsclick" >
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


@endsection
@section('modal')



@endsection

