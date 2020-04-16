<?php

    namespace App\Http\Controllers\Adm\Clients;

    use App\Http\Controllers\Controller;
    use Illuminate\Http\Request;


    class PagesController extends Controller
    {
        protected $request;

        public function __construct(Request $request)
        {
            $this->request = $request;
        }

        public function clientsList() {



            return view('admin.clients.clients_list_page', compact([  ]));
        }


    }
