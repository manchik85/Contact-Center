<?php

    namespace App\Http\Controllers\Adm\Setting;

    use App\Http\Controllers\Controller;
    use Illuminate\Http\Request;
    use App\Models\Adm\Settings;

    use function redirect;

    class PagesController extends Controller
    {
        protected $request;

        public function __construct(Request $request)
        {
            $this->request = $request;
        }

        public function permissionsAdd() {

            return view('admin.setting.permissions_add', compact([]));
        }

        public function permissionsList() {
            $list = Settings::getList();
            return view('admin.setting.permissions_list', compact([ 'list' ]));
        }

        public function permissionsAddApi() {
            $lastId           = Settings::getLastId();
            $data['group']    = $this->request->input('name');
            $data['group_id'] = (int)$lastId[0]->group_id+1;
            $pst              = Settings::createPermissions($data);
            print json_encode(['st' => $pst]);
        }

        public function permissionsDelApi() {

            $group_id = $this->request->input('group_id');

            $list = Settings::getAll($group_id);

            // dd($list);

            if( count($list) > 0 ) {

                $ids = [];

                foreach( $list AS $unit ) {
                    $ids[] = $unit->id;
                }

                Settings::removeUsersGroup($ids);
            }

            Settings::removePermissionsGroup($group_id);

        }


    }
