<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnInTaskc extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tasks', function (Blueprint $table) {
            //хранит фактическую дату завершения исполнения заявки.
            $table->dateTime('date_off_fact')->nullable()->default(NULL);
            //хранит дату закрытия заявки.
            $table->dateTime('date_closed')->nullable()->default(NULL);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('taskc', function (Blueprint $table) {
            //
        });
    }
}
