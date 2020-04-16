<?php

namespace App\Http\Controllers\Adm\Mail;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Adm\Email;
use Mail;

class PagesController extends Controller
{
  protected $request;

  public function __construct(Request $request)
  {
    $this->request = $request;
  }

  public function unitMailPage()
  {
    $id = $this->request->input('mail_page_id');
    $mail = Email::getAllEmail($id);
    return view('admin.mail.unit_mail_page', compact([
      'mail'
    ]));
  }

  public function addMailPage()
  {
    return view('admin.mail.add_mail_page', compact([
    ]));
  }
  public function mailPage()
  {
    $list = Email::getAllEmails();
    $i = 1;

    return view('admin.mail.mail_page', compact([
      'list',
      'i'
    ]));
  }

  public function myMailPage()

  { $list = Email::getMyEmails(\Auth::user()->id);
    $i = 1;

    return view('admin.mail.my_mail_page', compact([
      'list',
      'i'
    ]));
  }


  public function delMail()
  {
    $data = $this->request->input();
    Email::delEmails($data);
  }


  public function sendMail()
  {
    $data = $this->request->input();

    shell_exec('echo "'.$data['mail_body'].'" | mail -s "'.$data['mail_title'].'" -a "From: crm-info@hrm-gov.kz" '.$data['mail_adress']);

    return Email::addMail($data);
  }


}
