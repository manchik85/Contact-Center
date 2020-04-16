<?php

namespace App\Http\Controllers\Adm\Notice;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Adm\Notice;
use Cache;

class PagesController extends Controller
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function noticeUserPage() {
        $i = 1;
        $user_id = auth()->id();

        // start
        if ($this->request->input('date_notice_start') && $this->request->input('date_notice_start') != '') {
            $date_notice_start = $this->request->input('date_notice_start');
            Cache::forget('date_notice_start' . auth()->id());
            Cache::forever('date_notice_start' . auth()->id(), $date_notice_start);
        } else {
            $date_notice_start = '';
            Cache::forget('date_notice_start' . auth()->id());
            Cache::forever('date_notice_start' . auth()->id(), $date_notice_start);
        }

        // end
        if ($this->request->input('date_notice_end') && $this->request->input('date_notice_end') != '') {
            $date_notice_end = $this->request->input('date_notice_end');
            Cache::forget('date_notice_end' . auth()->id());
            Cache::forever('date_notice_end' . auth()->id(), $date_notice_end);
        } else {
            $date_notice_end = '';
            Cache::forget('date_notice_end' . auth()->id());
            Cache::forever('date_notice_end' . auth()->id(), $date_notice_end);
        }

        // read-notice
        if ($this->request->input('read-notice') && $this->request->input('read-notice') != '') {
            $readNotice = $this->request->input('read-notice');
            Cache::forget('read-notice' . auth()->id());
            Cache::forever('read-notice' . auth()->id(), $readNotice);
        } else {
            //только те с которыми еще не ознакомился
            $readNotice = 0;
            Cache::forget('read-notice' . auth()->id());
            Cache::forever('read-notice' . auth()->id(), $readNotice);
        }

        $start = $date_notice_start;
        $end = $date_notice_end;

        //теперь отбираем сами уведомления
        $notice_arr = Notice::getNoticeByParam($user_id, $readNotice, $date_notice_start, $date_notice_end);

        return view('admin.notice.notice_user_page', compact([
            'i',
            'user_id',
            'start',
            'end',
            'date_notice_start',
            'date_notice_end',
            'readNotice',
            'notice_arr'
        ]));
    }



}
