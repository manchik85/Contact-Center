<?php

    namespace App\Jobs;

    use App\System\DataCollectors\DealCollector as Collector;
    use Carbon\Carbon;
    use Illuminate\Bus\Queueable;
    use Illuminate\Contracts\Queue\ShouldQueue;
    use Illuminate\Foundation\Bus\Dispatchable;
    use Illuminate\Queue\InteractsWithQueue;
    use Illuminate\Queue\SerializesModels;

    class DealCollectorJob implements ShouldQueue
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
         * @param Collector $deal
         */
        public function handle(Collector $deal)
        {
            // отмечаем время старта Процесса
            $start_point = Carbon::now();

            // отключаем лог базы данных
            $deal->disableQueryLog();

            // отмечаемся в Redis что стартовали сбор данных
            $deal->redisFixStart( ['fild' => 'deal_start_point', 'begin' => $this->begin] );

            // запускаем сбор данных
            try {
                $job_log     = $deal->dealCollectorJobs(['begin'=>$this->begin, 'lines'=>$this->lines]);
                $data_status = 'complete Deal Collecting';
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
            $deal->logFixComplete([
                                      'jobs_id' => $this->job->getJobId(),
                                      'job_name' => 'Deal Collecting',
                                      'created_at' => $start_point,
                                      'start_at' => $start_point,
                                      'finish_at' => Carbon::now(),
                                      'job_status' => $data_status,
                                      'job_log' => json_encode($job_log)
                                  ]);

            // отмечаем завершение процесса
            $deal->redisFixComplete( ['fild' => 'deal_start_point', 'begin' => $this->begin] );

            // проверяем, есть ли ещё работающие Jobs, если нет, то фиксируем в SQL-лог завершение Сбора данных
            $status_check = $deal->checkStatusCollecting( ['fild' => 'deal_start_point'] );

            if(!$status_check) {
                $job_name_token = $deal->pullJobNameToken(['name' => 'deal_start_name']);
                $deal->logingFinishingCollector(['job_name' => $job_name_token]);

                // очищаем статусы подключения и проверки базы
                $deal->clearConnectStatus(['name' =>'deal']);

                // Отправляем сообщение через сокеты
                $deal->redisSocketReport([
                                             'event' => 'jobsComplete',
                                             'data' => [
                                                 'title' => 'Deal Collecting is complete!',
                                                 'body' => 'The system has successfully completed the Deal collection.',
                                                 'color' => '#3b5998'
                                             ]
                                         ]);
            }
        }
    }
