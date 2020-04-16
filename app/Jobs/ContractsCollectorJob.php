<?php

    namespace App\Jobs;

    use App\System\DataCollectors\ContractsCollector as Collector;
    use Carbon\Carbon;
    use Illuminate\Bus\Queueable;
    use Illuminate\Contracts\Queue\ShouldQueue;
    use Illuminate\Foundation\Bus\Dispatchable;
    use Illuminate\Queue\InteractsWithQueue;
    use Illuminate\Queue\SerializesModels;

    // use Log;

    class ContractsCollectorJob implements ShouldQueue
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
         * @param Collector $contracts
         */
        public function handle(Collector $contracts)
        {
            // отмечаем время старта Процесса
            $start_point = Carbon::now();

            // отключаем лог базы данных
            $contracts->disableQueryLog();

            // отмечаемся в Redis что стартовали сбор данных
            $contracts->redisFixStart( ['fild' => 'contract_start_point', 'begin' => $this->begin] );

            try {
                $job_log     = $contracts->contractsCollectorJobs(['begin' => $this->begin, 'lines' => $this->lines]);
                $data_status = 'complete Contract Collecting';

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
            $contracts->logFixComplete([
                                           'job_name' => 'Contract Collecting',
                                           'created_at' => $start_point,
                                           'start_at' => $start_point,
                                           'finish_at' => Carbon::now(),
                                           'job_status' => $data_status,
                                           'job_log' => json_encode($job_log)
                                       ]);

            // отмечаем завершение процесса
            $contracts->redisFixComplete( ['fild' => 'contract_start_point', 'begin' => $this->begin] );

            // проверяем, есть ли ещё работающие Jobs, если нет, то фиксируем в SQL-лог завершение Сбора данных
            $status_check = $contracts->checkStatusCollecting( ['fild' => 'contract_start_point'] );

            if(!$status_check) {
                $job_name_token = $contracts->pullJobNameToken(['name' => 'contracts_start_name']);
                $contracts->logingFinishingCollector(['job_name' => $job_name_token]);

                // Отправляем сообщение через сокеты
                $contracts->redisSocketReport([
                                                  'event' => 'jobsComplete',
                                                  'data' => [
                                                      'title' => 'Contracts Collecting is complete!',
                                                      'body' => 'The system has successfully completed the collection of all Contracts.',
                                                      'color' => '#739E73'
                                                  ]
                                              ]);


            }
        }
    }
