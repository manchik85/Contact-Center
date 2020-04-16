<?php

namespace App\Http\Controllers\Adm\Tacks;

//use Maatwebsite\Excel\Concerns\FromArray;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class ExelCancelExport implements FromView, ShouldAutoSize
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function view(): View
    {
        return view('admin.tacks.deal_list_page_exel', $this->data);
    }
}
