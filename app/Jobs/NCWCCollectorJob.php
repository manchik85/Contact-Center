<?php

    namespace App\Jobs;

    use App\System\DataCollectors\NCWCCollector as Collector;
    use Illuminate\Bus\Queueable;
    use Illuminate\Contracts\Queue\ShouldQueue;
    use Illuminate\Foundation\Bus\Dispatchable;
    use Illuminate\Queue\InteractsWithQueue;
    use Illuminate\Queue\SerializesModels;
    use Carbon\Carbon;

    class NCWCCollectorJob implements ShouldQueue
    {
        use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

        public $tries = 3;

        /**
         * Create a new job instance.
         *
         * @return void
         */
        public $begin = 0;
        public $lines = 50000;

        public function __construct($begin, $lines)
        {
            $this->begin = $begin;
            $this->lines = $lines;
        }

        /**
         * Execute the job.
         *
         * @param Collector $ncwc
         */
        public function handle(Collector $ncwc)
        {
            // отмечаем время старта Процесса
            $start_point = Carbon::now();

            // отключаем лог базы данных
            $ncwc->disableQueryLog();

            // отмечаемся в Redis что стартовали сбор данных
            $ncwc->redisFixStart( ['fild' => 'ncwc_start_point', 'begin' => $this->begin] );

            // запускаем сбор данных
            try {
                $job_log = $ncwc->ncwcCollectorJobs(['begin' => $this->begin, 'lines' => $this->lines]);
                $data_status = 'complete NCWC Collecting';
            }
            catch(\Throwable $exception) {

                $job_log = [
                    'status' => 0,
                    'Message' => $exception->getMessage(),
                    'Code' => $exception->getCode(),
                    'File' => $exception->getFile(),
                    'Line' => $exception->getLine(),
                ];

                $data_status = 'error';
            }

            // пишем в SQL-лог отчёт о результате
            $ncwc->logFixComplete([
                'jobs_id'    => $this->job->getJobId(),
                'job_name'   => 'NCWC Collecting',
                'created_at' => $start_point,
                'start_at'   => $start_point,
                'finish_at'  => Carbon::now(),
                'job_status' => $data_status,
                'job_log' => json_encode($job_log)
            ]);

            // отмечаем завершение процесса
            $ncwc->redisFixComplete( ['fild' => 'ncwc_start_point', 'begin' => $this->begin] );

            // проверяем, есть ли ещё работающие Jobs, если нет, то фиксируем в SQL-лог завершение Сбора данных
            $status_check = $ncwc->checkStatusCollecting( ['fild' => 'ncwc_start_point'] );

            if(!$status_check) {
                $job_name_token =$ncwc->pullJobNameToken(['name' => 'ncwc_start_name']);
                $ncwc->logingFinishingCollector([ 'job_name' => $job_name_token ]);

                // очищаем статусы подключения и проверки базы
                $ncwc->clearConnectStatus(['name' =>'ncwc']);

                // Отправляем сообщение через сокеты
                $ncwc->redisSocketReport([
                                             'event' => 'jobsComplete',
                                             'data' => [
                                                 'title' => 'NCWC Collecting is complete!',
                                                 'body' => 'The system has successfully completed the NCWC collection.',
                                                 'color' => '#008d4c'
                                             ] ]);
            }
        }
    }
