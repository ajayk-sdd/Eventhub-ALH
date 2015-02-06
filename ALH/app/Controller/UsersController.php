<?php

class UsersController extends AppController {

    var $helpers = array('Html', 'Form', 'Cache');
    public $components = array('Cookie', 'Email', 'Upload','Session', 'Mailgun');
    public $paginate = array(
        'limit' => 10
    );

    /** @Created:     25-April-2014
     * @Method :     beforeFilter
     * @Author:      Sachin Thakur
     * @Modified :   ---
     * @Purpose:     Rendered before any function
     * @Param:       none
     * @Return:      none    
     */
    function beforeFilter() {
        $unameCookie = $this->Cookie->read('UsernameCookie');
        $passCookie = $this->Cookie->read('PasswordCookie');
        $unameCookie = (isset($unameCookie) && !empty($unameCookie)) ? $unameCookie : '';
        $passCookie = (isset($passCookie) && !empty($passCookie)) ? $passCookie : '';
        $this->set(compact('unameCookie', 'passCookie'));
        parent::beforeFilter();
        $this->Auth->allow('checkEmail', 'loginAjax', 'visitBanner', 'testLogin', 'thumbnail', 'getFromWordpress', 'admin_forgotPassword', 'admin_reset', 'index', 'register', 'confirm_email', 'login', 'forgot_password', 'reset', 'fb_connect', 'catSearchCount', 'vibeSearchCount','immediateFetchfbEvent','checkUserName');
    }

    /** @Created:    30-April-2014
     * @Method :     admin_index
     * @Author:      Prateek Jadhav
     * @Modified :   ---
     * @Purpose:     
     * @Param:       none
     * @Return:      none
     */
    public function admin_index() {
        $this->redirect(array('controller' => 'Events', 'action' => 'list'));
    }

    /** @Created:    30-April-2014
     * @Method :     index
     * @Author:      Prateek Jadhav
     * @Modified :   ---
     * @Purpose:     
     * @Param:       none
     * @Return:      none
     */
    public function index($action = NULL) {
      
        $this->layout = 'front/home';
        $this->set('title_for_layout', 'ALIST Hub :: Home Page');
        $this->loadModel("MyCalendar");
        $this->loadModel("MyWpplugin");
        $this->loadModel("Region");
        $this->loadModel("Category");
        $this->loadModel("Vibe");
        $this->loadModel("User");
        $this->loadModel("Zip");
        $this->loadModel("Search");
        $this->loadModel("Event");
        $this->loadModel("EventCategory");
        $this->loadModel("EventVibe");

        // Save Search If User Is Logged In
        if (AuthComponent::user("id")) {
            $existing_search_data = $this->Search->find("first", array("conditions" => array("Search.user_id" => AuthComponent::user("id"))));
            if ($action && !empty($existing_search_data)) {
                $this->Search->delete($existing_search_data["Search"]["id"]);
                $existing_search_data = array();
            }
        } else {
            // using session id for not logged in status
            $existing_search_data = $this->Search->find("first", array("conditions" => array("Search.session_id" => $this->Session->id())));
            if ($action && !empty($existing_search_data)) {
                $this->Search->delete($existing_search_data["Search"]["id"]);
                $existing_search_data = array();
            }
        }

        $this->set('log_in', AuthComponent::user("id"));
        // Check If Existing Search Data Exist
        if ($this->data) {


            // delete existing search data
            if (!empty($existing_search_data)) {
                $this->Search->delete($existing_search_data["Search"]["id"]);
            }
            // for save data to database
            if (AuthComponent::user("id")) {
                $save_search_data["Search"]["user_id"] = AuthComponent::user("id");
            } else {
                $save_search_data["Search"]["session_id"] = $this->Session->id();
            }
            $save_search_data["Search"]["calendar_type"] = "calendar";
            $save_search_data["Search"]["title"] = $this->data["Event"]["title"];
            $save_search_data["Search"]["order"] = $this->data["Event"]["order"];
            $save_search_data["Search"]["date"] = $this->data["Event"]["date"];
            $save_search_data["Search"]["limit"] = $this->data["Event"]["limit"];
            if (isset($this->data["FacebookShow"])) {
                $save_search_data["Search"]["FacebookShow"] = $this->data["FacebookShow"];
            }
            if (isset($this->data["allFacebookShow"])) {
                $save_search_data["Search"]["allFacebookShow"] = $this->data["allFacebookShow"];
            }
            if (isset($this->data["myFacebookShow"])) {
                $save_search_data["Search"]["myFacebookShow"] = $this->data["myFacebookShow"];
            }
            if (isset($this->data["Event"]["is_feature"])) {
                $save_search_data["Search"]["is_feature"] = $this->data["Event"]["is_feature"];
            }
            $save_search_data["Search"]["distance"] = $this->data["Event"]["distance"];
            $save_search_data["Search"]["zip"] = $this->data["Event"]["zip"];
            // $save_search_data["Search"]["event_show"] = $this->data["Event"]["event_show"];
            if (isset($this->data["Event"]["giveaway"])) {
                $save_search_data["Search"]["giveaway"] = $this->data["Event"]["giveaway"];
            }
            if (!empty($this->data["EventCategory"]["id"])) {

                foreach ($this->data["EventCategory"]["id"] as $key => $value):
                    $categoryEvent[] = $key;
                endforeach;
                $save_search_data["Search"]["EventCategory"] = implode(",", $categoryEvent);
            }else {
                $save_search_data["Search"]["EventCategory"] = 0;
            }
            if (!empty($this->data["EventVibe"]["id"])) {
                foreach ($this->data["EventVibe"]["id"] as $key => $value):
                    $vibeEvent[] = $key;
                endforeach;
                $save_search_data["Search"]["EventVibe"] = implode(",", $vibeEvent);
            }else {
                $save_search_data["Search"]["EventVibe"] = 0;
            }

            $this->Search->save($save_search_data);
        } else {
            // for fetch data from database
            if (!empty($existing_search_data)) {
                $this->request->data["Event"]["title"] = $existing_search_data["Search"]["title"];
                $this->request->data["Event"]["date"] = $existing_search_data["Search"]["date"];
                $this->request->data["Event"]["limit"] = $existing_search_data["Search"]["limit"];
                $this->request->data["Event"]["order"] = $existing_search_data["Search"]["order"];

                if (!empty($existing_search_data["Search"]["FacebookShow"])) {
                    $this->request->data["FacebookShow"] = $existing_search_data["Search"]["FacebookShow"];
                }
                else
                {
                    $this->request->data["FacebookShow"] = 1;
                }
                if (!empty($existing_search_data["Search"]["allFacebookShow"])) {
                    $this->request->data["allFacebookShow"] = $existing_search_data["Search"]["allFacebookShow"];
                }
                else
                {
                    $this->request->data["allFacebookShow"] = 1;
                }
                if (!empty($existing_search_data["Search"]["myFacebookShow"])) {
                    $this->request->data["myFacebookShow"] = $existing_search_data["Search"]["myFacebookShow"];
                }
                else
                {
                    $this->request->data["myFacebookShow"] = 1;
                }

                if (!empty($existing_search_data["Search"]["is_feature"])) {
                    $this->request->data["Event"]["is_feature"] = $existing_search_data["Search"]["is_feature"];
                }

                $this->request->data["Event"]["distance"] = $existing_search_data["Search"]["distance"];
                $this->request->data["Event"]["zip"] = $existing_search_data["Search"]["zip"];
                // $this->request->data["Event"]["event_show"] = $existing_search_data["Search"]["event_show"];

                if (!empty($existing_search_data["Search"]["giveaway"])) {
                    $this->request->data["Event"]["giveaway"] = $existing_search_data["Search"]["giveaway"];
                }

                if (!empty($existing_search_data["Search"]["EventCategory"])) {
                    $categoryEvent = explode(",", $existing_search_data["Search"]["EventCategory"]);
                    foreach ($categoryEvent as $ce) {
                        $event_category["EventCategory"]["id"][$ce] = "on";
                    }
                    $this->request->data["EventCategory"] = $event_category["EventCategory"];
                } else {
                    $categories_List = $this->Category->find("all", array("conditions" => array(), "recursive" => -1));
                    foreach ($categories_List as $cate) {
                        $event_category["EventCategory"]["id"][$cate['Category']["id"]] = "on";
                    }
                    $this->request->data["EventCategory"] = $event_category["EventCategory"];
                }

                if (!empty($existing_search_data["Search"]["EventVibe"])) {
                    $vibeEvent = explode(",", $existing_search_data["Search"]["EventVibe"]);
                    foreach ($vibeEvent as $ve) {
                        $event_vibe["EventVibe"]["id"][$ve] = "on";
                    }
                    $this->request->data["EventVibe"] = $event_vibe["EventVibe"];
                } else {
                    $Vibe_List = $this->Vibe->find("all", array("conditions" => array(), "recursive" => -1));
                    foreach ($Vibe_List as $vbList) {
                        $event_vibe["EventVibe"]["id"][$vbList['Vibe']["id"]] = "on";
                    }
                    $this->request->data["EventVibe"] = $event_vibe["EventVibe"];
                }
            }
            else
            {
                $this->request->data["FacebookShow"] = 1;
                $this->request->data["allFacebookShow"] = 1;
                $this->request->data["myFacebookShow"] = 1;
                
                $categories_List = $this->Category->find("all", array("conditions" => array(), "recursive" => -1));
                    foreach ($categories_List as $cate) {
                        $event_category["EventCategory"]["id"][$cate['Category']["id"]] = "on";
                    }
                    $this->request->data["EventCategory"] = $event_category["EventCategory"];
                    
                $Vibe_List = $this->Vibe->find("all", array("conditions" => array(), "recursive" => -1));
                    foreach ($Vibe_List as $vbList) {
                        $event_vibe["EventVibe"]["id"][$vbList['Vibe']["id"]] = "on";
                    }
                    $this->request->data["EventVibe"] = $event_vibe["EventVibe"];   
                
                
            }
        }



        $conditions = array("Event.status" => 1);
        $myconditions = array("Event.status" => 1);
        // all events should be greater then today
        $this->loadModel("EventDate");
        $now = date('Y-m-d');
        $allEventGreterThenNow = $this->EventDate->find("list", array("conditions" => array("EventDate.date >=" => $now), "order" => "EventDate.date ASC", "fields" => array("EventDate.event_id", "EventDate.event_id")));
        $conditions = array_merge($conditions, array("Event.id" => $allEventGreterThenNow));

        // for is_feature functionlities
        if ($this->data) {
            if (isset($this->data["Event"]["is_feature"])) {
                $conditions = array_merge($conditions, array("Event.is_feature" => "1"));
                $this->set("is_feature", 1);
            } else {
                $this->set("is_feature", 0);
            }
        }



        if (isset($this->data["Event"]["order"]) && $this->data["Event"]["order"] != "Event.start_date ASC" && !empty($this->data["Event"]["order"])) {
            $order = $this->data["Event"]["order"];
        } else {
            $order = 'Field(Event.id, ' . implode(',', $allEventGreterThenNow) . ')';
        }

        $this->Event->unBindModel(array('hasMany' => array("EventCategory", "EventVibe"), 'belongsTo' => array('User')), false);

        $this->Event->bindModel(array('hasOne' => array("EventCategory", "EventVibe")), false);
        //   pr($this->Event->find('all'));die;

        $regions = $this->Region->find("all", array("conditions" => array(), "recursive" => -1));
        $categories = $this->Category->find("all", array("conditions" => array(), "recursive" => -1, "order" => "Category.name ASC"));
        $vibes = $this->Vibe->find("all", array("conditions" => array(), "recursive" => -1, "order" => "Vibe.name ASC"));
        $this->set(compact('regions', 'categories', 'vibes'));

        # Find out the list of top search category & vibes#

        $pop_categories = $this->Category->find("all", array("conditions" => array("Category.count !=" => 0), "order" => "Category.count DESC", "limit" => 6, "recursive" => -1));
        $pop_vibes = $this->Vibe->find("all", array("conditions" => array("Vibe.count !=" => 0), "order" => "Vibe.count DESC", "limit" => 3, "recursive" => -1));
        $this->set(compact('pop_categories', 'pop_vibes'));

        #lets find out which event is added as my calendar
        $my_calendar = $this->MyCalendar->find("list", array("conditions" => array("MyCalendar.user_id" => AuthComponent::user("id")), "fields" => array("MyCalendar.id", "MyCalendar.event_id")));


        $calevent = $this->Event->find("all", array("conditions" => array("Event.id" => $my_calendar, "Event.status" => 1)));

        $allEventGreterThenNowmycal = $this->EventDate->find("list", array("conditions" => array("EventDate.date >=" => $now, "EventDate.event_id" => $my_calendar), "order" => "EventDate.date ASC", "fields" => array("EventDate.event_id", "EventDate.event_id")));

        $this->set("my_calendar", $my_calendar);

        #lets find out which event is added as my Wp plugin
        $my_wpplugin = $this->MyWpplugin->find("list", array("conditions" => array("MyWpplugin.user_id" => AuthComponent::user("id")), "fields" => array("MyWpplugin.id", "MyWpplugin.event_id")));
        $this->set("my_wpplugin", $my_wpplugin);

        // find user data and apply data intelligences
        $user_data = $this->User->find("first", array("conditions" => array("User.id" => AuthComponent::user("id")), "fields" => array("User.id")));
        if (!empty($user_data) && empty($this->data)) {
            if (isset($user_data["UserpCategory"]) && !empty($user_data["UserpCategory"])) {
                foreach ($user_data["UserpCategory"] as $uc) {
                    $user_category["EventCategory"]["id"][$uc["category_id"]] = "on";
                }
                $this->request->data["EventCategory"] = $user_category["EventCategory"];
            }
            if (isset($user_data["UserpVibe"]) && !empty($user_data["UserpVibe"])) {
                foreach ($user_data["UserpVibe"] as $uv) {
                    $user_vibe["EventVibe"]["id"][$uv["vibe_id"]] = "on";
                }
                $this->request->data["EventVibe"] = $user_vibe["EventVibe"];
               
            }
        }
     
        if (!isset($this->data["Event"]["distance"])) {
            $this->request->data["Event"]["distance"] = 30;
        }
        if (empty($this->data["Event"]["distance"])) {
            $this->set("distance", "");
        }


        if (!isset($this->params['named']['page']) && !isset($this->params['named']['sort'])) {
            if ($this->Session->read('conditions')) {
                $this->Session->delete('conditions');
            }
        }

        $conditions = array_merge($conditions, array("Event.option_to_show" => array(1, 3)));


        if ($this->data) {

            // for giveaway functionlity
            if (isset($this->data["Event"]["giveaway"]) && $this->data["Event"]["giveaway"] == 1) {
                $conditions = array_merge($conditions, array("Giveaway.id !=" => ""));
                $this->set("giveaway", 1);
            } else {
                $this->set("giveaway", 0);
            }
            $conditions_vibe = array();
            if (isset($this->data['EventVibe']['id']) && $this->data['EventVibe']['id'] != "") {
                $arrayVibe = array();
                foreach ($this->data['EventVibe']['id'] as $key => $value):
                    $arrayVibe[] = $key;
                endforeach;


                $VibesSearch = $this->Vibe->find("all", array("conditions" => array('Vibe.id' => $arrayVibe), "recursive" => -1));
                $vibeSearchArray = array();
                foreach ($VibesSearch as $vibeSearch):
                    $vibeSearchArray[] = $vibeSearch['Vibe']['name'];
                endforeach;
                $vibesS = substr(implode(', ', $vibeSearchArray), 0, 50) . '...';
                $this->set("vibesS", $vibesS);

                if (isset($this->data['EventVibe']['id']) && !empty($this->data['EventVibe']['id'])) {
                    //$conditions = array_merge($conditions, array('EventVibe.vibe_id' => $arrayVibe));
                    $conditions_vibe = array('EventVibe.vibe_id' => $arrayVibe);
                }
            }
            $category_condition = array();
            if (isset($this->data['EventCategory']['id']) && $this->data['EventCategory']['id'] != "") {
                $arrayCategory = array();
                foreach ($this->data['EventCategory']['id'] as $key => $value):
                    $arrayCategory[] = $key;
                endforeach;

                $categoriesSearch = $this->Category->find("all", array("conditions" => array('Category.id' => $arrayCategory), "recursive" => -1));
                $catSearchArray = array();
                foreach ($categoriesSearch as $catSearch):
                    $catSearchArray[] = $catSearch['Category']['name'];
                endforeach;
                $categoriesS = substr(implode(', ', $catSearchArray), 0, 50) . '...';
                $this->set("categoriesS", $categoriesS);

                if (isset($this->data['EventCategory']['id']) && !empty($this->data['EventCategory']['id'])) {
                    //$conditions = array_merge($conditions, array('EventCategory.category_id' => $arrayCategory));
                    $category_condition = array('EventCategory.category_id' => $arrayCategory);
                }
            }
            $ererer = array("Event.event_from" => "facebook");
            $cate_vibe = array('OR' => array($conditions_vibe, $category_condition, $ererer));
            $conditions = array_merge($conditions, $cate_vibe);
            if (isset($this->data["Event"]["view"])) {
                $this->set("view", $this->data["Event"]["view"]);
            } else {
                $this->set("view", "list");
            }
            if (isset($this->data["Event"]["date"])) {
                $date = $this->data["Event"]["date"];
                $today = date("Y-m-d");
                $week = date("Y-m-d H:i:s", strtotime("+1 week"));
                $month = date("Y-m-d H:i:s", strtotime("+1 month"));
                $year = date("Y-m-d H:i:s", strtotime("+1 year"));
                if ($date == "today") {
                    $conditions = array_merge($conditions, array("Event.start_date LIKE" => '%' . $today . '%'));
                } else if ($date == "week") {
                    $conditions = array_merge($conditions, array("Event.start_date <= " => $week));
                } else if ($date == "month") {
                    $conditions = array_merge($conditions, array("Event.start_date <= " => $month));
                } else if ($date == "year") {
                    $conditions = array_merge($conditions, array("Event.start_date <= " => $year));
                }
            }

            if (isset($this->data["Event"]["title"])) {

                $conditions = array_merge($conditions, array("Event.title LIKE" => '%' . $this->data["Event"]["title"] . '%'));
            }

            // get ip detail of current user
            $ipDetail = $this->ipDetail();
            // find the city name as per zip
            if (isset($this->data["Event"]["zip"]) && !empty($this->data["Event"]["zip"])) {
                $change_zip = $this->data["Event"]["zip"];
                $zip_detail = $this->Zip->findByZip($change_zip);
                $this->set("save_from_zip", "1");

                if (!empty($zip_detail)) {//pr($zip_detail["Zip"]["city"]);
                    $this->set("city_name", $zip_detail["Zip"]["city"]);
                } else {
                    $this->set("city_name", "");
                }
            }
            // find if manual zipcode given
            if (isset($this->data["Event"]["distance"]) && !empty($this->data["Event"]["distance"])) {

                $distance = $this->data["Event"]["distance"];

                $this->set("distance", $distance);

                if (isset($this->data["Event"]["zip"]) && !empty($this->data["Event"]["zip"])) {
                    $change_zip = $this->data["Event"]["zip"];
                    $zip_detail = $this->Zip->findByZip($change_zip);
                    $this->set("save_from_zip", "1");

                    if (!empty($zip_detail)) {//pr($zip_detail);
                        $this->set("zip_code_name", $zip_detail["Zip"]["city"]);
                        $lat = $zip_detail["Zip"]["lat"];
                        $lng = $zip_detail["Zip"]["lng"];
                        $find_zip = $this->findzip($distance, $lat, $lng); //pr($find_zip);
                        $find_zip[$zip_detail["Zip"]["id"]] = $zip_detail["Zip"]["zip"];
                    } else {
                        //$this->Session->setFlash("Zip code you entered seems to be wrong, please check", "default", array("class" => "red"));
                    }
                } else {
                    // find from user current zip code
                    $zip = $ipDetail->zipCode;
                    $this->request->data["Event"]["zip"] = $ipDetail->zipCode;
                    $this->set("zip_code_name", $ipDetail->cityName);
                    $lat = $ipDetail->latitude;
                    $lng = $ipDetail->longitude;
                    $find_zip = $this->findzip($distance, $lat, $lng);
                    $find_zip[] = $ipDetail->zipCode;
                    $find_zip[] = $ipDetail->zipCode;
                }
                
                if (isset($find_zip) && !empty($find_zip)) {
                    $conditions = array_merge($conditions, array("Event.cant_find_zip_code IN" => $find_zip));
                }
            }
        }
        if (isset($this->data["Event"]["event_show"]) && !empty($this->data["Event"]["event_show"])) {
            $events_show = $this->data["Event"]["event_show"];
            if ($events_show == "ALH") {
                $conditions = array_merge($conditions, array("Event.event_from" => "ALH"));
                $myconditions = "";
                $ev = "ALH";
            } else if ($events_show == "FB") {
                $conditions = array_merge($conditions, array("Event.event_from" => "facebook"));
                if (isset($this->data["myFacebookShow"]) && !isset($this->data["allFacebookShow"])) {
                    $conditions = array_merge($conditions, array("Event.user_id" => AuthComponent::user("id")));
                }
                $myconditions = "";
                $ev = "FB";
            } elseif ($events_show == "mycalendar") {
                $myconditions = array_merge($myconditions, array('Event.id' => $allEventGreterThenNowmycal));
                $ev = "mycalendar";
            } elseif ($events_show == "EF") {
                $conditions = array_merge($conditions, array("Event.event_from" => "eventful"));
                $myconditions = "";
                $ev = "EF";
            } else {
              
                  if (isset($this->data["FacebookShow"]))
                 {
                    if (isset($this->data["myFacebookShow"]) && !isset($this->data["allFacebookShow"])) {
                    $conditions = array_merge($conditions, array("OR"=>array(array("Event.user_id" => AuthComponent::user("id"),"Event.event_from"=>"facebook"),array("Event.event_from IN" =>array("ALH", "eventful")))));
                $allEventFB = '';
                }
                else
                {
                    $allEventFB = array("facebook", "ALH", "eventful");
                }
                 }
                 else
                 {
                    $allEventFB = array("ALH", "eventful");
                 }
                 if(isset($allEventFB) && !empty($allEventFB))
                 {
            $conditions = array_merge($conditions, array("Event.event_from IN" => $allEventFB));
                 }
                 $myconditions = "";
                $ev = "all";
            }
        }  else {
            if (isset($this->data["FacebookShow"]))
                 {
                    if (isset($this->data["myFacebookShow"]) && !isset($this->data["allFacebookShow"])) {
                    $conditions = array_merge($conditions, array("OR"=>array(array("Event.user_id" => AuthComponent::user("id"),"Event.event_from"=>"facebook"),array("Event.event_from IN" =>array("ALH", "eventful")))));
                $allEventFB = '';
                }
                else
                {
                    $allEventFB = array("facebook", "ALH", "eventful");
                }
                 }
                 else
                 {
                    $allEventFB = array("ALH", "eventful");
                 }
                 if(isset($allEventFB) && !empty($allEventFB))
                 {
            $conditions = array_merge($conditions, array("Event.event_from IN" => $allEventFB));
                 }
            $myconditions = "";
            $ev = "all";
        }

        $this->set("event_show", $ev);
        if (isset($this->data["Event"]["limit"]) && !empty($this->data["Event"]["limit"])) {
            //  echo $this->data["Event"]["limit"];die;
            $limit = $this->data["Event"]["limit"];
        } else {
            $limit = 10;
        }
        
        $this->set("limit", $limit);
        if (sizeof($conditions) > 0) {
            $this->Session->write('conditions', $conditions);
        }


        if (isset($this->params["named"]["page"])) {
           
            if (isset($_SESSION["calender_conditions"])) {
                $conditions = $_SESSION["calender_conditions"];
            }
        } else if (!empty($conditions)) {
           
            $_SESSION["calender_conditions"] = $conditions;
        }



        if (isset($myconditions) && !empty($myconditions)) {
            $cond_new = $myconditions;
        } else {
            $cond_new = $conditions;
        }

        $this->paginate = array('conditions' => $cond_new, 'limit' => $limit, "order" => $order, 'group' => 'Event.id');
        $this->Event->unbindModel(array("hasMany" => array("EventImages", "EventEditedUser", "EventFbattendUser")));
      

        $countEvents = $this->Event->find('count', array("conditions" => $cond_new, "group" => "Event.id"));
       
        if (isset($this->params["named"]["page"])) {
            $pageCount = $this->params["named"]["page"];
           
            if (($limit * $pageCount) - $countEvents < $limit) {
                $events = $this->paginate('Event');
                $this->set('events', $events);
            }
        } else {

            $events = $this->paginate('Event');
           
            $this->set('events', $events);
        }

        /*         * *******************Recommended Event List Start********************* */

        $rec_conditions = array_merge($conditions, array("Event.is_feature" => '1'));
        
           
        unset($rec_conditions['Event.event_from IN']);
        unset($rec_conditions['Event.event_from']);
        unset($rec_conditions['Event.title LIKE']);
        unset($rec_conditions['EventVibe.vibe_id']);
        unset($rec_conditions['EventCategory.category_id']);
        unset($rec_conditions['Event.start_date LIKE']);
        unset($rec_conditions['OR']);
        unset($rec_conditions['Event.user_id']);
      
        
        $recommEvents = $this->Event->find('all', array("conditions" => $rec_conditions, 'order' => 'Field(Event.id, ' . implode(',', $allEventGreterThenNow) . ')', "limit" => 4, "group" => "Event.id"));
        $this->set('recommEvents', $recommEvents);

        /*         * ******************Recommended Event List End********************** */




        ///////////////// now start fetching eventfull events as per conditions ///////////


        if (AuthComponent::user("id")) {
            $existing_search_data = $this->Search->find("first", array("conditions" => array("Search.user_id" => AuthComponent::user("id"))));
            if ($action && !empty($existing_search_data)) {
                $this->Search->delete($existing_search_data["Search"]["id"]);
                $existing_search_data = array();
            }
        } else {
            $existing_search_data = $this->Search->find("first", array("conditions" => array("Search.session_id" => $this->Session->id())));
            if ($action && !empty($existing_search_data)) {
                $this->Search->delete($existing_search_data["Search"]["id"]);
                $existing_search_data = array();
            }
        }
        // Check If Existing Search Data Exist

        if ($this->data) {
            if (!empty($this->data["cat"])) {

                foreach ($this->data["cat"] as $key => $value):
                    $categoryEvent[] = $key;
                endforeach;
                $save_search_data["Search"]["EventCategory"] = implode(",", $categoryEvent);
            }
        } else {
            // for fetch data from database
            if (!empty($existing_search_data)) {
                $this->request->data["Event"]["keyword"] = $existing_search_data["Search"]["title"];
                $this->request->data["Event"]["date"] = $existing_search_data["Search"]["date"];

                $this->request->data["Event"]["distance"] = $existing_search_data["Search"]["distance"];
                $this->request->data["Event"]["city"] = $existing_search_data["Search"]["zip"];
                // $this->request->data["Event"]["event_show"] = $existing_search_data["Search"]["event_show"];



                if (!empty($existing_search_data["Search"]["EventCategory"])) {
                    $categoryEvent = explode(",", $existing_search_data["Search"]["EventCategory"]);
                    foreach ($categoryEvent as $ce) {
                        $event_category["cat"][$ce] = "on";
                    }
                    $this->request->data["cat"] = $event_category["cat"];
                }
            }
        }

        $ipDetail = $this->ipDetail();
      
        if (isset($this->data["Event"]["zip"])) {
            $Zip = $this->data["Event"]["zip"];
        } else {
            $Zip = $ipDetail->zipCode;
        }
        $zip_detail = $this->Zip->findByZip($Zip);
        if (isset($zip_detail['Zip']['city'])) {
            $zip_city = $zip_detail['Zip']['city'];
            $z = $Zip;
        } else {
            if ($this->data["Event"]["distance"] != "") {
                $zip_city = $ipDetail->cityName;
                $z = $ipDetail->cityName;
            } else {
                $zip_city = "United States";
                $z = "United States";
            }

            $zip_condition = "Not found";
        }

        $TopEventZip = $ipDetail->zipCode;
        $TopEventZip_detail = $this->Zip->findByZip($TopEventZip);
       
        if (isset($TopEventZip_detail['Zip']['city'])) {
            $TopEventZip_city = $TopEventZip_detail['Zip']['city'];
        } else {
            $TopEventZip_city = $ipDetail->cityName;
        }

        require_once "Eventful_new.php";

        $AppKey = "qGt8LCwr2CVprzVW";

        $eV = new Eventful($AppKey);
        if (isset($this->params["named"]["page"])) {
            $pages = $this->params["named"]["page"];
        } else {
            $pages = "";
        }
        if (!empty($this->data)) {
            if (!empty($this->data["EventCategory"]["id"])) {
                $categoryEvent = array();
                foreach ($this->data["EventCategory"]["id"] as $key => $value):
                    $eventcategory = $this->Category->find("first", array("conditions" => array("Category.id" => $key), "recursive" => -1));
                    $categoryEvent[$eventcategory["Category"]["id"]] = $eventcategory["Category"]["eventfulID"];
                endforeach;
                $CatSearch = implode(",", $categoryEvent);
            } else {
                $CatSearch = '';
            }
            $category = $CatSearch;
            $within = $this->data["Event"]["distance"];
            $location = $z;
            if (isset($this->data["Event"]["title"]))
                $keywords = $this->data["Event"]["title"];
            else
                $keywords = "";
            if (isset($this->data["Event"]["date"])) {
                if ($this->data["Event"]["date"] == "today") {
                    $search_date = "Today";
                } else if ($this->data["Event"]["date"] == "week") {
                    $search_date = "This Week";
                } else if ($this->data["Event"]["date"] == "month") {
                    $search_date = "Future";
                } else if ($this->data["Event"]["date"] == "year") {
                    $search_date = "Future";
                } else if ($this->data["Event"]["date"] == "all") {
                    $search_date = "Future";
                } else {
                    $search_date = "Future";
                }
            } else {
                $search_date = "Future";
            }
          
            $date = $search_date;
        } else {
            $location = $z;
            $within = '30';
            $keywords = '';
            $date = '';
            $category = '';
            if (isset($this->params["named"]["page"])) {
                $this->request->data["Event"]["city"] = $this->Session->read("con_zip");
            } else {
                $this->request->data["Event"]["city"] = $ipDetail->zipCode;
            }
        }
        if (isset($this->params["named"]["page"])) {
            
        } else if (!empty($this->data)) {
            $this->Session->write("con_location", $location);
            $this->Session->write("con_within", $within);
            $this->Session->write("con_keywords", $keywords);
            $this->Session->write("con_date", $date);
            $this->Session->write("con_category", $category);
            $this->Session->write("con_zip", $Zip);
            $this->Session->write("con_zip_city", $zip_city);
        }
        $this->set('within', $within);
        $this->set('scity', $zip_city);

        $evLogin = $eV->login('sachint', 'sachint');
        if ($evLogin) {
            $evArgs = array(
                'location' => $location,
                'page_number' => $pages,
                'within' => $within,
                'units' => 'mi',
                'keywords' => $keywords,
                'date' => $date,
                'category' => $category,
                'page_size' => $limit,
            );

            $evArgsTop = array(
                'location' => $location,
                'page_number' => $pages,
                'within' => 50,
                'units' => 'mi',
                'sort_order' => 'popularity',
                'include' => 'links',
            );

            $cEvent = $eV->call('events/search', $evArgs);
            $cEventTop = $eV->call('events/search', $evArgsTop);
            $eevent = json_decode(json_encode($cEvent), TRUE);
           
            $efevent = array();
            $i = 0;
            foreach ($eevent["events"]["event"] as $key => $value) {
              
                $efdate[$key] = $value["start_time"];
                $i++;
            }
            // sort this array
            array_multisort($efdate, SORT_ASC, $eevent["events"]["event"]);
            // set this sorted array
           
            $this->set("efsorted", $eevent["events"]["event"]);
            $this->set('eevent', $cEvent);
            $this->set('eeventTop', $cEventTop);

            $catList = $eV->call('categories/list');
            $this->set('catList', json_decode(json_encode($catList), true));
        } else {
            die("<strong>Error logging into Eventful API</strong>");
        }
       
        // fetch banner to display
        // fetch main banner

        $this->loadModel("Banner");
        $this->Banner->unbindModel(array("belongsTo" => array("Event", "Brand")));
        $mainBanner = $this->Banner->find('all', array("conditions" => array("Banner.status" => 1), 'order' => "Banner.order"));
        $this->set("main_banners", $mainBanner);
       }

    /** @Created:     25-April-2014
     * @Method :     admin_login
     * @Author:      Sachin Thakur
     * @Modified :   ---
     * @Purpose:     Admin Panel Login
     * @Param:       none
     * @Return:      none
     */
    function admin_login() {

        $this->loadModel("CommonLogin");
        $detail = $this->CommonLogin->findByIp($_SERVER["REMOTE_ADDR"]); //pr($detail);die;
        if (!empty($detail))
            $this->CommonLogin->delete($detail["CommonLogin"]["id"]);

        if (AuthComponent::user("id") && AuthComponent::user("role_id") == 1) {
            $this->redirect(array('controller' => 'Users', 'action' => 'dashboard'));
        }

        $this->layout = 'admin/login';
        $this->set('title_for_layout', 'ALIST Hub :: Login');
        $unameCookie = "";
        $passCookie = "";
        if ($this->request->is('post') && !empty($this->data)) {
            $password = AuthComponent::password($this->data['User']['password']);
            $user = $this->User->find("first", array("conditions" => array("OR" => array("User.username" => $this->data['User']['username'], "User.email" => $this->data['User']['username']), "User.password" => $password, 'User.status' => 1)));

            if ($user) {
                if ($this->data['User']['rememberme']) {
                    $time = 3600 * 24 * 30;
                    $this->Cookie->write('UsernameCookie', $this->data['User']['username'], false, $time);
                    $this->Cookie->write('PasswordCookie', $this->data['User']['password'], false, $time);
                } else {
                    $this->Cookie->delete('UsernameCookie');
                    $this->Cookie->delete('PasswordCookie');
                }

                $user['User']['Role'] = $user['Role'];
                $this->Auth->login($user['User']);
                $this->loadModel("CommonLogin");
                $data["CommonLogin"]["id"] = "";
                $data["CommonLogin"]["ip"] = $_SERVER["REMOTE_ADDR"];
                $data["CommonLogin"]["user_id"] = $user["User"]["id"];
                $this->CommonLogin->save($data);
                $this->redirect(array('controller' => 'Users', 'action' => 'admin_dashboard'));
            } else {
                $this->Session->setFlash('Please check credentials.', 'default', array('class' => 'red'));
            }
        } else {
            $unameCookie = $this->Cookie->read('UsernameCookie');
            $passCookie = $this->Cookie->read('PasswordCookie');
            $unameCookie = (isset($unameCookie) && !empty($unameCookie)) ? $unameCookie : '';
            $passCookie = (isset($passCookie) && !empty($passCookie)) ? $passCookie : '';
        }
        $this->set(compact('unameCookie', 'passCookie'));
    }

    /** @Created:     25-April-2014
     * @Method :     beforeFilter
     * @Author:      Sachin Thakur
     * @Modified :   ---
     * @Purpose:     Admin Dashboard
     * @Param:       none
     * @Return:      none
     */
    function admin_dashboard() {

        $this->layout = 'admin/admin';
        $this->set('title_for_layout', 'ALIST Hub :: Dashboard');
        $this->set('guest', $this->User->find('count', array('conditions' => array('User.role_id' => 5))));
        $this->set('member', $this->User->find('count', array('conditions' => array('User.role_id' => 2))));
        $this->set('super_member', $this->User->find('count', array('conditions' => array('User.role_id' => 3))));
        $this->set('premium_member', $this->User->find('count', array('conditions' => array('User.role_id' => 4))));
    }

    /** @Created:    28-April-2014
     * @Method :     admin_addRole
     * @Author:      Sachin Thakur
     * @Modified :   ---
     * @Purpose:     Admin Add Role
     * @Param:       none
     * @Return:      none
     */
    function admin_addRole($id = null) {
        $this->layout = 'admin/admin';
        
       
        $this->set('title_for_layout', 'ALIST Hub :: Add Role');
        $this->loadModel('Role');
        if ($this->data) {
            
        if(empty($this->data['Role']["id"]))
        {
            $upMsg = "Added Successfully"; 
        }
        else
        {
            $upMsg = "Updated Successfully";
        }
            $this->Role->create();
            if ($this->Role->save($this->data['Role'])) {
                $this->Session->setFlash($upMsg, 'default', array('class' => 'green'));
                $this->redirect(array('controller' => 'users', 'action' => 'roleList'));
            }
        }
        if ($id) {
            $id = base64_decode($id);
            $this->request->data = $this->Role->findById($id);
        }
    }

    /** @Created:    28-April-2014
     * @Method :     admin_add
     * @Author:      Sachin Thakur
     * @Modified :   ---
     * @Purpose:     Add User
     * @Param:       none
     * @Return:      none
     */
    function admin_add($id = null) {
        $this->layout = 'admin/admin';
        $this->set('title_for_layout', 'ALIST Hub :: Add Admin');
        $this->loadModel('Role');
        $this->loadModel('User');
        $this->set("roles", $this->Role->find("list", array('conditions' => array('Role.id != ' => 1), 'order' => "Role.name ASC")));
        if ($this->data) {
            $this->User->create();
          
            if(empty($id))
                {
                    $userDetailEmail = $this->User->find("first", array('conditions' => array('User.email' => $this->data['User']['email'])));
                    $userDetailUser = $this->User->find("first", array('conditions' => array('User.username' => $this->data['User']['username'])));
                }
                else
                {
                    $userDetailEmail = $this->User->find("first", array('conditions' => array('User.email' => $this->data['User']['email'],'User.id !=' => $id)));
                    $userDetailUser = $this->User->find("first", array('conditions' => array('User.username' => $this->data['User']['username'],'User.id !=' => $id)));
                }
            
            if(!empty($userDetailEmail))
            {
                $this->Session->setFlash($this->data['User']['email'].' Email already exist. Try again with another Email-Id!', 'default', array('class' => 'red'));
                if(!empty($id))
                {
                $this->redirect(array('controller' => 'Users', 'action' => 'add/'.base64_encode($id)));
                }
            }
            elseif(!empty($userDetailUser))
            {
                $this->Session->setFlash($this->data['User']['username'].' Username already exist. Try again with another Username!', 'default', array('class' => 'red'));
                 if(!empty($id))
                {
                $this->redirect(array('controller' => 'Users', 'action' => 'add/'.base64_encode($id)));
                }
            }
            else
            {
            if ($this->User->save($this->data['User'])) {
                if(!empty($id))
                {
                    $this->Session->setFlash('User Edited Successfully', 'default', array('class' => 'green'));
                }
                else
                {
                     $this->Session->setFlash('User Created Successfully', 'default', array('class' => 'green'));
                }
                $this->redirect(array('controller' => 'users', 'action' => 'list'));
            }
            }
        }
        if ($id) {
            $id = base64_decode($id);
            $this->request->data = $this->User->findById($id);
        }
    }

    /** @Created:    28-April-2014
     * @Method :     admin_logout
     * @Author:      Sachin Thakur
     * @Modified :   ---
     * @Purpose:     Admin Log Out
     * @Param:       none
     * @Return:      none
     */
    function admin_logout() {
        $this->Auth->logout();
        //$this->redirect(array('controller' => 'Users', 'action' => 'login'));
        $this->redirect("/admin/users/login");
    }

    /** @Created:    29-April-2014
     * @Method :     admin_list
     * @Author:      Sachin Thakur
     * @Modified :   ---
     * @Purpose:     Admin Users Listing
     * @Param:       none
     * @Return:      none
     */
    function admin_list($clear = NULL) {
        if(isset($clear)){
            $this->Session->delete("search_user");
            $this->redirect(array("controller"=>"Users","action"=>"list"));
        }
        $this->layout = 'admin/admin';
        $this->set('title_for_layout', 'ALIST Hub :: User Listing');
        $conditions = array("User.role_id !=" => 1);
        if (isset($this->params["named"]["page"])) {
            $this->request->data = $this->Session->read("user_list");
        } else {
            $this->Session->delete("user_list");
        }
        if($this->data){
            $this->Session->write("search_user",$this->data);
        } else {
            if($this->Session->check("search_user")){
                $this->request->data = $this->Session->read("search_user");
            }
        }
        
        if ($this->data) {
            
            if (!empty($this->data["User"]["id"])) {
                $conditions = array_merge($conditions, array("User.id LIKE" => "" . $this->data["User"]["id"] . ""));
            }
            if (!empty($this->data["User"]["first_name"])) {
                $conditions = array_merge($conditions, array("User.first_name LIKE" => "" . $this->data["User"]["first_name"] . ""));
            }
            if (!empty($this->data["User"]["last_name"])) {
                $conditions = array_merge($conditions, array("User.last_name LIKE" => "" . $this->data["User"]["last_name"] . ""));
            }
            if (!empty($this->data["User"]["username"])) {
                $conditions = array_merge($conditions, array("User.username LIKE" => "%" . $this->data["User"]["username"] . "%"));
            }
            if (!empty($this->data["User"]["email"])) {
                $conditions = array_merge($conditions, array("User.email LIKE" => "%" . $this->data["User"]["email"] . "%"));
            }
            if (!empty($this->data["User"]["role_id"])) {
                $conditions = array_merge($conditions, array("User.role_id LIKE" => "" . $this->data["User"]["role_id"] . ""));
            }
            $this->paginate = array('conditions' => $conditions, 'limit' => $this->data["User"]["limit"], 'order' => array($this->data["User"]["order"] => $this->data["User"]["direction"]));
            $this->request->data = $this->data;
            $this->Session->write("user_list", $this->data);
        } else {
            $this->paginate = array('conditions' => $conditions, 'limit' => 10, 'order' => array('User.id' => 'DESC'));
        }
        $this->set('users', $this->paginate('User'));
    }

    /** @Created:    29-April-2014
     * @Method :     admin_forgotPassword
     * @Author:      Sachin Thakur
     * @Modified :   ---
     * @Purpose:     Forgot Password
     * @Param:       none
     * @Return:      none
     */
    function admin_forgotPassword() {
        $this->layout = false;
        $this->loadModel('User');
        $this->loadModel('EmailTemplate');
        $user = $this->User->find('first', array('conditions' => array('User.email' => $this->data['User']['email'])));
        if (!empty($user)) {
            $token = md5($user['User']['email']) . time();
            $url = BASE_URL . "/admin/users/reset/" . $token . "/" . $user['User']['id'];
            $activation = '<a style="background-color: #6A6A6A;border-radius: 2px 2px 2px 2px;color: #FFFFFF;display: inline-block;font-size: 14px;font-weight: bold;padding: 10px 19px;text-decoration: none;" target="_blank" href="' . $url . '">CLICK HERE TO CHANGE PASSWORD</a>';
            $this->request->data['User']['id'] = $user['User']['id'];
            $this->request->data['User']['token'] = $token;
            if ($this->User->save($this->data)) {

                $emailTemp = $this->EmailTemplate->find('first', array('conditions' => array('EmailTemplate.alias' => "forgot-password")));
                $emailContent = utf8_decode($emailTemp['EmailTemplate']['description']);
                $data = str_replace(array('{USER_NAME}', '{FORGOT_PASSWORD}', '{url}'), array($user['User']['username'], $activation, $url), $emailContent);
                $this->set('mailData', $data);
                $this->Email->to = $user['User']['email'];
                $this->Email->subject = $emailTemp['EmailTemplate']['subject'];
                $this->Email->from = "aisthub@admin.com";
                $this->Email->template = "forgot";
                $this->Email->sendAs = 'html';

                if ($this->Email->send($data)) {
                    $this->Session->setFlash('Email Sent Successfully.', 'default', array('class' => 'green'));
                    $this->redirect(array('controller' => 'Users', 'action' => 'login', 'admin' => true));
                } else {
                    $this->Session->setFlash('Email Not Sent.', 'default', array('class' => 'red'));
                    $this->redirect(array('controller' => 'Users', 'action' => 'login', 'admin' => true));
                }
            }
        } else {
            $this->Session->setFlash('Email Not Exist.', 'default', array('class' => 'red'));
            $this->redirect(array('controller' => 'Users', 'action' => 'login', 'admin' => true));
        }
    }

    /** @Created:    29-April-2014
     * @Method :     admin_reset
     * @Author:      Sachin Thakur
     * @Modified :   ---
     * @Purpose:     Reset Password
     * @Param:       $token,$userId
     * @Return:      none
     */
    function admin_reset($token = NULL, $userId = NULL) {
        $this->layout = 'admin/login';
        $this->set('title_for_layout', 'ALIST Hub :: Reset Password');
        $this->loadModel('User');
        $this->set(compact('token', 'userId'));
        $userData = $this->User->find('first', array('conditions' => array('User.token' => $token, 'User.id' => $userId)));

        if (isset($userData)) {

            if (isset($this->data) && !empty($this->data)) {
                $newPassword = AuthComponent::password($this->data['User']['password']);
                $this->User->updateAll(array('User.token' => NULL, 'User.password' => '"' . $newPassword . '"'), array('User.id' => $this->data['User']['userId']));
                $this->Session->setFlash('Password changed successfully.', 'default', array('class' => 'green'));
                $this->redirect(array('controller' => 'Users', 'action' => 'login', 'admin' => true));
            }
        } else {
            $this->Session->setFlash('This link has been expired.', 'default', array('class' => 'green'));
            $this->redirect(array('controller' => 'Users', 'action' => 'login', 'admin' => true));
        }
    }

    /** @Created:    29-April-2014
     * @Method :      admin_view
     * @Author:      Prateek Jadhav
     * @Modified :   2-jun-2014
     * @Purpose:     view user
     * @Param:       $user_id
     * @Return:      none
     */
    public function admin_view($user_id = null) {
        $this->layout = 'admin/admin';
        $this->set('title_for_layout', 'ALIST Hub :: User Detail');
        if ($this->data) {
            if ($this->User->save($this->data)) {
                if (empty($this->data["User"]["password"])) {
                    unset($this->data["User"]["password"]);
                }
                $this->Session->setFlash("Users data saved", "default", array("class" => "green"));
                $this->redirect(array("controller" => "Users", "action" => "view", base64_encode($this->data["User"]["id"])));
            } else {
                $this->Session->setFlash("Unable to save data", "default", array("class" => "red"));
                $this->redirect(array("controller" => "Users", "action" => "view", base64_encode($this->data["User"]["id"])));
            }
        }
        if ($user_id) {
            $user_id = base64_decode($user_id);
            $this->request->data = $this->User->findById($user_id);

            $this->loadModel("VisitBanner");
            $this->set("visitBanner", $this->VisitBanner->find("all", array("conditions" => array("VisitBanner.user_id" => $user_id))));

            $this->loadModel("VisitEvent");
            $this->set("visitEvent", $this->VisitEvent->find("all", array("conditions" => array("VisitEvent.user_id" => $user_id), "fields" => array("VisitEvent.*", "Event.title", "Event.id"))));
            $this->loadModel('Role');
            $this->set("roles", $this->Role->find("list", array('conditions' => array('Role.id != ' => 1))));
            // now set event
            $this->loadModel("Event");
            $this->set("events", $this->Event->find("all", array("conditions" => array("Event.user_id" => $user_id))));
            // now set my saved event
            $this->loadModel("MyCalendar");
            $this->set("myEvents", $this->MyCalendar->find("all", array("conditions" => array("MyCalendar.user_id" => $user_id))));
            // now set my suscribe brand
            $this->loadModel("SuscribeBrand");
            $this->set("suscribeBrands", $this->SuscribeBrand->find("all", array("conditions" => array("SuscribeBrand.user_id" => $user_id))));
            // now set my list
            $this->loadModel("MyList");
            $this->set("myLists", $this->MyList->find("all", array("conditions" => array("MyList.user_id" => $user_id))));

            // now set login history
            $this->loadModel("Information");
            $this->set("information", $this->Information->find("all", array("conditions" => array("Information.user_id" => $user_id))));

            $this->loadModel("CashOutPoint");
            $cash_point_list = $this->CashOutPoint->find("all", array("conditions" => array("CashOutPoint.user_id" => $user_id)));

            $this->loadModel('Payment');
            $pay_points = $this->Payment->find("all", array("conditions" => array("Payment.user_id" => $user_id, "Payment.status" => 1)));
            $points_man = array();
            foreach ($pay_points as $pay) {
                if ($pay['Package']['buy_point_id'] > 0) {
                    $points_man[] = $pay;
                }
            }
            $point_list = array_merge($points_man, $cash_point_list);

            $this->set("cashoutpoint", $point_list);
            // now set search history
            $this->loadModel("Search");
            $search = $this->Search->find("first", array("conditions" => array("Search.user_id" => $user_id)));
           
            $this->set("search", $search);
            if (!isset($search["Search"]["EventCategory"]) && !empty($search["Search"]["EventCategory"])) {
                $this->loadModel("Category");
                $cate = explode(",", $search["Search"]["EventCategory"]);
                $category = $this->Category->find("list", array("Category.id" => $cate));
                $setCate = "";
                foreach ($category as $key => $value) {
                    $setCate = $setCate . "<br>" . $value;
                }
                $this->set("category", $setCate);
            }
            if (!isset($search["Search"]["EventVibe"]) && !empty($search["Search"]["EventVibe"])) {
                $this->loadModel("Vibe");
                $vibe = explode(",", $search["Search"]["EventVibe"]);
                $vibes = $this->Vibe->find("list", array("Category.id" => $vibe));
                $setVibe = "";
                foreach ($vibes as $key => $value) {
                    $setVibe = $setVibe . "<br>" . $value;
                }
                $this->set("vibe", $setVibe);
            }
        } else {
            $this->Session->setFlash('Cannot access directly', 'default', array('class' => 'red'));
            $this->redirect(array('contoller' => 'users', 'action' => 'list'));
        }
    }

    /** @Created:    29-April-2014
     * @Method :     admin_roleList
     * @Author:      Sachin Thakur
     * @Modified :   ---
     * @Purpose:     Role Listing
     * @Param:       none
     * @Return:      none
     */
    function admin_roleList($keyword = null) {
        $this->layout = 'admin/admin';
        $this->set('title_for_layout', 'ALIST Hub :: Role Listing');
        $this->loadModel('Role');
        if ($keyword) {
//$conditions = array("OR" => array('User.username LIKE' => "%$keyword%", 'User.email LIKE' => "%$keyword%", 'Role.name LIKE' => "%$keyword%"));
        } else {
            $conditions = array();
        }
        $this->paginate = array('conditions' => $conditions, 'limit' => 10, 'order' => array('Role.id' => 'DESC'));
        $this->set('roles', $this->paginate('Role'));
        if (!$keyword)
            $keyword = "";
        $this->set('keyword', $keyword);
    }

    /** @Created:    29-April-2014
     * @Method :     admin_list_csv
     * @Author:      Prateek Jadhav
     * @Modified :   ---
     * @Purpose:     Admin Users Listing CSV
     * @Param:       $keyword
     * @Return:      none
     */
    function admin_list_csv($keyword = null) {
        $this->layout = false;
        $conditions = array();
        if ($this->Session->check("user_list")) {
            $this->request->data = $this->Session->read("user_list");
        }
        if ($this->data) {
            if (!empty($this->data["User"]["username"])) {
                $conditions = array_merge($conditions, array("User.username LIKE" => "%" . $this->data["User"]["username"] . "%"));
            }
            if (!empty($this->data["User"]["email"])) {
                $conditions = array_merge($conditions, array("User.email LIKE" => "%" . $this->data["User"]["email"] . "%"));
            }
            $this->paginate = array('conditions' => $conditions, 'order' => array($this->data["User"]["order"] => $this->data["User"]["direction"]));
            $this->request->data = $this->data;
            $this->Session->write("user_list", $this->data);
        } else {
            $this->paginate = array('conditions' => $conditions, 'order' => array('User.id' => 'ASC'));
        }

        $this->set('users', $this->paginate('User'));
    }

    /** @Created:    5-may-2014
     * @Method :     register
     * @Author:      Prateek Jadhav
     * @Modified :   ---
     * @Purpose:     Users registration
     * @Param:       none
     * @Return:      none
     */
    public function register() {
        $this->layout = 'front/home';
        if ($this->data) {
            $this->request->data["User"]["token"] = md5($this->data["User"]["email"]);
            if ($this->User->save($this->data)) {
                $this->loadModel("EmailTemplate");
                $token = md5($this->data["User"]["email"]);
                $url = BASE_URL . "/users/confirm_email/" . $token;
                $activation = '<a style="background-color: #6A6A6A;border-radius: 2px 2px 2px 2px;color: #FFFFFF;display: inline-block;font-size: 14px;font-weight: bold;padding: 10px 19px;text-decoration: none;" target="_blank" href="' . $url . '">CLICK HERE TO CONFIRM EMAIL</a>';
                $emailTemp = $this->EmailTemplate->find('first', array('conditions' => array('EmailTemplate.alias' => "welcome_email")));
                $emailContent = utf8_decode($emailTemp['EmailTemplate']['description']);
                $data = str_replace(array('{USER_NAME}', '{WELCOME_LINK}', '{url}'), array($this->data['User']['username'], $activation, $url), $emailContent);
                $this->set('mailData', $data);
                $this->Email->to = $this->data['User']['email'];
                $this->Email->subject = $emailTemp['EmailTemplate']['subject'];
                $this->Email->from = "alisthub@admin.com";
                $this->Email->template = "forgot";
                $this->Email->sendAs = 'html';
                //$this->Email->send();
                $ids = trim($this->data["User"]["id"]);
                if (empty($ids)) {
                    
                    $this->Email->send($data); // pr($data);die;
                    header("location:".BASE_URL."/users/login?from=register");
                    die;
                   // $this->set("messagesd", "You have been Successfully register. Please check your mail and confirm your Email-id!");
                   // $this->Session->setFlash("Registerd successful", "default", array("class" => "green"));
                    //$this->redirect(array("controller" => "users", "action" => "login","?frm=register&msg=dfgdfg"));
                } else {
                    //$this->set("message", "");
                    $this->Session->setFlash("Update successful", "default", array("class" => "green"));
                    $this->redirect(array("controller" => "users", "action" => "viewProfile"));
                }
            }
        }
        if (AuthComponent::User("id")) {
            $this->request->data = $this->User->findById(AuthComponent::User("id"));
        }
    }

    /** @Created:    5-may-2014
     * @Method :     confirm_email
     * @Author:      Prateek Jadhav
     * @Modified :   ---
     * @Purpose:     Confirm Email
     * @Param:       token
     * @Return:      none
     */
    public function confirm_email($token) {
        $this->layout = false;
        $this->autoRender = false;
        if ($token) {
            $user = $this->User->findByToken($token);
            if ($user) {
                $data['User']['id'] = $user["User"]["id"];
                $data['User']['token'] = "";
                $data['User']['status'] = 1;
                if ($this->User->save($data)) {
                    $this->Session->setFlash("Email confirmed", "default", array("class" => "green"));
                    $this->redirect(array("controller" => "users", "action" => "login"));
                } else {
                    $this->Session->setFlash("A problem occured, please try again", "default", array("class" => "red"));
                    $this->redirect(array("controller" => "users", "action" => "login"));
                }
            } else {
                $this->Session->setFlash("Invalid security token", "default", array("class" => "red"));
                $this->redirect(array("controller" => "users", "action" => "login"));
            }
        } else {
            $this->Session->setFlash("Security token missing", "default", array("class" => "red"));
            $this->redirect(array("controller" => "users", "action" => "login"));
        }
    }

    /** @Created:    5-may-2014
     * @Method :     login
     * @Author:      Prateek Jadhav
     * @Modified :   ---
     * @Purpose:     Users login
     * @Param:       none
     * @Return:      none
     */
    public function login() {
        if (AuthComponent::User('id')) {
            $this->redirect(array('controller' => 'users', 'action' => 'index'));
        }
        $this->layout = 'front/home';
        $this->set('title_for_layout', 'ALIST Hub :: Login');
        $unameCookie = "";
        $passCookie = "";
        if ($this->request->is('post') && !empty($this->data)) {
            $password = AuthComponent::password($this->data['User']['password']);
            $user = $this->User->find("first", array("conditions" => array("OR" => array("User.username" => $this->data['User']['username'], "User.email" => $this->data['User']['username']), "User.password" => $password)));
            if (isset($user["User"]["status"]) && $user["User"]["status"] == 0) {
               
                if ($user["User"]["status"] == 0 && $user["User"]["token"] != NULL) {
                    $this->Session->setFlash('Please confirm your email address.', 'default', array('class' => 'red'));
                    //$this->redirect(array("controller" => "users", "action" => "login"));
                }
                if ($user["User"]["status"] == 0 && $user["User"]["token"] == NULL) {
                    $this->Session->setFlash('You are blocked by admin, we will contact you soon.', 'default', array('class' => 'red'));
                    //$this->redirect(array("controller" => "users", "action" => "login"));
                }
            } else if (!empty($user)) {
                if (isset($this->data['User']['rememberme'])) {
                    $time = 3600 * 24 * 30;
                    $this->Cookie->write('UsernameCookie', $this->data['User']['username'], false, $time);
                    $this->Cookie->write('PasswordCookie', $this->data['User']['password'], false, $time);
                } else {
                    $this->Cookie->delete('UsernameCookie');
                    $this->Cookie->delete('PasswordCookie');
                }

                $user['User']['Role'] = $user['Role'];
                if ($this->Auth->login($user['User'])) {
                    $this->loadModel("CommonLogin");
                    $old_data = $this->CommonLogin->findByIp($_SERVER["REMOTE_ADDR"]);
                    if ($old_data) {
                        $this->CommonLogin->delete($old_data["CommonLogin"]["id"]);
                    }
                    $this->loadModel("CommonLogin");
                    $data["CommonLogin"]["id"] = "";
                    $data["CommonLogin"]["ip"] = $_SERVER["REMOTE_ADDR"];
                    $data["CommonLogin"]["user_id"] = $user["User"]["id"];
                    $this->CommonLogin->save($data);

                    // save users additional information on informatoion table
                    $this->saveInfo();
                } else {
                    $this->Session->setFlash("Unable to login", "default", array("class" => "login"));
                    $this->redirect($this->referer());
                }
                if (isset($this->data['User']['red'])) {
                    $log_red = explode("/", $this->data['User']['red']);

                    $this->redirect(array('controller' => $log_red[0], 'action' => $log_red[1]));
                } else {
                    $this->redirect($this->referer());
                }
            } else {
                $this->Session->setFlash('Please check credentials.', 'default', array('class' => 'red'));
            }
        } else {
            $unameCookie = $this->Cookie->read('UsernameCookie');
            $passCookie = $this->Cookie->read('PasswordCookie');
            $unameCookie = (isset($unameCookie) && !empty($unameCookie)) ? $unameCookie : '';
            $passCookie = (isset($passCookie) && !empty($passCookie)) ? $passCookie : '';
        }
        $this->set(compact('unameCookie', 'passCookie'));
    }

    /** @Created:    10-Nov-2014
     * @Method :     loginAjax
     * @Author:      Prateek Jadhav
     * @Modified :   ---
     * @Purpose:     Users login
     * @Param:       none
     * @Return:      1/$return
     */
    public function loginAjax() {
        $this->autoRender = FALSE;
        $this->request->data["User"]["username"] = $_POST["username"];
        $this->request->data["User"]["password"] = $_POST["password"];
        $return = "";

        if (!empty($this->data)) {
            $password = AuthComponent::password($this->data['User']['password']);
            $user = $this->User->find("first", array("conditions" => array("OR" => array("User.username" => $this->data['User']['username'], "User.email" => $this->data['User']['username']), "User.password" => $password)));
            if (empty($user)) {
                $return = "Invalid username or password.";
            } else if (isset($user["User"]["status"]) && $user["User"]["status"] == 0) {
                
                if ($user["User"]["status"] == 0 && $user["User"]["token"] != NULL) {
                    $return = 'Please confirm your email address.';
                 
                }
                if ($user["User"]["status"] == 0 && $user["User"]["token"] == NULL) {
                    $return = 'You are blocked by admin, we will contact you soon.';
                   
                }
            } else if (!empty($user)) {
                if (isset($this->data['User']['rememberme'])) {
                    $time = 3600 * 24 * 30;
                    $this->Cookie->write('UsernameCookie', $this->data['User']['username'], false, $time);
                    $this->Cookie->write('PasswordCookie', $this->data['User']['password'], false, $time);
                } else {
                    $this->Cookie->delete('UsernameCookie');
                    $this->Cookie->delete('PasswordCookie');
                }

                $user['User']['Role'] = $user['Role'];
                if ($this->Auth->login($user['User'])) {
                    $this->loadModel("CommonLogin");
                    $old_data = $this->CommonLogin->findByIp($_SERVER["REMOTE_ADDR"]);
                    if ($old_data) {
                        $this->CommonLogin->delete($old_data["CommonLogin"]["id"]);
                    }
                    $this->loadModel("CommonLogin");
                    $data["CommonLogin"]["id"] = "";
                    $data["CommonLogin"]["ip"] = $_SERVER["REMOTE_ADDR"];
                    $data["CommonLogin"]["user_id"] = $user["User"]["id"];
                    $this->CommonLogin->save($data);

                    // save users additional information on informatoion table
                    $this->saveInfo();
                    $return = 1;
                } else {
                    $return = "Unable to login";
                   
                }
                if (isset($this->data['User']['red'])) {
                    $log_red = explode("/", $this->data['User']['red']);

                    $this->redirect(array('controller' => $log_red[0], 'action' => $log_red[1]));
                } else {
                  
                    $return = 1;
                }
            } else {
                $return = 'Please check credentials.';
            }
        }
        return $return;
    }

    /** @Created:    10-Nov-2014
     * @Method :     checkEmail
     * @Author:      Prateek Jadhav
     * @Modified :   ---
     * @Purpose:     Checking Email
     * @Param:       none
     * @Return:      none
     */
    public function checkEmail($email = NULL) {
        $this->layout = FALSE;
        $this->autoRender = FALSE;
        $return = "";
        if (!empty($email)) {
            $check = $this->User->findByEmail($email);
            if (!empty($check)) {
                $return = "$email already exist.";
            } else {
                $return = 1;
            }
        } else {
            $return = "Email is required";
        }
        return $return;
    }
    
    /** @Created:    17-Dec-2014
     * @Method :     checkUserName
     * @Author:      Arjun Dev
     * @Modified :   ---
     * @Purpose:     Checking Username
     * @Param:       none
     * @Return:      none
     */
    public function checkUserName($user = NULL) {
        $this->layout = FALSE;
        $this->autoRender = FALSE;
        $return = "";
        if (!empty($user)) {
            $check = $this->User->findByUsername($user);
            if (!empty($check)) {
                $return = "$user already exist.";
            } else {
                $return = 1;
            }
        } else {
            $return = "Username is required";
        }
        return $return;
    }

    /** @Created:    5-may-2014
     * @Method :     dashboard
     * @Author:      Prateek Jadhav
     * @Modified :   ---
     * @Purpose:     Users dashboard
     * @Param:       none
     * @Return:      none
     */
    public function dashboard() {
        $this->layout = 'front/home';
        $this->set('title_for_layout', 'ALIST Hub :: Dashboard');
        $this->loadModel("Campaign");
        $campaigns = $this->Campaign->find("list", array("conditions" => array("Campaign.user_id" => AuthComponent::user("id"), "Campaign.title !=" => ""), "fields" => array("Campaign.id", "Campaign.title")));
        $this->set("campaigns", $campaigns);
        $this->loadModel("MyList");
        $MyLists = $this->MyList->find("list", array("conditions" => array("MyList.user_id" => AuthComponent::user("id")), "fields" => array("MyList.id", "MyList.list_name")));
        $this->set("MyLists", $MyLists);
    }

    /** @Created:    5-may-2014
     * @Method :     logout
     * @Author:      Prateek Jadhav
     * @Modified :   ---
     * @Purpose:     Users dashboard
     * @Param:       none
     * @Return:      none
     */
    function logout() {
        $this->loadModel("CommonLogin");
        $detail = $this->CommonLogin->findByIp($_SERVER["REMOTE_ADDR"]); //pr($_SERVER["REMOTE_ADDR"]);die;
        if ($detail) {
            $this->CommonLogin->delete($detail["CommonLogin"]["id"]);
        }
      
        session_destroy();
        $this->redirect(array('controller' => 'Users', 'action' => 'index'));
    }

    /** @Created:    5-may-2014
     * @Method :     view_profile
     * @Author:      Prateek Jadhav
     * @Modified :   ---
     * @Purpose:     Users dashboard
     * @Param:       none
     * @Return:      none
     */
    public function viewProfile() {

        $this->layout = "front/home";
      
        #Start :: Get Package
        $this->loadModel('Payment');
        $this->loadModel('Package');
        $this->loadModel('Category');
        $this->loadModel('Vibe');
        $this->loadModel('Region');
        $this->loadModel("BuyPoint");
        $this->loadModel("Point");
        $this->set("buy_points", $this->BuyPoint->find("list", array("conditions" => array("BuyPoint.status" => 1), "fields" => array("BuyPoint.id", "BuyPoint.no_of_point"), "order" => "BuyPoint.price ASC")));

        $user = $this->User->find('first', array('recursive' => 2, 'conditions' => array('User.id' => AuthComponent::User("id"))));

        #End :: Get Package

        if ($this->data) {

            $tmp = $this->data;

            # now save region

            $UserpRegion = $tmp["UserpRegion"]["region_id"];
         
            $this->loadModel("UserpRegion");
            $this->UserpRegion->deleteAll(array('UserpRegion.user_id' => AuthComponent::User("id")), false);
            foreach ($UserpRegion as $re) {


                if ($re != 0) {
                    $save_re["UserpRegion"]["id"] = "";
                    $save_re["UserpRegion"]["user_id"] = AuthComponent::User("id");
                    $save_re["UserpRegion"]["region_id"] = $re;
                    $this->UserpRegion->save($save_re);
                }
            }

            # now save category

            $UserpCategory = $tmp["UserpCategory"]["category_id"];
          
            $this->loadModel("UserpCategory");
            $this->UserpCategory->deleteAll(array('UserpCategory.user_id' => AuthComponent::User("id")), false);
            foreach ($UserpCategory as $tc) {


                if ($tc != 0) {
                    $save_tc["UserpCategory"]["id"] = "";
                    $save_tc["UserpCategory"]["user_id"] = AuthComponent::User("id");
                    $save_tc["UserpCategory"]["category_id"] = $tc;
                    $this->UserpCategory->save($save_tc);
                }
            }

            # now save vibes
            $UserpVibe = $tmp["UserpVibe"]["vibe_id"];
            $this->loadModel("UserpVibe");
            $this->UserpVibe->deleteAll(array('UserpVibe.user_id' => AuthComponent::User("id")), false);
            foreach ($UserpVibe as $ev) {

                if ($ev != 0) {
                    $save_ev["UserpVibe"]["id"] = "";
                    $save_ev["UserpVibe"]["user_id"] = AuthComponent::User("id");
                    $save_ev["UserpVibe"]["vibe_id"] = $ev;
                    $this->UserpVibe->save($save_ev);
                  
                }
            }

            $this->Session->setflash("All changes saved successfully.", "default", array("class" => "green"));
            
        }

        #Fetch Categories
        $categories = $this->Category->find('all', array('recursive' => -1, 'conditions' => array('Category.status' => '1')));
        
        #Fetch Vibes
        $vibes = $this->Vibe->find('all', array('recursive' => -1, 'conditions' => array('Vibe.status' => '1')));

        #Fetch Regions
        $regions = $this->Region->find('all', array('recursive' => -1, 'conditions' => array('Region.status' => '1')));

        $point_detail = $this->Point->findById('1');

        if (!empty($point_detail)) {
            $price = $point_detail["Point"]["price"];
            $point_price = $price * $user['User']['ALH_point'];
            $this->set("point_price", $point_price);
        }

        $this->set(compact('categories', 'vibes', 'regions'));
        $this->set('image', $user['User']['profile_image']);
        $this->set("user", $user);
    }

    /** @Created:    5-may-2014
     * @Method :     change_password
     * @Author:      Prateek Jadhav
     * @Modified :   ---
     * @Purpose:     Users change password
     * @Param:       none
     * @Return:      none
     */
    public function changePassword() {
        $this->layout = "front/home";
        if ($this->data) {
            $old_password = AuthComponent::password($this->data["User"]["old_password"]);
            $new_password = $this->data["User"]["new_password"];
            $re_password = $this->data["User"]["re_password"];
            $user_info = $this->User->findById(AuthComponent::User("id"));
            $password = $user_info["User"]["password"];
            
            if ($old_password != $password) {
                $this->Session->setFlash("Old password does not match", "default", array("class" => "red"));
                $this->redirect(array("controller" => "users", "action" => "changePassword"));
          
             } else if ($new_password != $re_password) {
                $this->Session->setFlash("New Password and Confirm Password does not match", "default", array("class" => "red"));
                $this->redirect(array("controller" => "users", "action" => "changePassword"));
             } else {
                $user["User"]["id"] = AuthComponent::User("id");
                $user["User"]["password"] = $new_password;
                if ($this->User->save($user)) {
                    $this->Session->setFlash("Password changed successfuly", "default", array("class" => "green"));
                    $this->redirect(array("controller" => "users", "action" => "viewProfile"));
                } else {
                    $this->Session->setFlash("Unable to change password, please try again", "default", array("class" => "red"));
                }
            }
        }
    }

    /** @Created:    5-may-2014
     * @Method :     forgot_password
     * @Author:      Prateek Jadhav
     * @Modified :   ---
     * @Purpose:     Users forgot password
     * @Param:       none
     * @Return:      none
     */
    public function forgot_password() {
        $this->layout = "ajax";
        $this->autoRender = false;
        Configure::write('debug', 0);
        if ($this->data) {

            $user = $this->User->findByEmail($this->data["email"]);
            if ($user) {

                $this->loadModel("EmailTemplate");
                $token = time();
                $url = BASE_URL . "/users/reset/" . $token;
                $activation = '<a style="background-color: #6A6A6A;border-radius: 2px 2px 2px 2px;color: #FFFFFF;display: inline-block;font-size: 14px;font-weight: bold;padding: 10px 19px;text-decoration: none;" target="_blank" href="' . $url . '">CLICK HERE TO RESET PASSWORD</a>';
                $emailTemp = $this->EmailTemplate->find('first', array('conditions' => array('EmailTemplate.alias' => "forgot-password")));
                $emailContent = utf8_decode($emailTemp['EmailTemplate']['description']);
                $data = str_replace(array('{USER_NAME}', '{FORGOT_PASSWORD}', '{url}'), array($user['User']['username'], $activation, $url), $emailContent);
                $this->set('mailData', $data);
                $this->Email->to = $this->data['email'];
                $this->Email->subject = $emailTemp['EmailTemplate']['subject'];
                $this->Email->from = "alisthub@admin.com";
                $this->Email->template = "forgot";
                $this->Email->sendAs = 'html';
                if ($this->Email->send($data)) {
                    $user["User"]["token"] = $token;
                    $this->User->save($user);
                    echo "Success";
                    exit;
                }
            } else {
                echo "Not Success";
                exit;
            }
        }
    }

    /** @Created:    5-may-2014
     * @Method :     reset
     * @Author:      Prateek Jadhav
     * @Modified :   ---
     * @Purpose:     Users reset password
     * @Param:       none
     * @Return:      none
     */
    public function reset($token) {
        $this->layout = "front/home";
        if ($token) {

            $user = $this->User->findByToken($token);

            if ($user) {
                $this->set('token', $token);
                if ($this->data) {

                    if ($this->data["User"]["new_password"] == $this->data["User"]["re_password"]) {
                        $user["User"]["password"] = $this->data["User"]["new_password"];
                        $user["User"]["token"] = '';
                        if ($this->User->save($user)) {
                            //$this->Session->setFlash("Password has been changed", "default", array("class" => "green"));
                            $this->redirect(array("controller" => "users", "action" => "login?succ=".base64_encode("Password has been changed.")));
                        } else {
                            //$this->Session->setFlash("Error occured while saving, try again", "default", array("class" => "red"));
                            $this->redirect(array("controller" => "users", "action" => "login?error=".base64_encode("Error occured while saving, try again.")));
                        }
                    } else {
                        $this->request->data["User"]["new_password"] = "";
                        $this->request->data["User"]["re_password"] = "";
                        $this->Session->setFlash("New Password and Confirm Password does not match", "default", array("class" => "red"));

                    }
                }
            } else {
                 $this->redirect(array("controller" => "users", "action" => "login?error=".base64_encode("Invalid / Expired security token.")));
            }
        } else {
             $this->redirect(array("controller" => "users", "action" => "login?error=".base64_encode("Security token missing.")));
        }
    }

    /** @Created:    5-may-2014
     * @Method :     fb_connect
     * @Author:      Arjun Dev
     * @Modified :   ---
     * @Purpose:     Fb Login
     * @Param:       none
     * @Return:      none
     */
    public function fb_connect() {
        $this->layout = false;
        $this->autoRender = false;
        if ($_POST && !isset($_POST["data"]["User"]["error"])) {

            $userdetail = $this->User->find("first", array("conditions" => array("OR" => array("User.fbID" => $_POST["data"]["User"]["id"], 'User.email' => $_POST["data"]["User"]["email"]))));
            if (!empty($userdetail)) {
                $user_id = $userdetail["User"]["id"];

                $user = $userdetail;
                $this->User->updateAll(array('User.fb_connect' => 1), array('User.id' => $user_id));
            } else {
                if (isset($_POST["data"]["User"]["id"]))
                    $user["User"]["fbID"] = $_POST["data"]["User"]["id"];
                if (isset($_POST["data"]["User"]["email"]))
                    $user["User"]["email"] = $_POST["data"]["User"]["email"];
                if (isset($_POST["data"]["User"]["first_name"]))
                    $user["User"]["first_name"] = $_POST["data"]["User"]["first_name"];
                if (isset($_POST["data"]["User"]["last_name"]))
                    $user["User"]["last_name"] = $_POST["data"]["User"]["last_name"];
                if (isset($_POST["data"]["User"]["name"]))
                    $user["User"]["username"] = $_POST["data"]["User"]["name"];
                $password = uniqid();
                $user["User"]["password"] = $password;
                $user["User"]["status"] = 1;
                $user["User"]["fb_connect"] = 1;
                if (isset($_POST["data"]["User"]["access"]))
                    $user["User"]["access_token"] = $_POST["data"]["User"]["access"];
                if ($this->User->save($user['User'])) {
                    $user_id = $this->User->getLastInsertID();
                    $user["User"]["id"] = $user_id;
                    $this->loadModel("EmailTemplate");
                    $emailTemp = $this->EmailTemplate->find('first', array('conditions' => array('EmailTemplate.alias' => "welcome_email_facebook")));
                    $emailContent = utf8_decode($emailTemp['EmailTemplate']['description']);
                    $url = "http://" . $_SERVER["HTTP_HOST"];
                    $data = str_replace(array('{USER_NAME}', '{PASSWORD}', '{URL}'), array($user["User"]["username"], $password, $url), $emailContent);
                    $this->set('mailData', $data);
                    $this->Email->to = $user["User"]["email"];
                    $this->Email->subject = $emailTemp['EmailTemplate']['subject'];
                    $this->Email->from = "aisthub@admin.com";
                    $this->Email->template = "forgot";
                    $this->Email->sendAs = 'html';
                    if ($this->Email->send($data)) {
                        
                    }
                }
            }
            $this->Auth->authenticate = array('Form' => array('fields' => array('username' => 'id', 'password' => false)));
            if ($this->Auth->login($user_id)) {
                $this->Auth->Session->write('Auth', $user);
                $this->saveInfo();

                $this->loadModel("CommonLogin");
                $data1["CommonLogin"]["id"] = "";
                $data1["CommonLogin"]["ip"] = $_SERVER["REMOTE_ADDR"];
                $data1["CommonLogin"]["user_id"] = $user["User"]["id"];
                $this->CommonLogin->save($data1);
                
               
                return 1;
            } else {
                return 0;
            }
        } else {
            return 0;
        }
    }

    /** @Created:   20-May-2014
     * @Method :     changeImage
     * @Author:      Sachin Thakur
     * @Modified :   ---
     * @Purpose:     Change Picture
     * @Param:       none
     * @Return:      none
     */
    public function changeImage() {
        $this->layout = "ajax";
        $this->autoRender = false;
        $id = $this->Auth->User('id');
        $UserData = $this->User->find('first', array('conditions' => array('User.id' => $id), 'fields' => array('id', 'profile_image')));
      
   
        if (!empty($this->data['User']['image_name']["name"])) {
            $imgNameExt = pathinfo($this->data['User']['image_name']["name"]);
            $ext = $imgNameExt['extension'];
            $ext = strtolower($ext);
            if ($ext == 'jpg' || $ext == 'jpeg' || $ext == 'png' || $ext == 'gif') {
                
                $imgSize = floor($this->data['User']['image_name']['size']/1000);
                
                if($imgSize<=2000)
                {
                $newImgName = 'profile' . time();
                $tempFile = $this->data['User']['image_name']['tmp_name'];
                $destThumb = realpath('../webroot/img/ProfileImage/') . '/';
                $file = $this->data['User']['image_name'];

                $file['name'] = $imgNameExt['filename'] . '.' . $ext;
                if ($ext == 'gif') {
                    $result = $this->Upload->upload($file, $destThumb, $newImgName . ".gif", array('type' => 'resizemin', 'size' => array('200', '200')));
                    $name = $newImgName . ".gif";
                } else if ($ext == 'png') {

                    $result = $this->Upload->upload($file, $destThumb, $newImgName . ".png", array('type' => 'resizemin', 'size' => array('200', '200')));

                    $name = $newImgName . ".png";
                } else if ($ext == 'jpeg') {
                    $result = $this->Upload->upload($file, $destThumb, $newImgName . ".jpeg", array('type' => 'resizemin', 'size' => array('200', '200')));
                    $name = $newImgName . ".jpeg";
                } else {
                    $result = $this->Upload->upload($file, $destThumb, $newImgName . ".jpg", array('type' => 'resizemin', 'size' => array('200', '200')));
                    $name = $newImgName . ".jpg";
                }
                $name = "http://" . $_SERVER["HTTP_HOST"] . "/img/ProfileImage/" . $name;
                $tmp["User"]["image_name"] = $name;
                $this->User->updateAll(array('User.profile_image' => "'" . $name . "'"), array('User.id' => $id));
                $msg['success'] = "Updated";
                $msg['name'] = $name;
                return json_encode($msg);
                }
                else
                {
                    $msg['success'] = "MaxSizeReached";                
                    return json_encode($msg);
                }
            }
             else
            {
                $msg['success'] = "Failed";                
                return json_encode($msg);
            }
        }
    }

    /** @Created:   26-May-2014
     * @Method :     BillingInfo
     * @Author:      Sachin Thakur
     * @Modified :   ---
     * @Purpose:     Save the Payment Card etc
     * @Param:       none
     * @Return:      none
     */
    public function BillingInfo($new = NULL, $id = NULL) {
        $this->set('title_for_layout', 'ALIST Hub :: Billing Info');
        $this->layout = "front/home";
        $this->loadModel('BillingInfo');
        $this->loadModel('Zip');
        if ($new) {
            $this->set("new", $new);
        }
        $this->set("zip", $this->Zip->find("list", array("group" => array("Zip.state"), "fields" => array("Zip.state_name", "Zip.state_name"))));
        #Fetch Card Details
        $cardData = $this->BillingInfo->find('all', array('conditions' => array('BillingInfo.user_id' => $this->Auth->User('id'))));
        $this->set('cardData', $cardData);
        if ($this->data) {

            $info = $this->data;
          
            $info['BillingInfo']['user_id'] = $this->Auth->User('id');
            $this->BillingInfo->save($info['BillingInfo']);
            $this->Session->setFlash("Saved Successfully", "default", array("class" => "green"));
            $this->redirect(array("controller" => "Users", "action" => "BillingInfo"));
        }
        if ($id) {
            $id = base64_decode($id);
            $this->request->data = $this->BillingInfo->findById($id);
        }
    }

    /* @Created:     2-Jun-2014
     * @Method :     getFromWordpress
     * @Author:      Arjun Dev
     * @Modified :   ---
     * @Purpose:     Save and login the user coming from wordpress site
     * @Param:       json string in $_GET
     * @Return:      none
     */

    public function getFromWordpress() {
      
        $this->layout = false;
        $this->autoRender = false;
        $data = json_decode($_GET["wpuser"], true);
        $check = $this->User->findByEmail($data["email"]);
        if (empty($check)) {
            $userdata["User"]["id"] = "";
            $userdata["User"]["email"] = $data["email"];
            $userdata["User"]["username"] = $data["username"];
            $userdata["User"]["first_name"] = $data["fname"];
            $userdata["User"]["last_name"] = $data["lname"];
            $userdata["User"]["password"] = $data["email"];
            $userdata["User"]["role_id"] = 2;
            if (isset($data["key"]) && !empty($data["key"])) {
              
                $this->Session->write("Auth", $this->User->findByKey($data["key"]));
               
            } else {
                $this->User->save($userdata);
                $this->Session->write("Auth", $this->User->findByEmail($data["email"]));
            }
        }
        $redirect = array("controller" => "Events", "action" => "createEvent");
        $this->redirect($redirect);
        
    }

    /** @Created:   10-June-2014
     * @Method :     thumbnail
     * @Author:      Sachin Thakur
     * @Modified :   ---
     * @Purpose:     Show image without destorted
     * @Param:       none
     * @Return:      none
     */
    public function thumbnail() {
        ob_clean();
        $this->layout = false;
        $sImagePath = $_GET["file"];

        $iThumbnailWidth = (int) $_GET['width'];
        $iThumbnailHeight = (int) $_GET['height'];

        $iMaxWidth = (int) $_GET["maxw"];
        $iMaxHeight = (int) $_GET["maxh"];

        if ($iMaxWidth && $iMaxHeight)
            $sType = 'scale';
        else
        if ($iThumbnailWidth && $iThumbnailHeight)
            $sType = 'exact';

        $img = NULL;

        $sExtension = pathinfo($sImagePath, PATHINFO_EXTENSION); //(explode('.', $sImagePath));
        $path = realpath('../../app/webroot') . (substr($sImagePath, strpos($sImagePath, '/', 10)));

        $file = escapeshellarg($path);
        $mime = shell_exec("file -bi " . $file);
        $mime = explode(' ', $mime);

        if ($sExtension == 'jpg' || $sExtension == 'jpeg' || $mime[0] == 'image/jpeg;') {
            $img = @imagecreatefromjpeg($sImagePath) or die("Cannot create new JPEG image");
        } else if ($sExtension == 'png') {
            $img = @imagecreatefrompng($path) or die("Cannot create new PNG image");
        } else if ($sExtension == 'gif') {

            $img = @imagecreatefromgif($sImagePath) or die("Cannot create new GIF image");
        }
        if ($img) {
            $iOrigWidth = imagesx($img);
            $iOrigHeight = imagesy($img);

            if ($sType == 'scale') {

                $fScale = min($iMaxWidth / $iOrigWidth, $iMaxHeight / $iOrigHeight);
                if ($fScale < 1) {
                    $iNewWidth = floor($fScale * $iOrigWidth);
                    $iNewHeight = floor($fScale * $iOrigHeight);
                    $tmpimg = imagecreatetruecolor($iNewWidth, $iNewHeight);
                    imagecopyresampled($tmpimg, $img, 0, 0, 0, 0, $iNewWidth, $iNewHeight, $iOrigWidth, $iOrigHeight);
                    imagedestroy($img);
                    $img = $tmpimg;
                }
            } else if ($sType == "exact") {
                $fScale = max($iThumbnailWidth / $iOrigWidth, $iThumbnailHeight / $iOrigHeight);
                if ($fScale < 1) {
                    $iNewWidth = floor($fScale * $iOrigWidth);
                    $iNewHeight = floor($fScale * $iOrigHeight);
                    $tmpimg = imagecreatetruecolor($iNewWidth, $iNewHeight);
                    $tmp2img = imagecreatetruecolor($iThumbnailWidth, $iThumbnailHeight);
                    imagecopyresampled($tmpimg, $img, 0, 0, 0, 0, $iNewWidth, $iNewHeight, $iOrigWidth, $iOrigHeight);
                    if ($iNewWidth == $iThumbnailWidth) {

                        $yAxis = ($iNewHeight / 2) - ( $iThumbnailHeight / 2);
                        $xAxis = 0;
                    } else if ($iNewHeight == $iThumbnailHeight) {

                        $yAxis = 0;
                        $xAxis = ($iNewWidth / 2) -
                                ($iThumbnailWidth / 2);
                    }
                    imagecopyresampled($tmp2img, $tmpimg, 0, 0, $xAxis, $yAxis, $iThumbnailWidth, $iThumbnailHeight, $iThumbnailWidth, $iThumbnailHeight);
                    imagedestroy($img);
                    imagedestroy($tmpimg);
                    $img = $tmp2img;
                }
            }
            header("Content-type: image/jpeg");
            imagejpeg($img);
        }
        exit();
    }

    /* @Created:     30-June-2014
     * @Method :     function testLogin
     * @Author:      Prateek Jadhav & Sachin Thakur
     * @Modified :   ---
     * @Purpose:     for login purpose if already logged in ALE to ALH or ALH to ALE
     * @Param:       none
     * @Return:      none
     */

    function testLogin() {
        $id = AuthComponent::user("id");
        $this->loadModel("CommonLogin");
        $detail = $this->CommonLogin->findByIp($_SERVER["REMOTE_ADDR"]);
        if (!AuthComponent::user("id")) {
            session_destroy();
            session_start();

            if (!empty($detail)) {
                $_SESSION["Auth"] = $this->User->findById($detail["CommonLogin"]["user_id"]);
            }
        } else {
            if (empty($detail)) {
               
                session_destroy();
              
            } else if ($detail["CommonLogin"]["user_id"] != AuthComponent::user("id")) {
                session_destroy();
                session_start();
                $_SESSION["Auth"] = $this->User->findById($detail["CommonLogin"]["user_id"]);
            }
        }
    }

    /* @Created:     2-July-2014
     * @Method :     function myAddress
     * @Author:      Prateek Jadhav
     * @Modified :   ---
     * @Purpose:     List and add/edit my saved address
     * @Param:       $id/NULL
     * @Return:      none
     */

    public function myAddress($id = NULL) {
        $this->layout = "front/home";
        $this->loadModel("Address");
        $this->loadModel('Zip');

        $this->set("zip", $this->Zip->find("list", array("group" => array("Zip.state"), "fields" => array("Zip.state", "Zip.state_name"))));

        $conditions = array("Address.user_id" => AuthComponent::user("id"));
        $this->paginate = array('conditions' => $conditions, 'limit' => 10, 'order' => array('Address.name' => 'ASC'));
        $this->set("addresses", $this->paginate("Address"));

        if ($this->data) {
            if ($this->Address->save($this->data)) {
                $this->Session->setFlash("Address saved successfully", "default", array("class" => "green"));
                $this->redirect(array("controller" => "Users", "action" => "myAddress"));
            } else {
                $this->Session->setFlash("Unable to save address, please try again", "default", array("class" => "red"));
            }
        }

        if ($id) {
            $id = base64_decode($id);
            $this->request->data = $this->Address->findById($id);
        }
    }

    /** @Created:    10-jul-2014
     * @Method :     fb_events
     * @Author:      Arjun Dev
     * @Modified :   ---
     * @Purpose:     Users facebook Events
     * @Param:       none
     * @Return:      none
     */
    public function fb_events() {
        $this->layout = false;
        $this->autoRender = false;
        $this->loadModel("Event");
        $this->loadModel("User");
        $this->loadModel("EventDate");
        $this->loadModel("EventImage");
        if (isset($_POST) && !isset($_POST["data"]["events"]["error"])) {
      
            $userdetails = $this->User->find("first", array("conditions" => array("User.fbID" => $_POST["data"]["events"]["uid"], "User.id" => AuthComponent::user("id"))));

            if (!empty($userdetails)) {

                $user_id = $userdetails["User"]["id"];

                $user = $userdetails;


                foreach ($_POST["data"]["events"]["data"] as $post_event) {

                    if (isset($post_event["venue"]["city"])) {
                        $city = $post_event["venue"]["city"];
                    } else {
                        $city = "";
                    }

                    if (isset($post_event["venue"]["state"])) {
                        $state = $post_event["venue"]["state"];
                    } else {
                        $state = "";
                    }

                    if (isset($post_event["venue"]["zip"])) {
                        $zip = $post_event["venue"]["zip"];
                    } else {
                  
                        $url = "https://maps.googleapis.com/maps/api/geocode/json?address=".urlencode($post_event["location"]);
                        $ch = curl_init();
                        curl_setopt($ch, CURLOPT_URL, $url);
                        curl_setopt($ch, CURLOPT_HTTPGET, true);
                        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json','Accept: application/json'));
        
        
                        $result = curl_exec($ch);
                
                        curl_close($ch);
        
                        $response = json_decode($result); 
                      
                        if(isset($response->results[0]->address_components) && !empty($response->results[0]->address_components))
                        {
                        foreach($response->results[0]->address_components as $address_component)
                        {
                           if(isset($address_component->types[0]) && $address_component->types[0]=="postal_code")
                           {
                             $zip = $address_component->long_name;
                           }
                        }
                        }
                    else {
                        $zip = "";
                    }
                    }

                    if (isset($post_event["venue"]["street"])) {
                        $street = $post_event["venue"]["street"];
                    } else {
                        $street = "";
                    }

                    if (isset($post_event["venue"]["latitude"])) {
                        $latitude = $post_event["venue"]["latitude"];
                    } else {
                        $latitude = "";
                    }

                    if (isset($post_event["venue"]["longitude"])) {
                        $longitude = $post_event["venue"]["longitude"];
                    } else {
                        $longitude = "";
                    }
                    if (isset($post_event["owner"]["id"])) {
                        $ownr_id = $post_event["owner"]["id"];
                    } else {
                        $ownr_id = "";
                    }


                    if (isset($post_event["cover"])) {
                        $img = $post_event["cover"]["source"];
                    } else {
                        $img = "/fb_detail.png";
                    }

                    $stimeExp = explode("T", $post_event["start_time"]);
                    if ($stimeExp[1]) {
                        $stimeExpN = explode("-", $stimeExp[1]);

                        $startTime = date('g:ia', strtotime($stimeExpN[0]));
                    } else {
                        $startTime = '';
                    }
                    if (isset($post_event["end_time"])) {
                        $etimeExp = explode("T", $post_event["end_time"]);
                        if ($etimeExp[1]) {
                            $etimeExpN = explode("-", $etimeExp[1]);

                            $endTime = date('g:ia', strtotime($etimeExpN[0]));
                        } else {
                            $endTime = '';
                        }
                    } else {
                        $endTime = '';
                    }

                    $eventetails = $this->Event->find("first", array("conditions" => array("Event.fbevent_id" => $post_event["id"])));

                    if (empty($eventetails['Event'])) {

                        $event["Event"]["id"] = "";
                        $event["Event"]["user_id"] = $user_id;
                        $event["Event"]["title"] = $post_event["name"];
                        $event["Event"]["image_name"] = $img;
                        $event["Event"]["event_type"] = '1';
                        $event["Event"]["option_to_show"] = '3';
                        $event["Event"]["start_date"] = $post_event["start_time"];
                        $event["Event"]["event_location"] = $post_event["location"];
                        $event["Event"]["city"] = $city;
                        $event["Event"]["event_address"] = $post_event["location"];
                        $event["Event"]["cant_find_address"] = $street;
                        $event["Event"]["cant_find_city"] = $city;
                        $event["Event"]["cant_find_state"] = $state;
                        $event["Event"]["cant_find_zip_code"] = $zip;
                        $event["Event"]["lat"] = $latitude;
                        $event["Event"]["lng"] = $longitude;
                        $event["Event"]["ticket_vendor_url"] = "https://www.facebook.com/events/" . $post_event["id"];
                        $event["Event"]["website_url"] = "https://www.facebook.com/events/" . $post_event["id"];
                        $event["Event"]["facebook_url"] = "https://www.facebook.com/events/" . $post_event["id"];
                        $event["Event"]["flyer_image"] = $img;
                        $event["Event"]["main_info_name"] = $userdetails["User"]["first_name"] . ' ' . $userdetails["User"]["last_name"];
                        $event["Event"]["main_info_email"] = $userdetails["User"]["email"];
                        $event["Event"]["main_info_phone_no"] = $userdetails["User"]["phone_no"];
                        $event["Event"]["short_description"] = $post_event["description"];
                        $event["Event"]["description"] = $post_event["description"];
                        $event["Event"]["status"] = '1';
                        $event["Event"]["event_from"] = "facebook";
                        $event["Event"]["fbevent_id"] = $post_event["id"];
                        $event["Event"]["fbevent_ownerid"] = $ownr_id;
                        $this->Event->create();
                        if ($this->Event->save($event)) {
                            $event_id = $this->Event->getLastInsertID();
                            $eventdate["EventDate"]["id"] = "";
                            $eventdate["EventDate"]["event_id"] = $event_id;
                            $eventdate["EventDate"]["date"] = $post_event["start_time"];
                            $eventdate["EventDate"]["start_time"] = $startTime;
                            $eventdate["EventDate"]["end_time"] = $endTime;
                            $this->EventDate->save($eventdate);

                            $save_img["EventImage"]["id"] = "";
                            $save_img["EventImage"]["event_id"] = $event_id;
                            $save_img["EventImage"]["image_name"] = $img;

                            $this->EventImage->save($save_img);
                        }
                    }
                }

                return 1;
            } else {
                return 3;
            }
        } else {
            return 0;
        }
    }

    /** @Created:    15-July-2014
     * @Method :     track
     * @Author:      Prateek Jadhav
     * @Modified :   ---
     * @Purpose:     
     * @Param:       none
     * @Return:      none
     */
    public function track() {
        $this->layout = "front/home";
        $my_id = AuthComponent::user("id");
        $today = date("Y-m-d");
        // now set upcoming events
        $this->loadModel("EventDate");
        $events = $this->EventDate->find("all", array("conditions" => array("Event.user_id" => $my_id, "EventDate.date >=" => $today), "fields" => array("EventDate.*", "Event.id", "Event.title", "Event.event_address", "Event.cant_find_city", "Event.cant_find_state", "Event.cant_find_zip_code")));
        $this->set("events", $events);
       
        // now set upcoming campaigns
        $today = date("Y/m/d");
        $this->loadModel("Campaign");
        $campigns = $this->Campaign->find("all", array("conditions" => array("Campaign.user_id" => $my_id, "Campaign.date_to_send >=" => $today), "fields" => array("Campaign.id", "Campaign.title", "Campaign.from_name", "Campaign.date_to_send", "Campaign.subject_line", "Campaign.from_email")));
        $this->set("campaigns", $campigns);
     
        // now set not fulfill orders
        $this->loadModel("Payment");
        $orders = $this->Payment->find("all", array("conditions" => array("Payment.user_id" => $my_id, "Payment.status" => 0), "fields" => array("Payment.id", "Payment.amount", "Payment.transaction_id", "Payment.created", "Package.name", "User.id")));
        $this->set("orders", $orders);
       
    }

    /** @Created:    17-July-2014
     * @Method :     cart
     * @Author:      Sachin Thakur
     * @Modified :   ---
     * @Purpose:     
     * @Param:       none
     * @Return:      none
     */
    function cart() {
        $this->layout = "front/home";
        $this->set('title_for_layout', 'ALIST Hub :: My Cart');
        $this->loadModel("Cart");
        $userId = $this->Auth->User("id");
        $cartData = $this->Cart->find("all", array("conditions" => array("Cart.user_id" => $userId), 'recursive' => 2));
        $this->set('carts', $cartData);
    }

    /** @Created:    18-July-2014
     * @Method :     removeItem
     * @Author:      Sachin Thakur
     * @Modified :   ---
     * @Purpose:     
     * @Param:       $cartId
     * @Return:      none
     */
    function removeItem($cartId = NULL) {
        $this->layout = "ajax";
        $this->autoRender = false;
        $this->loadModel("Cart");
        if (!empty($cartId)) {
            $this->Cart->deleteAll(array('Cart.id' => $cartId));
            echo 0;
            exit;
        }
    }

    /** @Created:    18-July-2014
     * @Method :     getPackageIds
     * @Author:      Sachin Thakur
     * @Modified :   ---
     * @Purpose:     
     * @Param:       none
     * @Return:      none
     */
    function getPackageIds() {
        $this->layout = "ajax";
        $this->autoRender = false;
        $this->loadModel("Cart");
        $userId = $this->Auth->User('id');
        $cartData = $this->Cart->find("all", array('conditions' => array("Cart.user_id" => $userId), 'fields' => array('Package.id')));
        $data = "";
        foreach ($cartData as $package):
            $data .=$package['Package']['id'] . '-';
        endforeach;
        $ids = rtrim($data, "-");
        echo $ids;
        exit;
    }

    /** @Created:    18-jul-2014
     * @Method :     fbacc_link
     * @Author:      Arjun Dev
     * @Modified :   ---
     * @Purpose:     Linked facebook account with user ALH account
     * @Param:       none
     * @Return:      none
     */
    public function fbacc_link() {
        $this->autoRender = false;
        $this->layout = "ajax";

        $user = $this->User->find('first', array('recursive' => 2, 'conditions' => array('User.id' => AuthComponent::User("id"))));



        if (!empty($_POST['data'])) {
            $user_find = $this->User->find('first', array('recursive' => 2, 'conditions' => array('User.fbID' => $_POST['data']['fbadded']['id'])));

            if (empty($user_find)) {
                if (isset($_POST['data']['fbadded']['access_token'])) {
                    $Token = $_POST['data']['fbadded']['access_token'];
                } else {
                    $Token = '';
                }
                $user["User"]["fbID"] = $_POST['data']['fbadded']['id'];
                $user["User"]["fb_connect"] = "1";
                $user["User"]["access_token"] = $Token;
                $user["User"]["id"] = $_POST['data']['fbadded']['userid'];
                $this->User->save($user);
                return 1;
            } else {
                return 0;
            }
        }
    }

    /** @Created:    18-jul-2014
     * @Method :     fbacc_link_check
     * @Author:      Arjun Dev
     * @Modified :   ---
     * @Purpose:     Linked facebook account with user ALH account
     * @Param:       none
     * @Return:      none
     */
    public function fbacc_check() {
        $this->autoRender = false;
        $this->layout = "ajax";

        $user = $this->User->find('first', array('recursive' => 2, 'conditions' => array('User.id' => AuthComponent::User("id"), 'User.fbID !=' => '')));

        if (empty($user)) {

            if (!empty($_POST['data'])) {
                $user_find = $this->User->find('first', array('recursive' => 2, 'conditions' => array('User.fbID' => $_POST['data']['User']['id'])));

                if (empty($user_find)) {
                    $user["User"]["fbID"] = $_POST['data']['User']['id'];
                    $user["User"]["fb_connect"] = "1";
                    $user["User"]["access_token"] = $_POST['data']['User']['access'];
                    $user["User"]["id"] = AuthComponent::User("id");
                    $this->User->save($user);
                    return 2;
                } else {
                    return 1;
                }
            }
        } else {
            if ($user["User"]["fbID"] != $_POST['data']['User']['id']) {
                return 0;
            } else {
                return 3;
            }
        }
    }

    /** @Created:    3-Sep-2014
     * @Method :     admin_point_detail
     * @Author:      Arjun Dev
     * @Modified :   ---
     * @Purpose:     admin point detail
     * @Param:       $mode,$id
     * @Return:      none
     */
    public function admin_pointDetail($mode, $id = NULL) {
        $this->layout = "admin/admin";
        $this->set('title_for_layout', 'ALIST Hub :: Point Detail');
        if ($mode == 'cashout') {
            $this->loadModel("CashOutPoint");
            $cash_point_detail = $this->CashOutPoint->find("first", array("conditions" => array("CashOutPoint.id" => base64_decode($id))));

            $this->set("cashpointdetail", $cash_point_detail);
        } else {
            $this->loadModel('Payment');
            $pay_points = $this->Payment->find("first", array("conditions" => array("Payment.id" => base64_decode($id))));
            $this->set("buypointdetail", $pay_points);
        }
    }

    /** @Created:    5-Sep-2014
     * @Method :     visitBanner
     * @Author:      Prateek Jadhav
     * @Modified :   ---
     * @Purpose:     count banner visited user
     * @Param:       $banner
     * @Return:      1
     */
    public function visitBanner() {
        $this->layout = FALSE;
        $this->autoRender = FALSE;
        $this->loadModel("VisitBanner");
        if (AuthComponent::user("id")) {
            $banner = $_POST["banner"];
            $visitEvent["VisitBanner"]["user_id"] = AuthComponent::user("id");
            $visitEvent["VisitBanner"]["url"] = "http://" . $banner;
            $check_data = $this->VisitBanner->find("first", array("conditions" => array("VisitBanner.user_id" => AuthComponent::user("id"), "VisitBanner.url" => "http://" . $banner)));
            if (!empty($check_data)) {
                $visitEvent["VisitBanner"]["id"] = $check_data["VisitBanner"]["id"];
                $visitEvent["VisitBanner"]["count"] = $check_data["VisitBanner"]["count"] + 1;
            }
            $this->VisitBanner->save($visitEvent);
        }
        return $banner;
    }

    /** @Created:    18-Sep-2014
     * @Method :     vibeSearchCount
     * @Author:      Arjun Dev
     * @Modified :   ---
     * @Purpose:     count vibe searches
     * @Param:       $id
     * @Return:      1
     */
    public function vibeSearchCount() {
        $this->layout = FALSE;
        $this->autoRender = FALSE;
        $this->loadModel("Vibe");
        $vId = $_POST['data']['Vibe'];
        $vibe_data = $this->Vibe->find("first", array("conditions" => array("Vibe.id" => $vId)));
        if (!empty($vibe_data)) {
            $vibe["Vibe"]["id"] = $vibe_data["Vibe"]["id"];
            $vibe["Vibe"]["count"] = $vibe_data["Vibe"]["count"] + 1;
            $this->Vibe->save($vibe);
        }
    }

    /** @Created:    18-Sep-2014
     * @Method :     catSearchCount
     * @Author:      Arjun Dev
     * @Modified :   ---
     * @Purpose:     count Category searches
     * @Param:       $id
     * @Return:      1
     */
    public function catSearchCount() {
        $this->layout = FALSE;
        $this->autoRender = FALSE;
        $this->loadModel("Category");
        $cId = $_POST['data']['Category'];
        $cat_data = $this->Category->find("first", array("conditions" => array("Category.id" => $cId)));
        if (!empty($cat_data)) {
            $cat["Category"]["id"] = $cat_data["Category"]["id"];
            $cat["Category"]["count"] = $cat_data["Category"]["count"] + 1;
            $this->Category->save($cat);
        }
    }

    /** @Created:    25-Sep-2014
     * @Method :     saveprefrence
     * @Author:      Arjun Dev
     * @Modified :   ---
     * @Purpose:     save default prefrence from home page
     * @Param:       
     * @Return:      1
     */
    public function saveprefrence() {
        $this->layout = FALSE;
        $this->autoRender = FALSE;
        $this->loadModel("UserpCategory");

        $UserpCategory = $_POST['data']["Savep"];

        $this->UserpCategory->deleteAll(array('UserpCategory.user_id' => AuthComponent::User("id")), false);
        foreach ($UserpCategory as $tc) {


            if ($tc != 0) {
                $save_tc["UserpCategory"]["id"] = "";
                $save_tc["UserpCategory"]["user_id"] = AuthComponent::User("id");
                $save_tc["UserpCategory"]["category_id"] = $tc;
                $this->UserpCategory->save($save_tc);
            }
        }
    }

    /** @Created:    25-Sep-2014
     * @Method :     savevibprefrence
     * @Author:      Arjun Dev
     * @Modified :   ---
     * @Purpose:     save default prefrence from home page
     * @Param:       
     * @Return:      1
     */
    public function savevibprefrence() {
        $this->layout = FALSE;
        $this->autoRender = FALSE;
        $this->loadModel("UserpVibe");

        # now save vibes
        $UserpVibe = $_POST['data']["Savep"]; #pr($UserpVibe);die;

        $this->UserpVibe->deleteAll(array('UserpVibe.user_id' => AuthComponent::User("id")), false);
        foreach ($UserpVibe as $ev) {

            if ($ev != 0) {
                $save_ev["UserpVibe"]["id"] = "";
                $save_ev["UserpVibe"]["user_id"] = AuthComponent::User("id");
                $save_ev["UserpVibe"]["vibe_id"] = $ev;
                $this->UserpVibe->save($save_ev);
               
            }
        }
    }

     /** @Created:    25-Oct-2014
     * @Method :     campaignReport
     * @Author:      Arjun Dev
     * @Modified :   ---
     * @Purpose:     Campaign report
     * @Param:       
     * @Return:      1
     */
     
    public function campaignReport($campaign_id) {
        $this->layout = FALSE;
        if ($campaign_id) {
            $this->loadModel("CampaignEmail");
            $this->loadModel("Campaign");
            $campaign = array();
            $campaign = $this->Campaign->findById($campaign_id);
            $total_mail = $this->CampaignEmail->find("count", array("conditions" => array("CampaignEmail.campaign_id" => $campaign_id)));
            $sent_mail = $this->CampaignEmail->find("count", array("conditions" => array("CampaignEmail.sent_status" => 1, "CampaignEmail.campaign_id" => $campaign_id)));
            $bounce = $total_mail - $sent_mail;
            $open_mail = $this->CampaignEmail->find("count", array("conditions" => array("CampaignEmail.sent_status" => 1, "CampaignEmail.campaign_id" => $campaign_id, "CampaignEmail.open_status" => 1)));
            $click_mail = $this->CampaignEmail->find("count", array("conditions" => array("CampaignEmail.sent_status" => 1, "CampaignEmail.campaign_id" => $campaign_id, "CampaignEmail.click_status" => 1)));
            $campaign["Campaign"]["total_mail"] = $total_mail;
            $campaign["Campaign"]["sent_mail"] = $sent_mail;
            $campaign["Campaign"]["bounce_mail"] = $bounce;
            $campaign["Campaign"]["open_mail"] = $open_mail;
            $campaign["Campaign"]["click_mail"] = $click_mail;
            $this->set("campaign", $campaign);
        }
    }

     /** @Created:    25-Oct-2014
     * @Method :     viewList
     * @Author:      Arjun Dev
     * @Modified :   ---
     * @Purpose:     List of campaign
     * @Param:       
     * @Return:      1
     */
     
    public function viewList($list_id) {
        $this->layout = FALSE;
        if ($list_id) {
            $this->loadModel("MyList");
            $this->loadModel("CampaignEmail");
            $this->loadModel("ListEmail");
            $lists = $this->ListEmail->find("all", array("conditions" => array("ListEmail.my_list_id" => $list_id),
                "group" => array("DATE_FORMAT(ListEmail.created,'%m')", "ListEmail.from"),
                "fields" => array("count(ListEmail.id) as sum", "DATE_FORMAT(ListEmail.created,'%M')", "ListEmail.created", "ListEmail.from")
                    )
            );
           
            $text = array();
            foreach ($lists as $list) {
                $text[$list[0]["DATE_FORMAT(ListEmail.created,'%M')"]][$list["ListEmail"]["from"]] = $list[0]["sum"];
            }
            $this->set("lists", $text);

            $listdetail = $this->MyList->find("first", array("conditions" => array("MyList.id" => $list_id), "recursive" => -1));
            $list_id = $listdetail["MyList"]["id"];
            $total_mail = $this->ListEmail->find("count", array("conditions" => array("ListEmail.my_list_id" => $list_id)));
            
            $total_over_all = $this->CampaignEmail->find("count", array("conditions" => array("CampaignEmail.list_id" => $list_id)));
            
            $sent_mail = $this->CampaignEmail->find("count", array("conditions" => array("CampaignEmail.sent_status" => 1, "CampaignEmail.list_id" => $list_id)));
            $bounce = $total_over_all - $sent_mail;
            $open_mail = $this->CampaignEmail->find("count", array("conditions" => array("CampaignEmail.sent_status" => 1, "CampaignEmail.list_id" => $list_id, "CampaignEmail.open_status" => 1)));
            $click_mail = $this->CampaignEmail->find("count", array("conditions" => array("CampaignEmail.sent_status" => 1, "CampaignEmail.list_id" => $list_id, "CampaignEmail.click_status" => 1)));
            $last_send = $this->CampaignEmail->find("first", array("conditions" => array("CampaignEmail.list_id" => $list_id), "fields" => array("CampaignEmail.created"), "order" => "CampaignEmail.created DESC"));

            $listdetail["MyList"]["total_over_all"] = $total_over_all;
            $listdetail["MyList"]["total_mail"] = $total_mail;
            $listdetail["MyList"]["sent_mail"] = $sent_mail;
            $listdetail["MyList"]["bounce_mail"] = $bounce;
            $listdetail["MyList"]["open_mail"] = $open_mail;
            $listdetail["MyList"]["click_mail"] = $click_mail;
            if (!empty($last_send)) {
                $listdetail["MyList"]["last_send"] = $last_send["CampaignEmail"]["created"];
            }
            $this->set("listdetail", $listdetail);
        }
    }
    
     /** @Created:    28-Nov-2014
     * @Method :     facebookEventCron
     * @Author:      Arjun Dev
     * @Modified :   ---
     * @Purpose:     Fetch facebook events 
     * @Param:       
     * @Return:      1
     */
    
    public function facebookEventCron($reply = NULL, $id = NULL, $after = NULL) {
       
        $this->layout = FALSE;
        $this->set("reply", $reply);
        $this->set("id", $id);
        $this->set("after", $after);
       
    }

}

?>