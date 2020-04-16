<?php

namespace App\Models\Adm;

use DB;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Notice extends Model
{
    //
    static function getNotice($id) {
        return DB::table('notice')
            ->where('id', $id)
            ->get();
    }

    //создает уведомление с заданными параметрами
    static function addNotice($data) {
        //2020-03-16 Nikolay
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
        $task_title = '';
        $task_mess = '';

        //если были заранее заданы сообщения и наименование
        if (isset($data['name']) && isset($data['description'])) {
            $task_title = $data['name'];
            $task_mess = $data['description'];
        } else {
            if ($data['complete_id'] == 1) {
                $task_title = 'Информация о заявке №' . $data['task_id'];
                $task_mess = 'Для решения вопроса по Вашей заявке №' . $data['task_id'] . ' назначен исполнитель. Предположительная дата решения вопроса ' . $data['task_off'];
            } elseif ($data['complete_id'] == 2) {
                $task_title = 'Информация о заявке №' . $data['task_id'];
                $task_mess = 'Заявка №' . $data['task_id'] . ' принята в обработку специалистом. Предположительная дата решения вопроса ' . $data['task_off'];
            } elseif ($data['complete_id'] == 3) {
                $task_title = 'Информация о заявке №' . $data['task_id'];
                $task_mess = 'Заявка №' . $data['task_id'] . ' обработана специалистом. Заявка решена и требует проверки!';
            } elseif ($data['complete_id'] == 4) {
                $task_title = 'Информация о заявке №' . $data['task_id'];
                $task_mess = 'Создана новая заявка №' . $data['task_id'] . '.';
            } elseif ($data['complete_id'] == 5) {
                $task_title = 'Информация о заявке №' . $data['task_id'];
                $task_mess = 'Заявка №' . $data['task_id'] . ' отправлена на повторное исполнение.';
            } elseif ($data['complete_id'] == 6) {
                $task_title = 'Информация о заявке №' . $data['task_id'];
                $task_mess = 'Ваша заявка №' . $data['task_id'] . ' отправлена специалистом на доработку.';
            } elseif ($data['complete_id'] == 7) {
                $task_title = 'Информация о заявке №' . $data['task_id'];
                $task_mess = 'На заявку №' . $data['task_id'] . ' специалистом была написана консультация.';
            } elseif ($data['complete_id'] == 8) {
                $task_title = 'Информация о заявке №' . $data['task_id'];
                $task_mess = 'На заявку №' . $data['task_id'] . ' специалистом была изменина стадия работы на Не повторилась.';
            } elseif ($data['complete_id'] == 9) {
                $task_title = 'Информация о заявке №' . $data['task_id'];
                $task_mess = 'На заявку №' . $data['task_id'] . ' специалистом была изменина стадия работы на Не актуальна.';
            }
        }

        if (isset($data['avtor_id'])) {
            $avtor = $data['avtor_id'];
        } else {
            $avtor = auth()->user()->id;
        }

        if (isset($data['recipient']) && $data['recipient'] != '') {
            return DB::table('notice')
                ->insertGetId([
                    'avtor' => $avtor,
                    'recipient' => $data['recipient'],
                    'task' => $data['task_id'],
                    'name' => $task_title,
                    'description' => $task_mess,
                    'created_at' => Carbon::now(),
                ]);
        } else {
            return '';
        }
    }

    //проставляет отметку о том что пользователь ознакомился с уведомлением
    static function readNotice($id) {
        return DB::table('notice')
            ->where('id', $id)
            ->update([
                'updated_at' => Carbon::now(),
                'is_read' => 1,
            ]);
    }

    //функция отбирает актуальные не прочитанные уведомления заданного пользователя
    static function getNoticeUser($user_id) {
        return DB::table('notice')
            ->orderBy('created_at', 'asc')
            ->where('recipient', '=', $user_id)
            ->where('is_read', '=', '0')
            ->orWhereNull('is_read')
            ->get();
    }

    //функция используется для страницы Уведомления.
    // отбирает уведомления по заданным критериям
    static function getNoticeByParam($user_id, $isRead = 0, $startDate, $endDate) {

        $arrResult =  DB::table('notice')
            ->leftJoin('users as av', 'av.id', '=', 'notice.avtor')
            ->select(
                'notice.id AS id',
                'notice.is_read AS read',
                'notice.task AS task',
                'notice.name AS name',
                'notice.description AS description',
                'notice.created_at AS created_date',
                'av.name AS avtor_name'
            )
            ->orderBy('notice.created_at', 'asc')
            ->where('recipient', '=', $user_id);

        //2 - выбрано значение Все - т.е. без учета прочтено уведомление или нет
        //1 - только прочтеные уведомления
        //0 - только не прочтеные уведомления
        if ($isRead == 0 || $isRead == 1) {
            $arrResult->where('is_read', '=', $isRead);
        }

        if ($startDate != null && $startDate != '') {
            $startDate = explode('.', $startDate);
            if (count($startDate) >= 3) {
                $start_go = $startDate[2] . '-' . $startDate[1] . '-' . $startDate[0] . ' 00:00:00';
            } else {
                $start_go = '';
            }

            $arrResult->where('notice.created_at', '>=', $start_go);
        }

        if ($endDate != null && $endDate != '') {
            $endDate = explode('.', $endDate);
            if (count($endDate) >= 3) {
                $end_go = $endDate[2] . '-' . $endDate[1] . '-' . $endDate[0] . ' 23:59:59';
            } else {
                $end_go = '';
            }

            $arrResult->where('notice.created_at', '<=', $end_go);
        }

        return $arrResult->get();
    }
}
