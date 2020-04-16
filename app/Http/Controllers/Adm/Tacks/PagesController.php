<?php

namespace App\Http\Controllers\Adm\Tacks;

use App\Http\Controllers\Controller;
use App\Models\Adm\Notice;
use App\Models\Adm\Users;
use App\User;
use Auth;
use Cache;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Events\chenTaskUser;
use App\Models\Adm\Tacks;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Mail;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use App\Models\Adm\Region;

class PagesController extends Controller
{
    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function getHeadTableStyle() {
        //Устанавливаем стиль ячейки для полужирного шрифта,
        // выравнивания по центру и переносом текста в ячейках
        $styleArray = [
            'font' => [
                'bold' => true,
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'wrapText' => true,
            ],
        ];

        return $styleArray;
    }

    public function consList()
    {

        // start
        if ($this->request->input('date_task_start') && $this->request->input('date_task_start') != '') {
            $date_task_start = $this->request->input('date_task_start');
            Cache::forget('date_task_start' . auth()->id());
            Cache::forever('date_task_start' . auth()->id(), $date_task_start);
        } else if (Cache::has('date_task_start' . auth()->id()) && $this->request->input('page') != '') {
            $date_task_start = Cache::get('date_task_start');
        } else {
            $date_task_start = '';
            Cache::forget('date_task_start' . auth()->id());
            Cache::forever('date_task_start' . auth()->id(), $date_task_start);
        }

        // end
        if ($this->request->input('date_task_end') && $this->request->input('date_task_end') != '') {
            $date_task_end = $this->request->input('date_task_end');
            Cache::forget('date_task_end' . auth()->id());
            Cache::forever('date_task_end' . auth()->id(), $date_task_end);
        } else if (Cache::has('date_task_end' . auth()->id()) && $this->request->input('page') != '') {
            $date_task_end = Cache::get('date_task_end' . auth()->id());
        } else {
            $date_task_end = '';
            Cache::forget('date_task_end' . auth()->id());
            Cache::forever('date_task_end' . auth()->id(), $date_task_end);
        }
        $start = $date_task_start;
        $end = $date_task_end;

        // start
        if ($this->request->input('date_complete_start') && $this->request->input('date_complete_start') != '') {
            $date_complete_start = $this->request->input('date_complete_start');
            Cache::forget('date_complete_start' . auth()->id());
            Cache::forever('date_complete_start' . auth()->id(), $date_complete_start);
        } else if (Cache::has('date_complete_start' . auth()->id()) && $this->request->input('page') != '') {
            $date_complete_start = Cache::get('date_complete_start' . auth()->id());
        } else {
            $date_complete_start = '';
            Cache::forget('date_complete_start' . auth()->id());
            Cache::forever('date_complete_start' . auth()->id(), $date_complete_start);
        }

        // end
        if ($this->request->input('date_complete_end') && $this->request->input('date_complete_end') == '') {
            $date_complete_end = $this->request->input('date_complete_end');
            Cache::forget('date_complete_end' . auth()->id());
            Cache::forever('date_complete_end' . auth()->id(), $date_complete_end);
        } else if (Cache::has('date_complete_end' . auth()->id()) && $this->request->input('page') != '') {
            $date_complete_end = Cache::get('date_complete_end' . auth()->id());
        } else {
            $date_complete_end = '';
            Cache::forget('date_complete_end' . auth()->id());
            Cache::forever('date_complete_end' . auth()->id(), $date_complete_end);
        }
        $off_start = $date_complete_start;
        $off_end = $date_complete_end;

        // priority
        if ($this->request->input('priority') && $this->request->input('priority') != '') {
            $priority = $this->request->input('priority');
            Cache::forget('priority' . auth()->id());
            Cache::forever('priority' . auth()->id(), $priority);
        } else if (Cache::has('priority' . auth()->id()) && $this->request->input('page') != '') {
            $priority = Cache::get('priority' . auth()->id());
        } else {
            $priority = '';
            Cache::forget('priority' . auth()->id());
            Cache::forever('priority' . auth()->id(), $priority);
        }

        // Должность
        if ($this->request->input('client_spot') && $this->request->input('client_spot') != '') {
            $client_spot = $this->request->input('client_spot');
            Cache::forget('client_spot' . auth()->id());
            Cache::forever('client_spot' . auth()->id(), $client_spot);
        } else if (Cache::has('client_spot' . auth()->id()) && $this->request->input('page') != '') {
            $client_spot = Cache::get('client_spot' . auth()->id());
        } else {
            $client_spot = '';
            Cache::forget('client_spot' . auth()->id());
            Cache::forever('client_spot' . auth()->id(), $client_spot);
        }

        // Фио
        if ($this->request->input('client_fio') && $this->request->input('client_fio') != '') {
            $client_fio = $this->request->input('client_fio');
            Cache::forget('client_fio' . auth()->id());
            Cache::forever('client_fio' . auth()->id(), $client_fio);
        } else if (Cache::has('client_fio' . auth()->id() . auth()->id()) && $this->request->input('page') != '') {
            $client_fio = Cache::get('client_fio' . auth()->id());
        } else {
            $client_fio = '';
            Cache::forget('client_fio' . auth()->id());
            Cache::forever('client_fio' . auth()->id(), $client_fio);
        }

        // Логин
        if ($this->request->input('client_login') && $this->request->input('client_login') != '') {
            $client_login = $this->request->input('client_login');
            Cache::forget('client_login' . auth()->id());
            Cache::forever('client_login' . auth()->id(), $client_login);
        } else if (Cache::has('client_login' . auth()->id()) && $this->request->input('page') != '') {
            $client_login = Cache::get('client_login' . auth()->id());
        } else {
            $client_login = '';
            Cache::forget('client_login' . auth()->id());
            Cache::forever('client_login' . auth()->id(), $client_login);
        }

        // Телефон
        if ($this->request->input('client_phone') && $this->request->input('client_phone') != '') {
            $client_phone = $this->request->input('client_phone');
            Cache::forget('client_phone' . auth()->id());
            Cache::forever('client_phone' . auth()->id(), $client_phone);
        } else if (Cache::has('client_phone' . auth()->id()) && $this->request->input('page') != '') {
            $client_phone = Cache::get('client_phone' . auth()->id());
        } else {
            $client_phone = '';
            Cache::forget('client_phone' . auth()->id());
            Cache::forever('client_phone' . auth()->id(), $client_phone);
        }

        // Телефон
        if ($this->request->input('client_mail') && $this->request->input('client_mail') != '') {
            $client_mail = $this->request->input('client_mail');
            Cache::forget('client_mail' . auth()->id());
            Cache::forever('client_mail' . auth()->id(), $client_mail);
        } else if (Cache::has('client_mail' . auth()->id()) && $this->request->input('page') != '') {
            $client_mail = Cache::get('client_mail' . auth()->id());
        } else {
            $client_mail = '';
            Cache::forget('client_mail' . auth()->id());
            Cache::forever('client_mail' . auth()->id(), $client_mail);
        }

        // Гос. орган
        if ($this->request->input('gov_name') && $this->request->input('gov_name') != '') {
            $gov_name = $this->request->input('gov_name');
            Cache::forget('gov_name' . auth()->id());
            Cache::forever('gov_name' . auth()->id(), $gov_name);
        } else if (Cache::has('gov_name' . auth()->id()) && $this->request->input('page') != '') {
            $gov_name = Cache::get('gov_name' . auth()->id());
        } else {
            $gov_name = '';
            Cache::forget('gov_name' . auth()->id());
            Cache::forever('gov_name' . auth()->id(), $gov_name);
        }

        // Наименование
        if ($this->request->input('process_name') && $this->request->input('process_name') != '') {
            $process_name = $this->request->input('process_name');
            Cache::forget('process_name' . auth()->id());
            Cache::forever('process_name' . auth()->id(), $process_name);
        } else if (Cache::has('process_name' . auth()->id()) && $this->request->input('page') != '') {
            $process_name = Cache::get('process_name' . auth()->id());
        } else {
            $process_name = '';
            Cache::forget('process_name' . auth()->id());
            Cache::forever('process_name' . auth()->id(), $process_name);
        }

        // Оператор
        if ($this->request->input('operator') && $this->request->input('operator') != '') {
            $operator = $this->request->input('operator');
            Cache::forget('operator' . auth()->id());
            Cache::forever('operator' . auth()->id(), $operator);
        } else if (Cache::has('operator' . auth()->id()) && $this->request->input('page') != '') {
            $operator = Cache::get('operator' . auth()->id());
        } else {
            $operator = '';
            Cache::forget('operator' . auth()->id());
            Cache::forever('operator' . auth()->id(), $operator);
        }

        // task_id
        if ($this->request->input('task_id') && $this->request->input('task_id') != '') {
            $task_id = $this->request->input('task_id');
            Cache::forget('task_id' . auth()->id());
            Cache::forever('task_id' . auth()->id(), $task_id);
        } else if (Cache::has('task_id' . auth()->id()) && $this->request->input('page') != '') {
            $task_id = Cache::get('task_id' . auth()->id());
        } else {
            $task_id = '';
            Cache::forget('task_id' . auth()->id());
            Cache::forever('task_id' . auth()->id(), $task_id);
        }

        // Регион
        if ($this->request->input('task_district') && $this->request->input('task_district') != '') {
            $task_district = $this->request->input('task_district');
            Cache::forget('task_district' . auth()->id());
            Cache::forever('task_district' . auth()->id(), $task_district);
        } else if (Cache::has('task_district' . auth()->id()) && $this->request->input('page') != '') {
            $task_district = Cache::get('task_district' . auth()->id());
        } else {
            $task_district = '';
            Cache::forget('task_id' . auth()->id());
            Cache::forever('task_id' . auth()->id(), $task_district);
        }

        $start_a = explode('.', $date_task_start);
        $end_a = explode('.', $date_task_end);
        if (count($start_a) >= 3) {
            $start_go = $start_a[2] . '-' . $start_a[1] . '-' . $start_a[0] . ' 00:00:00';
        } else {
            $start_go = '';
        }
        if (count($end_a) >= 3) {
            $end_go = $end_a[2] . '-' . $end_a[1] . '-' . $end_a[0] . ' 23:59:59';
        } else {
            $end_go = '';
        }

        $off_start_a = explode('.', $date_complete_start);
        $off_end_a = explode('.', $date_complete_end);
        if (count($off_start_a) >= 3) {
            $off_start_go = $off_start_a[2] . '-' . $off_start_a[1] . '-' . $off_start_a[0] . ' 00:00:00';
        } else {
            $off_start_go = '';
        }
        if (count($off_end_a) >= 3) {
            $off_end_go = $off_end_a[2] . '-' . $off_end_a[1] . '-' . $off_end_a[0] . ' 23:59:59';
        } else {
            $off_end_go = '';
        }


        // страница
        if ($this->request->input('page') && $this->request->input('page') != '') {
            $page_linc = $this->request->input('page');
        } else {
            $page_linc = '';
        }

        $load_exel = $this->request->input('load_exel');

        $list = Tacks::getAdviceList(compact([
            'load_exel',
            'start_go',
            'end_go',
            'off_start_go',
            'off_end_go',

            'priority',
            'client_spot',
            'task_district',
            'client_fio',
            'client_login',
            'client_phone',
            'client_mail',
            'gov_name',
            'process_name',
            'operator',
            'task_id',
            'load_exel',
        ]));

        if ($load_exel > 0) {
            //создаем новую книгу
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            //формируем шапочку документа
            $sheet->setCellValue('A1', '№');
            $sheet->setCellValue('B1', 'Гос. орган');
            $sheet->setCellValue('C1', 'Должность');
            $sheet->setCellValue('D1', 'Фио');
            $sheet->setCellValue('E1', 'Логин');
            $sheet->setCellValue('F1', 'Телефон');
            $sheet->setCellValue('G1', 'Эл. почта');
            $sheet->setCellValue('H1', 'Наименование подсистемы');
            $sheet->setCellValue('I1', 'Оператор');
            $sheet->setCellValue('J1', 'Дата обращения');
            $sheet->setCellValue('K1', 'Решено');

            //Устанавливаем стили для шапки
            $spreadsheet->getActiveSheet()->getStyle('A1:K1')->applyFromArray($this->getHeadTableStyle());

            $i = 1;
            $index = 2;
            foreach ($list as $obj) {
                $sheet->setCellValue('A'.$index, $i++);
                $sheet->setCellValue('B'.$index, $obj->gov_name);
                $sheet->setCellValue('C'.$index, $obj->client_spot);
                $sheet->setCellValue('D'.$index, $obj->client_fio);
                $sheet->setCellValue('E'.$index, $obj->client_login);
                $sheet->setCellValue('F'.$index, $obj->client_phone);
                $sheet->setCellValue('G'.$index, $obj->client_mail);
                $sheet->setCellValue('H'.$index, $obj->task_name);
                $sheet->setCellValue('I'.$index, $obj->operator);
                $sheet->setCellValue('J'.$index, $obj->created_at);
                $sheet->setCellValue('K'.$index, $obj->task_off);

                $index++;
            }

            //Установка выравнивания по правой стороне
            $spreadsheet->getActiveSheet()->getStyle('A2:K'.$index)
                ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);

            //Установка переноса текста
            $spreadsheet->getActiveSheet()->getStyle('B2:K'.$index)
                ->getAlignment()->setWrapText(true);

            //Установка ширины столбца
            $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(6);
            $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(29);
            $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(13);
            $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(13);
            $spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(13);
            $spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(13);
            $spreadsheet->getActiveSheet()->getColumnDimension('G')->setWidth(6);
            $spreadsheet->getActiveSheet()->getColumnDimension('H')->setWidth(15);
            $spreadsheet->getActiveSheet()->getColumnDimension('I')->setWidth(15);
            $spreadsheet->getActiveSheet()->getColumnDimension('J')->setWidth(12);
            $spreadsheet->getActiveSheet()->getColumnDimension('K')->setWidth(12);

            $writer = new Xlsx($spreadsheet);
            $writer->save('Список Консультаций.xlsx');
            return response()->download(public_path('Список Консультаций.xlsx'));
        } else {
            $operators     = Users::getOperatorsList();
            $process_names = Tacks::namesForm();

            return view('admin.tacks.cons_list_page', compact([
                'list',
                'start',
                'end',
                'off_start',
                'off_end',

                'priority',
                'task_district',
                'client_spot',
                'client_fio',
                'client_login',
                'client_phone',
                'client_mail',
                'gov_name',
                'process_name',
                'process_names',
                'operators',
                'operator',
                'page_linc',
                'task_id',
            ]));
        }
    }

    public function consListGet()
    {

        // start
        if (Cache::has('date_task_start' . auth()->id())) {
            $date_task_start = Cache::get('date_task_start' . auth()->id());
        } else {
            $date_task_start = '';
            Cache::forget('date_task_start' . auth()->id());
            Cache::forever('date_task_start' . auth()->id(), $date_task_start);
        }

        // end
        if (Cache::has('date_task_end' . auth()->id())) {
            $date_task_end = Cache::get('date_task_end' . auth()->id());
        } else {
            $date_task_end = '';
            Cache::forget('date_task_end' . auth()->id());
            Cache::forever('date_task_end' . auth()->id(), $date_task_end);
        }
        $start = $date_task_start;
        $end = $date_task_end;

        // start
        if (Cache::has('date_complete_start' . auth()->id())) {
            $date_complete_start = Cache::get('date_complete_start' . auth()->id());
        } else {
            $date_complete_start = '';
            Cache::forget('date_complete_start' . auth()->id());
            Cache::forever('date_complete_start' . auth()->id(), $date_complete_start);
        }

        // end
        if (Cache::has('date_complete_end' . auth()->id())) {
            $date_complete_end = Cache::get('date_complete_end' . auth()->id());
        } else {
            $date_complete_end = '';
            Cache::forget('date_complete_end' . auth()->id());
            Cache::forever('date_complete_end' . auth()->id(), $date_complete_end);
        }
        $off_start = $date_complete_start;
        $off_end = $date_complete_end;

        // priority
        if (Cache::has('priority' . auth()->id())) {
            $priority = Cache::get('priority' . auth()->id());
        } else {
            $priority = '';
            Cache::forget('priority' . auth()->id());
            Cache::forever('priority' . auth()->id(), $priority);
        }

        // Должность
        if (Cache::has('client_spot' . auth()->id())) {
            $client_spot = Cache::get('client_spot' . auth()->id());
        } else {
            $client_spot = '';
            Cache::forget('client_spot' . auth()->id());
            Cache::forever('client_spot' . auth()->id(), $client_spot);
        }

        // Фио
        if (Cache::has('client_fio' . auth()->id())) {
            $client_fio = Cache::get('client_fio' . auth()->id());
        } else {
            $client_fio = '';
            Cache::forget('client_fio' . auth()->id());
            Cache::forever('client_fio' . auth()->id(), $client_fio);
        }

        // Логин
        if (Cache::has('client_login' . auth()->id())) {
            $client_login = Cache::get('client_login' . auth()->id());
        } else {
            $client_login = '';
            Cache::forget('client_login' . auth()->id());
            Cache::forever('client_login' . auth()->id(), $client_login);
        }

        // Телефон
        if (Cache::has('client_phone' . auth()->id())) {
            $client_phone = Cache::get('client_phone' . auth()->id());
        } else {
            $client_phone = '';
            Cache::forget('client_phone' . auth()->id());
            Cache::forever('client_phone' . auth()->id(), $client_phone);
        }

        // Телефон
        if (Cache::has('client_mail' . auth()->id())) {
            $client_mail = Cache::get('client_mail' . auth()->id());
        } else {
            $client_mail = '';
            Cache::forget('client_mail' . auth()->id());
            Cache::forever('client_mail' . auth()->id(), $client_mail);
        }

        // Гос. орган
        if (Cache::has('gov_name' . auth()->id())) {
            $gov_name = Cache::get('gov_name' . auth()->id());
        } else {
            $gov_name = '';
            Cache::forget('gov_name' . auth()->id());
            Cache::forever('gov_name' . auth()->id(), $gov_name);
        }

        // Наименование
        if (Cache::has('process_name' . auth()->id())) {
            $process_name = Cache::get('process_name' . auth()->id());
        } else {
            $process_name = '';
            Cache::forget('process_name' . auth()->id());
            Cache::forever('process_name' . auth()->id(), $process_name);
        }

        // Оператор
        if (Cache::has('operator' . auth()->id())) {
            $operator = Cache::get('operator' . auth()->id());
        } else {
            $operator = '';
            Cache::forget('operator' . auth()->id());
            Cache::forever('operator' . auth()->id(), $operator);
        }

        // task_id
        if (Cache::has('task_id' . auth()->id())) {
            $task_id = Cache::get('task_id' . auth()->id());
        } else {
            $task_id = '';
            Cache::forget('task_id' . auth()->id());
            Cache::forever('task_id' . auth()->id(), $task_id);
        }

        // Регион
        if (Cache::has('task_district' . auth()->id())) {
            $task_district = Cache::get('task_district' . auth()->id());
        } else {
            $task_district = '';
            Cache::forget('task_district' . auth()->id());
            Cache::forever('task_district' . auth()->id(), $task_district);
        }

        $start_a = explode('.', $date_task_start);
        $end_a = explode('.', $date_task_end);
        if (count($start_a) >= 3) {
            $start_go = $start_a[2] . '-' . $start_a[1] . '-' . $start_a[0] . ' 00:00:00';
        } else {
            $start_go = '';
        }
        if (count($end_a) >= 3) {
            $end_go = $end_a[2] . '-' . $end_a[1] . '-' . $end_a[0] . ' 23:59:59';
        } else {
            $end_go = '';
        }

        $off_start_a = explode('.', $date_complete_start);
        $off_end_a = explode('.', $date_complete_end);
        if (count($off_start_a) >= 3) {
            $off_start_go = $off_start_a[2] . '-' . $off_start_a[1] . '-' . $off_start_a[0] . ' 00:00:00';
        } else {
            $off_start_go = '';
        }
        if (count($off_end_a) >= 3) {
            $off_end_go = $off_end_a[2] . '-' . $off_end_a[1] . '-' . $off_end_a[0] . ' 23:59:59';
        } else {
            $off_end_go = '';
        }

        // страница
        if ($this->request->input('page') && $this->request->input('page') != '') {
            $page_linc = $this->request->input('page');
        } else {
            $page_linc = '';
        }

        $load_exel = 0;

        $operators = Users::getOperatorsList();
        $process_names = Tacks::namesForm();

        $list = Tacks::getAdviceList(compact([
            'start_go',
            'end_go',
            'off_start_go',
            'off_end_go',

            'priority',
            'task_district',
            'client_spot',
            'client_fio',
            'client_login',
            'client_phone',
            'client_mail',
            'gov_name',
            'process_name',
            'operator',
            'task_id',
            'load_exel',
        ]));

        return view('admin.tacks.cons_list_page', compact([
            'list',
            'start',
            'end',
            'off_start',
            'off_end',

            'priority',
            'client_spot',
            'client_fio',
            'client_login',
            'client_phone',
            'client_mail',
            'gov_name',
            'process_names',
            'process_name',
            'page_linc',
            'operators',
            'operator',
            'task_id',
        ]));
    }

    public function tackList()
    {
        // start
        if ($this->request->input('date_task_start') && $this->request->input('date_task_start') != '') {
            $date_task_start = $this->request->input('date_task_start');
            Cache::forget('date_task_start' . auth()->id());
            Cache::forever('date_task_start' . auth()->id(), $date_task_start);
        } else if (Cache::has('date_task_start' . auth()->id()) && $this->request->input('page') != '') {
            $date_task_start = Cache::get('date_task_start' . auth()->id());
        } else {
            $date_task_start = '';
            Cache::forget('date_task_start' . auth()->id());
            Cache::forever('date_task_start' . auth()->id(), $date_task_start);
        }

        // end
        if ($this->request->input('date_task_end') && $this->request->input('date_task_end') != '') {
            $date_task_end = $this->request->input('date_task_end');
            Cache::forget('date_task_end' . auth()->id());
            Cache::forever('date_task_end' . auth()->id(), $date_task_end);
        } else if (Cache::has('date_task_end' . auth()->id()) && $this->request->input('page') != '') {
            $date_task_end = Cache::get('date_task_end' . auth()->id());
        } else {
            $date_task_end = '';
            Cache::forget('date_task_end' . auth()->id());
            Cache::forever('date_task_end' . auth()->id(), $date_task_end);
        }
        $start = $date_task_start;
        $end = $date_task_end;

        // start
        if ($this->request->input('date_complete_start') && $this->request->input('date_complete_start') != '') {
            $date_complete_start = $this->request->input('date_complete_start');
            Cache::forget('date_complete_start' . auth()->id());
            Cache::forever('date_complete_start' . auth()->id(), $date_complete_start);
        } else if (Cache::has('date_complete_start' . auth()->id()) && $this->request->input('page') != '') {
            $date_complete_start = Cache::get('date_complete_start' . auth()->id());
        } else {
            $date_complete_start = '';
            Cache::forget('date_complete_start' . auth()->id());
            Cache::forever('date_complete_start' . auth()->id(), $date_complete_start);
        }

        // end
        if ($this->request->input('date_complete_end') && $this->request->input('date_complete_end') != '') {
            $date_complete_end = $this->request->input('date_complete_end');
            Cache::forget('date_complete_end' . auth()->id());
            Cache::forever('date_complete_end' . auth()->id(), $date_complete_end);
        } else if (Cache::has('date_complete_end' . auth()->id()) && $this->request->input('page') != '') {
            $date_complete_end = Cache::get('date_complete_end' . auth()->id());
        } else {
            $date_complete_end = '';
            Cache::forget('date_complete_end' . auth()->id());
            Cache::forever('date_complete_end' . auth()->id(), $date_complete_end);
        }
        $off_start = $date_complete_start;
        $off_end = $date_complete_end;

        // priority
        if ($this->request->input('priority') && $this->request->input('priority') != '') {
            $priority = $this->request->input('priority');
            Cache::forget('priority' . auth()->id());
            Cache::forever('priority' . auth()->id(), $priority);
        } else if (Cache::has('priority' . auth()->id()) && $this->request->input('page') != '') {
            $priority = Cache::get('priority' . auth()->id());
        } else {
            $priority = '';
            Cache::forget('priority' . auth()->id());
            Cache::forever('priority' . auth()->id(), $priority);
        }

        // Должность
        if ($this->request->input('client_spot') && $this->request->input('client_spot') != '') {
            $client_spot = $this->request->input('client_spot');
            Cache::forget('client_spot' . auth()->id());
            Cache::forever('client_spot' . auth()->id(), $client_spot);
        } else if (Cache::has('client_spot' . auth()->id()) && $this->request->input('page') != '') {
            $client_spot = Cache::get('client_spot' . auth()->id());
        } else {
            $client_spot = '';
            Cache::forget('client_spot' . auth()->id());
            Cache::forever('client_spot' . auth()->id(), $client_spot);
        }

        // Фио
        if ($this->request->input('client_fio') && $this->request->input('client_fio') != '') {
            $client_fio = $this->request->input('client_fio');
            Cache::forget('client_fio' . auth()->id());
            Cache::forever('client_fio' . auth()->id(), $client_fio);
        } else if (Cache::has('client_fio' . auth()->id()) && $this->request->input('page') != '') {
            $client_fio = Cache::get('client_fio' . auth()->id());
        } else {
            $client_fio = '';
            Cache::forget('client_fio' . auth()->id());
            Cache::forever('client_fio' . auth()->id(), $client_fio);
        }

        // Логин
        if ($this->request->input('client_login') && $this->request->input('client_login') != '') {
            $client_login = $this->request->input('client_login');
            Cache::forget('client_login' . auth()->id());
            Cache::forever('client_login' . auth()->id(), $client_login);
        } else if (Cache::has('client_login' . auth()->id()) && $this->request->input('page') != '') {
            $client_login = Cache::get('client_login' . auth()->id());
        } else {
            $client_login = '';
            Cache::forget('client_login' . auth()->id());
            Cache::forever('client_login' . auth()->id(), $client_login);
        }

        // Телефон
        if ($this->request->input('client_phone') && $this->request->input('client_phone') != '') {
            $client_phone = $this->request->input('client_phone');
            Cache::forget('client_phone' . auth()->id());
            Cache::forever('client_phone' . auth()->id(), $client_phone);
        } else if (Cache::has('client_phone' . auth()->id()) && $this->request->input('page') != '') {
            $client_phone = Cache::get('client_phone' . auth()->id());
        } else {
            $client_phone = '';
            Cache::forget('client_phone' . auth()->id());
            Cache::forever('client_phone' . auth()->id(), $client_phone);
        }

        // Телефон
        if ($this->request->input('client_mail') && $this->request->input('client_mail') != '') {
            $client_mail = $this->request->input('client_mail');
            Cache::forget('client_mail' . auth()->id());
            Cache::forever('client_mail' . auth()->id(), $client_mail);
        } else if (Cache::has('client_mail' . auth()->id()) && $this->request->input('page') != '') {
            $client_mail = Cache::get('client_mail' . auth()->id());
        } else {
            $client_mail = '';
            Cache::forget('client_mail' . auth()->id());
            Cache::forever('client_mail' . auth()->id(), $client_mail);
        }

        // Гос. орган
        if ($this->request->input('gov_name') && $this->request->input('gov_name') != '') {
            $gov_name = $this->request->input('gov_name');
            Cache::forget('gov_name' . auth()->id());
            Cache::forever('gov_name' . auth()->id(), $gov_name);
        } else if (Cache::has('gov_name' . auth()->id()) && $this->request->input('page') != '') {
            $gov_name = Cache::get('gov_name' . auth()->id());
        } else {
            $gov_name = '';
            Cache::forget('gov_name' . auth()->id());
            Cache::forever('gov_name' . auth()->id(), $gov_name);
        }

        // Наименование
        if ($this->request->input('process_name') && $this->request->input('process_name') != '') {
            $process_name = $this->request->input('process_name');
            Cache::forget('process_name' . auth()->id());
            Cache::forever('process_name' . auth()->id(), $process_name);
        } else if (Cache::has('process_name' . auth()->id()) && $this->request->input('page') != '') {
            $process_name = Cache::get('process_name' . auth()->id());
        } else {
            $process_name = '';
            Cache::forget('process_name' . auth()->id());
            Cache::forever('process_name' . auth()->id(), $process_name);
        }

        // Оператор
        if ($this->request->input('operator') && $this->request->input('operator') != '') {
            $operator = $this->request->input('operator');
            Cache::forget('operator' . auth()->id());
            Cache::forever('operator' . auth()->id(), $operator);
        } else if (Cache::has('operator' . auth()->id()) && $this->request->input('page') != '') {
            $operator = Cache::get('operator' . auth()->id());
        } else {
            $operator = '';
            Cache::forget('operator' . auth()->id());
            Cache::forever('operator' . auth()->id(), $operator);
        }

        // Исполнитель
        if ($this->request->input('developer') && $this->request->input('developer') != '') {
            $developer = $this->request->input('developer');
            Cache::forget('developer' . auth()->id());
            Cache::forever('developer' . auth()->id(), $developer);
        } else if (Cache::has('developer' . auth()->id()) && $this->request->input('page') != '') {
            $developer = Cache::get('developer' . auth()->id());
        } else {
            $developer = '';
            Cache::forget('developer' . auth()->id());
            Cache::forever('developer' . auth()->id(), $developer);
        }

        // Стадии
        if ($this->request->input('complete') && $this->request->input('complete') != '') {
            $complete = $this->request->input('complete');
            Cache::forget('complete' . auth()->id());
            Cache::forever('complete' . auth()->id(), $complete);
        } else if (Cache::has('complete' . auth()->id()) && $this->request->input('page') != '') {
            $complete = Cache::get('complete' . auth()->id());
        } else {
            $complete = '';
            Cache::forget('complete' . auth()->id());
            Cache::forever('complete' . auth()->id(), $complete);
        }

        // task_id
        if ($this->request->input('task_id') && $this->request->input('task_id') != '') {
            $task_id = $this->request->input('task_id');
            Cache::forget('task_id' . auth()->id());
            Cache::forever('task_id' . auth()->id(), $task_id);
        } else if (Cache::has('task_id' . auth()->id()) && $this->request->input('page') != '') {
            $task_id = Cache::get('task_id' . auth()->id());
        } else {
            $task_id = '';
            Cache::forget('task_id' . auth()->id());
            Cache::forever('task_id' . auth()->id(), $task_id);
        }

        // Регион
        if ($this->request->input('task_district') && $this->request->input('task_district') != '') {
            $task_district = $this->request->input('task_district');
            Cache::forget('task_district' . auth()->id());
            Cache::forever('task_district' . auth()->id(), $task_district);
        } else if (Cache::has('task_district' . auth()->id()) && $this->request->input('page') != '') {
            $task_district = Cache::get('task_district' . auth()->id());
        } else {
            $task_district = '';
            Cache::forget('task_district' . auth()->id());
            Cache::forever('task_district' . auth()->id(), $task_district);
        }

        // страница
        if ($this->request->input('page') && $this->request->input('page') != '') {
            $page_linc = $this->request->input('page');
        } else {
            $page_linc = '';
        }

        //запрос отправлен с главной страницы
        $is_dashboard = 0;
        if ($this->request->input('is_dashboard') && $this->request->input('is_dashboard') != '') {
            $is_dashboard = $this->request->input('is_dashboard');
        }

        $is_close = '';
        //если запрос был отправлен с главной страницы
        if ($is_dashboard == 1) {
            //проверяем какая стадия работы была отправлена
            // если выбрана сталия 3 - то представляем ее как все стадии
            // которые нуждаются в проверку у оператора. т.е. Решено, Консультация, Не повторилась, Не актуальна
            if ($complete == '3') {
                $complete = '11';
            }
            //так же ставим отметку о том что необходимо отобрать не закрытые заявки,
            // так как на главной странице в дашбордах отображены только не закрытые заявки.
            $is_close = '1';
        }

        //dd($this->request->input('complete'));

        $start_a = explode('.', $date_task_start);
        $end_a = explode('.', $date_task_end);
        if (count($start_a) >= 3) {
            $start_go = $start_a[2] . '-' . $start_a[1] . '-' . $start_a[0] . ' 00:00:00';
        } else {
            $start_go = '';
        }
        if (count($end_a) >= 3) {
            $end_go = $end_a[2] . '-' . $end_a[1] . '-' . $end_a[0] . ' 23:59:59';
        } else {
            $end_go = '';
        }

        $off_start_a = explode('.', $date_complete_start);
        $off_end_a = explode('.', $date_complete_end);
        if (count($off_start_a) >= 3) {
            $off_start_go = $off_start_a[2] . '-' . $off_start_a[1] . '-' . $off_start_a[0] . ' 00:00:00';
        } else {
            $off_start_go = '';
        }
        if (count($off_end_a) >= 3) {
            $off_end_go = $off_end_a[2] . '-' . $off_end_a[1] . '-' . $off_end_a[0] . ' 23:59:59';
        } else {
            $off_end_go = '';
        }

        $load_exel = $this->request->input('load_exel');

        $list = Tacks::getTaskList(compact([
            'load_exel',
            'start_go',
            'end_go',
            'off_start_go',
            'off_end_go',
            'priority',
            'client_spot',
            'task_district',
            'client_fio',
            'client_login',
            'client_phone',
            'client_mail',
            'gov_name',
            'process_name',
            'operator',
            'developer',
            'complete',
            'task_id',
            'is_close'
        ]));

        if ($this->request->input('load_exel') > 0) {
            //создаем новую книгу
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            //формируем шапочку документа
            $sheet->setCellValue('A1', '№');
            $sheet->setCellValue('B1', '№ заявки');
            $sheet->setCellValue('C1', 'Дата обращения');
            $sheet->setCellValue('D1', 'Оператор');
            $sheet->setCellValue('E1', 'Гос. орган');
            $sheet->setCellValue('F1', 'Логин');
            $sheet->setCellValue('G1', 'Фио');
            $sheet->setCellValue('H1', 'Телефон');
            $sheet->setCellValue('I1', 'Наименование подсистемы');
            $sheet->setCellValue('J1', 'Дата закрытия');
            $sheet->setCellValue('K1', 'Исполнитель');
            $sheet->setCellValue('L1', 'Приоритет');
            $sheet->setCellValue('M1', 'Стадия');

            //Устанавливаем стили для шапки
            $spreadsheet->getActiveSheet()->getStyle('A1:O1')->applyFromArray($this->getHeadTableStyle());

            $i = 1;
            $index = 2;
            //счетчики для итоговых значений
            $countFinished = 0;
            $countInProcess = 0;
            foreach ($list as $obj) {
                $sheet->setCellValue('A'.$index, $i++);
                $sheet->setCellValue('B'.$index, $obj->id);
                $sheet->setCellValue('C'.$index, $obj->created_at);
                $sheet->setCellValue('D'.$index, $obj->operator);
                $sheet->setCellValue('E'.$index, $obj->gov_name);
                $sheet->setCellValue('F'.$index, $obj->client_login);
                $sheet->setCellValue('G'.$index, $obj->client_fio);
                $sheet->setCellValue('H'.$index, $obj->client_phone);
                $sheet->setCellValue('I'.$index, $obj->task_name);
                if ($obj->task_type == 'request_tack') {
                    $sheet->setCellValue('J'.$index, $obj->date_closed);
                }
                $sheet->setCellValue('K'.$index, $obj->developer);
                if($obj->task_priority == '1' && $obj->task_type == 'request_tack') {
                    $sheet->setCellValue('L'.$index, "Высокий");
                } elseif($obj->task_priority == '2' && $obj->task_type == 'request_tack') {
                    $sheet->setCellValue('L'.$index, "Средний");
                } elseif($obj->task_priority == '3' && $obj->task_type == 'request_tack') {
                    $sheet->setCellValue('L'.$index, "Низкий");
                }
                //type complete:
                //1 - Назначен Исполнитель
                //2 - Принято в работу
                //3 - Решено
                //4 - Не начат
                //5 - Повторное исполнение
                //6 - На доработке у Оператора
                //7 - Консультация
                //8 - Не повторилась
                //9 - Не актуальная
                if($obj->complete == '1' && $obj->task_type == 'request_tack') {
                    $sheet->setCellValue('M'.$index, "Назначен Исполнитель");
                } elseif($obj->complete == '2' && $obj->task_type == 'request_tack') {
                    $sheet->setCellValue('M'.$index, "Принято в работу");
                } elseif($obj->complete == '3' && $obj->task_type == 'request_tack') {
                    $sheet->setCellValue('M'.$index, "Решено");
                } elseif($obj->complete == '4' && $obj->task_type == 'request_tack') {
                    $sheet->setCellValue('M'.$index, "Не начат");
                } elseif($obj->complete == '5' && $obj->task_type == 'request_tack') {
                    $sheet->setCellValue('M'.$index, "Повторное исполнение");
                } elseif($obj->complete == '6' && $obj->task_type == 'request_tack') {
                    $sheet->setCellValue('M'.$index, "На доработке у Оператора");
                } elseif($obj->complete == '7' && $obj->task_type == 'request_tack') {
                    $sheet->setCellValue('M'.$index, "Консультация");
                } elseif($obj->complete == '8' && $obj->task_type == 'request_tack') {
                    $sheet->setCellValue('M'.$index, "Не повторилась");
                } elseif($obj->complete == '9' && $obj->task_type == 'request_tack') {
                    $sheet->setCellValue('M'.$index, "Не актуальная");
                }

                if ($obj->task_type == 'request_tack') {
                    if ($obj->is_close == '1') {
                        $countFinished++;
                    } else {
                        $countInProcess++;
                    }
                }

                $index++;
                $obj = null;
            }

            //подсчет итогов по отобранным заявкам
            // сделаем отступ от основных данных в три строки
            $index = $index + 2;
            $indexStart = $index;
            //объядиним ячейки
            $sheet->mergeCells('B'.$index.':'.'C'.$index);
            $sheet->setCellValue('B'.$index, "Всего:");
            $sheet->setCellValue('D'.$index, $countFinished + $countInProcess);
            $index++;
            //объядиним ячейки
            $sheet->mergeCells('B'.$index.':'.'C'.$index);
            $sheet->setCellValue('B'.$index, "В работе/на проверке:");
            $sheet->setCellValue('D'.$index, $countInProcess);
            $index++;
            //объядиним ячейки
            $sheet->mergeCells('B'.$index.':'.'C'.$index);
            $sheet->setCellValue('B'.$index, "Закрыто:");
            $sheet->setCellValue('D'.$index, $countFinished);
            //Устанавливаем стили для шапкиs
            $spreadsheet->getActiveSheet()->getStyle('B'.$indexStart.':C'.$index)->applyFromArray($this->getHeadTableStyle());

            //Установка выравнивания по правой стороне
            $spreadsheet->getActiveSheet()->getStyle('A2:O'.$index)
                ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);

            //Установка переноса текста
            $spreadsheet->getActiveSheet()->getStyle('B2:O'.$index)
                ->getAlignment()->setWrapText(true);

            //Установка ширины столбца
            $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(6);
            $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(13);
            $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(13);
            $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(17);
            $spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(29);
            $spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(15);
            $spreadsheet->getActiveSheet()->getColumnDimension('G')->setWidth(17);
            $spreadsheet->getActiveSheet()->getColumnDimension('H')->setWidth(15);
            $spreadsheet->getActiveSheet()->getColumnDimension('I')->setWidth(29);
            $spreadsheet->getActiveSheet()->getColumnDimension('J')->setWidth(12);
            $spreadsheet->getActiveSheet()->getColumnDimension('K')->setWidth(17);
            $spreadsheet->getActiveSheet()->getColumnDimension('L')->setWidth(12);
            $spreadsheet->getActiveSheet()->getColumnDimension('M')->setWidth(12);
            $spreadsheet->getActiveSheet()->getColumnDimension('N')->setWidth(12);
            $spreadsheet->getActiveSheet()->getColumnDimension('O')->setWidth(12);

            //задаем числовой формат ячейки
            $spreadsheet->getActiveSheet()->getStyle('F2:F'.$index)->getNumberFormat()->setFormatCode(\PhpOffice\PhpSpreadsheet\Style\NumberFormat::FORMAT_NUMBER);

            $writer = new Xlsx($spreadsheet);
            $writer->save('Список Неисправностей.xlsx');
            return response()->download(public_path('Список Неисправностей.xlsx'));
        } else {
            $operators     = Users::getOperatorsList();
            $process_names = Tacks::namesForm();
            return view('admin.tacks.tack_list_page', compact([
                'list',
                'start',
                'end',
                'off_start',
                'off_end',
                'priority',
                'client_spot',
                'task_district',
                'client_fio',
                'client_login',
                'client_phone',
                'client_mail',
                'gov_name',
                'process_names',
                'process_name',
                'operators',
                'operator',
                'developer',
                'complete',
                'page_linc',
                'task_id'
            ]));
        }
    }

    public function tackListGet()
    {
        // start
        if (Cache::has('date_task_start' . auth()->id())) {
            $date_task_start = Cache::get('date_task_start' . auth()->id());
        } else {
            $date_task_start = '';
            Cache::forget('date_task_start' . auth()->id());
            Cache::forever('date_task_start' . auth()->id(), $date_task_start);
        }

        // end
        if (Cache::has('date_task_end' . auth()->id())) {
            $date_task_end = Cache::get('date_task_end' . auth()->id());
        } else {
            $date_task_end = '';
            Cache::forget('date_task_end' . auth()->id());
            Cache::forever('date_task_end' . auth()->id(), $date_task_end);
        }
        $start = $date_task_start;
        $end = $date_task_end;

        // start
        if (Cache::has('date_complete_start' . auth()->id())) {
            $date_complete_start = Cache::get('date_complete_start' . auth()->id());
        } else {
            $date_complete_start = '';
            Cache::forget('date_complete_start' . auth()->id());
            Cache::forever('date_complete_start' . auth()->id(), $date_complete_start);
        }

        // end
        if (Cache::has('date_complete_end' . auth()->id())) {
            $date_complete_end = Cache::get('date_complete_end' . auth()->id());
        } else {
            $date_complete_end = '';
            Cache::forget('date_complete_end' . auth()->id());
            Cache::forever('date_complete_end' . auth()->id(), $date_complete_end);
        }
        $off_start = $date_complete_start;
        $off_end = $date_complete_end;

        // priority
        if (Cache::has('priority' . auth()->id())) {
            $priority = Cache::get('priority' . auth()->id());
        } else {
            $priority = '';
            Cache::forget('priority' . auth()->id());
            Cache::forever('priority' . auth()->id(), $priority);
        }

        // Должность
        if (Cache::has('client_spot' . auth()->id())) {
            $client_spot = Cache::get('client_spot' . auth()->id());
        } else {
            $client_spot = '';
            Cache::forget('client_spot' . auth()->id());
            Cache::forever('client_spot' . auth()->id(), $client_spot);
        }

        // Фио
        if (Cache::has('client_fio' . auth()->id())) {
            $client_fio = Cache::get('client_fio' . auth()->id());
        } else {
            $client_fio = '';
            Cache::forget('client_fio' . auth()->id());
            Cache::forever('client_fio' . auth()->id(), $client_fio);
        }

        // Логин
        if (Cache::has('client_login' . auth()->id())) {
            $client_login = Cache::get('client_login' . auth()->id());
        } else {
            $client_login = '';
            Cache::forget('client_login' . auth()->id());
            Cache::forever('client_login' . auth()->id(), $client_login);
        }

        // Телефон
        if (Cache::has('client_phone' . auth()->id())) {
            $client_phone = Cache::get('client_phone' . auth()->id());
        } else {
            $client_phone = '';
            Cache::forget('client_phone' . auth()->id());
            Cache::forever('client_phone' . auth()->id(), $client_phone);
        }

        // Телефон
        if (Cache::has('client_mail' . auth()->id())) {
            $client_mail = Cache::get('client_mail' . auth()->id());
        } else {
            $client_mail = '';
            Cache::forget('client_mail' . auth()->id());
            Cache::forever('client_mail' . auth()->id(), $client_mail);
        }

        // Гос. орган
        if (Cache::has('gov_name' . auth()->id())) {
            $gov_name = Cache::get('gov_name' . auth()->id());
        } else {
            $gov_name = '';
            Cache::forget('gov_name' . auth()->id());
            Cache::forever('gov_name' . auth()->id(), $gov_name);
        }

        // Наименование
        if (Cache::has('process_name' . auth()->id())) {
            $process_name = Cache::get('process_name' . auth()->id());
        } else {
            $process_name = '';
            Cache::forget('process_name' . auth()->id());
            Cache::forever('process_name' . auth()->id(), $process_name);
        }

        // Оператор
        if (Cache::has('operator' . auth()->id())) {
            $operator = Cache::get('operator' . auth()->id());
        } else {
            $operator = '';
            Cache::forget('operator' . auth()->id());
            Cache::forever('operator' . auth()->id(), $operator);
        }

        // Исполнитель
        if (Cache::has('developer' . auth()->id())) {
            $developer = Cache::get('developer' . auth()->id());
        } else {
            $developer = '';
            Cache::forget('developer' . auth()->id());
            Cache::forever('developer' . auth()->id(), $developer);
        }

        // Стадии
        if (Cache::has('complete' . auth()->id())) {
            $complete = Cache::get('complete' . auth()->id());
        } else {
            $complete = '';
            Cache::forget('complete' . auth()->id());
            Cache::forever('complete' . auth()->id(), $complete);
        }

        // task_id
        if (Cache::has('task_id' . auth()->id())) {
            $task_id = Cache::get('task_id' . auth()->id());
        } else {
            $task_id = '';
            Cache::forget('task_id' . auth()->id());
            Cache::forever('task_id' . auth()->id(), $task_id);
        }

        // Регион
        if (Cache::has('task_district' . auth()->id())) {
            $task_district = Cache::get('task_district' . auth()->id());
        } else {
            $task_district = '';
            Cache::forget('task_district' . auth()->id());
            Cache::forever('task_district' . auth()->id(), $task_district);
        }


        // страница
        if ($this->request->input('page') && $this->request->input('page') != '') {
            $page_linc = $this->request->input('page');
        } else {
            $page_linc = '';
        }


        $start_a = explode('.', $date_task_start);
        $end_a = explode('.', $date_task_end);
        if (count($start_a) >= 3) {
            $start_go = $start_a[2] . '-' . $start_a[1] . '-' . $start_a[0] . ' 00:00:00';
        } else {
            $start_go = '';
        }
        if (count($end_a) >= 3) {
            $end_go = $end_a[2] . '-' . $end_a[1] . '-' . $end_a[0] . ' 23:59:59';
        } else {
            $end_go = '';
        }

        $off_start_a = explode('.', $date_complete_start);
        $off_end_a = explode('.', $date_complete_end);
        if (count($off_start_a) >= 3) {
            $off_start_go = $off_start_a[2] . '-' . $off_start_a[1] . '-' . $off_start_a[0] . ' 00:00:00';
        } else {
            $off_start_go = '';
        }
        if (count($off_end_a) >= 3) {
            $off_end_go = $off_end_a[2] . '-' . $off_end_a[1] . '-' . $off_end_a[0] . ' 23:59:59';
        } else {
            $off_end_go = '';
        }


        $operators     = Users::getOperatorsList();
        $process_names = Tacks::namesForm();

        $list = Tacks::getTaskList(compact([
            'start_go',
            'end_go',
            'off_start_go',
            'off_end_go',

            'priority',
            'client_spot',
            'task_district',
            'client_fio',
            'client_login',
            'client_phone',
            'client_mail',
            'gov_name',
            'process_name',
            'operator',
            'developer',
            'complete',
            'task_id'
        ]));

        return view('admin.tacks.tack_list_page', compact([
            'list',
            'start',
            'end',
            'off_start',
            'off_end',
            'priority',
            'client_spot',
            'task_district',
            'client_fio',
            'client_login',
            'client_phone',
            'client_mail',
            'gov_name',
            'process_names',
            'process_name',
            'operators',
            'operator',
            'developer',
            'complete',
            'page_linc',
            'task_id'
        ]));
    }

    public function dealList()
    {
        // start
        if ($this->request->input('date_task_start') && $this->request->input('date_task_start') != '') {
            $date_task_start = $this->request->input('date_task_start');
            Cache::forget('date_task_start' . auth()->id());
            Cache::forever('date_task_start' . auth()->id(), $date_task_start);
        } else if (Cache::has('date_task_start' . auth()->id()) && $this->request->input('page') != '') {
            $date_task_start = Cache::get('date_task_start' . auth()->id());
        } else {
            $date_task_start = '';
            Cache::forget('date_task_start' . auth()->id());
            Cache::forever('date_task_start' . auth()->id(), $date_task_start);
        }

        // end
        if ($this->request->input('date_task_end') && $this->request->input('date_task_end') != '') {
            $date_task_end = $this->request->input('date_task_end');
            Cache::forget('date_task_end' . auth()->id());
            Cache::forever('date_task_end' . auth()->id(), $date_task_end);
        } else if (Cache::has('date_task_end' . auth()->id()) && $this->request->input('page') != '') {
            $date_task_end = Cache::get('date_task_end' . auth()->id());
        } else {
            $date_task_end = '';
            Cache::forget('date_task_end' . auth()->id());
            Cache::forever('date_task_end' . auth()->id(), $date_task_end);
        }
        $start = $date_task_start;
        $end = $date_task_end;

        // start
        if ($this->request->input('date_complete_start') && $this->request->input('date_complete_start') != '') {
            $date_complete_start = $this->request->input('date_complete_start');
            Cache::forget('date_complete_start' . auth()->id());
            Cache::forever('date_complete_start' . auth()->id(), $date_complete_start);
        } else if (Cache::has('date_complete_start' . auth()->id()) && $this->request->input('page') != '') {
            $date_complete_start = Cache::get('date_complete_start' . auth()->id());
        } else {
            $date_complete_start = '';
            Cache::forget('date_complete_start' . auth()->id());
            Cache::forever('date_complete_start' . auth()->id(), $date_complete_start);
        }

        // end
        if ($this->request->input('date_complete_end') && $this->request->input('date_complete_end') != '') {
            $date_complete_end = $this->request->input('date_complete_end');
            Cache::forget('date_complete_end' . auth()->id());
            Cache::forever('date_complete_end' . auth()->id(), $date_complete_end);
        } else if (Cache::has('date_complete_end' . auth()->id()) && $this->request->input('page') != '') {
            $date_complete_end = Cache::get('date_complete_end' . auth()->id());
        } else {
            $date_complete_end = '';
            Cache::forget('date_complete_end' . auth()->id());
            Cache::forever('date_complete_end' . auth()->id(), $date_complete_end);
        }
        $off_start = $date_complete_start;
        $off_end = $date_complete_end;

        // priority
        if ($this->request->input('priority') && $this->request->input('priority') != '') {
            $priority = $this->request->input('priority');
            Cache::forget('priority' . auth()->id());
            Cache::forever('priority' . auth()->id(), $priority);
        } else if (Cache::has('priority' . auth()->id()) && $this->request->input('page') != '') {
            $priority = Cache::get('priority' . auth()->id());
        } else {
            $priority = '';
            Cache::forget('priority' . auth()->id());
            Cache::forever('priority' . auth()->id(), $priority);
        }

        // Должность
        if ($this->request->input('client_spot') && $this->request->input('client_spot') != '') {
            $client_spot = $this->request->input('client_spot');
            Cache::forget('client_spot' . auth()->id());
            Cache::forever('client_spot' . auth()->id(), $client_spot);
        } else if (Cache::has('client_spot' . auth()->id()) && $this->request->input('page') != '') {
            $client_spot = Cache::get('client_spot' . auth()->id());
        } else {
            $client_spot = '';
            Cache::forget('client_spot' . auth()->id());
            Cache::forever('client_spot' . auth()->id(), $client_spot);
        }

        // Фио
        if ($this->request->input('client_fio') && $this->request->input('client_fio') != '') {
            $client_fio = $this->request->input('client_fio');
            Cache::forget('client_fio' . auth()->id());
            Cache::forever('client_fio' . auth()->id(), $client_fio);
        } else if (Cache::has('client_fio' . auth()->id()) && $this->request->input('page') != '') {
            $client_fio = Cache::get('client_fio' . auth()->id());
        } else {
            $client_fio = '';
            Cache::forget('client_fio' . auth()->id());
            Cache::forever('client_fio' . auth()->id(), $client_fio);
        }

        // Логин
        if ($this->request->input('client_login') && $this->request->input('client_login') != '') {
            $client_login = $this->request->input('client_login');
            Cache::forget('client_login' . auth()->id());
            Cache::forever('client_login' . auth()->id(), $client_login);
        } else if (Cache::has('client_login' . auth()->id()) && $this->request->input('page') != '') {
            $client_login = Cache::get('client_login' . auth()->id());
        } else {
            $client_login = '';
            Cache::forget('client_login' . auth()->id());
            Cache::forever('client_login' . auth()->id(), $client_login);
        }

        // Телефон
        if ($this->request->input('client_phone') && $this->request->input('client_phone') != '') {
            $client_phone = $this->request->input('client_phone');
            Cache::forget('client_phone' . auth()->id());
            Cache::forever('client_phone' . auth()->id(), $client_phone);
        } else if (Cache::has('client_phone' . auth()->id()) && $this->request->input('page') != '') {
            $client_phone = Cache::get('client_phone' . auth()->id());
        } else {
            $client_phone = '';
            Cache::forget('client_phone' . auth()->id());
            Cache::forever('client_phone' . auth()->id(), $client_phone);
        }

        // Телефон
        if ($this->request->input('client_mail') && $this->request->input('client_mail') != '') {
            $client_mail = $this->request->input('client_mail');
            Cache::forget('client_mail' . auth()->id());
            Cache::forever('client_mail' . auth()->id(), $client_mail);
        } else if (Cache::has('client_mail' . auth()->id()) && $this->request->input('page') != '') {
            $client_mail = Cache::get('client_mail' . auth()->id());
        } else {
            $client_mail = '';
            Cache::forget('client_mail' . auth()->id());
            Cache::forever('client_mail' . auth()->id(), $client_mail);
        }

        // Гос. орган
        if ($this->request->input('gov_name') && $this->request->input('gov_name') != '') {
            $gov_name = $this->request->input('gov_name');
            Cache::forget('gov_name' . auth()->id());
            Cache::forever('gov_name' . auth()->id(), $gov_name);
        } else if (Cache::has('gov_name' . auth()->id()) && $this->request->input('page') != '') {
            $gov_name = Cache::get('gov_name' . auth()->id());
        } else {
            $gov_name = '';
            Cache::forget('gov_name' . auth()->id());
            Cache::forever('gov_name' . auth()->id(), $gov_name);
        }

        // Наименование
        if ($this->request->input('process_name') && $this->request->input('process_name') != '') {
            $process_name = $this->request->input('process_name');
            Cache::forget('process_name' . auth()->id());
            Cache::forever('process_name' . auth()->id(), $process_name);
        } else if (Cache::has('process_name' . auth()->id()) && $this->request->input('page') != '') {
            $process_name = Cache::get('process_name' . auth()->id());
        } else {
            $process_name = '';
            Cache::forget('process_name' . auth()->id());
            Cache::forever('process_name' . auth()->id(), $process_name);
        }

        // Оператор
        if ($this->request->input('operator') && $this->request->input('operator') != '') {
            $operator = $this->request->input('operator');
            Cache::forget('operator' . auth()->id());
            Cache::forever('operator' . auth()->id(), $operator);
        } else if (Cache::has('operator' . auth()->id()) && $this->request->input('page') != '') {
            $operator = Cache::get('operator' . auth()->id());
        } else {
            $operator = '';
            Cache::forget('operator' . auth()->id());
            Cache::forever('operator' . auth()->id(), $operator);
        }

        // Исполнитель
        if ($this->request->input('developer') && $this->request->input('developer') != '') {
            $developer = $this->request->input('developer');
            Cache::forget('developer' . auth()->id());
            Cache::forever('developer' . auth()->id(), $developer);
        } else if (Cache::has('developer' . auth()->id()) && $this->request->input('page') != '') {
            $developer = Cache::get('developer' . auth()->id());
        } else {
            $developer = '';
            Cache::forget('developer' . auth()->id());
            Cache::forever('developer' . auth()->id(), $developer);
        }

        // Стадии
        if ($this->request->input('complete') && $this->request->input('complete') != '') {
            $complete = $this->request->input('complete');
            Cache::forget('complete' . auth()->id());
            Cache::forever('complete' . auth()->id(), $complete);
        } else if (Cache::has('complete' . auth()->id()) && $this->request->input('page') != '') {
            $complete = Cache::get('complete' . auth()->id());
        } else {
            $complete = '';
            Cache::forget('complete' . auth()->id());
            Cache::forever('complete' . auth()->id(), $complete);
        }


        // task_id
        if ($this->request->input('task_id') && $this->request->input('task_id') != '') {
            $task_id = $this->request->input('task_id');
            Cache::forget('task_id' . auth()->id());
            Cache::forever('task_id' . auth()->id(), $task_id);
        } else if (Cache::has('task_id' . auth()->id()) && $this->request->input('page') != '') {
            $task_id = Cache::get('task_id' . auth()->id());
        } else {
            $task_id = '';
            Cache::forget('task_id' . auth()->id());
            Cache::forever('task_id' . auth()->id(), $task_id);
        }

        // Регион
        if ($this->request->input('task_district') && $this->request->input('task_district') != '') {
            $task_district = $this->request->input('task_district');
            Cache::forget('task_district' . auth()->id());
            Cache::forever('task_district' . auth()->id(), $task_district);
        } else if (Cache::has('task_district' . auth()->id()) && $this->request->input('page') != '') {
            $task_district = Cache::get('task_district' . auth()->id());
        } else {
            $task_district = '';
            Cache::forget('task_district' . auth()->id());
            Cache::forever('task_district' . auth()->id(), $task_district);
        }


        // страница
        if ($this->request->input('page') && $this->request->input('page') != '') {
            $page_linc = $this->request->input('page');
        } else {
            $page_linc = '';
        }

        //dd($this->request->input('complete'));

        $start_a = explode('.', $date_task_start);
        $end_a = explode('.', $date_task_end);
        if (count($start_a) >= 3) {
            $start_go = $start_a[2] . '-' . $start_a[1] . '-' . $start_a[0] . ' 00:00:00';
        } else {
            $start_go = '';
        }
        if (count($end_a) >= 3) {
            $end_go = $end_a[2] . '-' . $end_a[1] . '-' . $end_a[0] . ' 23:59:59';
        } else {
            $end_go = '';
        }

        $off_start_a = explode('.', $date_complete_start);
        $off_end_a = explode('.', $date_complete_end);
        if (count($off_start_a) >= 3) {
            $off_start_go = $off_start_a[2] . '-' . $off_start_a[1] . '-' . $off_start_a[0] . ' 00:00:00';
        } else {
            $off_start_go = '';
        }
        if (count($off_end_a) >= 3) {
            $off_end_go = $off_end_a[2] . '-' . $off_end_a[1] . '-' . $off_end_a[0] . ' 23:59:59';
        } else {
            $off_end_go = '';
        }

        $load_exel = $this->request->input('load_exel');

        $list = Tacks::dealTaskList(compact([
            'load_exel',
            'start_go',
            'end_go',
            'off_start_go',
            'off_end_go',

            'priority',
            'client_spot',
            'task_district',
            'client_fio',
            'client_login',
            'client_phone',
            'client_mail',
            'gov_name',
            'process_name',
            'operator',
            'developer',
            'complete',
            'task_id'
        ]));

        if ($this->request->input('load_exel') > 0) {

            return Excel::download(new ExelCancelExport(compact([
                'list',
                'start',
                'end',
                'off_start',
                'off_end',

                'priority',
                'client_spot',
                'task_district',
                'client_fio',
                'client_login',
                'client_phone',
                'client_mail',
                'gov_name',
                'process_name',
                'operator',
                'developer',
                'complete',
                'page_linc',
            ])), 'Общий список Звонков.xlsx');


        } else {

            $operators     = Users::getOperatorsList();
            $process_names = Tacks::namesForm();

            return view('admin.tacks.deal_list_page', compact([
                'list',
                'start',
                'end',
                'off_start',
                'off_end',

                'priority',
                'client_spot',
                'task_district',
                'client_fio',
                'client_login',
                'client_phone',
                'client_mail',
                'gov_name',
                'process_names',
                'process_name',
                'operators',
                'operator',
                'developer',
                'complete',
                'page_linc',
                'task_id'
            ]));
        }

    }

    public function dealListGet()
    {
        // start
        if (Cache::has('date_task_start' . auth()->id())) {
            $date_task_start = Cache::get('date_task_start' . auth()->id());
        } else {
            $date_task_start = '';
            Cache::forget('date_task_start' . auth()->id());
            Cache::forever('date_task_start' . auth()->id(), $date_task_start);
        }

        // end
        if (Cache::has('date_task_end' . auth()->id())) {
            $date_task_end = Cache::get('date_task_end' . auth()->id());
        } else {
            $date_task_end = '';
            Cache::forget('date_task_end' . auth()->id());
            Cache::forever('date_task_end' . auth()->id(), $date_task_end);
        }
        $start = $date_task_start;
        $end = $date_task_end;

        // start
        if (Cache::has('date_complete_start' . auth()->id())) {
            $date_complete_start = Cache::get('date_complete_start' . auth()->id());
        } else {
            $date_complete_start = '';
            Cache::forget('date_complete_start' . auth()->id());
            Cache::forever('date_complete_start' . auth()->id(), $date_complete_start);
        }

        // end
        if (Cache::has('date_complete_end' . auth()->id())) {
            $date_complete_end = Cache::get('date_complete_end' . auth()->id());
        } else {
            $date_complete_end = '';
            Cache::forget('date_complete_end' . auth()->id());
            Cache::forever('date_complete_end' . auth()->id(), $date_complete_end);
        }
        $off_start = $date_complete_start;
        $off_end = $date_complete_end;

        // priority
        if (Cache::has('priority' . auth()->id())) {
            $priority = Cache::get('priority' . auth()->id());
        } else {
            $priority = '';
            Cache::forget('priority' . auth()->id());
            Cache::forever('priority' . auth()->id(), $priority);
        }

        // Должность
        if (Cache::has('client_spot' . auth()->id())) {
            $client_spot = Cache::get('client_spot' . auth()->id());
        } else {
            $client_spot = '';
            Cache::forget('client_spot' . auth()->id());
            Cache::forever('client_spot' . auth()->id(), $client_spot);
        }

        // Фио
        if (Cache::has('client_fio' . auth()->id())) {
            $client_fio = Cache::get('client_fio' . auth()->id());
        } else {
            $client_fio = '';
            Cache::forget('client_fio' . auth()->id());
            Cache::forever('client_fio' . auth()->id(), $client_fio);
        }

        // Логин
        if (Cache::has('client_login' . auth()->id())) {
            $client_login = Cache::get('client_login' . auth()->id());
        } else {
            $client_login = '';
            Cache::forget('client_login' . auth()->id());
            Cache::forever('client_login' . auth()->id(), $client_login);
        }

        // Телефон
        if (Cache::has('client_phone' . auth()->id())) {
            $client_phone = Cache::get('client_phone' . auth()->id());
        } else {
            $client_phone = '';
            Cache::forget('client_phone' . auth()->id());
            Cache::forever('client_phone' . auth()->id(), $client_phone);
        }

        // Телефон
        if (Cache::has('client_mail' . auth()->id())) {
            $client_mail = Cache::get('client_mail' . auth()->id());
        } else {
            $client_mail = '';
            Cache::forget('client_mail' . auth()->id());
            Cache::forever('client_mail' . auth()->id(), $client_mail);
        }

        // Гос. орган
        if (Cache::has('gov_name' . auth()->id())) {
            $gov_name = Cache::get('gov_name' . auth()->id());
        } else {
            $gov_name = '';
            Cache::forget('gov_name' . auth()->id());
            Cache::forever('gov_name' . auth()->id(), $gov_name);
        }

        // Наименование
        if (Cache::has('process_name' . auth()->id())) {
            $process_name = Cache::get('process_name' . auth()->id());
        } else {
            $process_name = '';
            Cache::forget('process_name' . auth()->id());
            Cache::forever('process_name' . auth()->id(), $process_name);
        }

        // Оператор
        if (Cache::has('operator' . auth()->id())) {
            $operator = Cache::get('operator' . auth()->id());
        } else {
            $operator = '';
            Cache::forget('operator' . auth()->id());
            Cache::forever('operator' . auth()->id(), $operator);
        }

        // Исполнитель
        if (Cache::has('developer' . auth()->id())) {
            $developer = Cache::get('developer' . auth()->id());
        } else {
            $developer = '';
            Cache::forget('developer' . auth()->id());
            Cache::forever('developer' . auth()->id(), $developer);
        }

        // Стадии
        if (Cache::has('complete' . auth()->id())) {
            $complete = Cache::get('complete' . auth()->id());
        } else {
            $complete = '';
            Cache::forget('complete' . auth()->id());
            Cache::forever('complete' . auth()->id(), $complete);
        }

        // task_id
        if (Cache::has('task_id' . auth()->id())) {
            $task_id = Cache::get('task_id' . auth()->id());
        } else {
            $task_id = '';
            Cache::forget('task_id' . auth()->id());
            Cache::forever('task_id' . auth()->id(), $task_id);
        }

        // Регион
        if (Cache::has('task_district' . auth()->id())) {
            $task_district = Cache::get('task_district' . auth()->id());
        } else {
            $task_district = '';
            Cache::forget('task_district' . auth()->id());
            Cache::forever('task_district' . auth()->id(), $task_district);
        }


        // страница
        if ($this->request->input('page') && $this->request->input('page') != '') {
            $page_linc = $this->request->input('page');
        } else {
            $page_linc = '';
        }


        $start_a = explode('.', $date_task_start);
        $end_a = explode('.', $date_task_end);
        if (count($start_a) >= 3) {
            $start_go = $start_a[2] . '-' . $start_a[1] . '-' . $start_a[0] . ' 00:00:00';
        } else {
            $start_go = '';
        }
        if (count($end_a) >= 3) {
            $end_go = $end_a[2] . '-' . $end_a[1] . '-' . $end_a[0] . ' 23:59:59';
        } else {
            $end_go = '';
        }

        $off_start_a = explode('.', $date_complete_start);
        $off_end_a = explode('.', $date_complete_end);
        if (count($off_start_a) >= 3) {
            $off_start_go = $off_start_a[2] . '-' . $off_start_a[1] . '-' . $off_start_a[0] . ' 00:00:00';
        } else {
            $off_start_go = '';
        }
        if (count($off_end_a) >= 3) {
            $off_end_go = $off_end_a[2] . '-' . $off_end_a[1] . '-' . $off_end_a[0] . ' 23:59:59';
        } else {
            $off_end_go = '';
        }

        $operators     = Users::getOperatorsList();
        $process_names = Tacks::namesForm();

        $list = Tacks::dealTaskList(compact([
            'start_go',
            'end_go',
            'off_start_go',
            'off_end_go',

            'priority',
            'client_spot',
            'task_district',
            'client_fio',
            'client_login',
            'client_phone',
            'client_mail',
            'gov_name',
            'process_name',
            'operator',
            'developer',
            'complete',
            'task_id'
        ]));

        return view('admin.tacks.deal_list_page', compact([
            'list',
            'start',
            'end',
            'off_start',
            'off_end',

            'priority',
            'client_spot',
            'task_district',
            'client_fio',
            'client_login',
            'client_phone',
            'client_mail',
            'gov_name',
            'process_names',
            'process_name',
            'operators',
            'operator',
            'developer',
            'complete',
            'page_linc',
            'task_id'

        ]));
    }

    public function tackDeveloper()
    {
        // start
        if ($this->request->input('date_task_start') && $this->request->input('date_task_start') != '') {
            $date_task_start = $this->request->input('date_task_start');
            Cache::forget('date_task_start' . auth()->id());
            Cache::forever('date_task_start' . auth()->id(), $date_task_start);
        } else if (Cache::has('date_task_start' . auth()->id()) && $this->request->input('page') != '') {
            $date_task_start = Cache::get('date_task_start' . auth()->id());
        } else {
            $date_task_start = '';
            Cache::forget('date_task_start' . auth()->id());
            Cache::forever('date_task_start' . auth()->id(), $date_task_start);
        }

        // end
        if ($this->request->input('date_task_end') && $this->request->input('date_task_end') != '') {
            $date_task_end = $this->request->input('date_task_end');
            Cache::forget('date_task_end' . auth()->id());
            Cache::forever('date_task_end' . auth()->id(), $date_task_end);
        } else if (Cache::has('date_task_end' . auth()->id()) && $this->request->input('page') != '') {
            $date_task_end = Cache::get('date_task_end' . auth()->id());
        } else {
            $date_task_end = '';
            Cache::forget('date_task_end' . auth()->id());
            Cache::forever('date_task_end' . auth()->id(), $date_task_end);
        }
        $start = $date_task_start;
        $end = $date_task_end;

        // start
        if ($this->request->input('date_complete_start') && $this->request->input('date_complete_start') != '') {
            $date_complete_start = $this->request->input('date_complete_start');
            Cache::forget('date_complete_start' . auth()->id());
            Cache::forever('date_complete_start' . auth()->id(), $date_complete_start);
        } else if (Cache::has('date_complete_start' . auth()->id()) && $this->request->input('page') != '') {
            $date_complete_start = Cache::get('date_complete_start' . auth()->id());
        } else {
            $date_complete_start = '';
            Cache::forget('date_complete_start' . auth()->id());
            Cache::forever('date_complete_start' . auth()->id(), $date_complete_start);
        }

        // end
        if ($this->request->input('date_complete_end') && $this->request->input('date_complete_end') != '') {
            $date_complete_end = $this->request->input('date_complete_end');
            Cache::forget('date_complete_end' . auth()->id());
            Cache::forever('date_complete_end' . auth()->id(), $date_complete_end);
        } else if (Cache::has('date_complete_end' . auth()->id()) && $this->request->input('page') != '') {
            $date_complete_end = Cache::get('date_complete_end' . auth()->id());
        } else {
            $date_complete_end = '';
            Cache::forget('date_complete_end' . auth()->id());
            Cache::forever('date_complete_end' . auth()->id(), $date_complete_end);
        }
        $off_start = $date_complete_start;
        $off_end = $date_complete_end;

        // priority
        if ($this->request->input('priority') && $this->request->input('priority') != '') {
            $priority = $this->request->input('priority');
            Cache::forget('priority' . auth()->id());
            Cache::forever('priority' . auth()->id(), $priority);
        } else if (Cache::has('priority' . auth()->id()) && $this->request->input('page') != '') {
            $priority = Cache::get('priority' . auth()->id());
        } else {
            $priority = '';
            Cache::forget('priority' . auth()->id());
            Cache::forever('priority' . auth()->id(), $priority);
        }

        // Должность
        if ($this->request->input('client_spot') && $this->request->input('client_spot') != '') {
            $client_spot = $this->request->input('client_spot');
            Cache::forget('client_spot' . auth()->id());
            Cache::forever('client_spot' . auth()->id(), $client_spot);
        } else if (Cache::has('client_spot' . auth()->id()) && $this->request->input('page') != '') {
            $client_spot = Cache::get('client_spot' . auth()->id());
        } else {
            $client_spot = '';
            Cache::forget('client_spot' . auth()->id());
            Cache::forever('client_spot' . auth()->id(), $client_spot);
        }

        // Фио
        if ($this->request->input('client_fio') && $this->request->input('client_fio') != '') {
            $client_fio = $this->request->input('client_fio');
            Cache::forget('client_fio' . auth()->id());
            Cache::forever('client_fio' . auth()->id(), $client_fio);
        } else if (Cache::has('client_fio' . auth()->id()) && $this->request->input('page') != '') {
            $client_fio = Cache::get('client_fio' . auth()->id());
        } else {
            $client_fio = '';
            Cache::forget('client_fio' . auth()->id());
            Cache::forever('client_fio' . auth()->id(), $client_fio);
        }

        // Логин
        if ($this->request->input('client_login') && $this->request->input('client_login') != '') {
            $client_login = $this->request->input('client_login');
            Cache::forget('client_login' . auth()->id());
            Cache::forever('client_login' . auth()->id(), $client_login);
        } else if (Cache::has('client_login' . auth()->id()) && $this->request->input('page') != '') {
            $client_login = Cache::get('client_login' . auth()->id());
        } else {
            $client_login = '';
            Cache::forget('client_login' . auth()->id());
            Cache::forever('client_login' . auth()->id(), $client_login);
        }

        // Телефон
        if ($this->request->input('client_phone') && $this->request->input('client_phone') != '') {
            $client_phone = $this->request->input('client_phone');
            Cache::forget('client_phone' . auth()->id());
            Cache::forever('client_phone' . auth()->id(), $client_phone);
        } else if (Cache::has('client_phone' . auth()->id()) && $this->request->input('page') != '') {
            $client_phone = Cache::get('client_phone' . auth()->id());
        } else {
            $client_phone = '';
            Cache::forget('client_phone' . auth()->id());
            Cache::forever('client_phone' . auth()->id(), $client_phone);
        }

        // Эл. почта
        if ($this->request->input('client_mail') && $this->request->input('client_mail') != '') {
            $client_mail = $this->request->input('client_mail');
            Cache::forget('client_mail' . auth()->id());
            Cache::forever('client_mail' . auth()->id(), $client_mail);
        } else if (Cache::has('client_mail' . auth()->id()) && $this->request->input('page') != '') {
            $client_mail = Cache::get('client_mail' . auth()->id());
        } else {
            $client_mail = '';
            Cache::forget('client_mail' . auth()->id());
            Cache::forever('client_mail' . auth()->id(), $client_mail);
        }

        // Гос. орган
        if ($this->request->input('gov_name') && $this->request->input('gov_name') != '') {
            $gov_name = $this->request->input('gov_name');
            Cache::forget('gov_name' . auth()->id());
            Cache::forever('gov_name' . auth()->id(), $gov_name);
        } else if (Cache::has('gov_name' . auth()->id()) && $this->request->input('page') != '') {
            $gov_name = Cache::get('gov_name' . auth()->id());
        } else {
            $gov_name = '';
            Cache::forget('gov_name' . auth()->id());
            Cache::forever('gov_name' . auth()->id(), $gov_name);
        }

        // Наименование
        if ($this->request->input('process_name') && $this->request->input('process_name') != '') {
            $process_name = $this->request->input('process_name');
            Cache::forget('process_name' . auth()->id());
            Cache::forever('process_name' . auth()->id(), $process_name);
        } else if (Cache::has('process_name' . auth()->id()) && $this->request->input('page') != '') {
            $process_name = Cache::get('process_name' . auth()->id());
        } else {
            $process_name = '';
            Cache::forget('process_name' . auth()->id());
            Cache::forever('process_name' . auth()->id(), $process_name);
        }

        // Оператор
        if ($this->request->input('operator') && $this->request->input('operator') != '') {
            $operator = $this->request->input('operator');
            Cache::forget('operator' . auth()->id());
            Cache::forever('operator' . auth()->id(), $operator);
        } else if (Cache::has('operator') && $this->request->input('page') != '') {
            $operator = Cache::get('operator' . auth()->id());


        } else {
            $operator = '';
            Cache::forget('operator' . auth()->id());
            Cache::forever('operator' . auth()->id(), $operator);
        }


        // Исполнитель
        if ($this->request->input('developer') && $this->request->input('developer') != '') {
            $developer = $this->request->input('developer');
            Cache::forget('developer' . auth()->id());
            Cache::forever('developer' . auth()->id(), $developer);
        } else if (Cache::has('developer' . auth()->id()) && $this->request->input('page') != '') {
            $developer = Cache::get('developer' . auth()->id());
        } else {
            $developer = '';
            Cache::forget('developer' . auth()->id());
            Cache::forever('developer' . auth()->id(), $developer);
        }

        // Стадии
        if ($this->request->input('complete') && $this->request->input('complete') != '') {
            $complete = $this->request->input('complete');
            Cache::forget('complete' . auth()->id());
            Cache::forever('complete' . auth()->id(), $complete);
        } else if (Cache::has('complete' . auth()->id()) && $this->request->input('page') != '') {
            $complete = Cache::get('complete' . auth()->id());
        } else {
            $complete = '';
            Cache::forget('complete' . auth()->id());
            Cache::forever('complete' . auth()->id(), $complete);
        }

        // task_id
        if ($this->request->input('task_id') && $this->request->input('task_id') != '') {
            $task_id = $this->request->input('task_id');
            Cache::forget('task_id' . auth()->id());
            Cache::forever('task_id' . auth()->id(), $task_id);
        } else if (Cache::has('task_id' . auth()->id()) && $this->request->input('page') != '') {
            $task_id = Cache::get('task_id' . auth()->id());
        } else {
            $task_id = '';
            Cache::forget('task_id' . auth()->id());
            Cache::forever('task_id' . auth()->id(), $task_id);
        }

        // Регион
        if ($this->request->input('task_district') && $this->request->input('task_district') != '') {
            $task_district = $this->request->input('task_district');
            Cache::forget('task_district' . auth()->id());
            Cache::forever('task_district' . auth()->id(), $task_district);
        } else if (Cache::has('task_district' . auth()->id()) && $this->request->input('page') != '') {
            $task_district = Cache::get('task_district' . auth()->id());
        } else {
            $task_district = '';
            Cache::forget('task_district' . auth()->id());
            Cache::forever('task_district' . auth()->id(), $task_district);
        }


        $start_a = explode('.', $date_task_start);
        $end_a = explode('.', $date_task_end);
        if (count($start_a) >= 3) {
            $start_go = $start_a[2] . '-' . $start_a[1] . '-' . $start_a[0] . ' 00:00:00';
        } else {
            $start_go = '';
        }
        if (count($end_a) >= 3) {
            $end_go = $end_a[2] . '-' . $end_a[1] . '-' . $end_a[0] . ' 23:59:59';
        } else {
            $end_go = '';
        }

        $off_start_a = explode('.', $date_complete_start);
        $off_end_a = explode('.', $date_complete_end);
        if (count($off_start_a) >= 3) {
            $off_start_go = $off_start_a[2] . '-' . $off_start_a[1] . '-' . $off_start_a[0] . ' 00:00:00';
        } else {
            $off_start_go = '';
        }
        if (count($off_end_a) >= 3) {
            $off_end_go = $off_end_a[2] . '-' . $off_end_a[1] . '-' . $off_end_a[0] . ' 23:59:59';
        } else {
            $off_end_go = '';
        }

        $developer_id = $this->request->user()->id;
        $operators     = Users::getOperatorsList();
        $process_names = Tacks::namesForm();

        $list = Tacks::getTaskListId(compact([
            'start_go',
            'end_go',
            'off_start_go',
            'off_end_go',

            'priority',
            'task_district',
            'client_spot',
            'client_fio',
            'client_login',
            'client_phone',
            'client_mail',
            'gov_name',
            'process_name',
            'operator',
            'developer',
            'developer_id',
            'complete',
            'task_id'
        ]));

        return view('admin.tacks.my_tack_page_developer', compact([
            'list',
            'start',
            'end',
            'off_start',
            'off_end',

            'priority',
            'task_district',
            'client_spot',
            'client_fio',
            'client_login',
            'client_phone',
            'client_mail',
            'gov_name',
            'process_names',
            'process_name',
            'operators',
            'operator',
            'developer',
            'complete',
            'task_id']));
    }

    public function tackDeveloperGet()
    {
        // start
        if (Cache::has('date_task_start' . auth()->id())) {
            $date_task_start = Cache::get('date_task_start' . auth()->id());
        } else {
            $date_task_start = '';
            Cache::forget('date_task_start' . auth()->id());
            Cache::forever('date_task_start' . auth()->id(), $date_task_start);
        }

        // end
        if (Cache::has('date_task_end' . auth()->id())) {
            $date_task_end = Cache::get('date_task_end' . auth()->id());
        } else {
            $date_task_end = '';
            Cache::forget('date_task_end' . auth()->id());
            Cache::forever('date_task_end' . auth()->id(), $date_task_end);
        }
        $start = $date_task_start;
        $end = $date_task_end;

        // start
        if (Cache::has('date_complete_start' . auth()->id())) {
            $date_complete_start = Cache::get('date_complete_start' . auth()->id());
        } else {
            $date_complete_start = '';
            Cache::forget('date_complete_start' . auth()->id());
            Cache::forever('date_complete_start' . auth()->id(), $date_complete_start);
        }

        // end
        if (Cache::has('date_complete_end' . auth()->id())) {
            $date_complete_end = Cache::get('date_complete_end' . auth()->id());
        } else {
            $date_complete_end = '';
            Cache::forget('date_complete_end' . auth()->id());
            Cache::forever('date_complete_end' . auth()->id(), $date_complete_end);
        }
        $off_start = $date_complete_start;
        $off_end = $date_complete_end;

        // priority
        if (Cache::has('priority' . auth()->id())) {
            $priority = Cache::get('priority' . auth()->id());
        } else {
            $priority = '';
            Cache::forget('priority' . auth()->id());
            Cache::forever('priority' . auth()->id(), $priority);
        }

        // Должность
        if (Cache::has('client_spot' . auth()->id())) {
            $client_spot = Cache::get('client_spot' . auth()->id());
        } else {
            $client_spot = '';
            Cache::forget('client_spot' . auth()->id());
            Cache::forever('client_spot' . auth()->id(), $client_spot);
        }

        // Фио
        if (Cache::has('client_fio' . auth()->id())) {
            $client_fio = Cache::get('client_fio' . auth()->id());
        } else {
            $client_fio = '';
            Cache::forget('client_fio' . auth()->id());
            Cache::forever('client_fio' . auth()->id(), $client_fio);
        }

        // Логин
        if (Cache::has('client_login' . auth()->id())) {
            $client_login = Cache::get('client_login' . auth()->id());
        } else {
            $client_login = '';
            Cache::forget('client_login' . auth()->id());
            Cache::forever('client_login' . auth()->id(), $client_login);
        }

        // Телефон
        if (Cache::has('client_phone' . auth()->id())) {
            $client_phone = Cache::get('client_phone' . auth()->id());
        } else {
            $client_phone = '';
            Cache::forget('client_phone' . auth()->id());
            Cache::forever('client_phone' . auth()->id(), $client_phone);
        }

        // Телефон
        if (Cache::has('client_mail' . auth()->id())) {
            $client_mail = Cache::get('client_mail' . auth()->id());
        } else {
            $client_mail = '';
            Cache::forget('client_mail' . auth()->id());
            Cache::forever('client_mail' . auth()->id(), $client_mail);
        }

        // Гос. орган
        if (Cache::has('gov_name' . auth()->id())) {
            $gov_name = Cache::get('gov_name' . auth()->id());
        } else {
            $gov_name = '';
            Cache::forget('gov_name' . auth()->id());
            Cache::forever('gov_name' . auth()->id(), $gov_name);
        }

        // Наименование
        if (Cache::has('process_name' . auth()->id())) {
            $process_name = Cache::get('process_name' . auth()->id());
        } else {
            $process_name = '';
            Cache::forget('process_name' . auth()->id());
            Cache::forever('process_name' . auth()->id(), $process_name);
        }

        // Оператор
        if (Cache::has('operator' . auth()->id())) {
            $operator = Cache::get('operator' . auth()->id());
        } else {
            $operator = '';
            Cache::forget('operator' . auth()->id());
            Cache::forever('operator' . auth()->id(), $operator);
        }

        // Исполнитель
        if (Cache::has('developer' . auth()->id())) {
            $developer = Cache::get('developer' . auth()->id());
        } else {
            $developer = '';
            Cache::forget('developer' . auth()->id());
            Cache::forever('developer' . auth()->id(), $developer);
        }

        // Стадии
        if (Cache::has('complete' . auth()->id())) {
            $complete = Cache::get('complete' . auth()->id());
        } else {
            $complete = '';
            Cache::forget('complete' . auth()->id());
            Cache::forever('complete' . auth()->id(), $complete);
        }

        // task_id
        if (Cache::has('task_id' . auth()->id())) {
            $task_id = Cache::get('task_id' . auth()->id());
        } else {
            $task_id = '';
            Cache::forget('task_id' . auth()->id());
            Cache::forever('task_id' . auth()->id(), $task_id);
        }

        // Регион
        if (Cache::has('task_district' . auth()->id())) {
            $task_district = Cache::get('task_district' . auth()->id());
        } else {
            $task_district = '';
            Cache::forget('task_district' . auth()->id());
            Cache::forever('task_district' . auth()->id(), $task_district);
        }


        $start_a = explode('.', $date_task_start);
        $end_a = explode('.', $date_task_end);
        if (count($start_a) >= 3) {
            $start_go = $start_a[2] . '-' . $start_a[1] . '-' . $start_a[0] . ' 00:00:00';
        } else {
            $start_go = '';
        }
        if (count($end_a) >= 3) {
            $end_go = $end_a[2] . '-' . $end_a[1] . '-' . $end_a[0] . ' 23:59:59';
        } else {
            $end_go = '';
        }

        $off_start_a = explode('.', $date_complete_start);
        $off_end_a = explode('.', $date_complete_end);
        if (count($off_start_a) >= 3) {
            $off_start_go = $off_start_a[2] . '-' . $off_start_a[1] . '-' . $off_start_a[0] . ' 00:00:00';
        } else {
            $off_start_go = '';
        }
        if (count($off_end_a) >= 3) {
            $off_end_go = $off_end_a[2] . '-' . $off_end_a[1] . '-' . $off_end_a[0] . ' 23:59:59';
        } else {
            $off_end_go = '';
        }

        $developer_id = $this->request->user()->id;
        $operators     = Users::getOperatorsList();
        $process_names = Tacks::namesForm();

        $list = Tacks::getTaskListId(compact([
            'start_go',
            'end_go',
            'off_start_go',
            'off_end_go',

            'priority',
            'client_spot',
            'task_district',
            'client_fio',
            'client_login',
            'client_phone',
            'client_mail',
            'gov_name',
            'process_name',
            'operator',
            'developer',
            'developer_id',
            'complete', 'task_id'
        ]));

        return view('admin.tacks.my_tack_page_developer', compact([
            'list',
            'start',
            'end',
            'off_start',
            'off_end',

            'priority',
            'client_spot',
            'task_district',
            'client_fio',
            'client_login',
            'client_phone',
            'client_mail',
            'gov_name',
            'process_names',
            'process_name',
            'operators',
            'operator',
            'developer',
            'complete',
            'task_id']));
    }

    public function tackAdd() {
        $form = Tacks::getForm();
        $names = Tacks::namesForm();
        $prior = Tacks::getPriors();

        //2019-12-22 Nikolay
        //зануляем переменные
        $client_login = '';
        $region_name = '';
        //делаем запрос в таблицу астерикса cel,
        // ищем в ней последний разговор.
        // для начала вытащим номер телефона оператора
        $operator_phone = Tacks::getOperatorFromId(auth()->user()->id);
        //а теперь еже ищем последний звонивший номер,
        // это и будет номер телефона клиента.
        $client_phone = Tacks::getNumbLastCall($operator_phone);
        //$client_phone = '87123621645';
        //если найден последний звонящий номер, то автоматом заполняем поля
        if($client_phone != '') {
            //ищем абонента по номеру телефона
            $clientUser = Tacks::getClientForPhone($client_phone);
            //если клиент найден, переносим данные на форму
            if ($clientUser != null && count($clientUser) > 0) {
                $client_login = $clientUser[0]->client_login;
            }

            //2020-03-11 Kanat
            // ищем связку региона и телефона
            $region = Region::getRegionByTelephone($client_phone);
            if ($region != null && count($region) > 0) {
                $region_name = $region[0]->region;
            }
          //$region_name = 'город Нур-Султан';
        }

        return view('admin.tacks.tack_add_page', compact([
            'form',
            'names',
            'prior',
            'client_phone',
            'client_login',
            'region_name'
        ]));
    }

    public function taskClose() {
        $data = $this->request->input();

        if(isset($data['task_is_close']) && $data['task_is_close'] == true) {
            $task_id = $data['task_close_id'];
            $data['task_id'] = $task_id;
            $user_id = auth()->user()->id;
            Tacks::taskClose($data);

            //делаем запись в историю
            $task_comment = 'Заявка <b>' . $task_id . '</b> была закрыта оператором.';
            Tacks::addMess(compact(['task_comment', 'task_id', 'user_id']));

            //уведомление о закрытии заявки
            //получаем всех админов разработчиков
            $recipient_id = null;
            $listUsersAdmin = Users::getUsersListByStatus(11);
            if (count($listUsersAdmin) > 0) {
                $recipient_id = $listUsersAdmin[0]->id;
            }

            $dataNotice = [];
            $dataNotice['task_off'] = '';
            $dataNotice['complete_id'] = '';
            $dataNotice['recipient'] = $recipient_id;
            $dataNotice['task_id'] = $task_id;
            $dataNotice['name'] = 'Информация о заявке №' . $task_id;
            $dataNotice['description'] = 'Заявки №' . $task_id.' была закрыта оператором.';
            //create notice for user
            Notice::addNotice($dataNotice);
        }

        return redirect('/my_tack_page_operator?task_operator_page_id=' . $task_id);
    }

    public function taskEdit() {
        $data = $this->request->input();

        if (isset($data['complete_id']) && $data['complete_id'] != '') {
            Tacks::taskEditAdm($data);
        } else {
            Tacks::taskEditAdmChDate($data);
        }

        $mail_to = Tacks::mailToCallBack($data['client_id']);
        $task_unit = Tacks::getTaskUnit($data['task_id']);

        $task_mess = '';
        $task_title = '';

        //type complete:
        //1 - Назначен Исполнитель
        //2 - Принято в работу
        //3 - Решено
        //4 - Не начат
        //5 - Повторное исполнение
        //6 - На доработке у Оператора
        //7 - Консультация
        //8 - Не повторилась
        //9 - Не актуальная
        if ($data['complete_id'] == 1) {
            $task_title = 'Информация о заявке №' . $data['task_id'];
            $task_mess = 'Для решения вопроса по Вашей заявке №' . $data['task_id'] . ' назначен исполнитель. Предположительная дата решения вопроса ' . $data['task_off'];
            shell_exec('echo "' . $task_mess . '" | mail -s "' . $task_title . '" -a "From: crm-info@hrm-gov.kz" ' . $mail_to[0]->client_mail);

            //2019-12-20 Nikolay
            //исполнитель-разработчик может быть еще не выбран
            if (isset($data['developer_id'])) {
                $dev = Tacks::getDeveloper($data['developer_id']);
                $task_title_dev = 'Информация о заявке №' . $data['task_id'];
                $task_mess_dev = 'Вы назначены исполнителем по заявке №' . $data['task_id'] . '. Предположительная дата решения вопроса ' . $data['task_off'];
                shell_exec('echo "' . $task_mess_dev . '" | mail -s "' . $task_title_dev . '" -a "From: crm-info@hrm-gov.kz" ' . $dev[0]->email);

                $operator = Tacks::getDeveloper($task_unit[0]->users_id);
                $task_title_oper = 'Информация о заявке №' . $data['task_id'];
                $task_mess_oper = 'Для решения вопроса по заявке №' . $data['task_id'] . ' назначен исполнитель: ' . $dev[0]->name . '. Предположительная дата решения вопроса ' . $data['task_off'];
                shell_exec('echo "' . $task_mess_oper . '" | mail -s "' . $task_title_oper . '" -a "From: crm-info@hrm-gov.kz" ' . $operator[0]->email);
            }
        } else if ($data['complete_id'] == 2) {
            $task_title = 'Информация о заявке №' . $data['task_id'];
            $task_mess = 'Ваша заявка №' . $data['task_id'] . ' принята в обработку специалистом. Предположительная дата решения вопроса ' . $data['task_off'];
            shell_exec('echo "' . $task_mess . '" | mail -s "' . $task_title . '" -a "From: crm-info@hrm-gov.kz" ' . $mail_to[0]->client_mail);

            //2019-12-20 Nikolay
            //исполнитель-разработчик может быть еще не выбран
            if (isset($data['developer_id'])) {
                $dev = Tacks::getDeveloper($data['developer_id']);
                $operator = Tacks::getDeveloper($task_unit[0]->users_id);
                $task_title_oper = 'Информация о заявке №' . $data['task_id'];
                $task_mess_oper = 'Заявка №' . $data['task_id'] . ' принята в обработку специалистом: ' . $dev[0]->name . '. Предположительная дата решения вопроса ' . $data['task_off'];
                shell_exec('echo "' . $task_mess_oper . '" | mail -s "' . $task_title_oper . '" -a "From: crm-info@hrm-gov.kz" ' . $operator[0]->email);
            }
        } else if ($data['complete_id'] == 3) {
            $task_title = 'Информация о заявке №' . $data['task_id'];
            $task_mess = 'Заявка №' . $data['task_id'] . ' обработана специалистом. Заявка решена и требует проверки!';

            $operator = Tacks::getDeveloper($task_unit[0]->users_id);
            shell_exec('echo "' . $task_mess . '" | mail -s "' . $task_title . '" -a "From: crm-info@hrm-gov.kz" ' . $operator[0]->email);
        } else if ($data['complete_id'] == 4) {
            $task_title = 'Информация о заявке №' . $data['task_id'];
            $task_mess = 'Создана новая заявка №' . $data['task_id'] . '.';
        } else if ($data['complete_id'] == 5) {
            $task_title = 'Информация о заявке №' . $data['task_id'];
            $task_mess = 'Заявка №' . $data['task_id'] . ' отправлена на повторное исполнение.';
        } else if ($data['complete_id'] == 6) {
            $task_title = 'Информация о заявке №' . $data['task_id'];
            $task_mess = 'Ваша заявка №' . $data['task_id'] . ' отправлена специалистом на доработку.';
        } else if ($data['complete_id'] == 7) {
            $task_title = 'Информация о заявке №' . $data['task_id'];
            $task_mess = 'На вашу заявку №' . $data['task_id'] . ' специалистом была написана консультация.';
        } else if ($data['complete_id'] == 8) {
            $task_title = 'Информация о заявке №' . $data['task_id'];
            $task_mess = 'На вашу заявку №' . $data['task_id'] . ' специалистом была изменина стадия работы на Не повторилась.';
        } else if ($data['complete_id'] == 9) {
            $task_title = 'Информация о заявке №' . $data['task_id'];
            $task_mess = 'На вашу заявку №' . $data['task_id'] . ' специалистом была изменина стадия работы на Не актуальна.';
        }

        //2019-12-20 Nikolay
        $dataNotice = [];
        $dataNotice['task_off'] = $data['task_off'];
        $dataNotice['complete_id'] = $data['complete_id'];
        //если статус Не начат, то заявка только что создана. Поэтому отправляем
        // уведомление оператору разработчиков, который перераспределяет заявки.
        if ($data['complete_id'] == 4) {
            //получаем всех админов разработчиков
            $listUsersAdmin = Users::getUsersListByStatus(11);
            if (count($listUsersAdmin) > 0) {
                $dataNotice['recipient'] = $listUsersAdmin[0]->id;
            }
        } elseif ($data['complete_id'] == 5) {
            //если статус Повторное исполнение, то проверяем был ли у этой заявки
            // исполнитель, если он есть то отправим уведомление ему. Если,
            // не было то оператору разработчиков для перераспределения.
            if (isset($task_unit[0]->developer_id)) {
                $dataNotice['recipient'] = $task_unit[0]->developer_id;
            } else {
                //получаем всех админов разработчиков
                $listUsersAdmin = Users::getUsersListByStatus(11);
                if (count($listUsersAdmin) > 0) {
                    $dataNotice['recipient'] = $listUsersAdmin[0]->id;
                }
            }
        } else {
            //во всех остальных случаях отправляем уведомление автору заявки.
            $dataNotice['recipient'] = $task_unit[0]->users_id;
        }

        $dataNotice['task_id'] = $data['task_id'];
        //create notice for user
        Notice::addNotice($dataNotice);

        //но если статус был Назначен исполнитель, то дополнительно надо отправить еще
        // одно уведомление для этого исполнителя.
        if ($data['complete_id'] == 1 && isset($data['developer_id'])) {
            $dataNotice = [];
            $dataNotice['task_off'] = $data['task_off'];
            $dataNotice['complete_id'] = $data['complete_id'];
            $dataNotice['recipient'] = $data['developer_id'];
            $dataNotice['task_id'] = $data['task_id'];
            $dataNotice['name'] = 'Информация о заявке №' . $data['task_id'];
            $dataNotice['description'] = 'Вы были назначены в качестве исполнителя заявки №' . $data['task_id'].'.';
            //create notice for user
            Notice::addNotice($dataNotice);
        }

        if ($data['complete_id'] != 4) {
            broadcast(new chenTaskUser([
                'task' => $data['task_id'],
                'host' => $task_unit[0]->users_id,
                'title' => $task_unit[0]->task_name,
                'task_mess' => $task_mess
            ]));
        }

        return redirect('/my_tack_page_operator?task_operator_page_id=' . $data['task_id']);
    }

    public function taskEditDeveloper() {
        $data = $this->request->input();

        if (isset($data['complete_id']) && $data['complete_id'] != '') {
            Tacks::taskEditDeveloper($data);
        } else {
            Tacks::taskEditDeveloperChDate($data);
        }

        $task_unit = Tacks::getTaskUnit($data['task_id']);

        $idNotice = null;
        //создаем уведомление
        if (isset($data['complete_id']) && $data['complete_id'] != '') {
            //Первое уведомление отправляем автору заявки - оператору
            $dataNotice = [];
            $dataNotice['task_off'] = $data['task_off'];
            $dataNotice['complete_id'] = $data['complete_id'];
            $dataNotice['recipient'] = $task_unit[0]->users_id;
            $dataNotice['task_id'] = $data['task_id'];
            //create notice for user
            $idNotice = Notice::addNotice($dataNotice);

            //второе уведомление должно быть направлено админу разрабов
            // но если это не Доработка у оператора
            if ($data['complete_id'] != 6) {
                $dataNotice = [];
                $dataNotice['task_off'] = $data['task_off'];
                $dataNotice['complete_id'] = $data['complete_id'];
                //получаем всех админов разработчиков
                $listUsersAdmin = Users::getUsersListByStatus(11);
                if (count($listUsersAdmin) > 0) {
                    $dataNotice['recipient'] = $listUsersAdmin[0]->id;
                }
                $dataNotice['task_id'] = $data['task_id'];
                //create notice for user
                Notice::addNotice($dataNotice);
            }
        }

        if (isset($idNotice)) {
            $modelNotice = Notice::getNotice($idNotice);

            $task_mess = $modelNotice[0]->description;

            if ($task_mess != '') {
                broadcast(new chenTaskUser([
                    'task' => $data['task_id'],
                    'host' => $task_unit[0]->users_id,
                    'title' => $task_unit[0]->task_name,
                    'task_mess' => $task_mess
                ]));
            }
        }

        return redirect('/my_tack_page_operator?task_operator_page_id=' . $data['task_id']);
    }

    public function tackOperator() {
        $data = $this->request->input();
        $task = Tacks::getTask($data['task_operator_page_id']);

        //2019-12-20 Nikolay
        //с появлением уведомлений добавил кое-какой функционал сюда
        //меняем статус о прочтении уведомления и записываем дату
        if (isset($data['notice_id'])) {
            Notice::readNotice($data['notice_id']);
        }

        $add_filds = Tacks::getAddFilds();
        $add_plu = (array)json_decode($task[0]->task_add);

        $task_files = Tacks::renderTaskFiles($data['task_operator_page_id']);

        $developers = Tacks::getDevelopers();

        if (count($add_filds) > 0) {
            $add = [];
            foreach ($add_filds AS $fild) {
                foreach ($add_plu AS $_k => $_v) {
                    if ($_k == $fild->form_alias) {
                        $add[] = ['name' => $fild->form_name, 'val' => $_v];
                    }
                }
            }
        }

        return view('admin.tacks.my_tack_page_operator', compact([
            'task',
            'task_files',
            'developers',
            'add'
        ]));
    }

    public function tackEditForm()
    {
        $list = Tacks::getGov();
        $form = Tacks::getForm();
        $gov = Tacks::getGovGroup();
        $p_name = Tacks::getProcessName();
        $prior = Tacks::getPriors();
        return view('admin.tacks.tack_form_edit_page', compact(['list', 'form', 'gov', 'p_name', 'prior']));
    }

    public function tackConsOperator()
    {
        $data = $this->request->input();
        $task = Tacks::getTask($data['task_page_id']);

        $add_filds = Tacks::getAddFilds();
        $add_plu = (array)json_decode($task[0]->task_add);

        $task_files = Tacks::renderTaskFiles($data['task_page_id']);

        $developers = Tacks::getDevelopers();

        if (count($add_filds) > 0) {

            $add = [];
            foreach ($add_filds AS $fild) {
                foreach ($add_plu AS $_k => $_v) {
                    if ($_k == $fild->form_alias) {
                        $add[] = ['name' => $fild->form_name, 'val' => $_v];
                    }
                }
            }
        }

        return view('admin.tacks.tack_cons_page', compact([
            'task',
            'task_files',
            'developers',
            'add'
        ]));
    }

    public function govAdd()
    {
        $data = $this->request->input();
        Tacks::addGov($data);
    }

    public function govGroupAdd()
    {
        $data = $this->request->input();
        Tacks::addGovRole($data);
    }

    public function govDel()
    {
        Tacks::delGov($this->request->input('id'));
    }

    public function govGroupDel()
    {
        Tacks::delGovRole($this->request->input('gov_group_id'));
    }

    public function formAdd()
    {
        $data = $this->request->input();
        Tacks::addForm($data);
    }

    public function formDel()
    {
        Tacks::delForm($this->request->input('id'));
    }

    public function getGovOrgans()
    {
        return Tacks::getGov();
    }

    public function getRolesGroup()
    {
        $result = [];
        $arr = Tacks::getGovGroup();

        foreach ($arr AS $_k => $_v) {
            $result[$_k] = $_v->gov_group_name;
        }
        return $result;
    }

    public function getGovOrgansVue()
    {
        $data = $this->request->input();
        return Tacks::getGovVue($data);
    }

    public function getPhoneNumbsVue() {
        $data = $this->request->input();
        return Tacks::getPhoneNumbsVue($data);
    }

    public function getLoginClients()
    {
        $data = $this->request->input();
        return Tacks::getLogin($data);
    }

    public function getMailClients()
    {
        $data = $this->request->input();
        return Tacks::getMail($data);
    }

    public function getSingleClients()
    {
        $data = $this->request->input();
        return Tacks::getLoginUnit($data);
    }

    public function getSingleMailClients()
    {
        $data = $this->request->input();
        return Tacks::getLoginMailUnit($data);
    }

    public function getRegionName()
    {
      $data = $this->request->input();
      return Region::getRegionByTelephone($data);
    }

    public function tackAddApi()
    {
        $data = $this->request->input();
        //$add_form = [];
        //$add = Tacks::getSubTasks();
        $date_off = explode('.', $data['date_complete']);

        $data['users_id'] = $this->request->user()->id;

        if ($data['tack_type'] != 'error_tack') {
            $checker = Tacks::getUserForLogin([
              'client_login' => $data['client_login']
            ]);

            if (count($checker) == 0) {
                $data['user_client_id'] = Tacks::addUserClient($data);
            } else {
                Tacks::checkChangeAndUpdate($data, $checker[0]->id);
                $data['user_client_id'] = $checker[0]->id;
            }

            //2020-03-11 Kanat
            // связка телефон и региона
            Region::checkChangeAndUpdate($data['client_phone'], $data['task_district']);

//            if (count($add) > 0) {
//                foreach ($add AS $unit) {
//                    $add_form[$unit->form_alias] = $data['add_' . $unit->form_alias];
//                }
//            }

            $task['users_id'] = $data['users_id'];
            $task['client_id'] = $data['user_client_id'];
            $task['task_type'] = $data['tack_type'];
            $task['task_priority'] = $data['priority'];
            $task['task_name'] = $data['process_name'];
            $task['task_off'] = $date_off[2] . '-' . $date_off[1] . '-' . $date_off[0];
            $task['task_term'] = $data['term_complete'];
            $task['task_district'] = $data['task_district'];
            $task['task_add'] = '{"algorithm":null,"problem":'.json_encode($data['add_problem_request']).'}';

            $task['created_at'] = Carbon::now();
        } else {
            $task['users_id'] = $data['users_id'];
            $task['task_type'] = $data['tack_type'];
            $task['created_at'] = Carbon::now();
        }

        $task_id = Tacks::addTaskClient($task);

        if (isset($this->request->file()['file'])) {
            foreach ($this->request->file()['file'] AS $_k => $file) {

                $name = $file->getClientOriginalName();
                $check_name = explode('.', $name);
                $resol = array_pop($check_name);

                if (($resol == 'doc' ||
                        $resol == 'xls' ||
                        $resol == 'ppt' ||
                        $resol == 'docx' ||
                        $resol == 'xlsx' ||
                        $resol == 'pptx' ||
                        $resol == 'pdf' ||
                        $resol == 'jpg' ||
                        $resol == 'jpeg' ||
                        $resol == 'png' ||
                        $resol == 'gif') && $file->getSize() <= 29000000 && $task_id > 0) {

                    $doc['origin_name'] = $file->getClientOriginalName();
                    $doc['file_name'] = $this->translitIt($file->getClientOriginalName()) . rand(111111, 999999) . '.' . $resol;
                    $doc['resol'] = $resol;
                    $doc['task_id'] = $task_id;
                    $doc['created_at'] = $task['created_at'];

                    Tacks::addTasksFiles($doc);
                    Storage::disk('clientsData')->putFileAs($data['user_client_id'] . '/' . $task_id, $file, $doc['file_name']);
                }
            }
        }

        //    if ($task['task_type'] == 'request_tack') {
        //      $mail_to = Tacks::mailToCallBack($task['client_id']);
        //      Mail::send('emails.call_back', ['task_id' => $task_id], function ($message) use ($mail_to) {
        //        $message->to($mail_to[0]->client_mail, '')->subject('Ваша заявка принята');
        //        $message->from(env('APP_MAIL', 'robot@akkad.ru'), 'Контакт центр');
        //      });
        //    }

        //Nikolay 2020-03-02
        //создаем уведомление для администратора разработчиков
        // для того чтобы он в дальнейшем мог перераспределить задачу.
        // для начала получаем всех админов разработчиков
        $listUsersAdmin = Users::getUsersListByStatus(11);
        if (count($listUsersAdmin) > 0 && "request_tack" == $data['tack_type']) {
            $dataNotice = [];
            $dataNotice['task_off'] = $data['date_complete'];
            $dataNotice['complete_id'] = 4;
            $dataNotice['recipient'] = $listUsersAdmin[0]->id;
            $dataNotice['task_id'] = $task_id;
            //create notice for user
            Notice::addNotice($dataNotice);

            //создаем первую запись истории
            $task_comment = 'Создана заявка. Заявке присвоен №' . $task_id . '.';
            $user_id = $data['users_id'];
            Tacks::addMess(compact(['task_comment', 'task_id', 'user_id']));
        }

        return view('admin.tacks.tack_add_complete', compact([]));
    }

    public function uploadAdditions()
    {
        $data = $this->request->input();

        foreach ($this->request->file() AS $file) {

            $resol = $file->getClientOriginalExtension();

            if (($resol == 'doc' ||
                    $resol == 'xls' ||
                    $resol == 'ppt' ||
                    $resol == 'docx' ||
                    $resol == 'xlsx' ||
                    $resol == 'pptx' ||
                    $resol == 'pdf' ||
                    $resol == 'jpg' ||
                    $resol == 'jpeg' ||
                    $resol == 'png' ||
                    $resol == 'gif') && $file->getSize() <= 29000000 && $data['id_task'] > 0 && $data['id_client'] > 0) {

                $doc['origin_name'] = $file->getClientOriginalName();
                $doc['file_name'] = $this->translitIt($file->getClientOriginalName()) . rand(111111, 999999) . '.' . $resol;
                $doc['resol'] = $resol;
                $doc['task_id'] = $data['id_task'];
                $doc['created_at'] = Carbon::now();

                Tacks::addTasksFiles($doc);
                Storage::disk('clientsData')->putFileAs($data['id_client'] . '/' . $data['id_task'], $file, $doc['file_name']);
            }
        }
    }

    public function checkUserForId()
    {
        $data = $this->request->input();
        $res = Tacks::getUserForId($data);
        print json_encode($res);
    }

    public function checkUserForLogin()
    {
        $data = $this->request->input();
        $res = Tacks::getUserForLogin($data);
        print json_encode($res);
    }

    public function taskDel()
    {
        $id = $this->request->input('id');
        $client_id = $this->request->input('client_id');

        Tacks::deleteTask($id);

        Storage::disk('clientsData')
            ->deleteDirectory('/' . $client_id . '/' . $id);
        Storage::disk('clientsData')
            ->delete('/' . $client_id . '/' . $id);
    }

    public function taskHasOperator()
    {
        $id = (int)$this->request->input('id');
        $users_id = (int)$this->request->input('users_id');

//    return [$id, $users_id];

        return Tacks::hasOperator(compact(['id', 'users_id']));
    }

  /**
   * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View|\Maatwebsite\Excel\BinaryFileResponse|\Symfony\Component\HttpFoundation\BinaryFileResponse
   */
    public function callListPage()
    {
        // start
        if ($this->request->input('date_task_start') && $this->request->input('date_task_start') != '') {
            $date_task_start = $this->request->input('date_task_start');
            Cache::forget('date_task_start' . auth()->id());
            Cache::forever('date_task_start' . auth()->id(), $date_task_start);
        } else if (Cache::has('date_task_start' . auth()->id()) && $this->request->input('page') != '') {
            $date_task_start = Cache::get('date_task_start' . auth()->id());
        } else {
            $date_task_start = date('d.m.Y', strtotime(Carbon::now()->addDay(-6)));
            Cache::forget('date_task_start' . auth()->id());
            Cache::forever('date_task_start' . auth()->id(), $date_task_start);
        }

        // end
        if ($this->request->input('date_task_end') && $this->request->input('date_task_end') != '') {
            $date_task_end = $this->request->input('date_task_end');
            Cache::forget('date_task_end' . auth()->id());
            Cache::forever('date_task_end' . auth()->id(), $date_task_end);
        } else if (Cache::has('date_task_end' . auth()->id()) && $this->request->input('page') != '') {
            $date_task_end = Cache::get('date_task_end' . auth()->id());
        } else {
            $date_task_end = date('d.m.Y');
            Cache::forget('date_task_end' . auth()->id());
            Cache::forever('date_task_end' . auth()->id(), $date_task_end);
        }
        $start = $date_task_start;
        $end = $date_task_end;

        // Оператор
        if ($this->request->input('operator') && $this->request->input('operator') != '') {
            $operator = $this->request->input('operator');
            Cache::forget('operator' . auth()->id());
            Cache::forever('operator' . auth()->id(), $operator);
        } else if (Cache::has('operator' . auth()->id()) && $this->request->input('page') != '') {
            $operator = Cache::get('operator' . auth()->id());
        } else {
            $operator = '';
            Cache::forget('operator' . auth()->id());
            Cache::forever('operator' . auth()->id(), $operator);
        }

        // Входящий номер
        $caller = '';
        if ($this->request->input('caller') && $this->request->input('caller') != '') {
            $caller = $this->request->input('caller');
            Cache::forget('caller' . auth()->id());
            Cache::forever('caller' . auth()->id(), $caller);
        } else if (Cache::has('caller' . auth()->id()) && $this->request->input('page') != '') {
            $caller = Cache::get('caller' . auth()->id());
        } else {
            $caller = '';
            Cache::forget('caller' . auth()->id());
            Cache::forever('caller' . auth()->id(), $caller);
        }

        // type-call
        if ($this->request->input('type-call') && $this->request->input('type-call') != '') {
            $typeCall = $this->request->input('type-call');
            Cache::forget('type-call' . auth()->id());
            Cache::forever('type-call' . auth()->id(), $typeCall);
        } else if (Cache::has('type-call' . auth()->id()) && $this->request->input('page') != '') {
            $typeCall = Cache::get('type-call' . auth()->id());
        } else {
            $typeCall = '';
            Cache::forget('type-call' . auth()->id());
            Cache::forever('type-call' . auth()->id(), $typeCall);
        }

        $i = 1;

        if ($operator == 'all') {
            $operator = '';
        }

        $load_exel = $this->request->input('load_exel');

        $users = Tacks::getOperators();
        $calls = Tacks::getCalls(compact([
            'load_exel',
            'start',
            'operator',
            'end',
            'caller',
            'typeCall',
        ]));
        $calls_arr = [];
        $callers = [];

        if (count($calls) > 0) {

          foreach ($calls AS $_k => $call) {

            //$calls_arr[$call->linkedid]['user'] = '';
            /*
             * поле dst_billsec_rec
             * содержит dst, billsec, recordfile
             * поэтому распарсим и запишем эти значения в ответ
             * пример записи
             *
                  4105|0|,4107|0|,4108|0|,4104|95|,
                  2000|44|q-2000-87142535240-20191203-191540-1575378940.25815.wav
                   4111|118|,2000|171|q-2000-87172786895-20191203-170038-1575370838.25111.wav

             *
             * */
            $dst_bill_rec = $call->dst_billsec_rec;
            $items = explode(',', $dst_bill_rec);
            $call_time = 0;
            //записываем операторов и время звонка
            foreach ($items  AS $item) {
              $sp_item = [];
              //пропускаем в списке звонков 2000 номер
              $sp_item = explode('|', $item);

              if(count($items) != 1 && $sp_item[0] == 2000){
                continue;
              }
              //этот форич мне не нравится в дальнейшем нужно переделать на мапу и брать пользователя через гет или же в sql пользователя за джоинить
              foreach ($users AS $user) {
                if ((int)$user->users_phone === (int)$sp_item[0]) {
                  $calls_arr[$call->linkedid]['operators'][$sp_item[0]]['user'] = $user->name;
                }
              }

              $calls_arr[$call->linkedid]['operators'][$sp_item[0]]['time'] = $this->dataFormater($sp_item[1]);
              $calls_arr[$call->linkedid]['operators'][$sp_item[0]]['dst'] = [$sp_item[0]];
              //суммируем время
              $call_time += $sp_item[1];

            }
            /*
             * это проверка для вот таких случаев пока не знаю что с ними делать
             * 2000|12|,2000|283|q-2000-87172546134-20191115-152002-1573809576.145813.wav,2000|388|q-2000-87172546134-20191115-151818-1573809498.145802.wav
             * получается оператор не принял звонок
             * */
            if(!isset($calls_arr[$call->linkedid]['operators'])){
              $item = $items[count($items) - 1];
              $sp_item = explode('|', $item);
              $calls_arr[$call->linkedid]['operators'][$sp_item[0]]['time'] = $this->dataFormater($sp_item[1]);
              $calls_arr[$call->linkedid]['operators'][$sp_item[0]]['dst'] = [$sp_item[0]];
            }
            //запись разговора это последний элемент $items
            $record = explode('-', $items[count($items) - 1]);
            if (count($record) > 5) {
              $r_p = str_split($record[3]);
              $calls_arr[$call->linkedid]['link'] = '/calls/' . $r_p[0] . $r_p[1] . $r_p[2] . $r_p[3] . '/' . $r_p[4] . $r_p[5] . '/' . $r_p[6] . $r_p[7] . '/' . $sp_item[2];
            }else{
              $calls_arr[$call->linkedid]['link'] = '';
            }
            //длительность звонка
            $calls_arr[$call->linkedid]['duration'] = $this->dataFormater($call->sum_billsec);
            $date_call = explode(' ', $call->calldate);
            $calls_arr[$call->linkedid]['calldate'] = $date_call[0];
            $calls_arr[$call->linkedid]['calltime'] = $date_call[1] . '.';
            $calls_arr[$call->linkedid]['src'] = $call->src;
            if($call->sum_billsec == 0){
              $calls_arr[$call->linkedid]['disposition'] = 'NO ANSWER';}
            else{
              $calls_arr[$call->linkedid]['disposition'] = '';
            }



            $calls_arr[$call->linkedid]['playback'] = Tacks::getPlayback($call->uniqueid);
            $callers[$call->src] = $call->src;
          }
        }

        if ($this->request->input('load_exel') > 0) {
            //создаем новую книгу
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            //формируем шапочку документа
            $sheet->setCellValue('A1', '№');
            $sheet->setCellValue('B1', 'Дата');
            $sheet->setCellValue('C1', 'Оператор');
            $sheet->setCellValue('D1', 'От кого');
            $sheet->setCellValue('E1', 'Звонок (сек)');
            $sheet->setCellValue('F1', 'Оценка');

            //Устанавливаем стили для шапки
            $spreadsheet->getActiveSheet()->getStyle('A1:F1')->applyFromArray($this->getHeadTableStyle());

            $i = 1;
            $index = 2;
            foreach ($calls_arr as $obj) {
                $sheet->setCellValue('A'.$index, $i++);
                $sheet->setCellValue('B'.$index, $obj['calldate']."   ".$obj['calltime']);
                $textUsers = '';
                foreach($obj['operators'] AS $operator) {
                    $dst = $operator['dst'][0];
                    if (array_key_exists("user", $operator)) {
                        $userName = $operator['user'];
                    } else {
                        $userName = '';
                    }
                    if ($textUsers == '') {
                        $textUsers = $dst.' ('.$userName.')';
                    } else {
                        $textUsers = $textUsers.' '.$dst.' ('.$userName.')';
                    }
                }
                $sheet->setCellValue('C'.$index, $textUsers);
                $sheet->setCellValue('D'.$index, $obj['src']);
                if($obj['disposition'] =='NO ANSWER') {
                    $sheet->setCellValue('E'.$index, "Пропущеный");
                } else {
                    $sheet->setCellValue('E'.$index, $obj['duration']);
                }
                $sheet->setCellValue('F'.$index, $obj['playback']);

                $index++;
            }

            //Установка выравнивания по правой стороне
            $spreadsheet->getActiveSheet()->getStyle('A2:K'.$index)
                ->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);

            //Установка переноса текста
            $spreadsheet->getActiveSheet()->getStyle('B2:K'.$index)
                ->getAlignment()->setWrapText(true);

            //Установка ширины столбца
            $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(6);
            $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(20);
            $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(29);
            $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(13);
            $spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(13);
            $spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(9);

            $writer = new Xlsx($spreadsheet);
            $writer->save('Список Звонков.xlsx');
            return response()->download(public_path('Список Звонков.xlsx'));
        } else {
            return view('admin.tacks.call_list_page', compact([
                'i',
                'operator',
                'callers',
                'caller',
                'users',
                'start',
                'calls',
                'calls_arr',
                'end',
                'typeCall'
            ]));
        }
    }

    public function callUserPage()
    {
        // start
        if ($this->request->input('date_task_start') && $this->request->input('date_task_start') != '') {
            $date_task_start = $this->request->input('date_task_start');
            Cache::forget('date_task_start' . auth()->id());
            Cache::forever('date_task_start' . auth()->id(), $date_task_start);
        } else if (Cache::has('date_task_start' . auth()->id()) && $this->request->input('page') != '') {
            $date_task_start = Cache::get('date_task_start' . auth()->id());
        } else {
            $date_task_start = date('d.m.Y', strtotime(Carbon::now()->addDay(-6)));
            Cache::forget('date_task_start' . auth()->id());
            Cache::forever('date_task_start' . auth()->id(), $date_task_start);
        }

        // end
        if ($this->request->input('date_task_end') && $this->request->input('date_task_end') != '') {
            $date_task_end = $this->request->input('date_task_end');
            Cache::forget('date_task_end' . auth()->id());
            Cache::forever('date_task_end' . auth()->id(), $date_task_end);
        } else if (Cache::has('date_task_end' . auth()->id()) && $this->request->input('page') != '') {
            $date_task_end = Cache::get('date_task_end' . auth()->id());
        } else {
            $date_task_end = date('d.m.Y');
            Cache::forget('date_task_end' . auth()->id());
            Cache::forever('date_task_end' . auth()->id(), $date_task_end);
        }

        // type-call
        if ($this->request->input('type-call') && $this->request->input('type-call') != '') {
            $typeCall = $this->request->input('type-call');
            Cache::forget('type-call' . auth()->id());
            Cache::forever('type-call' . auth()->id(), $typeCall);
        } else if (Cache::has('type-call' . auth()->id()) && $this->request->input('page') != '') {
            $typeCall = Cache::get('type-call' . auth()->id());
        } else {
            $typeCall = '';
            Cache::forget('type-call' . auth()->id());
            Cache::forever('type-call' . auth()->id(), $typeCall);
        }

        $start = $date_task_start;
        $end = $date_task_end;
        $operator = Tacks::getOperatorFromId(auth()->id());

        // Входящий номер
        if ($this->request->input('caller') && $this->request->input('caller') != '') {
            $caller = $this->request->input('caller');
            Cache::forget('caller' . auth()->id());
            Cache::forever('caller' . auth()->id(), $caller);
        } else if (Cache::has('caller' . auth()->id()) && $this->request->input('page') != '') {
            $caller = Cache::get('caller' . auth()->id());
        } else {
            $caller = '';
            Cache::forget('caller');
            Cache::forever('caller', $caller);
        }

        $i = 1;
        $users = Tacks::getOperators();
        $calls = Tacks::getCalls(compact([
            'start',
            'operator',
            'end',
            'caller',
            'typeCall',
        ]));
        $calls_arr = [];
        $callers = [];

        if (count($calls) > 0) {
          foreach ($calls AS $_k => $call) {

            //$calls_arr[$call->linkedid]['user'] = '';
            /*
             * поле dst_billsec_rec
             * содержит dst, billsec, recordfile
             * поэтому распарсим и запишем эти значения в ответ
             * пример записи
             *
                  4105|0|,4107|0|,4108|0|,4104|95|,
                  2000|44|q-2000-87142535240-20191203-191540-1575378940.25815.wav
                   4111|118|,2000|171|q-2000-87172786895-20191203-170038-1575370838.25111.wav

             *
             * */
            $dst_bill_rec = $call->dst_billsec_rec;
            $items = explode(',', $dst_bill_rec);
            $call_time = 0;
            //записываем операторов и время звонка
            foreach ($items  AS $item) {
              $sp_item = [];
              //пропускаем в списке звонков 2000 номер
              $sp_item = explode('|', $item);

              if(count($items) != 1 && $sp_item[0] == 2000){
                continue;
              }
              //этот форич мне не нравится в дальнейшем нужно переделать на мапу и брать пользователя через гет или же в sql пользователя за джоинить
              foreach ($users AS $user) {
                if ((int)$user->users_phone === (int)$sp_item[0]) {
                  $calls_arr[$call->linkedid]['operators'][$sp_item[0]]['user'] = $user->name;
                }
              }

              $calls_arr[$call->linkedid]['operators'][$sp_item[0]]['time'] = $this->dataFormater($sp_item[1]);
              $calls_arr[$call->linkedid]['operators'][$sp_item[0]]['dst'] = [$sp_item[0]];
              //суммируем время
              $call_time += $sp_item[1];

            }
            /*
             * это проверка для вот таких случаев пока не знаю что с ними делать
             * 2000|12|,2000|283|q-2000-87172546134-20191115-152002-1573809576.145813.wav,2000|388|q-2000-87172546134-20191115-151818-1573809498.145802.wav
             * получается оператор не принял звонок
             * */
            if(!isset($calls_arr[$call->linkedid]['operators'])){
              $item = $items[count($items) - 1];
              $sp_item = explode('|', $item);
              $calls_arr[$call->linkedid]['operators'][$sp_item[0]]['time'] = $this->dataFormater($sp_item[1]);
              $calls_arr[$call->linkedid]['operators'][$sp_item[0]]['dst'] = [$sp_item[0]];
            }
            //запись разговора это последний элемент $items
            $record = explode('-', $items[count($items) - 1]);
            if (count($record) > 5) {
              $r_p = str_split($record[3]);
              $calls_arr[$call->linkedid]['link'] = '/calls/' . $r_p[0] . $r_p[1] . $r_p[2] . $r_p[3] . '/' . $r_p[4] . $r_p[5] . '/' . $r_p[6] . $r_p[7] . '/' . $sp_item[2];
            }else{
              $calls_arr[$call->linkedid]['link'] = '';
            }
            $calls_arr[$call->linkedid]['duration'] = $this->dataFormater($call->sum_billsec);

            $date_call = explode(' ', $call->calldate);

            $calls_arr[$call->linkedid]['calldate'] = $date_call[0];
            $calls_arr[$call->linkedid]['calltime'] = $date_call[1] . '.';
            $calls_arr[$call->linkedid]['src'] = $call->src;
            $calls_arr[$call->linkedid]['dst'] = $call->dst;
            //$calls_arr[$call->linkedid]['operators'][$call->dst]['dst'] = $call->dst;
            if($call->sum_billsec == 0){
              $calls_arr[$call->linkedid]['disposition'] = 'NO ANSWER';}
            else{
              $calls_arr[$call->linkedid]['disposition'] = '';
            }
            $calls_arr[$call->linkedid]['playback'] = Tacks::getPlayback($call->uniqueid);
            $callers[$call->src] = $call->src;
          }
        }

        return view('admin.tacks.call_user_page', compact([
            'i',
            'callers',
            'caller',
            'users',
            'start',
            'calls',
            'calls_arr',
            'end',
            'typeCall',
            'operator'
        ]));
    }

    public function addTaskMess()
    {
        $task_comment = $this->request->input('task_comment');
        $task_id = (int)$this->request->input('task_id');
        $user_id = (int)$this->request->input('user_id');
        return Tacks::addMess(compact(['task_comment', 'task_id', 'user_id']));
    }

    public function chDataComplete()
    {
        $new_date = $this->request->input('new_date');
        $justification = $this->request->input('justification');
        $task_id = (int)$this->request->input('task_id');
        $user_id = (int)$this->request->input('users_id');
        $users_name = $this->request->input('users_name');

        $task_comment = 'Дата окончания работ по данной Задаче изменена на <b>' . $new_date . '</b>. <u>Комментарий:</u> ' . $justification;

        return Tacks::addMess(compact(['task_comment', 'task_id', 'user_id']));
    }

    public function chDataWorkStage() {
        $new_complete = $this->request->input('new_complete');
        $comment_complete = $this->request->input('comment_complete');
        $task_id = (int)$this->request->input('task_id');
        $user_id = (int)$this->request->input('users_id');
        $users_name = $this->request->input('users_name');

        $nameWorkStage = '';
        //type complete:
        //1 - Назначен Исполнитель
        //2 - Принято в работу
        //3 - Решено
        //4 - Не начат
        //5 - Повторное исполнение
        //6 - На доработке у Оператора
        //7 - Консультация
        //8 - Не повторилась
        //9 - Не актуальная
        if($new_complete == '1') {
            $nameWorkStage = 'Назначен Исполнитель';
        } elseif($new_complete == '2') {
            $nameWorkStage = 'Принято в работу';
        } elseif($new_complete == '3') {
            $nameWorkStage = 'Решено';
        } elseif($new_complete == '4') {
            $nameWorkStage = 'Не начат';
        } elseif($new_complete == '5') {
            $nameWorkStage = 'Повторное исполнение';
        } elseif($new_complete == '6') {
            $nameWorkStage = 'На доработке у Оператора';
        } elseif($new_complete == '7') {
            $nameWorkStage = 'Консультация';
        } elseif($new_complete == '8') {
            $nameWorkStage = 'Не повторилась';
        } elseif($new_complete == '9') {
            $nameWorkStage = 'Не актуальная';
        }

        $task_comment = 'Изменена стадия работы на <b>' . $nameWorkStage . '</b>. <u>Комментарий:</u> ' . $comment_complete;

        return Tacks::addMess(compact(['task_comment', 'task_id', 'user_id']));
    }

    public function chResponseComplete() {
        $comment_complete = $this->request->input('comment_complete');
        $task_off = $this->request->input('task_off');
        $task_id = (int)$this->request->input('task_id');
        $user_id = (int)$this->request->input('users_id');
        $users_name = $this->request->input('users_name');
        $task_comment = 'Оператор вернул на исполнение заявку. <u>Комментарий:</u> ' . $comment_complete;

        //создадим уведомление для исполнителя или админа оператора
        $task_unit = Tacks::getTaskUnit($task_id);
        if ($task_unit[0]->developer_id != null) {
            //1 - Назначен Исполнитель
            $complete_id = 1;
            //если уже имеется назначенный разработчик
            $recipient_id = $task_unit[0]->developer_id;
        } else {
            //4 - Не начат
            $complete_id = 4;
            //если нету разработчика, то отправим админу разрабов
            //получаем всех админов разработчиков
            $listUsersAdmin = Users::getUsersListByStatus(11);
            if (count($listUsersAdmin) > 0) {
                $recipient_id = $listUsersAdmin[0]->id;
            }
        }
        $dataNotice = [];
        $dataNotice['task_off'] = '';
        $dataNotice['avtor_id'] = $user_id;
        $dataNotice['complete_id'] = '';
        $dataNotice['recipient'] = $recipient_id;
        $dataNotice['task_id'] = $task_id;
        $dataNotice['name'] = 'Информация о заявке №' . $task_id;
        $dataNotice['description'] = 'Оператор дал ответ и вернул Заявку №' . $task_id.' на исполнение.';
        //create notice for user
        Notice::addNotice($dataNotice);

        //меняем стадию работы у заявки
        $data = [];
        $data['task_id'] = $task_id;
        $data['complete_id'] = $complete_id;
        $data['task_off'] = $task_off;
        $data['task_priority'] = $task_unit[0]->task_priority;
        Tacks::taskEditDeveloper($data);

        return Tacks::addMess(compact(['task_comment', 'task_id', 'user_id']));
    }

    public function processNamesAdd()
    {
        $process = $this->request->input('process');
        return Tacks::addNames(compact(['process']));
    }

    public function processNamesDel()
    {
        $id = $this->request->input('id');
        return Tacks::delNames(compact(['id']));
    }

    public function getTaskMess()
    {
        $task_id = (int)$this->request->input('task_id');
        return Tacks::getMess(compact(['task_id']));
    }

    public function priorDays()
    {
        $data = $this->request->input();

        Tacks::priorDaysCh(['id' => 1, 'prior' => $data['big']]);
        Tacks::priorDaysCh(['id' => 2, 'prior' => $data['mid']]);
        Tacks::priorDaysCh(['id' => 3, 'prior' => $data['low']]);
    }

    function translitIt($str_name)
    {
        $tr = [
            "А" => "a",
            "Б" => "b",
            "В" => "v",
            "Г" => "g",
            "Д" => "d",
            "Е" => "e",
            "Ё" => "e",
            "Ж" => "j",
            "З" => "z",
            "И" => "i",
            "Й" => "y",
            "К" => "k",
            "Л" => "l",
            "М" => "m",
            "Н" => "n",
            "О" => "o",
            "П" => "p",
            "Р" => "r",
            "С" => "s",
            "Т" => "t",
            "У" => "u",
            "Ф" => "f",
            "Х" => "h",
            "Ц" => "ts",
            "Ч" => "ch",
            "Ш" => "sh",
            "Щ" => "sch",
            "Ъ" => "",
            "Ы" => "yi",
            "Ь" => "",
            "Э" => "e",
            "Ю" => "yu",
            "Я" => "ya",
            "а" => "a",
            "б" => "b",
            "в" => "v",
            "г" => "g",
            "д" => "d",
            "ё" => "e",
            "е" => "e",
            "ж" => "j",
            "з" => "z",
            "и" => "i",
            "й" => "y",
            "к" => "k",
            "л" => "l",
            "м" => "m",
            "н" => "n",
            "о" => "o",
            "п" => "p",
            "р" => "r",
            "с" => "s",
            "т" => "t",
            "у" => "u",
            "ф" => "f",
            "х" => "h",
            "ц" => "ts",
            "ч" => "ch",
            "ш" => "sh",
            "щ" => "sch",
            "ъ" => "y",
            "ы" => "yi",
            "ь" => "",
            "э" => "e",
            "ю" => "yu",
            "я" => "ya",
            " " => "-",
            "." => "",
            "/" => "-",
            "№" => "-N-",
            "+" => "-plus-",
            "'" => "",
            '"' => "",
            '&quot;' => "",
            '?' => "",
            '%' => ""
        ];
        return strtr($str_name, $tr);
    }

    private function dataFormater($count_sec)
    {
        //    $hour = floor($count_sec / 3600);
        //    $sec = $count_sec - ($hour * 3600);
        //    $min = floor($sec / 60);
        //    $sec = $sec - ($min * 60);

        return sprintf('%02d:%02d:%02d', $count_sec / 3600, ($count_sec % 3600) / 60, ($count_sec % 3600) % 60);
    }

    public function getCalls(){
      // start
      if ($this->request->input('date_task_start') && $this->request->input('date_task_start') != '') {
        $date_task_start = $this->request->input('date_task_start');
        Cache::forget('date_task_start' . auth()->id());
        Cache::forever('date_task_start' . auth()->id(), $date_task_start);
      } else if (Cache::has('date_task_start' . auth()->id()) && $this->request->input('page') != '') {
        $date_task_start = Cache::get('date_task_start' . auth()->id());
      } else {
        $date_task_start = date('d.m.Y', strtotime(Carbon::now()->addDay(-6)));
        Cache::forget('date_task_start' . auth()->id());
        Cache::forever('date_task_start' . auth()->id(), $date_task_start);
      }

      // end
      if ($this->request->input('date_task_end') && $this->request->input('date_task_end') != '') {
        $date_task_end = $this->request->input('date_task_end');
        Cache::forget('date_task_end' . auth()->id());
        Cache::forever('date_task_end' . auth()->id(), $date_task_end);
      } else if (Cache::has('date_task_end' . auth()->id()) && $this->request->input('page') != '') {
        $date_task_end = Cache::get('date_task_end' . auth()->id());
      } else {
        $date_task_end = date('d.m.Y');
        Cache::forget('date_task_end' . auth()->id());
        Cache::forever('date_task_end' . auth()->id(), $date_task_end);
      }
      $start = $date_task_start;
      $end = $date_task_end;

      // Оператор
      if ($this->request->input('operator') && $this->request->input('operator') != '') {
        $operator = $this->request->input('operator');
        Cache::forget('operator' . auth()->id());
        Cache::forever('operator' . auth()->id(), $operator);
      } else if (Cache::has('operator' . auth()->id()) && $this->request->input('page') != '') {
        $operator = Cache::get('operator' . auth()->id());
      } else {
        $operator = '';
        Cache::forget('operator' . auth()->id());
        Cache::forever('operator' . auth()->id(), $operator);
      }

      // Входящий номер
      $caller = '';
      if ($this->request->input('caller') && $this->request->input('caller') != '') {
        $caller = $this->request->input('caller');
        Cache::forget('caller' . auth()->id());
        Cache::forever('caller' . auth()->id(), $caller);
      } else if (Cache::has('caller' . auth()->id()) && $this->request->input('page') != '') {
        $caller = Cache::get('caller' . auth()->id());
      } else {
        $caller = '';
        Cache::forget('caller' . auth()->id());
        Cache::forever('caller' . auth()->id(), $caller);
      }

      // type-call
      if ($this->request->input('type-call') && $this->request->input('type-call') != '') {
        $typeCall = $this->request->input('type-call');
        Cache::forget('type-call' . auth()->id());
        Cache::forever('type-call' . auth()->id(), $typeCall);
      } else if (Cache::has('type-call' . auth()->id()) && $this->request->input('page') != '') {
        $typeCall = Cache::get('type-call' . auth()->id());
      } else {
        $typeCall = '';
        Cache::forget('type-call' . auth()->id());
        Cache::forever('type-call' . auth()->id(), $typeCall);
      }

      $i = 1;

      if ($operator == 'all') {
        $operator = '';
      }

      $load_exel = $this->request->input('load_exel');

      $users = Tacks::getOperators();

      $limit = $this->request->limit ? $this->request->limit : 10;
      $offset = $this->request->offset ? $this->request->offset : 0;

      $count = Tacks::getCalls(compact([
        'load_exel',
        'start',
        'operator',
        'end',
        'caller',
        'typeCall'
      ]));

      $calls = Tacks::getCalls(compact([
        'load_exel',
        'start',
        'operator',
        'end',
        'caller',
        'typeCall',
        'limit',
        'offset',
      ]));
      $calls_arr = [];
      $resp_arr = [];
      $id = $offset;
      if (count($calls) > 0) {
        foreach ($calls AS $_k => $call) {

          //$calls_arr[$call->linkedid]['user'] = '';
          /*
           * поле dst_billsec_rec
           * содержит dst, billsec, recordfile
           * поэтому распарсим и запишем эти значения в ответ
           * пример записи
           *
                4105|0|,4107|0|,4108|0|,4104|95|,
                2000|44|q-2000-87142535240-20191203-191540-1575378940.25815.wav
                 4111|118|,2000|171|q-2000-87172786895-20191203-170038-1575370838.25111.wav

           *
           * */

          $dst_bill_rec = $call->dst_billsec_rec;
          $items = explode(',', $dst_bill_rec);
          $call_time = 0;
          //записываем операторов и время звонка
          $operators = [];
          foreach ($items  AS $item) {
            $sp_item = [];
            $operator = [];
            //пропускаем в списке звонков 2000 номер
            $sp_item = explode('|', $item);

            if(count($items) != 1 && $sp_item[0] == 2000){
              continue;
            }
            $operator['number'] = (int)$sp_item[0];
            $operator['user'] = '';
            //этот форич мне не нравится в дальнейшем нужно переделать на мапу и брать пользователя через гет или же в sql пользователя за джоинить
            foreach ($users AS $user) {
              if ((int)$user->users_phone === (int)$sp_item[0]) {
                $operator['user'] = $user->name;
              }
            }
            $operator['time'] = $this->dataFormater($sp_item[1]);
            //суммируем время
            $call_time += $sp_item[1];
            $operators[] = $operator;
          }
          /*
           * это проверка для вот таких случаев пока не знаю что с ними делать
           * 2000|12|,2000|283|q-2000-87172546134-20191115-152002-1573809576.145813.wav,2000|388|q-2000-87172546134-20191115-151818-1573809498.145802.wav
           * получается оператор не принял звонок
           * */
          if(count($operators) == 0){
            $operator = [];
            $item = $items[count($items) - 1];
            $sp_item = explode('|', $item);
            $operator['time'] = $this->dataFormater($sp_item[1]);
            $operator['dst'] = $sp_item[0];
            $operator['number'] = (int)$sp_item[0];
            $operator['user'] = '';
            $operators[] = $operator;
          }
          //запись разговора это последний элемент $items
          $record = explode('-', $items[count($items) - 1]);
          if (count($record) > 5) {
            $r_p = str_split($record[3]);
            $row_item['link'] = '/calls/' . $r_p[0] . $r_p[1] . $r_p[2] . $r_p[3] . '/' . $r_p[4] . $r_p[5] . '/' . $r_p[6] . $r_p[7] . '/' . $sp_item[2];
          }else{
            $row_item['link'] = '';
          }
          $row_item['duration'] = $this->dataFormater($call->sum_billsec);
          $date_call = explode(' ', $call->calldate);
          $row_item['calldate'] = $date_call[0];
          $row_item['calltime'] = $date_call[1] . '.';
          $row_item['src'] = $call->src;
          $row_item['dst'] = $call->dst;
          $row_item['operators'] = $operators;

          if($call->sum_billsec == 0){
            $row_item['disposition'] = 'NO ANSWER';
          }
          else{
            $row_item['disposition'] = '';
          }
          $row_item['playback'] = Tacks::getPlayback($call->uniqueid);
          $id++;
          $row_item['id'] = $id;
          $resp_arr[] = $row_item;
        }
      }

      return  response((['rows' => $resp_arr, 'total' => count($count)]));

    }
}
