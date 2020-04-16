<?php
    
    namespace App\Http\Controllers\Adm;
    
    use App\Http\Controllers\Controller;
    use App\Models\Adm\Catalog\CatalogA;
    use App\Models\Adm\Catalog\CatalogG;
    use App\Models\Adm\Catalog\CatalogL;
    use App\Models\Adm\Catalog\CatalogPh;
    use App\Models\Adm\Catalog\CatalogV;
    use App\Models\Adm\Parts\PartsCommon;
    use App\Models\Adm\Users;
    use File;
    use Illuminate\Http\Request;
    use function array_pop;
    use function explode;
    use function krsort;
    
    set_time_limit(250000);
    
    class DashboardController extends Controller
    {
        protected $request;
        
        public function __construct(Request $request)
        {
            $this->request = $request;
        }
        
        /**
         * Display a listing of the resource.
         *
         * @return \Illuminate\Http\Response
         */
        public function index()
        {
            
            $user         = $this->request->user();
            $data         = [];
            $users        = [];
            $data['user'] = $user;
            
            $menuStatusItem = $this->request->session()
                ->get('menuStatusItem');
            $parts          = PartsCommon::defaultOrder()
                ->get()
                ->toTree();
            
            $node_page[]      = (object)[];
            $node_page[0]->id = 0;
            
            $all_users = Users::getAll();
            $users_ast = Users::getUsersList();
            foreach($users_ast AS $_v) {
                $users[$_v->group_id]['group']                        = $_v->group;
                $users[$_v->group_id]['data'][$_v->id]['name']        = $_v->name;
                $users[$_v->group_id]['data'][$_v->id]['email']       = $_v->email;
                $users[$_v->group_id]['data'][$_v->id]['users_phone'] = $_v->users_phone;
                $users[$_v->group_id]['data'][$_v->id]['iduser']      = $_v->id;
            }
            
            krsort($users);
            // dd($users);
    
    
            $video    = CatalogV::getCount();
            $photo    = CatalogPh::getCount();
            $lights   = CatalogL::getCount();
            $advanced = CatalogA::getCount();
            $grouping = CatalogG::getCount();
            $show     = CatalogG::getCountShow();
            $show_ind = CatalogG::getCountInd();


//        Users::pars();
            
            return view('admin.adm_dashboard', compact([
                                                           'parts',
                                                           'show',
                                                           'show_ind',
                                                           'video',
                                                           'photo',
                                                           'lights',
                                                           'advanced',
                                                           'grouping',
                                                           'all_users',
                                                           'users',
                                                           'node_page',
                                                           'menuStatusItem',
                                                       ]));
        }
        
        public function makeSitemap()
        {
            $_urls                 = '';
            $data_Sitemap_Info     = PartsCommon::getSitemapInfo();
            $data_Sitemap_Grouping = PartsCommon::getSitemapGroupingCatalog();
            $data_Sitemap_Advanced = PartsCommon::getSitemapAdvancedCatalog();
            foreach($data_Sitemap_Info as $_vv) {
                
                $url_complete = '';
                
                $node_url = PartsCommon::defaultOrder()
                    ->ancestorsAndSelf((int)$_vv->id);
                
                foreach($node_url AS $k => $url) {
                    if($k > 0) {
                        $url_complete .= '/' . $url->parts_alias;
                    }
                }
                
                $_urls .= '
                      <url>
                          <loc>' . url('/' . $_vv->id . $url_complete . '/' . $_vv->parts_alias) . '.html</loc>
                          <lastmod>' . date("Y-m-d H:i:s") . '</lastmod>
                          <changefreq>monthly</changefreq>
                          <priority>1.0</priority>
                       </url>';
            }
            foreach($data_Sitemap_Grouping as $_vv) {
                if($_vv->parts_status == 1) {
                    $url_complete = '';
                    
                    $node_url = PartsCommon::defaultOrder()
                        ->ancestorsAndSelf((int)$_vv->id_part_rel);
                    
                    foreach($node_url AS $k => $url) {
                        if($k > 1) {
                            $url_complete .= '/' . $url->parts_alias;
                        }
                    }
                    
                    $_urls .= '
                          <url>
                              <loc>' . url('/' . $_vv->id_part_rel . '/' . $_vv->id . $url_complete . '/' . $_vv->catalog_g_alias) . '.html</loc>
                              <lastmod>' . date("Y-m-d H:i:s") . '</lastmod>
                              <changefreq>monthly</changefreq>
                              <priority>1.0</priority>
                           </url>';
                }
            }
            foreach($data_Sitemap_Advanced as $_vv) {
                
                $url_complete = '';
                
                $node_url = PartsCommon::defaultOrder()
                    ->ancestorsAndSelf((int)$_vv->id_part_rel);
                
                foreach($node_url AS $k => $url) {
                    if($k > 0) {
                        $url_complete .= '/' . $url->parts_alias;
                    }
                }
                
                $_urls .= '
                      <url>
                          <loc>' . url('/info/' . $_vv->id_part_rel . '/' . $_vv->id . $url_complete . '/' . $_vv->catalog_a_alias) . '.html</loc>
                          <lastmod>' . date("Y-m-d H:i:s") . '</lastmod>
                          <changefreq>monthly</changefreq>
                          <priority>1.0</priority>
                       </url>';
            }
            $_data = '<?xml version="1.0" encoding="UTF-8"?>
                        <urlset xmlns="https://www.sitemaps.org/schemas/sitemap/0.9">
                           <url>
                              <loc>' . env('APP_URL') . '</loc>
                              <lastmod>' . date("Y-m-d H:i:s") . '</lastmod>
                              <changefreq>monthly</changefreq>
                              <priority>1.0</priority>
                           </url>
                           ' . $_urls . '
                        </urlset>';
            
            $fp = fopen($_SERVER['DOCUMENT_ROOT'] . '/public/sitemap.xml', 'w');
            fwrite($fp, $_data);
            fclose($fp);
        }
        
        public function makeYML()
        {
            $data_XML_title = PartsCommon::getXmlDataTitle();
            $data_XML_body  = PartsCommon::getXmlDataBady();
            
            $_categories = '';
            $_offers     = '';
            
            foreach($data_XML_title as $row) {
                $parentId = '';
                if($row->id != ID_ITEM) {
                    if($row->parts_type == 4) {
                        $parentId = ' parentId="' . $row->parent_id . '"';
                    }
                    
                    $_categories .= '<category id="' . $row->id . '"' . $parentId . '>' . $row->parts_name . '</category>
				';
                }
            }
            
            foreach($data_XML_body as $_vv) {
                if($_vv->parts_status == 1) {
                    
                    $picture      = url('/usersdata/' . $_vv->id_part . '/g_cat/full/' . $_vv->catalog_g_img);
                    $url_complete = '';
                    
                    //            if((int)$_vv->catalog_g_price<=0){
                    //                $_vv->catalog_g_price = 1;
                    //            }
                    
                    if($_vv->catalog_g_img == '') {
                        $picture = url('/img/noimg.png');
                    }
                    
                    $node_url = PartsCommon::defaultOrder()
                        ->ancestorsAndSelf((int)$_vv->id_part);
                    foreach($node_url AS $k => $url) {
                        if($k > 0) {
                            $url_complete .= '/' . $url->parts_alias;
                        }
                    }
                    
                    $_offers .= '<offer id="' . $_vv->id_rel . '" available="true">
            
                            <url>' . url('/' . $_vv->id_part . '/' . $_vv->id . $url_complete . '/' . $_vv->catalog_g_alias) . '.html</url>
                            <price>' . $_vv->catalog_g_price . '</price>
                            <currencyId>BYN</currencyId>
                            <categoryId type="Own">' . $_vv->id_part_rel . '</categoryId>
                            <picture>' . $picture . '</picture>
                            <delivery>true</delivery>
                            <local_delivery_cost>150000</local_delivery_cost>
                            <typePrefix>' . str_replace('"', '&quot;',
                                                        str_replace("&", "&amp;",
                                                                    str_replace(">", "&gt;",
                                                                                str_replace("<", "&lt;",
                                                                                            str_replace("'", "&apos;", $_vv->parts_name))))) . '</typePrefix>
                            <vendorCode>' . str_replace('"', '&quot;',
                                                        str_replace("&", "&amp;",
                                                                    str_replace(">", "&gt;",
                                                                                str_replace("<", "&lt;",
                                                                                            str_replace("'", "&apos;", $_vv->catalog_g_articul))))) . '</vendorCode>
                            <name>' . str_replace('"', '&quot;',
                                                  str_replace("&", "&amp;",
                                                              str_replace(">", "&gt;",
                                                                          str_replace("<", "&lt;",
                                                                                      str_replace("'", "&apos;", $_vv->catalog_g_name))))) . '</name>
                            <description>' . str_replace('"', '&quot;',
                                                         str_replace("&", "&amp;",
                                                                     str_replace(">", "&gt;",
                                                                                 str_replace("<", "&lt;",
                                                                                             str_replace("'", "&apos;", $_vv->catalog_g_title))))) . '</description>
                        </offer>
                        ';
                    
                    
                }
            }
            
            $_data = '<?xml version="1.0" encoding="utf-8"?>
                    <!DOCTYPE yml_catalog SYSTEM "shops.dtd">
                    <yml_catalog date="' . date("Y-m-d H:i") . '">
                        <shop>
                            <name>' . env('APP_NAME') . '</name>
                            <company>' . env('NAME_COMPANY') . '</company>
                            <url>' . env('APP_URL') . '</url>
                    
                            <currencies>
                                <currency id="BYN" rate="1" plus="0"/>
                            </currencies>
                    
                            <categories>
                                ' . $_categories . '
                            </categories>
                    
                            <local_delivery_cost>150000</local_delivery_cost>
                    
                            <offers>
                                ' . $_offers . '
                            </offers>
                            
                        </shop>
                    </yml_catalog> ';
            
            
            $fp = fopen($_SERVER['DOCUMENT_ROOT'] . '/public/YML.xml', 'w');
            
            fwrite($fp, $_data);
            fclose($fp);
            
        }
        
        public function uploadRobotsTxt()
        {
            if($this->request->file('robots_add')) {
                
                $robots = $this->request->file('robots_add');
                
                $name       = $robots->getClientOriginalName();
                $check_name = explode('.', $name);
                $resol      = array_pop($check_name);
                if($resol == 'txt' || $resol == 'TXT') {
                    $fp = fopen($_SERVER['DOCUMENT_ROOT'] . '/public/robots.txt', 'w');
                    
                    $contents = File::get($robots);
                    
                    fwrite($fp, $contents);
                    fclose($fp);
                }
            }
            
            return redirect()->route('admin.dashboard');
        }
        
        public function parserBiosvet()
        {
            $in  = 0;
            $up  = 0;
            $url = 'https://www.biosvet.ru/api/v1/stock/full/';
            // $url = 'https://www.biosvet.ru/api/v1/stock/short/';
            
            $login    = '3830366@mail.ru';
            $password = 'psda85131ar6';
            
            $headers = [
                'Authorization-Login: ' . $login,
                'Authorization-Password: ' . $password
            ];
            
            $curl = curl_init($url);
            curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
            $response = curl_exec($curl);
            curl_close($curl);
            
            $array = json_decode($response, true);
            
            $arr_ids = [];
            
            //  dd($array['products']);
            
            foreach($array['categories'] AS $k => $v) {
                
                $hasher = PartsCommon::parsPart($v['id']);
                
                if(!isset($hasher[0]->id) || (int)$hasher[0]->id == 0) {
                    $data                 = [];
                    $data['id_pars']      = $v['id'];
                    $data['parts_name']   = $v['name'];
                    $data['parts_alias']  = $this->translitIt($v['name']);
                    $data['parts_status'] = 0;
                    $data['parts_type']   = 4;
                    
                    $parent = PartsCommon::find(208);
                    $node   = PartsCommon::create($data, $parent, false);
                    $id     = $node->id;
                    PartsCommon::createPartDirectorys($id);
                } else {
                    $id = $hasher[0]->id;
                }
                
                $arr_ids[$v['id']] = $id;
            }
            $cat_id = 0;
            
            foreach($array['products'] AS $k => $v) {
                
                foreach($arr_ids AS $ks => $vs) {
                    if($ks == $v['cat_id']) {
                        $cat_id = $vs;
                        break;
                    }
                }
                
                $articul = str_replace('bs', 'us', $v['article']);
                
                $data                      = [];
                $data['catalog_g_articul'] = $articul;
                $data['catalog_g_price']   = $v['price_rrc'];
                
                $hasher = CatalogG::hashUnitPars($data);
                
                //CatalogG::updateUnitPars($data);
                
                $hash = $hasher[0]->id ?? 0;
                
                if($hash > 0) {
                    if($hasher[0]->catalog_g_price != $v['price_rrc']) {
                        $up += CatalogG::updateUnitPars($data);
                    }
                } else {
                    if($hash == 0 && $cat_id > 0) {
                        
                        $data['id_part']                =  $cat_id;
                        $data['catalog_g_name']         =  $v['name'];
                        $data['catalog_g_alias']        =  $this->translitIt($v['name']);
                        $data['catalog_g_desc']         =  $v['description'];
                        $data['catalog_g_availability'] =  2;
                        $data['catalog_g_valu']         = 'руб';
                        
                        $id_part = CatalogG::create($data, null, true);
                        CatalogG::createRel([
                                                'id_part' => $data['id_part'],
                                                'id_catalog' => $id_part->id,
                                                'catalog_g_status' => 1
                                            ]);
                        
                        $in++;
                        
                        if(isset($v['properties']) && count($v['properties']) > 0) {
                            foreach($v['properties'] AS $properti) {
                                
                                $properti['name'];
                                
                                $hashSpecific = CatalogG::hashSpecific([
                                                                           'id_part' => $cat_id,
                                                                           'specification_name' => $properti['name']
                                                                       ]);
                                
                                $specific_id = $hashSpecific[0]->id ?? 0;
                                
                                if($specific_id == 0) {
                                    
                                    $specific_id = CatalogG::specificAdd([
                                                                             'id_part' => $cat_id,
                                                                             'specification_name' => $properti['name'],
                                                                             'specification_dimansion' => 1,
                                                                             'dimansion_limit' => 2,
                                                                             'specification_status' => 1,
                                                                             '_sort_n' => 10
                                                                         ]);
                                }
                                CatalogG::createSpecificationUnitInfo([
                                                                          'id_part' => $cat_id,
                                                                          'id_catalog' => $id_part->id,
                                                                          'id_specific' => $specific_id,
                                                                          'type_value_text' => $properti['value'],
                                                                      ]);
                                
                            }
                        }
                        
                        $image_url = $v['image'] ?? '';
                        if($image_url != '') {
                            $imag       = $image_url;
                            $check_name = explode('.', $imag);
                            $resol      = array_pop($check_name);
                            if($resol == 'jpg' || $resol == 'jpeg' || $resol == 'png' || $resol == 'gif') {
                                $name_img = strtotime("now") . rand(111111, 999999) . '.' . $resol;
                                CatalogG::createCommonImg($data['id_part'], $imag, $name_img);
                                CatalogG::addCommonImgInDB([
                                                               'id_part' => $data['id_part'],
                                                               'id_catalog' => $id_part->id,
                                                               'catalog_g_img' => $name_img,
                                                               'catalog_g_img_status' => 2
                                                           ]);
                            }
                        }
                    }
                }
            }
            
            
            //            $f = fopen($_SERVER['DOCUMENT_ROOT'] . '/public/biosvet_full.json', 'w');
            //
            //            file_put_contents($_SERVER['DOCUMENT_ROOT'] . '/public/biosvet_full.json' ,$response);
            //
            //            fclose($f);
            
            
            print json_encode([
                                  'in' => $in,
                                  'up' => $up
                              ]);
            
        }
        
        function translitIt($str_name)
        {
            $tr = [
                "А" => "a",
                "Б" => "b",
                "В" => "v",
                "Г" => "g",
                "Д" => "d",
                "Е" => "e",
                "Ё" => "e",
                "Ж" => "j",
                "З" => "z",
                "И" => "i",
                "Й" => "y",
                "К" => "k",
                "Л" => "l",
                "М" => "m",
                "Н" => "n",
                "О" => "o",
                "П" => "p",
                "Р" => "r",
                "С" => "s",
                "Т" => "t",
                "У" => "u",
                "Ф" => "f",
                "Х" => "h",
                "Ц" => "ts",
                "Ч" => "ch",
                "Ш" => "sh",
                "Щ" => "sch",
                "Ъ" => "",
                "Ы" => "yi",
                "Ь" => "",
                "Э" => "e",
                "Ю" => "yu",
                "Я" => "ya",
                "а" => "a",
                "б" => "b",
                "в" => "v",
                "г" => "g",
                "д" => "d",
                "ё" => "e",
                "е" => "e",
                "ж" => "j",
                "з" => "z",
                "и" => "i",
                "й" => "y",
                "к" => "k",
                "л" => "l",
                "м" => "m",
                "н" => "n",
                "о" => "o",
                "п" => "p",
                "р" => "r",
                "с" => "s",
                "т" => "t",
                "у" => "u",
                "ф" => "f",
                "х" => "h",
                "ц" => "ts",
                "ч" => "ch",
                "ш" => "sh",
                "щ" => "sch",
                "ъ" => "y",
                "ы" => "yi",
                "ь" => "",
                "э" => "e",
                "ю" => "yu",
                "я" => "ya",
                " " => "-",
                "." => "",
                "/" => "-",
                "№" => "-N-",
                "+" => "-plus-",
                "'" => "",
                '"' => "",
                '&quot;' => "",
                '?' => "",
                '%' => ""
            ];
            return strtr($str_name, $tr);
        }
        
        /**
         *
         *
         * if($this->request->file('unit_photo_add')) {
         * $imag       = $this->request->file('unit_photo_add');
         * $name       = $imag->getClientOriginalName();
         * $check_name = explode('.', $name);
         * $resol      = array_pop($check_name);
         * if($resol == 'jpg' || $resol == 'jpeg' || $resol == 'png' || $resol == 'gif') {
         * $name_img = strtotime("now").rand(111111, 999999).'.'. $resol;
         * CatalogG::createCommonImg($data['id_part'], $imag, $name_img);
         * CatalogG::addCommonImgInDB(['id_part'=>$data['id_part'], 'id_catalog'=>$id_part->id, 'catalog_g_img'=>$name_img, 'catalog_g_img_status'=>2 ]);
         * }
         * },
         *
         *
         */
        
        
        public function parser_toys()
        {
            $get_all_parts = CatalogG::parsOutParts();
            foreach($get_all_parts AS $k => $v) {
                
                $data_p[$v->id]['id']              = $v->id;
                $data_p[$v->id]['id_part']         = $v->id_part;
                $data_p[$v->id]['catalog_g_name']  = $v->_name;
                $data_p[$v->id]['catalog_g_alias'] = $this->translitIt($v->_name);
                $data_p[$v->id]['catalog_g_title'] = $v->l_cont;
                $data_p[$v->id]['catalog_g_desc']  = $v->f_cont;
//                $data_p[$v->id]['catalog_g_size']             = $v->_cont;
                $data_p[$v->id]['catalog_g_measure']          = $v->measure_unit;
                $data_p[$v->id]['catalog_g_sale']             = $v->_sale;
                $data_p[$v->id]['catalog_g_price']            = $v->_price;
                $data_p[$v->id]['catalog_g_valu']             = $v->_valu;
                $data_p[$v->id]['catalog_g_availability']     = $v->_nal_unit;
                $data_p[$v->id]['catalog_g_availability_sum'] = $v->_col_nal;
                $data_p[$v->id]['catalog_g_articul']          = $v->_articul;
                $data_p[$v->id]['catalog_g_additional_unit']  = $v->_dop_unit;
                $data_p[$v->id]['catalog_g_to_ind']           = $v->_to_ind;
                $data_p[$v->id]['catalog_g_to_showroom']      = $v->_to_vit;
                $data_p[$v->id]['catalog_g_produser_s']       = $v->_proper_s;
                $data_p[$v->id]['catalog_g_produser_s_linc']  = $v->_proper_s_linc;
                $data_p[$v->id]['catalog_g_dillers']          = $v->_dillers;
//                $data_p[$v->id]['catalog_g_dillers_linc']     = $v->c_tipe;
                $data_p[$v->id]['catalog_g_servises'] = $v->_servises;
//                $data_p[$v->id]['catalog_g_servises_linc']    = $v->c_tipe;   http://uralsvettorg.ru/data/g_catalog/prem/Photo_energosberegayuschie_lampyi_serii_Ecola_Light_171_0306199.jpg
                $data_p[$v->id]['catalog_g_status']          = $v->_status;
                $data_p[$v->id]['photo'][$v->f_st][$v->f_id] = $v->f_name;
            }
            
            foreach($data_p AS $data) {
                
                $id_part = CatalogG::create($data, null, true);
                CatalogG::createRel([
                                        'id_part' => $data['id_part'],
                                        'id_catalog' => $id_part->id,
                                        'catalog_g_status' => 1
                                    ]);
                
                if(isset($data['photo'][1]) && count($data['photo'][1]) > 0) {
                    
                    foreach($data['photo'][1] AS $gen_photo) {
                        $image = 'http://uralsvettorg.ru/data/g_catalog/full/' . $gen_photo->f_name;
                        $resol = explode('.', $gen_photo->f_name);
                        $res   = array_pop($resol);
                        
                        $name_img = strtotime("now") . rand(111111, 999999) . '.' . $res;
                        CatalogG::createCommonImg($data['id_part'], $image, $name_img);
                        CatalogG::addCommonImgInDB([
                                                       'id_part' => $data['id_part'],
                                                       'id_catalog' => $id_part->id,
                                                       'catalog_g_img' => $name_img,
                                                       'catalog_g_img_status' => 2
                                                   ]);
                    }
                }
                if(isset($data['photo'][2]) && count($data['photo'][2]) > 0) {
                    
                    foreach($data['photo'][2] AS $gen_photo) {
                        $image = 'http://uralsvettorg.ru/data/g_catalog/full/' . $gen_photo->f_name;
                        $resol = explode('.', $gen_photo->f_name);
                        $res   = array_pop($resol);
                        
                        $name_img = strtotime("now") . rand(111111, 999999) . '.' . $res;
                        CatalogG::createCommonImg($data['id_part'], $image, $name_img);
                        CatalogG::addCommonImgInDB([
                                                       'id_part' => $data['id_part'],
                                                       'id_catalog' => $id_part->id,
                                                       'catalog_g_img' => $name_img,
                                                       'catalog_g_img_status' => 1
                                                   ]);
                    }
                }
            }
        }
        
        public function parser_toys_pfoto()
        {
            
            $get_all_parts = CatalogG::parsInSitePhoto(184);
            
            foreach($get_all_parts AS $gen_photo) {
                
                if($gen_photo->_status == 2) {
                    
                    try {
                        $image = 'http://uralsvettorg.ru/data/g_catalog/full/' . $gen_photo->f_name;
                        
                        $resol = explode('.', $gen_photo->f_name);
                        $res   = array_pop($resol);
                        
                        $name_img = strtotime("now") . rand(111111, 999999) . '.' . $res;
                        CatalogG::createCommonImg($gen_photo->id_part, $image, $name_img);
                        CatalogG::addCommonImgInDB([
                                                       'id_part' => $gen_photo->id_part,
                                                       'id_catalog' => $gen_photo->id_master,
                                                       'catalog_g_img' => $name_img,
                                                       'catalog_g_img_status' => 1
                                                   ]);
                    }
                    finally {
                        //clean up
                    }
                } else {
                    if($gen_photo->_status == 1) {
                        
                        try {
                            $image = 'http://uralsvettorg.ru/data/g_catalog/full/' . $gen_photo->f_name;
                            
                            $resol = explode('.', $gen_photo->f_name);
                            $res   = array_pop($resol);
                            
                            $name_img = strtotime("now") . rand(111111, 999999) . '.' . $res;
                            CatalogG::createCommonImg($gen_photo->id_part, $image, $name_img);
                            CatalogG::addCommonImgInDB([
                                                           'id_part' => $gen_photo->id_part,
                                                           'id_catalog' => $gen_photo->id_master,
                                                           'catalog_g_img' => $name_img,
                                                           'catalog_g_img_status' => 2
                                                       ]);
                        }
                        finally {
                            //clean up
                        }
                        
                        
                    }
                }
                
                
            }


//            $get_all_parts = CatalogG::parsExisrsPhoto(182);
//
//
//            foreach( $get_all_parts AS $v){
//
//                $unit[0] = $v;
//
//                CatalogG::removeImgUnitFile($unit);
//                CatalogG::removeImgUnitBD((int)$v->id);
//            }
            
            
            print 'ok';
            
            
        }
        
        public function parser_parts()
        {
            $get_all_parts = PartsCommon::parsOutParts();
            foreach($get_all_parts AS $v) {
                
                $data['id']            = $v->id;
                $data['parts_name']    = $v->_name;
                $data['parts_alias']   = $this->translitIt($v->_name);
                $data['parts_content'] = $v->_cont;
                $data['parts_type']    = $v->c_tipe;
                $data['parts_status']  = $v->invisible;
                
                
                $parent = PartsCommon::find($v->id_master);
                $node   = PartsCommon::create($data, $parent, false);
                $id     = $node->id;
                PartsCommon::createPartDirectorys($id);
                
                if($v->ph_name != '') {
                    
                    $image = 'http://uralsvettorg.ru/data/images/' . $v->ph_name . '.' . $v->_res;
                    PartsCommon::createImgPart($id, $image, $v->_res);
                }
            }
        }
        
        
    }
