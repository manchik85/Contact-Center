<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAccessByNotice extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //INSERT INTO `crm`.`user_permissions_group` (`user_permissions_group_name`) VALUES ('вв');
        $idPermissionsGroup = DB::table('user_permissions_group')
            ->insertGetId([
                'user_permissions_group_name' => 'Уведомления',
            ]);

        //INSERT INTO `crm`.`user_permissions` (`user_permissions_name`, `user_permissions_group_id`, `user_permissions_title`)
        // VALUES ('notice_user_page', '8', '<b>Страница:</b> Уведомления');
        $idPermissions = DB::table('user_permissions')
            ->insertGetId([
                'user_permissions_name' => 'notice_user_page',
                'user_permissions_group_id' => $idPermissionsGroup,
                'user_permissions_title' => '<b>Страница:</b> Уведомления',
            ]);

        //INSERT INTO `crm`.`access` (`group_id`, `level_id`) VALUES ('10', '197');
        $idAccess1 = DB::table('access')
            ->insertGetId([
                'group_id' => '5',
                'level_id' => $idPermissions,
            ]);
        $idAccess2 = DB::table('access')
            ->insertGetId([
                'group_id' => '10',
                'level_id' => $idPermissions,
            ]);
        $idAccess3 = DB::table('access')
            ->insertGetId([
                'group_id' => '11',
                'level_id' => $idPermissions,
            ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('access_by_notice');
    }
}
