<?php

namespace App\Http\Controllers\Adm\Users;

//use Maatwebsite\Excel\Concerns\FromArray;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ExelStatisticExport implements FromView, ShouldAutoSize
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function view(): View
    {

      return view('admin.users.list_operators_statistic_exel', $this->data);
    }
}
