<?php

namespace App\Http\Controllers\Adm\Tacks;

//use Maatwebsite\Excel\Concerns\FromArray;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class ExelCallExport implements FromView, WithEvents
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }
    /**
     * @return array
     */
    public function registerEvents(): array
    {
      return [
        AfterSheet::class    => function(AfterSheet $event) {
          // ... HERE YOU CAN DO ANY FORMATTING
          $event->sheet->getDelegate()->getStyle('B1:B10000')->getAlignment()->setWrapText(true);
          $event->sheet->getDelegate()->getColumnDimension('A')->setAutoSize(true);
          $event->sheet->getDelegate()->getColumnDimension('B')->setWidth(100);
          $event->sheet->getDelegate()->getColumnDimension('C')->setAutoSize(true);
          $event->sheet->getDelegate()->getColumnDimension('D')->setAutoSize(true);
          $event->sheet->getDelegate()->getColumnDimension('E')->setAutoSize(true);


        },
      ];
    }
    public function view(): View
    {
        return view('admin.tacks.call_list_page_exel', $this->data);
    }
}
