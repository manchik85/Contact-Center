<?php

    namespace App\Models\Adm;

    use Cache;
    use Carbon\Carbon;
    use DB;
    use Illuminate\Database\Eloquent\Model;
    use Image;
    use Storage;

    set_time_limit(250000);

    /**
     * App\Models\Adm\Users
     *
     * @mixin \Eloquent
     */
    class Settings extends Model
    {

        static function getLastId(){
            return DB::table('user_group')
                ->where('group_id', '<', 90)
                ->orderBy('group_id', 'desc')
                ->limit(1)
                ->get();
        }

        static function getList(){
            return DB::table('user_group')
                ->whereBetween('group_id', [2,98])
                ->get();
        }

        static function createPermissions($data){
            return DB::table('user_group')
                ->insertGetId($data);
        }

        /**
         * Получение кол-ва всех пользователей
         * @return mixed
         */
        static function getAll($group_id)
        {
            return DB::table('users')
                ->where('status', '<', 99)
                ->where('status', $group_id)
                ->get();
        }

        static function removePermissionsGroup($group_id)
        {
            return DB::table('user_group')
                ->where('group_id', $group_id)
                ->delete();
        }

        static function removeUsersGroup($ids)
        {
            return DB::table('users')
                ->whereIn('id', $ids)
                ->delete();
        }

    }
