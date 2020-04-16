<?php

    namespace App\Jobs;

    use App\System\DataCollectors\DataAdapters\ForteDataAdapter as Adapter;
    use Carbon\Carbon;
    use Illuminate\Bus\Queueable;
    use Illuminate\Contracts\Queue\ShouldQueue;
    use Illuminate\Foundation\Bus\Dispatchable;
    use Illuminate\Queue\InteractsWithQueue;
    use Illuminate\Queue\SerializesModels;


//    use Log;

    class ForteMasterDataCollectorJob implements ShouldQueue
    {
        use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

        public $tries = 3;

        /**
         * Create a new job instance.
         *
         * @return void
         */
        public $PD    = [];
        public $lines = 50000;

        public function __construct($PD, $lines)
        {
            $this->PD    = $PD;
            $this->lines = $lines;
        }

        /**
         * Execute the job.
         *
         * @param Adapter $adapter
         */
        public function handle(Adapter $adapter)
        {
            //throw new \Exception('test error', 101);

            // отмечаем время старта Процесса
            $start_point = Carbon::now();

            // отключаем лог базы данных
            $adapter->disableQueryLog();

            // получаем количество записей в Продюссере и пишем в Redis Количество
            $count_recods_producer = $adapter->countRecodsProducer($this->PD);

            //обнуляем счётчик кол-ва строк в базе данных в redis
            $adapter->redisFixLines(['id'=>$this->PD->id, 'lines'=>0]);

            $data_status = '';
            $i           = 0;
            $begin       = 0;

            if($count_recods_producer > 0) {

                // отмечаемся в Redis что стартовали сбор данных
                $adapter->redisFixStart(['id' => $this->PD->id]);

                // вычисляем сколько нужно проходов
                $sum_cicl = round($count_recods_producer / $this->lines, 0, PHP_ROUND_HALF_UP);

                $data_status = 'complete Forte Master Data Collecting';
                // $fileName = storage_path('app/parse/producers/').$this->PD->forte_pg_database . '_' . date("m-d-Y") . '_' . date("H-i-s") . '.txt';

                for($m = 0; $m <= $sum_cicl; $m++) {

                    // забираем данные из cp_con_m1 у продюссера
                    $dump_producer = $adapter->parseCpConM1Skiped(['PG' => $this->PD, 'begin' => $begin, 'lines' => $this->lines]);

                    if(count($dump_producer) > 0) {

                        // $adapter -> writeFileForteData($dump_producer, $fileName);
                        // $i                   += count($dump_producer);

                        $begin += $this->lines;

                        // преобразовываем данные для insert в forte_master_data
                        foreach($dump_producer AS $dump) {

                            // преобразуем данные как нам надо
                            $producer_dump_array = $adapter->adaptForteMasterData($this->PD->id, $dump);

                            // пишем данные в forte_master_data
                            $log_data = $adapter->insertForteMasterData($producer_dump_array);

                            // если данные не записались
                            if($log_data['status'] < 1) {

                                $data_status = 'error';

                                // пишем в файл кусок данных, которые не записались в таблицу
                                $errorFilePart = storage_path('app/parse/producers/errors/');
                                $errorFileName = $this->PD->bussines_name . '_' . date("m-d-Y") . '_' . date("H-i-s") . '_' . rand(1000, 9999) . '.txt';
                                $adapter->writeFileForteData(['dump' => $producer_dump_array, 'fileName' => $errorFilePart . $errorFileName]);

                                // пишем в SQL-лог результат парсинга с данными о продюссере
                                $adapter->logFixComplete([
                                                             'jobs_id' => $this->job->getJobId(),
                                                             'job_name' => $this->job->getName(),
                                                             'created_at' => $start_point,
                                                             'start_at' => $start_point,
                                                             'finish_at' => Carbon::now(),
                                                             'job_status' => $data_status,
                                                             'job_log' => json_encode([
                                                                                          'PD' => $this->PD,
                                                                                          'log_data' => $log_data,
                                                                                          'data_status' => $data_status,
                                                                                          'lines' => $i,
                                                                                          'error_dump' => $errorFileName,
                                                                                      ])
                                                         ]);

                                break;
                            }
                            $i++;
                        }
                    } else {
                        break;
                    }

                    // пишем в Redis кол-во забраных строк
                    $adapter->redisFixLines(['id'=>$this->PD->id, 'lines' => $i]);

                    // пишем в Redis время записи строк в базу
                    $adapter->redisFixTime(['id'=>$this->PD->id]);

                }
            } else {
                // пишем в Redis кол-во забраных строк равное 0
                $adapter->redisFixLines(['id'=>$this->PD->id, 'lines' => 0]);
            }

            // отмечаем завершение процесса в Redis
            $adapter->redisFixComplete(['id' => $this->PD->id]);

            // проверяем, есть ли ещё работающие Jobs, если нет, то фиксируем в SQL-лог завершение Сбора данных
            $status_check = $adapter->checkStatusCollectingForteMasterData(0);
            if(!$status_check) {
                $job_name_token = $adapter->pullJobNameToken(['token'=>'forte_master_data_start_name']);
                $adapter->logingEndForteMasterDataCollector(['job_name' => $job_name_token]);

                // Отправляем сообщение через сокеты
                $adapter->redisSocketReport([
                                                'event' => 'jobsComplete',
                                                'data' => [
                                                    'title' => 'Forte master data is collect!',
                                                    'body' => 'The system has successfully completed the collection of data from all producers',
                                                    'color' => '#3276B1'
                                                ]
                                            ]);
            }

            // формируем данные для записи в SQL-лог отчёта о результат
            $report_date = [
                'jobs_id' => $this->job->getJobId(),
                'job_name' => $this->job->getName(),
                'created_at' => $start_point,
                'start_at' => $start_point,
                'finish_at' => Carbon::now(),
                'job_status' => $data_status,
                'job_log' => json_encode(['Forte producer name' => $this->PD->bussines_name, 'lines' => $i ])
            ];

            // Пишем в SQL-лог строку из таблицы jobs
            $adapter->fixJobsRecord($report_date);

            // пишем в SQL-лог отчёт о результате, если количество lines > 0
            if($i > 0) {
                $adapter->logFixComplete($report_date);
            }

            // пишем в Системный лог результат
            //Log::info("Forte producer name: " . $this->PD->bussines_name . " -- lines: " . $i . " -- insert status: " . $data_status);

            //            if($this->attempts() >= 5) {
            //                $this->delete();
            //            }
        }

    }
