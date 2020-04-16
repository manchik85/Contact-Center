<?php

    namespace App\Jobs;

    use App\System\DataCollectors\ContractsCollector as Collector;
    use Carbon\Carbon;
    use Illuminate\Bus\Queueable;
    use Illuminate\Contracts\Queue\ShouldQueue;
    use Illuminate\Foundation\Bus\Dispatchable;
    use Illuminate\Queue\InteractsWithQueue;
    use Illuminate\Queue\SerializesModels;

    //    use Log;

    class QuotesCollectorJob implements ShouldQueue
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
        public $bell  = false;

        public function __construct($begin, $lines, $bell)
        {
            $this->begin = $begin;
            $this->lines = $lines;
            $this->bell  = $bell;
        }

        /**
         * Execute the job.
         *
         * @param Collector $quotes
         */
        public function handle(Collector $quotes)
        {
            // отмечаем время старта Процесса
            $start_point = Carbon::now();

            // отключаем лог базы данных
            $quotes->disableQueryLog();

            // отмечаемся в Redis что стартовали сбор данных
            $quotes->redisFixStart([
                                       'fild' => 'quote_start_point',
                                       'begin' => $this->begin
                                   ]);
            try {
                $job_log = $quotes->quotesCollectorJobs([
                                                            'begin' => $this->begin,
                                                            'lines' => $this->lines
                                                        ]);
                $data_status = 'complete Quote Collecting';

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
            $quotes->logFixComplete([
                                        'job_name' => 'Quote Collecting',
                                        'created_at' => $start_point,
                                        'start_at' => $start_point,
                                        'finish_at' => Carbon::now(),
                                        'job_status' => $data_status,
                                        'job_log' => json_encode($job_log)
                                    ]);

            // отмечаем завершение процесса
            $quotes->redisFixComplete([
                                          'fild' => 'quote_start_point',
                                          'begin' => $this->begin
                                      ]);

            // проверяем, есть ли ещё работающие Jobs, если нет, то фиксируем в SQL-лог завершение Сбора данных
            $status_check = $quotes->checkStatusCollecting(['fild' => 'quote_start_point']);
            //            &&  $this->bell == true
            if( !$status_check) {
                $job_name_token = $quotes->pullJobNameToken(['name' => 'quotes_start_name']);
                $quotes->logingFinishingCollector(['job_name' => $job_name_token]);

                // Отправляем сообщение через сокеты
                $quotes->redisSocketReport([
                                               'event' => 'jobsComplete',
                                               'data' => [
                                                   'title' => 'Quotes Collecting is complete!',
                                                   'body' => 'The system has successfully completed the collection of Quotes.',
                                                   'color' => '#C79121'
                                               ]
                                           ]);
            }
        }
    }
