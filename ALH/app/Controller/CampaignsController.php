<?php

class CampaignsController extends AppController {

    var $helpers = array('Html', 'Form', 'Cache');
    public $components = array('Cookie', 'Email', 'Upload', 'Mailgun');
    public $paginate = array(
        'limit' => 10
    );

    /** @Created:     07-April-2014
     * @Method :     beforeFilter
     * @Author:      Prateek Jadhav
     * @Modified :   ---
     * @Purpose:     Rendered before any function
     * @Param:       none
     * @Return:      none
     */
    function beforeFilter() {
        parent::beforeFilter();
        $this->Auth->allow("campaignCron");
    }

    function index() {
        $this->layout = "front/home";
        $this->loadModel("CampaignEmail");
        $this->paginate = array("fields" => array("Campaign.id", "Campaign.title", "Campaign.subject_line", "Campaign.created", "Campaign.date_to_send", "Campaign.status", "Campaign.mail_status"), "recursive" => -1, 'conditions' => array("Campaign.user_id" => AuthComponent::user("id")), 'limit' => 10, 'order' => "Campaign.created DESC");
        $campaigns = $this->paginate('Campaign');
        $i = 0;
        foreach ($campaigns as $campaign) {
            $campaign_id = $campaign["Campaign"]["id"];
            $total_mail = $this->CampaignEmail->find("count", array("conditions" => array("CampaignEmail.campaign_id" => $campaign_id)));
            $sent_mail = $this->CampaignEmail->find("count", array("conditions" => array("CampaignEmail.sent_status" => 1, "CampaignEmail.campaign_id" => $campaign_id)));
            $bounce = $total_mail - $sent_mail;
            $open_mail = $this->CampaignEmail->find("count", array("conditions" => array("CampaignEmail.sent_status" => 1, "CampaignEmail.campaign_id" => $campaign_id, "CampaignEmail.open_status" => 1)));
            $click_mail = $this->CampaignEmail->find("count", array("conditions" => array("CampaignEmail.sent_status" => 1, "CampaignEmail.campaign_id" => $campaign_id, "CampaignEmail.click_status" => 1)));

            $campaigns[$i]["Campaign"]["total_mail"] = $total_mail;
            $campaigns[$i]["Campaign"]["sent_mail"] = $sent_mail;
            $campaigns[$i]["Campaign"]["bounce_mail"] = $bounce;
            $campaigns[$i]["Campaign"]["open_mail"] = $open_mail;
            $campaigns[$i]["Campaign"]["click_mail"] = $click_mail;
            $i++;
        }
        //pr($campaigns);die;
        $this->set("campaigns", $campaigns);
    }

    /** @Created:     07-April-2014
     * @Method :     beforeFilter
     * @Author:      Prateek Jadhav
     * @Modified :   ---
     * @Purpose:     Campaign design step 1
     * @Param:       none
     * @Return:      none
     */
    function campaignDesign() {
        $this->layout = "front/home";
        $this->Session->delete("CampaignType");
        $this->Session->delete("CampaignEvent");
    }

    /** @Created:     07-April-2014
     * @Method :     campaignDesignStep2
     * @Author:      Prateek Jadhav
     * @Modified :   ---
     * @Purpose:     Campaign design step 2
     * @Param:       $campaign_id
     * @Return:      none
     */
    function campaignDesignStep2($campaign_id = NULL) {
        $this->layout = "front/home";
        $this->set('title_for_layout', 'ALIST Email :: Campaign Design step 2');
        //$this->Session->write("campaignType", $type);
        // set the event template content here
        $this->loadModel("EventTemplate");
        $event_template = $this->EventTemplate->find("all", array("conditions" => array("EventTemplate.status" => 1)));
        $this->set("event_template", $event_template);

        //////////////////////////////////////
        //$this->set("type", $type);
        $this->layout = "front/home";
        $this->loadModel("MyCalendar");
        $this->loadModel("Region");
        $this->loadModel("Category");
        $this->loadModel("Vibe");
        $this->loadModel("Event");
        if (isset($this->data["Event"]["order"])) {
            $order = $this->data["Event"]["order"];
        } else {
            $order = "Event.id ASC";
        }
        $this->Event->unBindModel(array('hasMany' => array("EventCategory", "EventVibe"), 'belongsTo' => array('User')), false);

        $this->Event->bindModel(array('hasOne' => array("EventCategory", "EventVibe")), false);
        //   pr($this->Event->find('all'));die;

        $regions = $this->Region->find("all", array("conditions" => array(), "recursive" => -1));
        $categories = $this->Category->find("all", array("conditions" => array(), "recursive" => -1));
        $vibes = $this->Vibe->find("all", array("conditions" => array(), "recursive" => -1));
        $this->set(compact('regions', 'categories', 'vibes'));
        #lets find out which event added for campaign
        if (!$this->Session->check("campaignEvent")) {
            $this->Session->write("campaignEvent", array());
        }
        $campaignEvent = $this->Session->read("campaignEvent");
        $this->set("campaignEvent", $campaignEvent);

        //pr($this->Event->find("all"));die;
        /*  if (!isset($this->data["Event"]["distance"])) {
          $this->request->data["Event"]["distance"] = 30;
          } */
        if (empty($this->data["Event"]["distance"])) {
            $this->set("distance", "");
        }
        if (!AuthComponent::User("id")) {
            $this->set("distance", "");
            $this->request->data["Event"]["distance"] = "";
        }
        $conditions = array();
        if (!isset($this->params['named']['page']) && !isset($this->params['named']['sort'])) {
            if ($this->Session->read('conditions')) {
                $this->Session->delete('conditions');
            }
        }
        $conditions = array_merge($conditions, array("Event.option_to_show" => array(1, 3)));
        if ($this->data) {
            if (isset($this->data['EventVibe']['id']) && $this->data['EventVibe']['id'] != "") {
                $arrayVibe = array();
                foreach ($this->data['EventVibe']['id'] as $key => $value):
                    $arrayVibe[] = $key;
                endforeach;


                $VibesSearch = $this->Vibe->find("all", array("conditions" => array('Vibe.id' => $arrayVibe), "recursive" => -1));

                foreach ($VibesSearch as $vibeSearch):
                    $vibeSearchArray[] = $vibeSearch['Vibe']['name'];
                endforeach;
                $vibesS = substr(implode(', ', $vibeSearchArray), 0, 50) . '...';
                $this->set("vibesS", $vibesS);

                if (isset($this->data['EventVibe']['id']) && !empty($this->data['EventVibe']['id'])) {
                    $conditions = array_merge($conditions, array('EventVibe.vibe_id' => $arrayVibe));
                }
            }
            if (isset($this->data['EventCategory']['id']) && $this->data['EventCategory']['id'] != "") {
                $arrayCategory = array();
                foreach ($this->data['EventCategory']['id'] as $key => $value):
                    $arrayCategory[] = $key;
                endforeach;

                $categoriesSearch = $this->Category->find("all", array("conditions" => array('Category.id' => $arrayCategory), "recursive" => -1));

                foreach ($categoriesSearch as $catSearch):
                    $catSearchArray[] = $catSearch['Category']['name'];
                endforeach;
                $categoriesS = substr(implode(', ', $catSearchArray), 0, 50) . '...';
                $this->set("categoriesS", $categoriesS);

                if (isset($this->data['EventCategory']['id']) && !empty($this->data['EventCategory']['id'])) {
                    $conditions = array_merge($conditions, array('EventCategory.category_id' => $arrayCategory));
                }
            }
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

            if (isset($this->data["Event"]["distance"]) && !empty($this->data["Event"]["distance"])) {

                $distance = $this->data["Event"]["distance"];

                $this->set("distance", $distance);
                $this->loadModel("Zip");
                if (isset($this->data["Event"]["zip"]) && !empty($this->data["Event"]["zip"])) {
                    $change_zip = $this->data["Event"]["zip"];
                    $zip_detail = $this->Zip->findByZip($change_zip);
                    if (!empty($zip_detail)) {//pr($zip_detail);
                        $lat = $zip_detail["Zip"]["lat"];
                        $lng = $zip_detail["Zip"]["lng"];
                        $find_zip = $this->findzip($distance, $lat, $lng); //pr($find_zip);
                        $find_zip[$zip_detail["Zip"]["id"]] = $zip_detail["Zip"]["zip"];
                    } else {
                        $this->Session->setFlash("Zip code you entered seems to be wrong, please check", "default", array("class" => "red"));
                    }
                } else {
                    $zip = AuthComponent::user("zip");
                    $zip_detail = $this->Zip->findByZip($zip);
                    $find_zip = array();
                    if (isset($this->data["Event"]["lat_long"]) && !empty($this->data["Event"]["lat_long"])) {
                        $lat_long = $this->data["Event"]["lat_long"];
                        $pieces = explode(",", $lat_long);
                        $lat = $pieces[0];
                        $lng = $pieces[1];
                        $find_zip = $this->findzip($distance, $lat, $lng);
                    }
                    if (empty($find_zip)) {
                        $lat = $zip_detail["Zip"]["lat"];
                        $lng = $zip_detail["Zip"]["lng"];
                        $find_zip = $this->findzip($distance, $lat, $lng);
                        $this->Session->setFlash("Finding distnace from profile zip instead of IP address", "default", array("class" => "red"));
                    }

                    $find_zip[$zip_detail["Zip"]["id"]] = $zip_detail["Zip"]["zip"];
                }
                if (isset($find_zip) && !empty($find_zip)) {
                    $conditions = array_merge($conditions, array("Event.cant_find_zip_code IN" => $find_zip));
                }
            }
        }
        $conditions = array_merge($conditions, array('Event.user_id' => AuthComponent::User("id")));

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
        //   pr($this->Session->read('conditions'));

        $this->paginate = array('conditions' => $this->Session->read('conditions'), 'limit' => $limit, 'order' => $order, 'group' => 'Event.id');

        $this->set('events', $this->paginate('Event'));
        if ($campaign_id) {
            $campaign_id = base64_decode($campaign_id);
            //pr($this->Campaign->findById($campaign_id));die;
            $campaign_data = $this->Campaign->findById($campaign_id);
            $this->set("campaign_data", $campaign_data);
            $event_ids = "";
            foreach ($campaign_data["CampaignEvent"] as $cd) {
                $event_ids[] = $cd["event_id"];
            }
            $events = $this->Event->find("list", array("conditions" => array("Event.id" => $event_ids), "fields" => array("Event.id", "Event.title")));
            $this->Session->write("campaignEvent", $events);
        } else {
            $this->set("campaign_data", "");
        }
    }

    /** @Created:     07-July-2014
     * @Method :     setUp
     * @Author:      Sachin Thakur
     * @Modified :   08-July-2014(Prateek Jadhav)
     * @Purpose:     Create Campaign
     * @Param:       $campaign_id
     * @Return:      none
     */
    function setUp($campaign_id = NULL) {
        $this->layout = 'front/home';
        $this->set('title_for_layout', 'ALIST Email :: Campaign Set Up');
        $this->set("campaign_id", base64_decode($campaign_id));
        if (isset($this->data) && !empty($this->data)) {
            $this->Campaign->save($this->data);
            if (isset($this->data["Campaign"]["campaign_status"]) && $this->data["Campaign"]["campaign_status"] == "new") {
                $this->redirect(array('controller' => 'Campaigns', 'action' => 'preview', base64_encode($this->data["Campaign"]["id"])));
            } else {
                $this->redirect(array('controller' => 'Campaigns', 'action' => 'listing'));
            }
        }
        if ($campaign_id) {
            $campaign_id = base64_decode($campaign_id);
            $data = $this->Campaign->findById($campaign_id);
            $this->request->data = $data;
            if (isset($data["Campaign"]["title"]) && !empty($data["Campaign"]["title"])) {
                $this->set("campaign_status", "edit");
            } else {
                $this->set("campaign_status", "new");
            }
        }
    }

    /** @Created:     07-July-2014
     * @Method :     preview
     * @Author:      Sachin Thakur
     * @Modified :   ---
     * @Purpose:     Preview Campaign
     * @Param:       $campaign_id
     * @Return:      none
     */
    function preview($campaign_id = NULL) {
        $this->layout = 'front/home';
        $this->set('title_for_layout', 'ALIST Email :: Campaign');
        $events = $this->Session->read('campaignEvent');
        $this->set('event', $events);
        if ($campaign_id) {
            $campaign_id = base64_decode($campaign_id);
            $this->set("campaign", $this->Campaign->findById($campaign_id));
        }
    }

    /** @Created:     07-April-2014
     * @Method :     addToEvent
     * @Author:      Prateek Jadhav
     * @Modified :   ---
     * @Purpose:     For add/remove an event
     * @Param:       $id, $title
     * @Return:      none
     */
    function addToEvent($id = NULL, $title = NULL) {
        $this->layout = FALSE;
        $this->autoRender = FALSE;
        $campaignEvent = $this->Session->read("campaignEvent");
        if (isset($campaignEvent[$id])) {
            unset($campaignEvent[$id]);
            $this->Session->write("campaignEvent", $campaignEvent);
            return 1;
        } else {
            $campaignEvent[$id] = $title;
            $this->Session->write("campaignEvent", $campaignEvent);
            return 2;
        }
    }

    /** @Created:     07-April-2014
     * @Method :     removeEvent
     * @Author:      Prateek Jadhav
     * @Modified :   ---
     * @Purpose:     For remove an event
     * @Param:       $id
     * @Return:      none
     */
    function removeEvent($id = NULL) {
        $this->layout = FALSE;
        $this->autoRender = FALSE;
        $campaignEvent = $this->Session->read("campaignEvent");
        if (isset($campaignEvent[$id])) {
            unset($campaignEvent[$id]);
            $this->Session->write("campaignEvent", $campaignEvent);
            return 1;
        } else {
            return "unable to remove";
        }
    }

    /** @Created:     07-April-2014
     * @Method :     campaignRecipient
     * @Author:      Prateek Jadhav
     * @Modified :   ---
     * @Purpose:     For send to recipients
     * @Param:       none
     * @Return:      none
     */
    public function campaignRecipient($campaign_id = NULL, $mylist_id = NULL) {
        $this->layout = "front/home";
        $this->set('title_for_layout', 'ALIST Email :: Campaign Recipients');
        $this->loadModel("MyList");
        $this->loadModel("ListEmail");
        $conditions = array("MyList.user_id" => AuthComponent::user("id"));
        $fields = array("MyList.id", "MyList.list_name");
        $mylists = $this->MyList->find("list", array("conditions" => $conditions, "fields" => $fields));
        $this->set("myList", $mylists);

        $conditions_my = array("MyList.user_id" => AuthComponent::user("id"));

        $mlist = $this->MyList->find("all", array('conditions' => $conditions_my, 'order' => array('MyList.id' => 'ASC'), "recursive" => -1));


        $this->set('mylists_link', $mlist);

        if (!$mylist_id) {
            $mylist_id = base64_encode($mlist[0]["MyList"]["id"]);
        } //pr($mylist_id);die;
        $this->set("list_id", $mylist_id);
        $campaign_id = base64_decode($campaign_id);
        $this->set("campaign_id", $campaign_id);

        $listdetail = $this->MyList->find("first", array("conditions" => array("MyList.id" => base64_decode($mylist_id)), "recursive" => 2));
//pr($listdetail);die;
        $this->set("listdetail", $listdetail);

        $conditions = array("ListEmail.my_list_id" => base64_decode($mylist_id));

        if ($this->data) { //pr($this->data);die;
            if (!empty($this->data["ListEmail"]["email"])) {//pr($this->data["ListEmail"]["email"]);die;
                $conditions = array_merge($conditions, array("ListEmail.email LIKE" => "%" . trim($this->data["ListEmail"]["email"]) . "%"));
            }

            $this->paginate = array('conditions' => $conditions, 'limit' => 20, 'order' => array('ListEmail.email' => 'ASC'));
            $this->request->data = $this->data;
            $this->Session->write("list_emails", $this->data);
        } else {

            $this->paginate = array('conditions' => $conditions, 'limit' => 20, 'order' => array('ListEmail.email' => 'ASC'));
        }

        $this->set('list_emails', $this->paginate('ListEmail'));
    }

    /** @Created:     07-April-2014
     * @Method :     addRecipient
     * @Author:      Prateek Jadhav
     * @Modified :   ---
     * @Purpose:     For send to recipients
     * @Param:       none
     * @Return:      none
     */
    public function addRecipient($my_array = NULL) {
        $this->layout = FALSE;
        $this->autoRender = FALSE;
        $this->Session->delete("addRecipient");
        #if($this->Session->check("addRecipient")){
        #$myRecipient = $this->Session->read("addRecipient");
        #   $myRecipient = $myRecipient.','.$my_array;
        $this->Session->write("addRecipient", $my_array);
        /* } else {
          $myRecipient = $my_array;
          $this->Session->write("addRecipient",$myRecipient);
          } */
        return 1;
    }

    /** @Created:     08-April-2014
     * @Method :     campaignList
     * @Author:      Sachin Thakur
     * @Modified :   ---
     * @Purpose:     Fetch all email Id 
     * @Param:       none
     * @Return:      none
     */
    public function campaignList() {
        $this->layout = FALSE;
        $this->autoRender = FALSE;
        if (!empty($this->data)) { //pr($this->data);die;
            $this->loadModel("MyList");
            $selected = array();
            foreach ($this->data["MyList"]["id"] as $key => $value):
                if ($value != 0):
                    $selected[] = $value;
                endif;
            endforeach;
            //$this->MyList->unBindModel(array('hasMany' => array("ListVibe", "ListCategory", "ListRegion"), 'belongsTo' => array('User')), false);
            //$lists = $this->MyList->find('all', array('conditions' => array('MyList.id' => $selected), 'fields' => array("MyList.id")));
            //pr($lists);die;
            $this->loadModel("ListEmail");
            $lists = $this->ListEmail->find("list", array("conditions" => array("ListEmail.my_list_id" => $selected), "fields" => array("ListEmail.id", "ListEmail.id")));
            //pr($listid);die;
            if ($this->Session->check("addRecipient")) {
                $myRecipient = explode(',', $this->Session->read("addRecipient"));
            } else {
                $myRecipient = array();
            }

            $finalEmailIdsToSend = array_merge($lists, $myRecipient);

            $finalEmailIdsToSend = array_unique($finalEmailIdsToSend);
//            pr($finalEmailIdsToSend);
//            die;
            $this->loadModel("CampaignEmail");
            foreach ($finalEmailIdsToSend as $finalid) {
                $data["CampaignEmail"]["id"] = "";
                $data["CampaignEmail"]["campaign_id"] = $this->data["Campaign"]["id"];
                $data["CampaignEmail"]["list_email_id"] = $finalid;
                $this->CampaignEmail->save($data);
            }
            $this->redirect(array("controller" => "Campaigns", "action" => "confirmation"));
        }
    }

    /** @Created:     08-July-2014
     * @Method :     selectTemplate
     * @Author:      Prateek Jadhav
     * @Modified :   ---
     * @Purpose:     Select template for email
     * @Param:       $id
     * @Return:      none
     */
    public function selectTemplate($id = NULL) {
        $this->layout = FALSE;
        $this->autoRender = FALSE;
        if ($id) {
            $this->loadModel("EventTemplate");
            $this->loadModel("Event");
            $event_id = $this->Session->read("campaignEvent");
            $event_id = array_flip($event_id);
            $event_id = array_values($event_id);
            //  pr($event_id);
            $this->Event->unBindModel(array('hasMany' => array("EventCategory", "EventVibe", "TicketPrice", "EventEditedUser", "EventImages"), 'belongsTo' => array("User", "MyWpplugin")), false);
            $events = $this->Event->find("all", array("conditions" => array("Event.id" => $event_id), "recursive" => 1));
            //pr($events);die;
            $send_data = "";
            $final_data = "";
            $data = $this->EventTemplate->findById($id);
            foreach ($events as $event) {

                //pr($event);
                // calculate event date time
                $event_date_time = "";
                foreach ($event["EventDate"] as $ed) {
                    $event_date_time = $event_date_time . date('l, F d, Y', strtotime($ed["date"])) . "  " . date('H:i A', strtotime($ed['start_time'])) . " - " . date('H:i A', strtotime($ed['end_time'])) . "<br>";
                }

                $event_title = $event['Event']['title'];
                $event_sub_title = $event['Event']['sub_title'];
                $event_image = PARENT_URL . "/img/flyerImage/small/" . $event['Event']['flyer_image'];
                $event_url = PARENT_URL . "/Events/viewEvent/" . base64_encode($event['Event']['id']);
                $event_city = $event['Event']['city'];
                $event_description = $event['Event']['description'];
                $event_address = explode(",", $event['Event']['event_address']);
                $event_address1 = $event_address[0];
                $event_address2 = $event_address[1];
                //$event_start_date = date("d F", strtotime($event['Event']['start_date']));
                //$event_end_date = date("d F", strtotime($event['Event']['end_date']));
                $send_data = utf8_decode($data["EventTemplate"]["html"]);
                $send_data = str_replace(array('{event_title}', '{event_sub_title}'), array($event_title, $event_sub_title), $send_data);
                $send_data = str_replace(array('{event_image}', '{event_url}'), array($event_image, $event_url), $send_data);
                $send_data = str_replace(array('{event_city}', '{event_description}'), array($event_city, $event_description), $send_data);
                $send_data = str_replace(array('{event_address1}', '{event_address2}'), array($event_address1, $event_address2), $send_data);
                $send_data = str_replace(array('{event_date_time}'), array($event_date_time), $send_data);
                $final_data .= $send_data;
                //$send_data = str_replace(array('{event_start_date}', '{event_end_date}'), array($event_start_date, $event_end_date), $send_data);
            }
            return $final_data;
        }
    }

    /** @Created:     08-July-2014
     * @Method :     saveHtml
     * @Author:      Prateek Jadhav
     * @Modified :   ---
     * @Purpose:     For save html for campaign email
     * @Param:       none
     * @Return:      none
     */
    public function saveHtml() {
        $this->layout = FALSE;
        $this->autoRender = FALSE;
        if ($this->data) { //pr($this->data);die;
            $this->Campaign->save($this->data);
            if (!empty($this->data["Campaign"]["id"])) {
                $campaign_id = $this->data["Campaign"]["id"];
            } else {
                $campaign_id = $this->Campaign->getLastInsertID();
            }
            $event_id = $this->Session->read("campaignEvent");
            $event_id = array_flip($event_id);
            $event_id = array_values($event_id);
            $this->loadModel("CampaignEvent");
            foreach ($event_id as $ei) {
                $data["CampaignEvent"]["id"] = "";
                $data["CampaignEvent"]["event_id"] = $ei;
                $data["CampaignEvent"]["campaign_id"] = $campaign_id;
                $this->CampaignEvent->save($data);
            }
            $this->redirect(array("controller" => "Campaigns", "action" => "setUp", base64_encode($campaign_id)));
        }
    }

    /*     * @Created:     16-July-2014
     * @Method :     listing
     * @Author:      Prateek Jadhav
     * @Modified :   ---
     * @Purpose:     For Listing of campaign
     * @Param:       none
     * @Return:      none
     */

    public function listing() {
        $this->layout = "front/home";
        $conditions = array();
        $order = "Campaign.id DESC";
        $conditions = array_merge($conditions, array("Campaign.user_id" => AuthComponent::user("id")));
      
        $this->paginate = array('conditions' => $conditions, 'limit' => 10, 'order' => $order);
        $this->set('campaigns', $this->paginate('Campaign'));
    }

    /*     * @Created:     16-July-2014
     * @Method :     viewCampaign
     * @Author:      Prateek Jadhav
     * @Modified :   ---
     * @Purpose:     For view detail of campaign
     * @Param:       $campaign_id
     * @Return:      none
     */

    public function viewCampaign($campaign_id = NULL) {
        $this->layout = "front/home";
        $this->loadModel("CampaignEmail");
        $campaign_id = base64_decode($campaign_id);
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

    /*     * @Created:     16-July-2014
     * @Method :     previewCampaign
     * @Author:      Prateek Jadhav
     * @Modified :   ---
     * @Purpose:     For preview campaign email of campaign
     * @Param:       $campaign_id
     * @Return:      none
     */

    public function previewCampaign($campaign_id = NULL) {
        $this->layout = "front/home";
        $this->loadModel("MyList");
        $this->loadModel("Campaign");
        $this->loadModel("ListEmail");
        $this->loadModel("CampaignEmail");
        $this->loadModel("OpenRate");
        $this->loadModel("SegmentEmail");
        $this->loadModel("Segment");
        $this->loadModel("Event");

        if ($this->data) {

            $CampDetail = $this->Campaign->findById($this->data['Campaign']['id']);

            if ($CampDetail['Campaign']['mail_status'] == 0) {
                if (isset($CampDetail['CampaignList'][0]) && !empty($CampDetail['CampaignList'][0]['id'])) {
                    foreach ($CampDetail['CampaignList'] as $camListId) {
                        if (!empty($camListId["my_list_id"])) {
                            $ListEmail = $this->ListEmail->find("all", array("conditions" => array("ListEmail.status" => '1', "ListEmail.my_list_id" => $camListId['my_list_id']), "fields" => array("ListEmail.id", "ListEmail.email")));
                        } else {
                            $ListEmail = $this->SegmentEmail->find("all", array("conditions" => array("SegmentEmail.segment_id" => $camListId['segment_id']), "fields" => array("SegmentEmail.id", "SegmentEmail.email")));

                            $datamail = array();
                            $jk = 0;
                            foreach ($ListEmail as $lm) {
                                $datamail[$jk]["ListEmail"]["id"] = $lm["SegmentEmail"]["id"];
                                $datamail[$jk]["ListEmail"]["email"] = $lm["SegmentEmail"]["email"];
                                $jk++;
                            }
                            unset($ListEmail);
                            $ListEmail = $datamail;
                        }





                        $ListEmailCount = count($ListEmail);
                        if ($ListEmailCount != 0) {

                            if (isset($CampDetail['Campaign']['custom_email']) && !empty($CampDetail['Campaign']['custom_email'])) {
                                $customEmail = explode(",", $CampDetail['Campaign']['custom_email']);
                                if (isset($customEmail[0]) && !empty($customEmail[0])) {
                                    foreach ($customEmail as $cusEmail) {
                                        $cusEmailList[]['ListEmail']['email'] = $cusEmail;
                                    }
                                    $ListEmail = array_merge($ListEmail, $cusEmailList);
                                }
                            }


                            ############## Send Batch Mail via Mailgun Start ###########

                            foreach ($ListEmail as $finalid) {
                                $emailCamp = $finalid['ListEmail']['email'];
                                $emailArray[] = $emailCamp;
                                $emailName = explode("@", $emailCamp);

                                $recipientVar[$emailCamp]['first'] = $emailName[0];
                            }
                           

                            if ($CampDetail['Campaign']['send_mode'] == 0) {

                                $my_list_id = $camListId['my_list_id'];

                                $emailToList = implode(",", $emailArray);

                                $dataHtml = $CampDetail['Campaign']['html'];
                                $dataHtml = str_replace("contenteditable = 'true'", "", $dataHtml);
                                $dataHtml = str_replace('contenteditable="true"', "", $dataHtml);
                                $options = array("html" => $dataHtml, "subject" => $CampDetail['Campaign']['subject_line'], "recipient-variables" => $recipientVar);

                                $response = $this->Mailgun->send_mail($CampDetail['Campaign']['from_email'], $emailToList, $options);
                                //$response = $this->Mailgun->get_mail();
                               
                                $msg1 = explode('<', $response['id']);
                                $msg2 = explode('>', $msg1[1]);
                                $msgId = $msg2[0];
                                $sent_date = date("Y-m-d");
                                if (isset($msgId) && !empty($msgId)) {

                                    foreach ($ListEmail as $saveEmailCampaign) {
                                        $data["CampaignEmail"]["id"] = "";
                                        $data["CampaignEmail"]["campaign_id"] = $this->data["Campaign"]["id"];
                                        $data["CampaignEmail"]["email"] = $saveEmailCampaign['ListEmail']['email'];
                                        if (!empty($camListId['my_list_id'])) {
                                            $data["CampaignEmail"]["list_id"] = $camListId['my_list_id'];
                                        } else {
                                            $data["CampaignEmail"]["segment_id"] = $camListId['segment_id'];
                                        }

                                        $data["CampaignEmail"]["message_id"] = $msgId;
                                        $data["CampaignEmail"]["sent_date"] = $sent_date;


                                        $this->CampaignEmail->save($data);
                                    }
                                } else {
                                    echo "Not Ok";
                                }
                            }

                            if ($CampDetail['Campaign']['send_mode'] == 1) {

                                foreach ($ListEmail as $saveEmailCampaignSchedule) {

                                    $data["CampaignEmail"]["id"] = "";
                                    $data["CampaignEmail"]["campaign_id"] = $this->data["Campaign"]["id"];
                                    $data["CampaignEmail"]["email"] = $saveEmailCampaignSchedule['ListEmail']['email'];
                                    $data["CampaignEmail"]["list_id"] = $camListId['my_list_id'];

                                    $this->CampaignEmail->save($data);
                                }
                            }

                            ############## Send Batch Mail via Mailgun End ###########
                        }
                    }

                    if ($CampDetail['Campaign']['send_mode'] == 0) {
                        $sts = '2';
                    } else {
                        $sts = '1';
                    }
                    $campdata["Campaign"]["id"] = $this->data['Campaign']['id'];
                    $campdata["Campaign"]["mail_status"] = $sts;
                    $this->Campaign->save($campdata);

                    $this->redirect(array("controller" => "campaigns", "action" => "confirmation"));
                }
            } else {
                $this->redirect(array("controller" => "Users", "action" => "index"));
            }
        }

        if ($campaign_id) {

            $campaign_id = base64_decode($campaign_id);
            $this->set("campaign_id", $campaign_id);
            $campaign = $this->Campaign->findById($campaign_id);
            $this->set("campaign", $campaign);

            if (!empty($campaign['CampaignEvent'])) {
                foreach ($campaign['CampaignEvent'] as $campEvent) {
                    $event[] = $this->Event->findById($campEvent['event_id']);
                }

                $this->set("event", $event);
            }

            if (!empty($campaign["CampaignList"])) {
                foreach ($campaign["CampaignList"] as $ce) {
                    //pr($ce);
                    if (!empty($ce["segment_id"])) {
                        $ids_seg[] = $ce["segment_id"];
                        $ids = '';
                    } else {
                        $ids[] = $ce["my_list_id"];
                        $ids_seg = '';
                    }
                }
                if (!empty($ids_seg)) {
                    $this->set("count", $this->SegmentEmail->find("count", array("conditions" => array("SegmentEmail.segment_id" => $ids_seg))));
                    $this->set("campaignLists", $this->Segment->find("list", array("conditions" => array("Segment.id" => $ids_seg), "fields" => array("Segment.id", "Segment.name"))));
                } else {
                    $this->set("count", $this->ListEmail->find("count", array("conditions" => array("ListEmail.my_list_id" => $ids))));
                    $this->set("campaignLists", $this->MyList->find("list", array("conditions" => array("MyList.id" => $ids), "fields" => array("MyList.id", "MyList.list_name"))));
                }
            } else {
                $this->set("campaignLists", array());
            }
        } else {
            $this->redirect(array("controller" => "Users", "action" => "index"));
        }
    }

    /*     * @Created:     16-July-2014
     * @Method :     confirmation
     * @Author:      Prateek Jadhav
     * @Modified :   ---
     * @Purpose:     For campaign confirmation
     * @Param:       none
     * @Return:      none
     */

    public function confirmation() {
        $this->layout = "front/home";
        if ($this->Session->check("campaignEvent")) {
            $this->Session->delete("campaignEvent");
        }
    }

    /* @Created:     17-July-2014
     * @Method :     campaignCron
     * @Author:      Prateek Jadhav
     * @Modified :   ---
     * @Purpose:     For sending cron on specific date
     * @Param:       none
     * @Return:      none
     */

    public function campaignCron() {
        $this->autoRender = FALSE;
        $this->layout = FALSE;
        $this->loadModel("ListEmail");
        $this->loadModel("OpenRate");
        $today = strtotime(date("Y-m-d h:i:s"));

        // find the data  
        $data = $this->Campaign->find("all", array("conditions" => array("unix_timestamp(Campaign.date_to_send) <=" => $today, "mail_status" => 1, "send_mode" => 1)));
        // now find the emails to send
        $this->loadModel("CampaignEmail");

        if ($data) {
            foreach ($data as $dt) {


                ############## Send Batch Mail via Mailgun Start ###########

                $ListEmail = $this->CampaignEmail->find("all", array("conditions" => array("CampaignEmail.campaign_id" => $dt["Campaign"]["id"])));
                //$emails = $this->ListEmail->find("all", array("conditions" => array("ListEmail.id" => $lists), "recursive" => -1));
                // now send the email
                if (!empty($ListEmail)) {

                    foreach ($ListEmail as $finalid) {
                        $emailCamp = $finalid['CampaignEmail']['email'];
                        $emailArray[] = $emailCamp;
                        $emailName = explode("@", $emailCamp);

                        $recipientVar[$emailCamp]['first'] = $emailName[0];
                    }



                    $emailToList = implode(",", $emailArray);

                    $dataHtml = $dt['Campaign']['html'];
                    $dataHtml = str_replace("contenteditable = 'true'", "", $dataHtml);
                    $dataHtml = str_replace('contenteditable="true"', "", $dataHtml);
                    $options = array("html" => $dataHtml, "subject" => $dt['Campaign']['subject_line'], "recipient-variables" => $recipientVar);

                    $response = $this->Mailgun->send_mail($dt['Campaign']['from_email'], $emailToList, $options);
                    //$response = $this->Mailgun->get_mail();
                    

                    $msg1 = explode('<', $response['id']);
                    $msg2 = explode('>', $msg1[1]);
                    $msgId = $msg2[0];
                    $sent_date = date("Y-m-d");
                    if (isset($msgId) && !empty($msgId)) {

                        foreach ($ListEmail as $saveEmailCampaign) {
                            $data["CampaignEmail"]["id"] = $saveEmailCampaign["CampaignEmail"]["id"];
                            $data["CampaignEmail"]["message_id"] = $msgId;
                            $data["CampaignEmail"]["sent_date"] = $sent_date;

                            $this->CampaignEmail->save($data);
                        }
                    } else {
                        echo "Not Ok";
                    }



                    $camData['Campaign']['id'] = $dt["Campaign"]["id"];
                    $camData['Campaign']['mail_status'] = '2';
                    $this->Campaign->save($camData);
                } else {
                    echo "No Email";
                }
                ############## Send Batch Mail via Mailgun End ###########
            }
        } else {
            echo "No Campaign Found To Send Email";
        }die;
    }

    /* @Created:     16-Sept-2014
     * @Method :     admin_list
     * @Author:      Prateek Jadhav
     * @Modified :   ---
     * @Purpose:     For listing campaign from admin end
     * @Param:       none
     * @Return:      none
     */

    public function admin_list() {
        $this->layout = "admin/admin";
        $this->set('title_for_layout', 'ALIST Hub :: List Campaigns');
        $this->loadModel("CampaignEmail");
        $conditions = array("Campaign.user_id !=" => 0);
        //$fields = array("Campaign.id", "Campaign.title", "Campaign.subject_line", "Campaign.date_to_send", "Campaign.user_id", "Campaign.status", "User.username");
        if (isset($this->params["named"]["page"])) {
            $this->request->data = $this->Session->read("campaign_list");
        } else {
            $this->Session->delete("campaign_list");
        }
        if ($this->data) {
            if (!empty($this->data["Campaign"]["title"])) {
                $conditions = array_merge($conditions, array("Campaign.title LIKE" => "%" . $this->data["Campaign"]["title"] . "%"));
            }
            if (!empty($this->data["User"]["username"])) {
                $conditions = array_merge($conditions, array("User.username LIKE" => "%" . $this->data["User"]["username"] . "%"));
            }
            $this->paginate = array('conditions' => $conditions, "recursive" => 2, 'limit' => $this->data["Campaign"]["limit"], 'order' => array($this->data["Campaign"]["order"] => $this->data["Campaign"]["direction"]));
            $this->request->data = $this->data;
            $this->Session->write("campaign_list", $this->data);
        } else {
            $this->paginate = array('conditions' => $conditions, "recursive" => 2, 'limit' => 10, 'order' => array('Campaign.created' => 'DESC'));
        }
        $campaigns = $this->paginate('Campaign');

        $i = 0;
        foreach ($campaigns as $campaign) {
            $campaign_id = $campaign["Campaign"]["id"];
            $total_mail = $this->CampaignEmail->find("count", array("conditions" => array("CampaignEmail.campaign_id" => $campaign_id)));
            $sent_mail = $this->CampaignEmail->find("count", array("conditions" => array("CampaignEmail.sent_status" => 1, "CampaignEmail.campaign_id" => $campaign_id)));
            $bounce = $total_mail - $sent_mail;
            $open_mail = $this->CampaignEmail->find("count", array("conditions" => array("CampaignEmail.sent_status" => 1, "CampaignEmail.campaign_id" => $campaign_id, "CampaignEmail.open_status" => 1)));
            $click_mail = $this->CampaignEmail->find("count", array("conditions" => array("CampaignEmail.sent_status" => 1, "CampaignEmail.campaign_id" => $campaign_id, "CampaignEmail.click_status" => 1)));

            $campaigns[$i]["Campaign"]["total_mail"] = $total_mail;
            $campaigns[$i]["Campaign"]["sent_mail"] = $sent_mail;
            $campaigns[$i]["Campaign"]["bounce_mail"] = $bounce;
            $campaigns[$i]["Campaign"]["open_mail"] = $open_mail;
            $campaigns[$i]["Campaign"]["click_mail"] = $click_mail;
            $i++;
        }

        $this->set('campaigns', $campaigns);
    }

    /* @Created:     16-Sept-2014
     * @Method :     admin_list_csv
     * @Author:      Prateek Jadhav
     * @Modified :   ---
     * @Purpose:     For download csv for campaign from admin end
     * @Param:       none
     * @Return:      none
     */

    public function admin_list_csv() {
        $this->layout = FALSE;
        $conditions = array();
        $fields = array("Campaign.id", "Campaign.title", "Campaign.subject_line", "Campaign.date_to_send", "Campaign.user_id", "Campaign.status", "User.username", "Campaign.from_name", "Campaign.reply_email", "Campaign.from_email", "Campaign.created", "Campaign.modified");
        if ($this->Session->check("campaign_list")) {
            $this->request->data = $this->Session->read("campaign_list");
        }
        if ($this->data) {
            if (!empty($this->data["Campaign"]["title"])) {
                $conditions = array_merge($conditions, array("Campaign.title LIKE" => "%" . $this->data["Campaign"]["title"] . "%"));
            }
            if (!empty($this->data["User"]["username"])) {
                $conditions = array_merge($conditions, array("User.username LIKE" => "%" . $this->data["User"]["username"] . "%"));
            }
            $this->paginate = array('conditions' => $conditions, 'limit' => $this->data["Campaign"]["limit"], 'order' => array($this->data["Campaign"]["order"] => $this->data["Campaign"]["direction"]));
            $this->request->data = $this->data;
            $this->Session->write("campaign_list", $this->data);
        } else {
            $this->paginate = array('conditions' => $conditions, 'order' => array('Campaign.name' => 'ASC'), "fields" => $fields);
        }
        $campaigns = $this->paginate('Campaign');
        $this->set('campaigns', $campaigns);
    }

    /* @Created:     16-Sept-2014
     * @Method :     admin_view
     * @Author:      Prateek Jadhav
     * @Modified :   ---
     * @Purpose:     For view campaign from admin end
     * @Param:       $campaign_id
     * @Return:      none
     */

    public function admin_view($campaign_id) {
        $this->layout = "admin/admin";
        $this->set('title_for_layout', 'ALIST Hub :: View Campaigns');
        if ($campaign_id) {
            $this->loadModel("MyList");
            $this->loadModel("Campaign");
            $this->loadModel("ListEmail");
            $this->loadModel("CampaignEmail");
            $this->loadModel("OpenRate");
            $this->loadModel("SegmentEmail");
            $this->loadModel("Segment");
            $this->loadModel("Event");

            $campaign_id = base64_decode($campaign_id);
            $this->set("campaign_id", $campaign_id);
            $campaign = $this->Campaign->findById($campaign_id);
            $this->set("campaign", $campaign);

            if (!empty($campaign['CampaignEvent'])) {
                foreach ($campaign['CampaignEvent'] as $campEvent) {
                    $event[] = $this->Event->findById($campEvent['event_id']);
                }

                $this->set("event", $event);
            }

            if (!empty($campaign["CampaignList"])) {
                foreach ($campaign["CampaignList"] as $ce) {
                    //pr($ce);
                    if (!empty($ce["segment_id"])) {
                        $ids_seg[] = $ce["segment_id"];
                        $ids = '';
                    } else {
                        $ids[] = $ce["my_list_id"];
                        $ids_seg = '';
                    }
                }
                if (!empty($ids_seg)) {
                    $this->set("count", $this->SegmentEmail->find("count", array("conditions" => array("SegmentEmail.segment_id" => $ids_seg))));
                    $this->set("campaignLists", $this->Segment->find("list", array("conditions" => array("Segment.id" => $ids_seg), "fields" => array("Segment.id", "Segment.name"))));
                } else {
                    $this->set("count", $this->ListEmail->find("count", array("conditions" => array("ListEmail.my_list_id" => $ids))));
                    $this->set("campaignLists", $this->MyList->find("list", array("conditions" => array("MyList.id" => $ids), "fields" => array("MyList.id", "MyList.list_name"))));
                }
            } else {
                $this->set("campaignLists", array());
            }
        } else {
            $this->redirect(array("controller" => "Campaigns", "action" => "list"));
        }
    }

    /* @Created:     26-Sept-2014
     * @Method :     editMultipleTemplate
     * @Author:      Prateek Jadhav
     * @Modified :   ---
     * @Purpose:     For edit campaign template
     * @Param:       $campaign_id/NULL
     * @Return:      $changeArr
     */

    public function editMultipleTemplate($campaign_id = NULL) {
        $this->layout = FALSE;
        $this->autoRender = FALSE;
        if ($campaign_id) {
            $this->loadModel("CampaignEvent");
            $campaignEvents = $this->CampaignEvent->findAllByCampaignId($campaign_id);
            $oldEvent = $this->Session->read("CampaignEvent");
            $ar_new = array_keys($oldEvent);
            $ar_old = array();
            foreach ($campaignEvents as $ce) {
                $ar_old[] = $ce["CampaignEvent"]["event_id"];
                $ar_old_id[] = $ce["CampaignEvent"]["id"];
            }
            $deletedArr = array_diff($ar_old, $ar_new);
            $AddedArr = array_diff($ar_new, $ar_old);
            $finalArr["Delete"] = $deletedArr;
            $finalArr["Add"] = $AddedArr;
            $this->CampaignEvent->delete($ar_old_id);
            foreach ($ar_new as $an) {
                $data["CampaignEvent"]["id"] = "";
                $data["CampaignEvent"]["event_id"] = $an;
                $data["CampaignEvent"]["campaign_id"] = $campaign_id;
                $data["CampaignEvent"]["status"] = 1;
                $this->CampaignEvent->save($data);
            }
            return $finalArr;
        }
    }

    /* @Created:     26-Nov-2014
     * @Method :     addMoreEventToTemplate
     * @Author:      Prateek Jadhav
     * @Modified :   ---
     * @Purpose:     For add more event to campaign template
     * @Param:       $campaiign_id/NULL
     * @Return:      none
     */

    public function addMoreEventToTemplate($campaign_id = NULL) {
        $this->layout = FALSE;
        $this->autoRender = FALSE;
        if ($this->Session->check('finalArr')) {
            $finalArr = $this->Session->read('finalArr');
            if (!empty($finalArr['Add'])) {
                $eventsIDs = $finalArr['Add'];

                $campaign = $this->Campaign->findById($campaign_id);
                $repeat = $campaign['Campaign']['repeat'];
                //echo "<pre>";print_r($repeat);die;
                // pr($end_html);die;
                // find event
                $this->loadModel("Event");
                $events = $this->Event->find("all", array("conditions" => array("Event.id" => $eventsIDs)));
                $i = 0;
                $ht = "";
                foreach ($events as $event) {
                    $i++;
                    // set variables
                    $ht1 = $repeat;
                    $address = $event["Event"]["event_address"] . "," . $event["Event"]["cant_find_city"] . "," . $event["Event"]["cant_find_state"] . "," . $event["Event"]["cant_find_zip_code"];
                    $cost = "";
                    foreach ($event["TicketPrice"] as $tp) {
                        $price = str_replace("$", "", $tp['ticket_price']);
                        $cost .= "$" . $price . "-" . $tp["ticket_label"] . ",";
                    }
                    if ($event["Event"]["event_from"] == "facebook" && $event["Event"]["image_name"] != "/fb_detail.png") {
                        $primary_image = $event["Event"]["image_name"];
                    } elseif ($event["Event"]["event_from"] == "eventful" && $event["Event"]["image_name"] != "/no_image.jpeg") {
                        $primary_image = $event["Event"]["image_name"];
                    } elseif (!empty($event["Event"]["image_name"])) {
                        $primary_image = PARENT_URL . "/img/EventImages/large/" . $event["Event"]["image_name"];
                    } else {
                        $primary_image = "http://alisthub.com/img/primary.gif";
                    }

                    if ($event["Event"]["event_from"] == "facebook" && $event["Event"]["flyer_image"] != "/fb_detail.png") {
                        $flyer_image = $event["Event"]["flyer_image"];
                    } elseif ($event["Event"]["event_from"] == "eventful" && $event["Event"]["flyer_image"] != "/no_image.jpeg") {
                        $flyer_image = $event["Event"]["flyer_image"];
                    } elseif (!empty($event["Event"]["flyer_image"])) {
                        $flyer_image = PARENT_URL . "/img/flyerImage/large/" . $event["Event"]["flyer_image"];
                    } else {
                        $flyer_image = "http://alisthub.com/img/flyer.gif";
                    }

                    $date = "";
                    $i = 0;
                    foreach ($event["EventDate"] as $ed) {
                        if ($i < 6) {
                            $date .= date('l, F d, Y', strtotime($ed["date"]));
                            if (!empty($ed["start_time"])) {
                                $date .= "    " . date('g:i A', strtotime($ed['start_time']));
                            } if (!empty($ed["end_time"])) {
                                $date .= " - " . date('g:i A', strtotime($ed['end_time']));
                            }
                            $date .= "<br>";
                        }
                        $i++;
                    }

                    $short_description = $event["Event"]["short_description"];
                    $short_description = str_replace("<p></p>", "", $short_description);
                    $short_description = str_replace("<p><br></p>", "", $short_description);
                    $short_description = str_replace("<p>&nbsp;</p>", "", $short_description);
                    $short_description = str_replace("<p> </p>", "", $short_description);

                    $long_description = $event["Event"]["description"];
                    $long_description = str_replace("<p></p>", "", $long_description);
                    $long_description = str_replace("<p><br></p>", "", $long_description);
                    $long_description = str_replace("<p>&nbsp;</p>", "", $long_description);
                    $long_description = str_replace("<p> </p>", "", $long_description);

                    $ticket_url = $event["Event"]["ticket_vendor_url"];

                    $site_url = $event["Event"]["website_url"];

                    $facebook_url = $event["Event"]["facebook_url"];

                    if ($event["Event"]["event_from"] == "eventful") {
                        $event_detail_url = PARENT_URL . '/Events/viewEventfulEvent/' . $event["Event"]["ef_event_id"];
                    } else {
                        $event_detail_url = PARENT_URL . "/Events/viewEvent/" . base64_encode($event["Event"]["id"]);
                    }

                    $uniqueID = "id_" . uniqid();
                    $event_id = "event_" . $event["Event"]["id"];
                    $ht .= "<tr id='" . $event_id . "'><td><table><div style='clear:both;'></div><div  contenteditable='true'>" . str_replace(array('*|EVENT_NAME|*', '*|VENUE|*', '*|COST|*', "http://alisthub.com/img/primary.gif", "http://alisthub.com/img/flyer.gif", "*|Date|*", "*|SHORT_DESCRIPTION|*", "*|LONG_DESCRIPTION|*"
                                , "*|TICKET_URL|*", "*|FACEBOOK_URL|*", "*|SITE_URL|*", "*|EVENT_DETAIL_URL|*", "launchEditorImageid"), array($event["Event"]["title"], $address, $cost, $primary_image, $flyer_image, $date, $short_description, $long_description
                                , $ticket_url, $facebook_url, $site_url, $event_detail_url, $uniqueID), $ht1) . "</div></table></td></tr>";

                    //$ht .= "<div style='clear:both;'></div>";
                }
                $start = 0;
                $html = $campaign["Campaign"]["html"];
                $pos = strpos($campaign["Campaign"]["html"], "*|NEXT|*");
                $end = strlen($campaign["Campaign"]["html"]);

                $start_html = substr($html, 0, $pos);
                $start_html = str_replace("*|NEXT|*", "", $start_html);
                $end_html = substr($html, $pos + 8, $end);
                $end_html = str_replace("*|NEXT|*", "", $end_html);
                $new_html = $ht . "*|NEXT|*";
                $data["Campaign"]["id"] = $campaign_id;
                $final = $start_html . $new_html . $end_html;
                $final = str_replace(" *|/REPEAT|*", "", $final);
                $data["Campaign"]["html"] = $final;
                $this->Campaign->save($data);
            }
        }return TRUE;
    }

    /* @Created:     16-Sept-2014
     * @Method :     chooseTemplate
     * @Author:      Prateek Jadhav
     * @Modified :   ---
     * @Purpose:     For choose campaign template
     * @Param:       $id/NULL
     * @Return:      none
     */

  public function chooseTemplate($id = NULL) {
        $this->layout = "front/home";
        $this->loadModel("EventTemplate");

          if ($this->Session->check("CampaignType")) {
            if ($this->Session->read("CampaignType") == "single") {
                $type = 1;
            } else {
                $type = 2;
            }
        } else {
            $type = 0;
        }
        
        if (!empty($id)) {
            if ($id == "new") {
                $this->Session->delete("CampaignType");
                $this->Session->delete("CampaignEvent");
            } elseif($type==2) {
                $campaign_id = base64_decode($id);
                $return = $this->editMultipleTemplate($campaign_id);
                $this->Session->write("finalArr", $return);
                //pr($return);
                if (!empty($return["Add"])) {
                    $this->addMoreEventToTemplate($campaign_id);
                }
                //die;
                
                $this->redirect(array("controller" => "Campaigns", "action" => "designTemplate", base64_encode($campaign_id)));
            }
        }
      
        $datas = $this->EventTemplate->find("all", array("conditions" => array("EventTemplate.status" => 1, "EventTemplate.type" => $type)));
        $this->set("datas", $datas);
        if ($type == 2) {
            $datas = $this->EventTemplate->find("first", array("conditions" => array("EventTemplate.status" => 1, "EventTemplate.type" => 2)));
            $id = base64_encode($datas["EventTemplate"]["id"]);
        }
        if ($id && $id != "new") {
            $id = base64_decode($id);
            $detail = $this->EventTemplate->findById($id);
            $campaign["Campaign"]["html"] = $detail["EventTemplate"]["html"];
            $this->Campaign->save($campaign);
            $campaign_id = $this->Campaign->getLastInsertID();
            if ($this->Session->check("CampaignType")) {
                if ($this->Session->read("CampaignType") == "single") {
                    $return = $this->configureSingleEvent($campaign_id);
                } else {
                    
                    $return = $this->configureMultipleEvent($campaign_id);
                   
                }
            }
            $this->redirect(array("controller" => "Campaigns", "action" => "designTemplate", base64_encode($campaign_id)));
        }
    }

  
    /* @Created:     18-Sept-2014
     * @Method :     configureSingleEvent
     * @Author:      Prateek Jadhav
     * @Modified :   ---
     * @Purpose:     For design campaign template
     * @Param:       $campaign_id
     * @Return:      none
     */

    public function configureSingleEvent($campaign_id = NULL) {
        $this->layout = FALSE;
        $this->autoRender = FALSE;
        $this->loadModel("CampaignEvent");

        $campaign = $this->Campaign->findById($campaign_id);
        $html = $campaign["Campaign"]["html"];
        $html = utf8_decode($html);
        // find event
        $this->loadModel("Event");
        $arr = $this->Session->read("CampaignEvent");
        $ar = array_keys($arr);
        $event_id = $ar[0];
        $event = $this->Event->findById($event_id);

        // set variables
        $address = $event["Event"]["event_address"] . "," . $event["Event"]["cant_find_city"] . "," . $event["Event"]["cant_find_state"] . "," . $event["Event"]["cant_find_zip_code"];
        $cost = "";
        foreach ($event["TicketPrice"] as $tp) {
            $price = str_replace("$", "", $tp['ticket_price']);
            $cost .= "$" . $price . "-" . $tp["ticket_label"] . ",";
        }
        if ($event["Event"]["event_from"] == "facebook" && $event["Event"]["image_name"] != "/fb_detail.png") {
            $primary_image = $event["Event"]["image_name"];
        } elseif ($event["Event"]["event_from"] == "eventful" && $event["Event"]["image_name"] != "/no_image.jpeg") {
            $primary_image = $event["Event"]["image_name"];
        } elseif (!empty($event["Event"]["image_name"])) {
            $primary_image = PARENT_URL . "/img/EventImages/large/" . $event["Event"]["image_name"];
        } else {
            $primary_image = "http://alisthub.com/img/primary.gif";
        }

        if ($event["Event"]["event_from"] == "facebook" && $event["Event"]["flyer_image"] != "/fb_detail.png") {
            $flyer_image = $event["Event"]["flyer_image"];
        } elseif ($event["Event"]["event_from"] == "eventful" && $event["Event"]["flyer_image"] != "/no_image.jpeg") {
            $flyer_image = $event["Event"]["flyer_image"];
        } elseif (!empty($event["Event"]["flyer_image"])) {
            $flyer_image = PARENT_URL . "/img/flyerImage/large/" . $event["Event"]["flyer_image"];
        } else {
            $flyer_image = "http://alisthub.com/img/flyer.gif";
        }

        $date = "";
        $i = 0;
        foreach ($event["EventDate"] as $ed) {
            if ($i < 6) {
                $date .= date('l, F d, Y', strtotime($ed["date"]));
                if (!empty($ed["start_time"])) {
                    $date .= "    " . date('g:i A', strtotime($ed['start_time']));
                } if (!empty($ed["end_time"])) {
                    $date .= " - " . date('g:i A', strtotime($ed['end_time']));
                }
                $date .= "<br>";
            }
            $i++;
        }

        $short_description = $event["Event"]["short_description"];
        $short_description = str_replace("<p></p>", "", $short_description);
        $short_description = str_replace("<p><br></p>", "", $short_description);
        $short_description = str_replace("<p>&nbsp;</p>", "", $short_description);
        $short_description = str_replace("<p> </p>", "", $short_description);

        $long_description = $event["Event"]["description"];
        $long_description = str_replace("<p></p>", "", $long_description);
        $long_description = str_replace("<p><br></p>", "", $long_description);
        $long_description = str_replace("<p>&nbsp;</p>", "", $long_description);
        $long_description = str_replace("<p> </p>", "", $long_description);

        $ticket_url = $event["Event"]["ticket_vendor_url"];

        $site_url = $event["Event"]["website_url"];

        $facebook_url = $event["Event"]["facebook_url"];

        if ($event["Event"]["event_from"] == "eventful") {
            $event_detail_url = "http://" . $_SERVER["HTTP_HOST"] . '/Events/viewEventfulEvent/' . $event["Event"]["ef_event_id"];
        } else {
            $event_detail_url = "http://" . $_SERVER["HTTP_HOST"] . "/Events/viewEvent/" . base64_encode($event["Event"]["id"]);
        }
        $html = str_replace(array('*|EVENT_NAME|*', '*|VENUE|*', '*|COST|*', "http://alisthub.com/img/primary.gif", "http://alisthub.com/img/flyer.gif", "*|Date|*", "*|SHORT_DESCRIPTION|*", "*|LONG_DESCRIPTION|*"
            , "*|TICKET_URL|*", "*|FACEBOOK_URL|*", "*|SITE_URL|*", "*|EVENT_DETAIL_URL|*"), array($event["Event"]["title"], $address, $cost, $primary_image, $flyer_image, $date, $short_description, $long_description
            , $ticket_url, $facebook_url, $site_url, $event_detail_url), $html);

        $data["Campaign"]["id"] = $campaign_id;
        $data["Campaign"]["html"] = $html;
        $this->Campaign->save($data);

        $datacamp["CampaignEvent"]["id"] = '';
        $datacamp["CampaignEvent"]["campaign_id"] = $campaign_id;
        $datacamp["CampaignEvent"]["event_id"] = $event_id;
        $datacamp["CampaignEvent"]["status"] = 1;
        $this->CampaignEvent->save($datacamp);
        return TRUE;
    }

    /* @Created:     24-Sept-2014
     * @Method :     configureMultipleEvent
     * @Author:      Prateek Jadhav
     * @Modified :   ---
     * @Purpose:     For design campaign template
     * @Param:       $campaign_id
     * @Return:      none
     */

    public function configureMultipleEvent($campaign_id = NULL) {
        $this->layout = FALSE;
        $this->autoRender = FALSE;
        $this->loadModel("CampaignEvent");

        $campaign = $this->Campaign->findById($campaign_id);
        $html = $campaign["Campaign"]["html"];
        $html = utf8_decode($html);
       
        // find repeatable content
        $start_pos = strpos($html, "*|REPEAT|*");
        $end_pos = strpos($html, "*|/REPEAT|*");
        $start = 0;
        $end = strlen($html);
        $length = $end_pos - $start_pos;
        $repeat = substr($html, $start_pos + 10, $length);
        $repeat = "<span>" . $repeat . "<span>";
      
        $start_html = substr($html, $start, $start_pos);
        $end_html = substr($html, $end_pos + 11, $end);
       
        // find event
        $this->loadModel("Event");
        $arr = $this->Session->read("CampaignEvent");
        $ar = array_keys($arr);
        $events = $this->Event->find("all", array("conditions" => array("Event.id" => $ar)));
        $i = 0;

        foreach ($events as $event) {
            $i++;
            // set variables
            $ht1 = $repeat;
            $address = $event["Event"]["event_address"] . "," . $event["Event"]["cant_find_city"] . "," . $event["Event"]["cant_find_state"] . "," . $event["Event"]["cant_find_zip_code"];
            $cost = "";
            foreach ($event["TicketPrice"] as $tp) {
                $price = str_replace("$", "", $tp['ticket_price']);
                $cost .= "$" . $price . "-" . $tp["ticket_label"] . ",";
            }
            if ($event["Event"]["event_from"] == "facebook" && $event["Event"]["image_name"] != "/fb_detail.png") {
                $primary_image = $event["Event"]["image_name"];
            } elseif ($event["Event"]["event_from"] == "eventful" && $event["Event"]["image_name"] != "/no_image.jpeg") {
                $primary_image = $event["Event"]["image_name"];
            } elseif (!empty($event["Event"]["image_name"])) {
                $primary_image = PARENT_URL . "/img/EventImages/large/" . $event["Event"]["image_name"];
            } else {
                $primary_image = "http://alisthub.com/img/primary.gif";
            }

            if ($event["Event"]["event_from"] == "facebook" && $event["Event"]["flyer_image"] != "/fb_detail.png") {
                $flyer_image = $event["Event"]["flyer_image"];
            } elseif ($event["Event"]["event_from"] == "eventful" && $event["Event"]["flyer_image"] != "/no_image.jpeg") {
                $flyer_image = $event["Event"]["flyer_image"];
            } elseif (!empty($event["Event"]["flyer_image"])) {
                $flyer_image = PARENT_URL . "/img/flyerImage/large/" . $event["Event"]["flyer_image"];
            } else {
                $flyer_image = "http://alisthub.com/img/flyer.gif";
            }

            $date = "";
            $i = 0;
            foreach ($event["EventDate"] as $ed) {
                if ($i < 6) {
                    $date .= date('l, F d, Y', strtotime($ed["date"]));
                    if (!empty($ed["start_time"])) {
                        $date .= "    " . date('g:i A', strtotime($ed['start_time']));
                    } if (!empty($ed["end_time"])) {
                        $date .= " - " . date('g:i A', strtotime($ed['end_time']));
                    }
                    $date .= "<br>";
                }
                $i++;
            }

            $short_description = $event["Event"]["short_description"];
            $short_description = str_replace("<p></p>", "", $short_description);
            $short_description = str_replace("<p><br></p>", "", $short_description);
            $short_description = str_replace("<p>&nbsp;</p>", "", $short_description);
            $short_description = str_replace("<p> </p>", "", $short_description);

            $long_description = $event["Event"]["description"];
            $long_description = str_replace("<p></p>", "", $long_description);
            $long_description = str_replace("<p><br></p>", "", $long_description);
            $long_description = str_replace("<p>&nbsp;</p>", "", $long_description);
            $long_description = str_replace("<p> </p>", "", $long_description);

            $ticket_url = $event["Event"]["ticket_vendor_url"];

            $site_url = $event["Event"]["website_url"];

            $facebook_url = $event["Event"]["facebook_url"];

            if ($event["Event"]["event_from"] == "eventful") {
                $event_detail_url = PARENT_URL . '/Events/viewEventfulEvent/' . $event["Event"]["ef_event_id"];
            } else {
                $event_detail_url = PARENT_URL . "/Events/viewEvent/" . base64_encode($event["Event"]["id"]);
            }

            $uniqueID = "id_" . uniqid();
            $event_id = "event_" . $event["Event"]["id"];
            $ht .= "<tr id='" . $event_id . "'><td><table><div style='clear:both;'></div><div  contenteditable='true'>" . str_replace(array('*|EVENT_NAME|*', '*|VENUE|*', '*|COST|*', "http://alisthub.com/img/primary.gif", "http://alisthub.com/img/flyer.gif", "*|Date|*", "*|SHORT_DESCRIPTION|*", "*|LONG_DESCRIPTION|*"
                        , "*|TICKET_URL|*", "*|FACEBOOK_URL|*", "*|SITE_URL|*", "*|EVENT_DETAIL_URL|*", "launchEditorImageid"), array($event["Event"]["title"], $address, $cost, $primary_image, $flyer_image, $date, $short_description, $long_description
                        , $ticket_url, $facebook_url, $site_url, $event_detail_url, $uniqueID), $ht1) . "</div></table></td></tr>";

            //$ht .= "<div style='clear:both;'></div>";

            $datacamp["CampaignEvent"]["id"] = '';
            $datacamp["CampaignEvent"]["campaign_id"] = $campaign_id;
            $datacamp["CampaignEvent"]["event_id"] = $event["Event"]["id"];
            $datacamp["CampaignEvent"]["status"] = 1;
            $this->CampaignEvent->save($datacamp);
        }
       
        $data["Campaign"]["id"] = $campaign_id;
        $final = $start_html . $ht . "<tr><td>*|NEXT|*</td></tr>" . $end_html;

        $final = str_replace("*|/REPEAT|", "", $final);

        $data["Campaign"]["html"] = $final;
        $data["Campaign"]["repeat"] = $repeat;
        $this->Campaign->save($data);
        return TRUE;
    }

    /* @Created:     18-Sept-2014
     * @Method :     designTemplate
     * @Author:      Prateek Jadhav
     * @Modified :   ---
     * @Purpose:     For design campaign template
     * @Param:       $campaign_id
     * @Return:      none
     */

    public function designTemplate($campaign_id = NULL) {
        $this->layout = "front/home";
        if ($campaign_id) {
            $campaign_id = base64_decode($campaign_id);
            $data = $this->Campaign->findById($campaign_id);
            $this->set("data", $data);
        } else {
            $this->redirect(array("controller" => "Campaigns", "action" => "index"));
        }
    }

    /* @Created:     18-Sept-2014
     * @Method :     createCampaign
     * @Author:      Prateek Jadhav
     * @Modified :   ---
     * @Purpose:     For create campaign after design template
     * @Param:       none
     * @Return:      none
     */

    public function createCampaign() {
        $this->layout = FALSE;
        $this->autoRender = FALSE;
        if ($this->data) {
            $this->request->data["Campaign"]["user_id"] = AuthComponent::user("id");
            $this->Campaign->save($this->data);
            if (!empty($this->data["Campaign"]["id"])) {
                $id = $this->data["Campaign"]["id"];
            } else {
                $id = $this->Campaign->getLastInsertID();
            }
            $this->redirect(array("controller" => "Campaigns", "action" => "campaignDetail", base64_encode($id)));
        }
    }

    /* @Created:     18-Sept-2014
     * @Method :     campaignDetail
     * @Author:      Prateek Jadhav
     * @Modified :   ---
     * @Purpose:     Campaign Detail
     * @Param:       $campaign_id
     * @Return:      none
     */

    public function campaignDetail($campign_id) {
        $this->layout = "front/home";
        if ($this->data) {
          
            $this->loadModel("ListEmail");
            $list_email = array();
            $email = $this->data["Campaign"]["emailList"];
            $this->loadModel("CampaignList");
            $this->CampaignList->deleteAll(array("CampaignList.campaign_id" => $this->data["Campaign"]["id"]));
            // foreach ($emails as $email) {
            if ($email != 0) {
                $datamail["CampaignList"]["id"] = "";
                $datamail["CampaignList"]["campaign_id"] = $this->data["Campaign"]["id"];
                $datamail["CampaignList"]["my_list_id"] = $email;
                $this->CampaignList->save($datamail);
            }
            // }
            if (isset($this->data["Campaign"]["emailListSegment"]) && !empty($this->data["Campaign"]["emailListSegment"])) {
                $segmentmail["CampaignList"]["id"] = "";
                $segmentmail["CampaignList"]["campaign_id"] = $this->data["Campaign"]["id"];
                $segmentmail["CampaignList"]["segment_id"] = $this->data["Campaign"]["emailListSegment"];
                $this->CampaignList->save($segmentmail);
            }



            $this->Campaign->save($this->data);
            $this->redirect(array("controller" => "campaigns", "action" => "previewCampaign", base64_encode($this->data["Campaign"]["id"])));
        } else if ($campign_id) {
            $campign_id = base64_decode($campign_id);
            $camDetail = $this->Campaign->findById($campign_id);
            $this->request->data = $camDetail;
            $this->set("campign_id", $campign_id);

            #pr($camDetail);
            if (empty($camDetail['Campaign']['subject_line'])) {
                $this->set("campTitle", $camDetail['Campaign']['title']);
            } else {
                $this->set("campTitle", $camDetail['Campaign']['subject_line']);
            }

            if (empty($camDetail['Campaign']['from_name'])) {
                $this->set("fromName", $camDetail['User']['first_name'] . " " . $camDetail['User']['last_name']);
            } else {
                $this->set("fromName", $camDetail['Campaign']['from_name']);
            }

            if (empty($camDetail['Campaign']['reply_email'])) {
                $this->set("campReplyEmail", $camDetail['User']['email']);
            } else {
                $this->set("campReplyEmail", $camDetail['Campaign']['reply_email']);
            }

            if (empty($camDetail['Campaign']['from_email'])) {
                $this->set("campFromEmail", $camDetail['User']['email']);
            } else {
                $this->set("campFromEmail", $camDetail['Campaign']['from_email']);
            }

            if (!empty($camDetail['CampaignList'][0]['my_list_id'])) {
                $listMode = "mylistdiv";
                $listIdMail = $camDetail['CampaignList'][0]['my_list_id'];
                $segmntId = '';
            } elseif (!empty($camDetail['CampaignList'][0]['segment_id'])) {
                $listMode = "segmentid";
                $segmntId = $camDetail['CampaignList'][0]['segment_id'];
                $listIdMail = '';
            } else {
                $listMode = "";
                $segmntId = "";
                $listIdMail = "";
            }

            $this->set("listMode", $listMode);
            $this->set("listIdMail", $listIdMail);
            $this->set("segmntId", $segmntId);
            $this->loadModel("MyList");
            $this->loadModel("ListEmail");

            $lists = $this->MyList->find("all", array("conditions" => array("MyList.user_id" => AuthComponent::user("id")), "recursive" => -1));
            $i = 0;
            foreach ($lists as $list) {
                $count = $this->ListEmail->find("count", array("conditions" => array("ListEmail.my_list_id" => $list["MyList"]["id"])));
                $lists[$i]["MyList"]["count"] = $count;
                $i++;
                $listids[] = $list["MyList"]["id"];
            }
            $this->set("lists", $lists);
            if (isset($listids) && !empty($listids)) {
                $this->loadModel("Segment");
                $this->loadModel("SegmentEmail");
                $segments = $this->Segment->find("all", array("conditions" => array("Segment.my_list_id" => $listids), "recursive" => -1));
                $k = 0;
                foreach ($segments as $segment) {
                    $segmentEmail = $this->SegmentEmail->find("count", array("conditions" => array("SegmentEmail.segment_id" => $segment["Segment"]["id"])));
                    $segments[$k]["Segment"]["count"] = $segmentEmail;
                    $k++;
                }
                $this->set("segments", $segments);
            }
        } else {
            $this->redirect(array("controller" => "Campaigns", "action" => "campaignDetail", base64_encode($id)));
        }
    }

    /* @Created:     19-Sept-2014
     * @Method :     chooseEventSingle
     * @Author:      Prateek Jadhav
     * @Modified :   ---
     * @Purpose:     for choose single event
     * @Param:       $action/NULL
     * @Return:      none  
     */

    public function chooseEventSingle($action = NULL) {
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
                $this->request->data["Event"]["limit"] = $existing_search_data["Search"]["limit"];

                if (!empty($existing_search_data["Search"]["FacebookShow"])) {
                    $this->request->data["FacebookShow"] = $existing_search_data["Search"]["FacebookShow"];
                }
                if (!empty($existing_search_data["Search"]["allFacebookShow"])) {
                    $this->request->data["allFacebookShow"] = $existing_search_data["Search"]["allFacebookShow"];
                }
                if (!empty($existing_search_data["Search"]["myFacebookShow"])) {
                    $this->request->data["myFacebookShow"] = $existing_search_data["Search"]["myFacebookShow"];
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


        if (isset($this->data["Event"]["order"]) && $this->data["Event"]["order"] != "Event.start_date ASC") {
            $order = $this->data["Event"]["order"];
        } else {
            $order = 'Field(Event.id, ' . implode(',', $allEventGreterThenNow) . ')';
        }

        $this->Event->unBindModel(array('hasMany' => array("EventCategory", "EventVibe"), 'belongsTo' => array('User')), false);

        $this->Event->bindModel(array('hasOne' => array("EventCategory", "EventVibe")), false);
  

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
                //die("here");
            }
        }
        //pr($this->data);
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
            $cate_vibe = array('OR' => array($conditions_vibe, $category_condition));
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

                if (!empty($zip_detail)) {
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

                $conditions = array_merge($conditions, array("Event.event_from IN" => array("facebook", "ALH", "eventful")));
                $myconditions = "";
                $ev = "all";
            }
        } elseif (isset($this->data["FacebookShow"])) {
            $conditions = array_merge($conditions, array("Event.event_from" => "facebook"));
            $myconditions = "";
            $ev = "FB";
        } else {
            $conditions = array_merge($conditions, array("Event.event_from IN" => array("facebook", "ALH", "eventful")));
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
                $zip_city = $ipDetail->regionName;
                $z = $ipDetail->regionName;
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
            $TopEventZip_city = "United States";
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
            //pr($this->data["Event"]["date"]);
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



            $cEvent = $eV->call('events/search', $evArgs);

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


            $catList = $eV->call('categories/list');
            $this->set('catList', json_decode(json_encode($catList), true));
        } else {
            die("<strong>Error logging into Eventful API</strong>");
        }
    }

    /* @Created:     19-Sept-2014
     * @Method :     selectSingleEvent
     * @Author:      Prateek Jadhav
     * @Modified :   ---
     * @Purpose:     for select single event
     * @Param:       none
     * @Return:      none  
     */

    public function selectSingleEvent($event_id = NULL, $event_title = NULL) {
        $this->layout = FALSE;
        $this->autoRender = FALSE;
        if ($event_id && $event_title) {
            $event_id = base64_decode($event_id);
            $event_title = base64_decode($event_title);
           
            $arr = array($event_id => $event_title);
            $this->Session->delete("CampaignEvent");
            $this->Session->write("CampaignEvent", $arr);
            //$this->Session->delete("CampaignEvent");
            $this->Session->delete("CampaignType");
            $this->Session->write("CampaignType", "single");
            $this->loadModel("EventTemplate");
            $datas = $this->EventTemplate->find("first", array("conditions" => array("EventTemplate.status" => 1, "EventTemplate.type" => 1)));
            $template_id = $datas["EventTemplate"]["id"];
            $this->redirect(array("controller" => "Campaigns", "action" => "chooseTemplate", base64_encode($template_id)));
        }
    }

    /* @Created:     24-Sept-2014
     * @Method :     chooseEventMultiple
     * @Author:      Prateek Jadhav
     * @Modified :   ---
     * @Purpose:     for choose multiple event
     * @Param:       $campaign_id/NULL
     * @Return:      none  
     */

    public function chooseEventMultiple($campaign_id = NULL) {
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

        if ($campaign_id && !empty($campaign_id)) {
            $this->Session->write("campaign_edit", base64_decode($campaign_id));
        } else {
            if ($this->Session->check('campaign_edit')) {
                $this->Session->delete('campaign_edit');
            }
        }
       

        if ($this->Session->check("CampaignEvent")) {
            $readAddedEvent = $this->Session->read("CampaignEvent");
            $readAddedEventKey = array_keys($readAddedEvent);
           
            $EFAddedEvent = $this->Event->find("all", array("conditions" => array("Event.id" => $readAddedEventKey, "Event.event_from" => "eventful"), "fields" => array("Event.ef_event_id"), "recursive" => -1));
           
            if (isset($EFAddedEvent) && !empty($EFAddedEvent)) {
                foreach ($EFAddedEvent as $valKey) {
                    $EFAddedEventId[] = $valKey['Event']['ef_event_id'];
                }

                $this->set("EFAddedEventId", $EFAddedEventId);
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
                $this->request->data["Event"]["limit"] = $existing_search_data["Search"]["limit"];

                if (!empty($existing_search_data["Search"]["FacebookShow"])) {
                    $this->request->data["FacebookShow"] = $existing_search_data["Search"]["FacebookShow"];
                }
                if (!empty($existing_search_data["Search"]["allFacebookShow"])) {
                    $this->request->data["allFacebookShow"] = $existing_search_data["Search"]["allFacebookShow"];
                }
                if (!empty($existing_search_data["Search"]["myFacebookShow"])) {
                    $this->request->data["myFacebookShow"] = $existing_search_data["Search"]["myFacebookShow"];
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


        if (isset($this->data["Event"]["order"]) && $this->data["Event"]["order"] != "Event.start_date ASC") {
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
            $cate_vibe = array('OR' => array($conditions_vibe, $category_condition));
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

                if (!empty($zip_detail)) {
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
                $conditions = array_merge($conditions, array("Event.event_from IN" => array("facebook", "ALH", "eventful")));
                $myconditions = "";
                $ev = "all";
            }
        } elseif (isset($this->data["FacebookShow"])) {
            $conditions = array_merge($conditions, array("Event.event_from" => "facebook"));
            $myconditions = "";
            $ev = "FB";
        } else {
            $conditions = array_merge($conditions, array("Event.event_from IN" => array("facebook", "ALH", "eventful")));
            $myconditions = "";
            $ev = "all";
        }

        $this->set("event_show", $ev);
        if (isset($this->data["Event"]["limit"]) && !empty($this->data["Event"]["limit"])) {
            
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

        $recommEvents = $this->Event->find('all', array("conditions" => $rec_conditions, 'order' => 'Field(Event.id, ' . implode(',', $allEventGreterThenNow) . ')', "limit" => 4, "group" => "Event.id"));
        $this->set('recommEvents', $recommEvents);

        /*         * ******************Recommended Event List End********************** */


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
                $zip_city = $ipDetail->regionName;
                $z = $ipDetail->regionName;
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
            $TopEventZip_city = "United States";
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



            $cEvent = $eV->call('events/search', $evArgs);

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


            $catList = $eV->call('categories/list');
            $this->set('catList', json_decode(json_encode($catList), true));
        } else {
            die("<strong>Error logging into Eventful API</strong>");
        }
    }

    /* @Created:     24-Sept-2014
     * @Method :     selectEventMultiple
     * @Author:      Prateek Jadhav
     * @Modified :   ---
     * @Purpose:     for select multiple event
     * @Param:       $id, $title
     * @Return:      none  
     */

    public function selectMultipleEvent($id = NULL, $title = NULL, $class = NULL) {
        $this->layout = FALSE;
        $title = base64_decode($title);
        if ($id && $title) {
            $event_id = $id;
            $event_title = $title;
            $arr = array($event_id => $event_title);

            if ($this->Session->check("CampaignEvent")) {

                $chk_arr = $this->Session->read("CampaignEvent");

                if (isset($chk_arr[$event_id])) {
                    unset($chk_arr[$event_id]);
                } else {
                    $chk_arr[$event_id]["title"] = $title;
                    $chk_arr[$event_id]["class"] = $class;
                }
            } else {
                $chk_arr[$event_id]["title"] = $title;
                $chk_arr[$event_id]["class"] = $class;
            }

            $this->Session->delete("CampaignEvent");
            $this->Session->write("CampaignEvent", $chk_arr);
            $this->Session->delete("CampaignType");
            $this->Session->write("CampaignType", "multiple");
        }
    }

    /*     * @Created:     15-Oct-2014
     * @Method :     reportCampaign
     * @Author:      Arjun Dev
     * @Modified :   ---
     * @Purpose:     For view detail of campaign
     * @Param:       $campaign_id
     * @Return:      none
     */

    public function reportCampaign($campaign_id = NULL) {
        $this->layout = "front/home";
        $this->loadModel("CampaignEmail");
        $campaign_id = base64_decode($campaign_id);
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

    /*     * @Created:     15-Oct-2014
     * @Method :     replicate
     * @Author:      Arjun Dev
     * @Modified :   ---
     * @Purpose:     For view detail of campaign
     * @Param:       $campaign_id
     * @Return:      none
     */

    public function replicateEvent($campaign_id = NULL) {
        $this->layout = "front/home";
        $this->loadModel("CampaignEmail");
        $campaign_id = base64_decode($campaign_id);
        $campaign = $this->Campaign->findById($campaign_id);

        $campaignNew["Campaign"]["user_id"] = $campaign["Campaign"]["user_id"];
        $campaignNew["Campaign"]["title"] = $campaign["Campaign"]["title"];
        $campaignNew["Campaign"]["subject_line"] = $campaign["Campaign"]["subject_line"] . " - Copy";
        $campaignNew["Campaign"]["from_name"] = $campaign["Campaign"]["from_name"];
        $campaignNew["Campaign"]["reply_email"] = $campaign["Campaign"]["reply_email"];
        $campaignNew["Campaign"]["from_email"] = $campaign["Campaign"]["from_email"];
        $campaignNew["Campaign"]["custom_email"] = $campaign["Campaign"]["custom_email"];
        $campaignNew["Campaign"]["html"] = $campaign["Campaign"]["html"];


        $this->Campaign->save($campaignNew);
        $campaign_id_latest = $this->Campaign->getLastInsertID();
        $this->redirect(array('controller' => 'Campaigns', 'action' => 'designTemplate', base64_encode($campaign_id_latest)));
       
        //$this->set("campaign", $campaign);
    }

    /* @Created:    25-Sep-2014
     * @Method :    selectEventfulSingleEvent
     * @Author:     Arjun Dev
     * @Modified :   ---
     * @Purpose:    store eventful event to ALH database 
     * @Param:       
     * @Return:      none
     */

    public function selectEventfulSingleEvent($ef_event_id = NULL) {
        $this->layout = FALSE;
        $this->autoRender = FALSE;
        $this->loadModel("Event");
        $this->loadModel("User");
        $this->loadModel("EventDate");
        $this->loadModel("Category");
        $this->loadModel("EventCategory");

        if ($ef_event_id) {
            // check data


            $eventetails = $this->Event->find("first", array("conditions" => array("Event.ef_event_id" => $ef_event_id)));

            if (empty($eventetails['Event'])) {

                require_once "Eventful_new.php";

                $AppKey = "qGt8LCwr2CVprzVW";

                $eV = new Eventful($AppKey);


                $evLogin = $eV->login('sachint', 'sachint');
                if ($evLogin) {

                    $evArgs = array('id' => $ef_event_id);
                    $cEvent = $eV->call('events/get', $evArgs);

                 

                    $er = json_decode(json_encode($cEvent), true);

                    $evnt_id = $er['@attributes']['id'];
                    //  echo $stTime = date("Y-m-d",strtotime($er['start_time']));
                    //  echo $enTime = date("Y-m-d",strtotime($er['stop_time']));
                    //  if($enTime>$stTime)

                    if (isset($er["images"]["image"][0]["medium"]["url"])) {
                        $img = $er["images"]["image"][0]["medium"]["url"];
                    } elseif (isset($er["images"]["image"][0]["url"])) {
                        $img = $er["images"]["image"][0]["url"];
                    } elseif (isset($er["images"]["image"]["medium"]["url"])) {
                        $img = $er["images"]["image"]["medium"]["url"];
                    } elseif (isset($er["images"]["image"]["url"])) {
                        $img = $er["images"]["image"]["url"];
                    } elseif (isset($er["image"]["medium"]["url"])) {
                        $img = $er["image"]["medium"]["url"];
                    } elseif (isset($er["image"]["url"])) {
                        $img = $er["image"]["url"];
                    } else {
                        $img = '/no_image.jpeg';
                    }
                    if (!empty($er["description"]) && $er["description"] != "Array") {
                        $desc = $er["description"];
                    } else {
                        $desc = '';
                    }

                    if (is_string($er["postal_code"])) {
                        $zipCode = $er["postal_code"];
                    } else {
                        $zipCode = '';
                    }
                    if (isset($er['stop_time'])) {
                        $stopTime = $er['stop_time'];
                    } else {
                        $stopTime = '';
                    }

                    $event["Event"]["user_id"] = AuthComponent::user("id");
                    $event["Event"]["title"] = $er["title"];
                    $event["Event"]["image_name"] = $img;
                    $event["Event"]["event_type"] = '1';
                    $event["Event"]["option_to_show"] = '3';
                    $event["Event"]["start_date"] = $er["start_time"];
                    $event["Event"]["event_location"] = $er["city"];
                    $event["Event"]["city"] = $er["city"];
                    $event["Event"]["event_address"] = $er["venue_name"] . ' ' . $er["address"];
                    $event["Event"]["cant_find_address"] = $er["venue_name"] . ' ' . $er["address"];
                    $event["Event"]["cant_find_city"] = $er["city"];
                    $event["Event"]["cant_find_state"] = $er["region"];
                    $event["Event"]["cant_find_zip_code"] = $zipCode;
                    $event["Event"]["ticket_vendor_url"] = $er["url"];
                    $event["Event"]["website_url"] = $er["url"];
                    $event["Event"]["facebook_url"] = $er["url"];
                    $event["Event"]["flyer_image"] = $img;

                    $event["Event"]["short_description"] = $desc;
                    $event["Event"]["description"] = $desc;
                    $event["Event"]["status"] = '1';
                    $event["Event"]["event_from"] = "eventful";
                    $event["Event"]["ef_event_id"] = $evnt_id;
                    $event["Event"]["end_date"] = $stopTime;
                 
                    $this->Event->create();
                    if ($this->Event->save($event)) {
                        $event_id = $this->Event->getLastInsertID();
                        if (isset($er['stop_time']) && !empty($er['stop_time'])) {
                            $stTime = date("Y-m-d", strtotime($er['start_time']));
                            $enTime = date("Y-m-d", strtotime($er['stop_time']));
                            $stTimeT = date("h:iA", strtotime($er['start_time']));
                            $enTimeT = date("h:iA", strtotime($er['stop_time']));
                            $diff = date_diff(date_create($stTime), date_create($enTime));
                            $diffrence = $diff->format("%a");
                            for ($p = 0; $p <= $diffrence; $p++) {

                                $dateStore = date("Y-m-d", strtotime($er['start_time'] . " +" . $p . " day"));
                                $eventdate["EventDate"]["id"] = "";
                                $eventdate["EventDate"]["event_id"] = $event_id;
                                $eventdate["EventDate"]["date"] = $dateStore;
                                $eventdate["EventDate"]["start_time"] = $stTimeT;
                                $eventdate["EventDate"]["end_time"] = $enTimeT;
                                $this->EventDate->save($eventdate);
                            }
                        } else {
                            $eventdate["EventDate"]["id"] = "";
                            $eventdate["EventDate"]["event_id"] = $event_id;
                            $eventdate["EventDate"]["date"] = $er["start_time"];
                            $eventdate["EventDate"]["start_time"] = date("h:iA", strtotime($er['start_time']));
                            $this->EventDate->save($eventdate);
                        }
                        // delete all old values
                        $this->EventCategory->deleteAll(array("EventCategory.event_id" => $event_id));

                        if (isset($er['categories']['category'][0]['id'])) {
                            foreach ($er['categories']['category'] as $CatList) {
                                $Catid = $this->Category->find("first", array("conditions" => array("Category.eventfulID " => $CatList['id'])));
                                $ListCat = $Catid['Category']['id'];
                                if ($ListCat != "") {
                                    $save_tc["EventCategory"]["id"] = "";
                                    $save_tc["EventCategory"]["category_id"] = $ListCat;
                                    $save_tc["EventCategory"]["event_id"] = $event_id;
                                    $this->EventCategory->save($save_tc);
                                }
                            }
                        } else {

                            $Catids = $this->Category->find("first", array("conditions" => array("Category.eventfulID" => $er['categories']['category']['id'])));
                            $ListCats = $Catids['Category']['id'];
                            if ($ListCats != "") {
                                $save_tc["EventCategory"]["id"] = "";
                                $save_tc["EventCategory"]["category_id"] = $ListCats;
                                $save_tc["EventCategory"]["event_id"] = $event_id;
                                $this->EventCategory->save($save_tc);
                            }
                        }


                        if ($event_id && $er["title"]) {

                            $event_title = $er["title"];

                            $arr = array($event_id => $event_title);
                            $this->Session->delete("CampaignEvent");
                            $this->Session->write("CampaignEvent", $arr);
                            $this->Session->delete("CampaignType");
                            $this->Session->write("CampaignType", "single");
                            $this->loadModel("EventTemplate");
                            $datas = $this->EventTemplate->find("first", array("conditions" => array("EventTemplate.status" => 1, "EventTemplate.type" => 1)));
                            $template_id = $datas["EventTemplate"]["id"];
                            $this->redirect(array("controller" => "Campaigns", "action" => "chooseTemplate", base64_encode($template_id)));
                        }

                        return 1;
                    }
                } else {
                    return 0;
                }
            } else {

                if ($eventetails['Event']['id'] && $eventetails['Event']["title"]) {

                    $event_titles = $eventetails['Event']["title"];
                    $event_ids = $eventetails['Event']['id'];

                    $arr = array($event_ids => $event_titles);
                    $this->Session->delete("CampaignEvent");
                    $this->Session->write("CampaignEvent", $arr);
                    $this->Session->delete("CampaignType");
                    $this->Session->write("CampaignType", "single");
                    $this->loadModel("EventTemplate");
                    // echo "dsfgds"; die;
                    $datas = $this->EventTemplate->find("first", array("conditions" => array("EventTemplate.status" => 1, "EventTemplate.type" => 1)));
                    $template_id = $datas["EventTemplate"]["id"];
                    $this->redirect(array("controller" => "Campaigns", "action" => "chooseTemplate", base64_encode($template_id)));
                }
                return 2;
            }
        } else {
            return 0;
        }
    }

    /* @Created:    25-Sep-2014
     * @Method :    selectMultipleEFEvent
     * @Author:     Arjun Dev
     * @Modified :   ---
     * @Purpose:    store eventful event to ALH database 
     * @Param:       
     * @Return:      none
     */

    public function selectMultipleEFEvent($ef_event_id = NULL, $class = NULL) {
        $this->layout = FALSE;
        $this->autoRender = FALSE;
        $this->loadModel("Event");
        $this->loadModel("User");
        $this->loadModel("EventDate");
        $this->loadModel("Category");
        $this->loadModel("EventCategory");

        if ($ef_event_id) {
            // check data


            $eventetails = $this->Event->find("first", array("conditions" => array("Event.ef_event_id LIKE" => trim($ef_event_id))));
           
            if (empty($eventetails['Event'])) {

                require_once "Eventful_new.php";

                $AppKey = "qGt8LCwr2CVprzVW";

                $eV = new Eventful($AppKey);


                $evLogin = $eV->login('sachint', 'sachint');
                if ($evLogin) {

                    $evArgs = array('id' => $ef_event_id);
                    $cEvent = $eV->call('events/get', $evArgs);

                

                    $er = json_decode(json_encode($cEvent), true);
                   
                    $evnt_id = $er['@attributes']['id'];
                    //  echo $stTime = date("Y-m-d",strtotime($er['start_time']));
                    //  echo $enTime = date("Y-m-d",strtotime($er['stop_time']));
                    //  if($enTime>$stTime)
                    //  die;

                    if (isset($er["images"]["image"][0]["medium"]["url"])) {
                        $img = $er["images"]["image"][0]["medium"]["url"];
                    } elseif (isset($er["images"]["image"][0]["url"])) {
                        $img = $er["images"]["image"][0]["url"];
                    } elseif (isset($er["images"]["image"]["medium"]["url"])) {
                        $img = $er["images"]["image"]["medium"]["url"];
                    } elseif (isset($er["images"]["image"]["url"])) {
                        $img = $er["images"]["image"]["url"];
                    } elseif (isset($er["image"]["medium"]["url"])) {
                        $img = $er["image"]["medium"]["url"];
                    } elseif (isset($er["image"]["url"])) {
                        $img = $er["image"]["url"];
                    } else {
                        $img = '/no_image.jpeg';
                    }

                    if (!empty($er["description"]) && $er["description"] != "Array") {
                        $desc = $er["description"];
                    } else {
                        $desc = '';
                    }

                    if (is_string($er["postal_code"])) {
                        $zipCode = $er["postal_code"];
                    } else {
                        $zipCode = '';
                    }
                    if (isset($er['stop_time'])) {
                        $stopTime = $er['stop_time'];
                    } else {
                        $stopTime = '';
                    }

                    $event["Event"]["user_id"] = AuthComponent::user("id");
                    $event["Event"]["title"] = $er["title"];
                    $event["Event"]["image_name"] = $img;
                    $event["Event"]["event_type"] = '1';
                    $event["Event"]["option_to_show"] = '3';
                    $event["Event"]["start_date"] = $er["start_time"];
                    $event["Event"]["event_location"] = $er["city"];
                    $event["Event"]["city"] = $er["city"];
                    $event["Event"]["event_address"] = $er["venue_name"] . ' ' . $er["address"];
                    $event["Event"]["cant_find_address"] = $er["venue_name"] . ' ' . $er["address"];
                    $event["Event"]["cant_find_city"] = $er["city"];
                    $event["Event"]["cant_find_state"] = $er["region"];
                    $event["Event"]["cant_find_zip_code"] = $zipCode;
                    $event["Event"]["ticket_vendor_url"] = $er["url"];
                    $event["Event"]["website_url"] = $er["url"];
                    $event["Event"]["facebook_url"] = $er["url"];
                    $event["Event"]["flyer_image"] = $img;

                    $event["Event"]["short_description"] = $desc;
                    $event["Event"]["description"] = $desc;
                    $event["Event"]["status"] = '1';
                    $event["Event"]["event_from"] = "eventful";
                    $event["Event"]["ef_event_id"] = $evnt_id;
                    $event["Event"]["end_date"] = $stopTime;
                   
                    $this->Event->create();
                    if ($this->Event->save($event)) {
                        $event_id = $this->Event->getLastInsertID();
                        if (isset($er['stop_time']) && !empty($er['stop_time'])) {
                            $stTime = date("Y-m-d", strtotime($er['start_time']));
                            $enTime = date("Y-m-d", strtotime($er['stop_time']));
                            $stTimeT = date("h:iA", strtotime($er['start_time']));
                            $enTimeT = date("h:iA", strtotime($er['stop_time']));
                            $diff = date_diff(date_create($stTime), date_create($enTime));
                            $diffrence = $diff->format("%a");
                            for ($p = 0; $p <= $diffrence; $p++) {

                                $dateStore = date("Y-m-d", strtotime($er['start_time'] . " +" . $p . " day"));
                                $eventdate["EventDate"]["id"] = "";
                                $eventdate["EventDate"]["event_id"] = $event_id;
                                $eventdate["EventDate"]["date"] = $dateStore;
                                $eventdate["EventDate"]["start_time"] = $stTimeT;
                                $eventdate["EventDate"]["end_time"] = $enTimeT;
                                $this->EventDate->save($eventdate);
                            }
                        } else {
                            $eventdate["EventDate"]["id"] = "";
                            $eventdate["EventDate"]["event_id"] = $event_id;
                            $eventdate["EventDate"]["date"] = $er["start_time"];
                            $eventdate["EventDate"]["start_time"] = date("h:iA", strtotime($er['start_time']));
                            $this->EventDate->save($eventdate);
                        }
                        // delete all old values
                        $this->EventCategory->deleteAll(array("EventCategory.event_id" => $event_id));

                        if (isset($er['categories']['category'][0]['id'])) {
                            foreach ($er['categories']['category'] as $CatList) {
                                $Catid = $this->Category->find("first", array("conditions" => array("Category.eventfulID " => $CatList['id'])));
                                $ListCat = $Catid['Category']['id'];
                                if ($ListCat != "") {
                                    $save_tc["EventCategory"]["id"] = "";
                                    $save_tc["EventCategory"]["category_id"] = $ListCat;
                                    $save_tc["EventCategory"]["event_id"] = $event_id;
                                    $this->EventCategory->save($save_tc);
                                }
                            }
                        } else {

                            $Catids = $this->Category->find("first", array("conditions" => array("Category.eventfulID" => $er['categories']['category']['id'])));
                            $ListCats = $Catids['Category']['id'];
                            if ($ListCats != "") {
                                $save_tc["EventCategory"]["id"] = "";
                                $save_tc["EventCategory"]["category_id"] = $ListCats;
                                $save_tc["EventCategory"]["event_id"] = $event_id;
                                $this->EventCategory->save($save_tc);
                            }
                        }


                        $this->redirect(array("controller" => "Campaigns", "action" => "selectMultipleEvent", $event_id, base64_encode($er["title"]), $class));

                        return 1;
                    }
                } else {
                    return 0;
                }
            } else {

                $this->redirect(array("controller" => "Campaigns", "action" => "selectMultipleEvent", $eventetails['Event']['id'], base64_encode($eventetails['Event']["title"]), $class));

                return 2;
            }
        } else {
            return 0;
        }
    }

}
