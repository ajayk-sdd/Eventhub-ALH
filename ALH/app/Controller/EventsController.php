<?php

class EventsController extends AppController {

    var $helpers = array('Html', 'Form', 'Cache');
    public $components = array('Email', 'Upload', 'Cookie');
    public $paginate = array(
        'limit' => 10
    );

    /** @Created:    05-May-2014
     * @Method :     beforeFilter
     * @Author:      Sachin Thakur
     * @Modified :   ---
     * @Purpose:     Rendered before any function
     * @Param:       none
     * @Return:      none
     */
    function beforeFilter() {
       
        parent::beforeFilter();
        $this->Auth->allow('dayOfMounth', 'findNoOfDay', 'findWeekDay', 'all', 'findVenue', 'fetchCategoryFromEventful', 'reviewEvent', 'calendar', 'viewEvent', 'eventfulEvents', 'ticketGiveaway', 'ticketGiveawayDetail', 'ticketGiveawayThankYou', 'addattend', 'addattendmult', 'viewEventfulEvent', 'confirm', 'activate', 'totalCount', 'marketing', 'email');
    }

    /** @Created:    27-June-2014
     * @Method :     getspecifyaddress
     * @Author:      Neha jain
     * @Modified :   ---
     * @Purpose:     to get address of specific user and send to ajax function

     */
    function getspecifyaddress($specify, $id = null) {

        $this->layout = false;
        $this->autoRender = null;
        $data = array();
        $this->loadModel('Address');
        if ($id == null)
            $id = $this->Auth->User('id');
        $AddressData = $this->Address->find('first', array('conditions' => array('Address.user_id' => $id, 'Address.name' => $specify), 'fields' => array('billing_address_1', 'billing_address_2', 'city', 'state', 'zip')));
        if (!empty($AddressData)) {
            $address1 = $AddressData['Address']['billing_address_1'];
            $address2 = $AddressData['Address']['billing_address_2'];
            $city = $AddressData['Address']['city'];
            $state = $AddressData['Address']['state'];
            $zip = $AddressData['Address']['zip'];
            $data['address1'] = $address1;
            $data['address2'] = $address2;
            $data['city'] = $city;
            $data['state'] = $state;
            $data['zip'] = $zip;
        }
        return json_encode($data);
    }

    /* s@Created:    05-May-2014
     * @Method :     admin_index
     * @Author:      Sachin Thakur
     * @Modified :   ---
     * @Purpose:     
     * @Param:       $event_id
     * @Return:      none
     */

    function createEvent($id = null, $type = null) {

        $this->layout = 'front/home';
        $this->set('title_for_layout', 'ALIST Hub :: Create Event');
        $this->loadModel('Category');
        $this->loadModel('Vibe');
        $this->loadModel('Zip');
        $this->loadModel('Address');
        $ipDetail = $this->ipDetail();
        $this->set("latitude", $ipDetail->latitude);
        $this->set("longitude", $ipDetail->longitude);

        $this->loadModel('User');
        $this->set("user_detail", $this->User->find('first', array("conditions" => array("User.id" => AuthComponent::user("id")))));

        $this->set("zip", $this->Zip->find("list", array("group" => array("Zip.state"), "fields" => array("Zip.state", "Zip.state_name"))));
        #Fetch Categories
        $categories = $this->Category->find('list', array("conditions" => array("Category.status" => 1), "fields" => array("Category.id", "Category.name"), "order" => "Category.name ASC"));
        #Fetch Vibes
        $vibes = $this->Vibe->find('list', array("conditions" => array("Vibe.status" => 1), "fields" => array("Vibe.id", "Vibe.name"), "order" => "Vibe.name ASC"));
        $this->set(compact('categories', 'vibes'));
        //$id = $this->Auth->User('id');
        $address1 = '';
        $address2 = '';
        if ($this->data) {
            
            if (isset($this->data["Event"]["video"]) && !empty($this->data["Event"]["video"])) {
                $test_string = $this->data["Event"]["video"];
                $ts = explode("v=", $test_string);
                if (isset($ts) && !empty($ts))
                    $this->request->data["Event"]["video"] = @$ts[1];
            }
            if (isset($this->data["Event"]["id"]) && !empty($this->data["Event"]["id"])) {
                
            } else {
                $this->request->data["Event"]["user_id"] = AuthComponent::user("id");
            }

            if (!empty($this->request->data["Event"]["specify"])) {
                $specify = $this->request->data["Event"]["specify"];
                $AddressData = $this->Address->find('first', array('conditions' => array('Address.user_id' => $this->Auth->User('id'), 'Address.name' => $specify), 'fields' => array('billing_address_1', 'billing_address_2')));
                $address1 = $AddressData['Address']['billing_address_1'];
                $address2 = $AddressData['Address']['billing_address_2'];
              
                $this->request->data["Event"]["event_address"] = $address1 . "," . $address2;
            } else {
                $this->request->data["Event"]["specify"] = $this->data["Event"]["address_name"];
                $this->request->data["Event"]["event_address"] = $this->data["Event"]["event_address1"] . "," . $this->data["Event"]["event_address2"];
            }
            if (!isset($this->data["Event"]["id"]) && !empty($this->data["Event"]["id"]))
                $this->request->data["Event"]["user_id"] = $this->Auth->User('id');
            $tmp = $this->data;

           
            #save flyer image
            if (!empty($this->data['Event']['flyer_image']['name'])) {
                $imgNameExt = pathinfo($this->data['Event']['flyer_image']['name']);
                $ext = $imgNameExt['extension'];
                $ext = strtolower($ext);
                if ($ext == 'jpg' || $ext == 'jpeg' || $ext == 'png' || $ext == 'gif') {
                    $newImgName = 'flyer_image_' . time();
                    $tempFile = $this->data['Event']['flyer_image']['tmp_name'];
                    $destLarge = realpath('../webroot/img/flyerImage/large/') . '/';
                    $destThumb = realpath('../webroot/img/flyerImage/small/') . '/';
                    $destOriginal = realpath('../webroot/img/flyerImage/original/') . '/';
                    $file = $this->data['Event']['flyer_image'];
                    $file['name'] = $imgNameExt['filename'] . '.' . $ext;
                    if ($ext == 'gif') {
                        $result = $this->Upload->upload($file, $destLarge, $newImgName . ".gif", array('type' => 'resizemin', 'size' => array('300', '450')));
                        $result = $this->Upload->upload($file, $destOriginal, $newImgName . ".gif", NULL, array('gif'));
                        $result = $this->Upload->upload($file, $destThumb, $newImgName . ".gif", array('type' => 'resizemin', 'size' => array('120', '120')));
                        $name = $newImgName . ".gif";
                    } else if ($ext == 'png') {
                        $result = $this->Upload->upload($file, $destLarge, $newImgName . ".png", array('type' => 'resizemin', 'size' => array('300', '450')));
                        $result = $this->Upload->upload($file, $destOriginal, $newImgName . ".png", NULL, array('png'));
                        $result = $this->Upload->upload($file, $destThumb, $newImgName . ".png", array('type' => 'resizemin', 'size' => array('120', '120')));
                        $name = $newImgName . ".png";
                    } else if ($ext == 'jpeg') {
                        $result = $this->Upload->upload($file, $destLarge, $newImgName . ".jpeg", array('type' => 'resizemin', 'size' => array('300', '450')));
                        $result = $this->Upload->upload($file, $destOriginal, $newImgName . ".jpeg", NULL, array('jpeg'));
                        $result = $this->Upload->upload($file, $destThumb, $newImgName . ".jpeg", array('type' => 'resizemin', 'size' => array('120', '120')));
                        $name = $newImgName . ".jpeg";
                    } else {
                        $result = $this->Upload->upload($file, $destLarge, $newImgName . ".jpg", array('type' => 'resizemin', 'size' => array('300', '450')));
                        $result = $this->Upload->upload($file, $destOriginal, $newImgName . ".jpg", NULL, array('jpg'));
                        $result = $this->Upload->upload($file, $destThumb, $newImgName . ".jpg", array('type' => 'resizemin', 'size' => array('120', '120')));
                        $name = $newImgName . ".jpg";
                    }
                    $tmp["Event"]["flyer_image"] = $name;
                }
            } else {
                unset($tmp["Event"]["flyer_image"]);
            }
            # now save Event Primary Image
            if (!empty($this->data['Event']['image_name']['name'])) {
                $imgNameExt = pathinfo($this->data['Event']['image_name']['name']);
                $ext = $imgNameExt['extension'];
                $ext = strtolower($ext);
                if ($ext == 'jpg' || $ext == 'jpeg' || $ext == 'png' || $ext == 'gif') {
                    $newImgName = 'EventImage' . time();
                    $tempFile = $this->data['Event']['image_name']['tmp_name'];
                    $destLarge = realpath('../webroot/img/EventImages/large/') . '/';
                    $destThumb = realpath('../webroot/img/EventImages/small/') . '/';
                    $destOriginal = realpath('../webroot/img/EventImages/original/') . '/';
                    $file = $this->data['Event']['image_name'];
                    $file['name'] = $imgNameExt['filename'] . '.' . $ext;
                    if ($ext == 'gif') {
                        $result = $this->Upload->upload($file, $destLarge, $newImgName . ".gif", array('type' => 'resizemin', 'size' => array('300', '450')));
                        $result = $this->Upload->upload($file, $destOriginal, $newImgName . ".gif", NULL, array('gif'));
                        $result = $this->Upload->upload($file, $destThumb, $newImgName . ".gif", array('type' => 'resizemin', 'size' => array('400', '400')));
                        $name = $newImgName . ".gif";
                    } else if ($ext == 'png') {
                        $result = $this->Upload->upload($file, $destLarge, $newImgName . ".png", array('type' => 'resizemin', 'size' => array('300', '450')));
                        $result = $this->Upload->upload($file, $destOriginal, $newImgName . ".png", NULL, array('png'));
                        $result = $this->Upload->upload($file, $destThumb, $newImgName . ".png", array('type' => 'resizemin', 'size' => array('400', '400')));
                        $name = $newImgName . ".png";
                    } else if ($ext == 'jpeg') {
                        $result = $this->Upload->upload($file, $destLarge, $newImgName . ".jpeg", array('type' => 'resizemin', 'size' => array('300', '450')));
                        $result = $this->Upload->upload($file, $destOriginal, $newImgName . ".jpeg", NULL, array('jpeg'));
                        $result = $this->Upload->upload($file, $destThumb, $newImgName . ".jpeg", array('type' => 'resizemin', 'size' => array('400', '400')));
                        $name = $newImgName . ".jpeg";
                    } else {
                        $result = $this->Upload->upload($file, $destLarge, $newImgName . ".jpg", array('type' => 'resizemin', 'size' => array('300', '450')));
                        $result = $this->Upload->upload($file, $destOriginal, $newImgName . ".jpg", NULL, array('jpg'));
                        $result = $this->Upload->upload($file, $destThumb, $newImgName . ".jpg", array('type' => 'resizemin', 'size' => array('400', '400')));
                        $name = $newImgName . ".jpg";
                    }
                    $tmp["Event"]["image_name"] = $name;
                }
            } else {
                unset($tmp["Event"]["image_name"]);
            }

            if (isset($tmp['Event']['option_to_show'][1]) && $tmp['Event']['option_to_show'][1] == 1 && isset($tmp['Event']['option_to_show'][3]) && $tmp['Event']['option_to_show'][3] == 1) {
                $tmp['Event']['option_to_show'] = 3;
            } elseif (isset($tmp['Event']['option_to_show'][1]) && $tmp['Event']['option_to_show'][1] == 1) {
                $tmp['Event']['option_to_show'] = 1;
            } elseif (isset($tmp['Event']['option_to_show'][3]) && $tmp['Event']['option_to_show'][3] == 1) {
                $tmp['Event']['option_to_show'] = 2;
            }

            if (!isset($tmp["Event"]["event_type"]) || $tmp["Event"]["event_type"] == 2 || empty($tmp["Event"]["event_type"])) {
                unset($tmp["Event"]["option_to_show"]);
            }
          
            # now save all data in event table

            if ($this->Event->save($tmp)) {
                #get event_id
                if (isset($tmp["Event"]["id"]) && !empty($tmp["Event"]["id"])) {
                    $event_id = $tmp["Event"]["id"];
                    $this->eventEmail($event_id, "edit-event");
                } else {
                    $event_id = $this->Event->getLastInsertID();
                    $this->eventEmail($event_id, "new-event");
                }
                # save giveaway
                if (!empty($this->data["Giveaway"]["no_of_ticket"])) {
                    $this->loadModel("Giveaway");
                    if (empty($this->data["Giveaway"]["id"])) {
                        $giveaway["Giveaway"]["id"] = "";
                        $giveaway["Giveaway"]["user_id"] = AuthComponent::user("id");
                        $giveaway["Giveaway"]["event_id"] = $event_id;
                    } else {
                        $giveaway["Giveaway"]["id"] = $this->data["Giveaway"]["id"];
                    }
                    $giveaway["Giveaway"]["no_of_ticket"] = $this->data["Giveaway"]["no_of_ticket"];
                    $this->Giveaway->save($giveaway);
                }

                # now save address if new were found
                $save_my_add = $tmp["Event"]["address_name"];
                $already_address = $this->Address->find("first", array("conditions" => array("Address.name LIKE" => "%" . $save_my_add . "%")));
                $already_address["Address"]["name"] = $save_my_add;
                $already_address["Address"]["user_id"] = AuthComponent::user("id");
                $already_address["Address"]["first_name"] = AuthComponent::user("first_name");
                $already_address["Address"]["last_name"] = AuthComponent::user("last_name");
                $already_address["Address"]["billing_address_1"] = $tmp["Event"]["event_address1"];
                $already_address["Address"]["billing_address_2"] = $tmp["Event"]["event_address2"];
                $already_address["Address"]["city"] = $tmp["Event"]["cant_find_city"];
                $already_address["Address"]["state"] = $tmp["Event"]["cant_find_state"];
                $already_address["Address"]["zip"] = $tmp["Event"]["cant_find_zip_code"];
                $already_address["Address"]["country"] = $tmp["Event"]["country"];
                $this->Address->save($already_address);

                # now save Event Other Images
                if (!empty($this->data['EventImage']['image_name'][0]['name'])) {//pr($this->data['EventImage']['image_name']);die;
                    $this->loadModel('EventImage');
                    for ($i = 0; $i < count($this->data['EventImage']['image_name']); $i++) {

                        $this->EventImage->create();
                        $imgNameExt = pathinfo($this->data['EventImage']['image_name'][$i]['name']);
                        $ext = $imgNameExt['extension'];
                        $ext = strtolower($ext);
                        if ($ext == 'jpg' || $ext == 'jpeg' || $ext == 'png' || $ext == 'gif') {
                            $newImgName = "Secondary_" . microtime();
                            $tempFile = $this->data['EventImage']['image_name'][$i]['tmp_name'];
                            $destLarge = realpath('../webroot/img/EventImages/large/') . '/';
                            $destThumb = realpath('../webroot/img/EventImages/small/') . '/';
                            $destOriginal = realpath('../webroot/img/EventImages/original/') . '/';
                            $file = $this->data['EventImage']['image_name'][$i];
                            $file['name'] = $imgNameExt['filename'] . '.' . $ext;
                            if ($ext == 'gif') {
                                $result = $this->Upload->upload($file, $destLarge, $newImgName . ".gif", array('type' => 'resizemin', 'size' => array('300', '450')));
                                $result = $this->Upload->upload($file, $destOriginal, $newImgName . ".gif", NULL, array('gif'));
                                $result = $this->Upload->upload($file, $destThumb, $newImgName . ".gif", array('type' => 'resizemin', 'size' => array('400', '400')));
                                $name = $newImgName . ".gif";
                            } else if ($ext == 'png') {
                                $result = $this->Upload->upload($file, $destLarge, $newImgName . ".png", array('type' => 'resizemin', 'size' => array('300', '450')));
                                $result = $this->Upload->upload($file, $destOriginal, $newImgName . ".png", NULL, array('png'));
                                $result = $this->Upload->upload($file, $destThumb, $newImgName . ".png", array('type' => 'resizemin', 'size' => array('400', '400')));
                                $name = $newImgName . ".png";
                            } else if ($ext == 'jpeg') {
                                $result = $this->Upload->upload($file, $destLarge, $newImgName . ".jpeg", array('type' => 'resizemin', 'size' => array('300', '450')));
                                $result = $this->Upload->upload($file, $destOriginal, $newImgName . ".jpeg", NULL, array('jpeg'));
                                $result = $this->Upload->upload($file, $destThumb, $newImgName . ".jpeg", array('type' => 'resizemin', 'size' => array('400', '400')));
                                $name = $newImgName . ".jpeg";
                            } else {
                                $result = $this->Upload->upload($file, $destLarge, $newImgName . ".jpg", array('type' => 'resizemin', 'size' => array('300', '450')));
                                $result = $this->Upload->upload($file, $destOriginal, $newImgName . ".jpg", NULL, array('jpg'));
                                $result = $this->Upload->upload($file, $destThumb, $newImgName . ".jpg", array('type' => 'resizemin', 'size' => array('400', '400')));
                                $name = $newImgName . ".jpg";
                            }
                        }
                        $EventImages["EventImage"]["image_name"] = $name;
                        $EventImages["EventImage"]["event_id"] = $event_id;
                        $this->EventImage->save($EventImages);
                    }
                }

                # now save TicketPrices
                #Save data in event edited user
                $this->loadModel('EventEditedUser');
                $editedUsers = explode(',', $tmp['EventEditedUser']['user_email']);
                foreach ($editedUsers as $eUsers) {
                    $this->EventEditedUser->create();
                    $save_eu["EventEditedUser"]["user_email"] = $eUsers;
                    $save_eu["EventEditedUser"]["event_id"] = $event_id;
                    $this->EventEditedUser->save($save_eu);
                }
                #Save Data in Event Dates Table
                $this->loadModel("EventDate");
                $Eventdate = $tmp["EventDate"]["start_date"];
               
                $Eventstarttime = $tmp["EventDate"]["start_time"];
                $Eventendtime = $tmp["EventDate"]["end_time"];
              
                if (isset($this->params['data']['Event']['id']) && !empty($this->params['data']['Event']['id'])) {

                    $this->EventDate->deleteAll(array('EventDate.event_id' => $this->params['data']['Event']['id']));
                }
              
                if ($tmp["Event"]["recurring_or_not"] == 0) {
                    sort($Eventdate);
                }
              
                for ($i = 0; $i < count($Eventdate); $i++) {
                    $this->EventDate->create();
                    $tmp_date["EventDate"]["event_id"] = $event_id;
                    $tmp_date['EventDate']['date'] = $Eventdate[$i];
                    $tmp_date['EventDate']['start_time'] = $Eventstarttime[$i];
                    $tmp_date['EventDate']['end_time'] = $Eventendtime[$i];


                    $this->EventDate->save($tmp_date);
                }
               
                $dateExtra[] = '';
                $dateExtra['rec_mode'] .= $tmp["Event"]["recurring_type"];
                if ($tmp["Event"]["recurring_type"] == "d") {
                    $dateExtra['daily_days'] .= $tmp["Event"]["daily_days"];
                } elseif ($tmp["Event"]["recurring_type"] == "w") {
                    $dateExtra['rept_days'] .= $tmp["Event"]["weekRepeatDay"];
                } elseif ($tmp["Event"]["recurring_type"] == "m") {
                    $dateExtra['month_mode'] .= $_POST['month_mode'];
                    if ($_POST['month_mode'] == "mode1") {
                        $dateExtra['month_day1'] .= $_POST['month_day1'];
                    } else {
                        $dateExtra['monthly_period'] .= $_POST['monthly_period'];
                        $dateExtra['monthly_pattern_day'] .= $_POST['monthly_pattern_day'];
                    }
                }

                $date_extra = serialize($dateExtra);

                $EventdateReverse = array_reverse($Eventdate);
               
                $this->Event->id = $event_id;
                $this->Event->saveField('start_date', $Eventdate[0]);
                $this->Event->saveField('end_date', $EventdateReverse[0]);
                $this->Event->saveField('edate_extra', $date_extra);

                #End Save Data in Event Date Table 
                # now save category
                $EventCategory = $tmp["EventCategory"];
               
                $this->loadModel("EventCategory");
                // delete all old values
                $this->EventCategory->deleteAll(array("EventCategory.event_id" => $event_id));
                foreach ($EventCategory as $tc) {
                 

                    if ($tc != 0) {
                        $save_tc["EventCategory"]["id"] = "";
                        $save_tc["EventCategory"]["category_id"] = $tc;
                        $save_tc["EventCategory"]["event_id"] = $event_id;
                        $this->EventCategory->save($save_tc);
                    }
                }
                # now save vibes
                # now save ticket price

                $this->loadModel("TicketPrice");
                $cnt = $this->TicketPrice->find('count', array('conditions' => array('TicketPrice.event_id' => $event_id)));
                if ($cnt != 0) {
                    $this->TicketPrice->deleteAll(array('TicketPrice.event_id' => $event_id));
                }
                $TicketPrice = $tmp["TicketPrice"];
                for ($i = 0; $i < count($TicketPrice['ticket_label']); $i++) {

                    # now save ticket price

                    $this->TicketPrice->create();
                    $save_tp["TicketPrice"]["ticket_label"] = $TicketPrice['ticket_label'][$i];
                    $save_tp["TicketPrice"]["ticket_price"] = $TicketPrice['ticket_price'][$i];
                    $save_tp["TicketPrice"]["event_id"] = $event_id;
                    $this->TicketPrice->save($save_tp);
                }

                # now save ticket price


                $EventVibe = $tmp["EventVibe"]; #pr($EventVibe);die;
                $this->loadModel("EventVibe");
                $this->EventVibe->deleteAll(array("EventVibe.event_id" => $event_id));
                foreach ($EventVibe as $ev) {

                    if ($ev != 0) {
                        $save_ev["EventVibe"]["id"] = "";
                        $save_ev["EventVibe"]["vibe_id"] = $ev;
                        $save_ev["EventVibe"]["event_id"] = $event_id;
                        $this->EventVibe->save($save_ev);
                       
                    }
                }

                $userInfoName = explode(" ", $tmp["Event"]['main_info_name']);
                $UserFname = array_shift($userInfoName);
                $UserLname = implode(" ", $userInfoName);

                $userdata["User"]["id"] = AuthComponent::user("id");
                $userdata["User"]["first_name"] = $UserFname;
                $userdata["User"]["last_name"] = $UserLname;
                $userdata["User"]["phone_no"] = $tmp["Event"]['main_info_phone_no'];
              
                $this->User->save($userdata);

                if ($this->Session->check('Campaign_Type')) {
                    $this->Session->delete('Campaign_Type');
                    $this->redirect(array("controller" => "Campaigns", "action" => "preview"));
                }
                $this->Session->setflash("Event added", "default", array("class" => "green"));

                if (isset($this->data["review"]))
                    $this->redirect(array("controller" => "Events", "action" => "reviewEvent", base64_encode($event_id)));
                else
                    $this->redirect(array("controller" => "Events", "action" => "reviewEvent", base64_encode($event_id)));
            }
        }
        if ($id) {
            if ($type) {
                $this->Session->write("Campaign_Type", base64_decode($type));
            }
            $id = base64_decode($id);
            $event_detail = $this->Event->findById($id);
            $this->request->data = $event_detail;
            if (!empty($event_detail["Event"]["cant_find_zip_code"])) {
                $zipcode = $this->Zip->findByZip($event_detail["Event"]["cant_find_zip_code"]);
                $this->set("zipcode", $zipcode);
            }
            $this->request->data["Event"]["video"] = "http://www.youtube.com/watch?v=" . $this->data["Event"]["video"];
            $save_add = $this->Address->find("list", array("conditions" => array("Address.user_id" => $event_detail["Event"]["user_id"]), "fields" => array("Address.name", "Address.name")));
            $this->set("saved_address", $save_add);

            if (AuthComponent::user("role_id") != 1) {
                if (AuthComponent::user("id") != $event_detail["Event"]["user_id"]) {
                    $this->redirect(array("controller" => "Users", "action" => "index"));
                }
            }
        } else {
            $save_add = $this->Address->find("list", array("conditions" => array("Address.user_id" => AuthComponent::user("id")), "fields" => array("Address.name", "Address.name")));
            $this->set("saved_address", $save_add);
        }
    }

    /** @Created:    05-May-2014
     * @Method :     admin_index
     * @Author:      Sachin Thakur
     * @Modified :   06-Jun-2014(Prateek Jadahv)
     * @Purpose:     to review events
     * @Param:       $event_id
     * @Return:      none
     */
    function reviewEvent($event_id = null) {
        $this->layout = "front/home";
        $this->loadModel("EventFbattendUser");
        $this->set('title_for_layout', 'ALIST Hub :: Review Event');
        $this->loadModel("Zip");
        if ($event_id) {

            if ($this->Session->read('Auth.User')) {
                $logUser = $this->User->find("first", array("conditions" => array("User.id" => AuthComponent::User("id"))));
                $this->set("logUser", $logUser);
            }
            $event_id = base64_decode($event_id);
            $event = $this->Event->find("first", array("recursive" => "2", "conditions" => array("Event.id" => $event_id)));
           
            if (!empty($event)) {
                $zip = $this->Zip->findByZip($event["Event"]["cant_find_zip_code"]);
                $this->set("zip", $zip);
                $this->set("event", $event);
                $edited_user = array();
              
                foreach ($event["EventEditedUser"] as $ev) {
                    $edited_user[] = trim($ev["user_email"]);
                }
                $this->set("edited_user", $edited_user);
            } else {
                $this->Session->setFlash("Data not found", "default", array("class" => "red"));
                $this->redirect(array("controller" => "Events", "action" => "createEvent"));
            }
        } else {
            $this->Session->setFlash("Direct access not allowed", "default", array("class" => "red"));
            $this->redirect(array("controller" => "Events", "action" => "createEvent"));
        }
    }

    /** @Created:    06-Jun-2014
     * @Method :     eventMarketing
     * @Author:      Prateek Jadhav
     * @Modified :   
     * @Purpose:     to market an events
     * @Param:       $event_id
     * @Return:      none
     */
    public function eventMarketing($event_id) {
        $this->layout = FALSE;
        $this->autoRender = FALSE;
        if ($event_id) {
            $event_id = base64_decode($event_id);
            // now update event status
            $data["Event"]["id"] = $event_id;
            $data["Event"]["event_steps"] = 2;
            $this->Event->save($data);
            $this->Session->write("event_id", $event_id);
            $this->redirect(array("controller" => "Services", "action" => "promotionalPackages"));
        } else {
            $this->Session->setFlash("Direct access not allowed", "default", array("class" => "red"));
            $this->redirect(array("controller" => "Users", "action" => "dashboard"));
        }
    }

    /** @Created:    06-Jun-2014
     * @Method :     shareEvent
     * @Author:      Prateek Jadhav
     * @Modified :   
     * @Purpose:     to share an events
     * @Param:       none
     * @Return:      none
     */
    public function shareEvent() {
        $this->layout = "front/home";
        $this->set('title_for_layout', 'ALIST Hub :: Share Event');
        if ($this->Session->check("event_id")) {
            $data["Event"]["id"] = $this->Session->read("event_id");
            $data["Event"]["event_steps"] = 3;
            $data["Event"]["payment"] = $this->Session->read("payment_id");
            $this->Event->save($data);
            $url = $_SERVER["HTTP_HOST"] . "/Events/viewEvent/" . base64_encode($this->Session->read("event_id"));
            $this->set("url", $url);
            $this->loadModel("MyList");
            $this->set("my_list", $this->MyList->find("list", array("conditions" => array("MyList.user_id" => AuthComponent::User("id")), "fields" => array("MyList.id", "MyList.list_name"))));
        } else {
            $this->Session->setFlash("You need to create event first.", "default", array("class" => "red"));
            $this->redirect(array("controller" => "users", "action" => "dashboard"));
        }
    }

    /** @Created:    06-May-2014
     * @Method :     admin_createEvent
     * @Author:      Prateek Jadhav
     * @Modified :   ---
     * @Purpose:     to add and modify events
     * @Param:       none
     * @Return:      none
     */
    public function admin_createEvent($id = null) {

        $this->layout = 'admin/admin';
        $this->set('title_for_layout', 'ALIST Hub :: Create Event');

        $this->loadModel('Category');
        $this->loadModel('Vibe');
        $this->loadModel('Zip');
        $this->loadModel('Address');
        $this->loadModel("User");

        $this->set("users", $this->User->find("list", array("fields" => array("User.id", "User.username"))));
        $ipDetail = $this->ipDetail();
        $this->set("latitude", $ipDetail->latitude);
        $this->set("longitude", $ipDetail->longitude);


        $this->set("zip", $this->Zip->find("list", array("group" => array("Zip.state"), "fields" => array("Zip.state", "Zip.state_name"))));
        #Fetch Categories
        $categories = $this->Category->find('list', array("conditions" => array("Category.status" => 1), "fields" => array("Category.id", "Category.name"), "order" => "Category.name ASC"));
        #Fetch Vibes
        $vibes = $this->Vibe->find('list', array("conditions" => array("Vibe.status" => 1), "fields" => array("Vibe.id", "Vibe.name"), "order" => "Vibe.name ASC"));
        $this->set(compact('categories', 'vibes'));
        //$id = $this->Auth->User('id');
        $address1 = '';
        $address2 = '';
        if ($this->data) {
         
            if (isset($this->data["Event"]["video"]) && !empty($this->data["Event"]["video"])) {
                $test_string = $this->data["Event"]["video"];
                $ts = explode("v=", $test_string);
                if (!empty($ts))
                    $this->request->data["Event"]["video"] = $ts[1];
            }
            //$this->request->data["Event"]["user_id"] = AuthComponent::user("id");
            if (!empty($this->request->data["Event"]["specify"])) {
                $specify = $this->request->data["Event"]["specify"];
                $AddressData = $this->Address->find('first', array('conditions' => array('Address.user_id' => $this->Auth->User('id'), 'Address.name' => $specify), 'fields' => array('billing_address_1', 'billing_address_2')));
                $address1 = $AddressData['Address']['billing_address_1'];
                $address2 = $AddressData['Address']['billing_address_2'];
               
                $this->request->data["Event"]["event_address"] = $address1 . "," . $address2;
            } else {
                if (!empty($this->request->data["Event"]["event_address"]))
                    $this->request->data["Event"]["event_address"] = $this->data["Event"]["event_address1"] . "," . $this->data["Event"]["event_address2"];
            }
            $this->request->data["Event"]["event_address"] = $this->data["Event"]["event_address1"] . "," . $this->data["Event"]["event_address2"];
          
            $tmp = $this->data; 
           
            #save flyer image
            if (!empty($this->data['Event']['flyer_image']['name'])) {
                $imgNameExt = pathinfo($this->data['Event']['flyer_image']['name']);
                $ext = $imgNameExt['extension'];
                $ext = strtolower($ext);
                if ($ext == 'jpg' || $ext == 'jpeg' || $ext == 'png' || $ext == 'gif') {
                    $newImgName = 'flyer_image_' . time();
                    $tempFile = $this->data['Event']['flyer_image']['tmp_name'];
                    $destLarge = realpath('../webroot/img/flyerImage/large/') . '/';
                    $destThumb = realpath('../webroot/img/flyerImage/small/') . '/';
                    $destOriginal = realpath('../webroot/img/flyerImage/original/') . '/';
                    $file = $this->data['Event']['flyer_image'];
                    $file['name'] = $imgNameExt['filename'] . '.' . $ext;
                    if ($ext == 'gif') {
                        $result = $this->Upload->upload($file, $destLarge, $newImgName . ".gif", array('type' => 'resizemin', 'size' => array('300', '450')));
                        $result = $this->Upload->upload($file, $destOriginal, $newImgName . ".gif", NULL, array('gif'));
                        $result = $this->Upload->upload($file, $destThumb, $newImgName . ".gif", array('type' => 'resizemin', 'size' => array('75', '75')));
                        $name = $newImgName . ".gif";
                    } else if ($ext == 'png') {
                        $result = $this->Upload->upload($file, $destLarge, $newImgName . ".png", array('type' => 'resizemin', 'size' => array('300', '450')));
                        $result = $this->Upload->upload($file, $destOriginal, $newImgName . ".png", NULL, array('png'));
                        $result = $this->Upload->upload($file, $destThumb, $newImgName . ".png", array('type' => 'resizemin', 'size' => array('75', '75')));
                        $name = $newImgName . ".png";
                    } else if ($ext == 'jpeg') {
                        $result = $this->Upload->upload($file, $destLarge, $newImgName . ".jpeg", array('type' => 'resizemin', 'size' => array('300', '450')));
                        $result = $this->Upload->upload($file, $destOriginal, $newImgName . ".jpeg", NULL, array('jpeg'));
                        $result = $this->Upload->upload($file, $destThumb, $newImgName . ".jpeg", array('type' => 'resizemin', 'size' => array('75', '75')));
                        $name = $newImgName . ".jpeg";
                    } else {
                        $result = $this->Upload->upload($file, $destLarge, $newImgName . ".jpg", array('type' => 'resizemin', 'size' => array('300', '450')));
                        $result = $this->Upload->upload($file, $destOriginal, $newImgName . ".jpg", NULL, array('jpg'));
                        $result = $this->Upload->upload($file, $destThumb, $newImgName . ".jpg", array('type' => 'resizemin', 'size' => array('75', '75')));
                        $name = $newImgName . ".jpg";
                    }
                    $tmp["Event"]["flyer_image"] = $name;
                }
            } else {
                unset($tmp["Event"]["flyer_image"]);
            }
            # now save Event Primary Image
            if (!empty($this->data['Event']['image_name']['name'])) {
                $imgNameExt = pathinfo($this->data['Event']['image_name']['name']);
                $ext = $imgNameExt['extension'];
                $ext = strtolower($ext);
                if ($ext == 'jpg' || $ext == 'jpeg' || $ext == 'png' || $ext == 'gif') {
                    $newImgName = 'EventImage' . time();
                    $tempFile = $this->data['Event']['image_name']['tmp_name'];
                    $destLarge = realpath('../webroot/img/EventImages/large/') . '/';
                    $destThumb = realpath('../webroot/img/EventImages/small/') . '/';
                    $destOriginal = realpath('../webroot/img/EventImages/original/') . '/';
                    $file = $this->data['Event']['image_name'];
                    $file['name'] = $imgNameExt['filename'] . '.' . $ext;
                    if ($ext == 'gif') {
                        $result = $this->Upload->upload($file, $destLarge, $newImgName . ".gif", array('type' => 'resizemin', 'size' => array('300', '450')));
                        $result = $this->Upload->upload($file, $destOriginal, $newImgName . ".gif", NULL, array('gif'));
                        $result = $this->Upload->upload($file, $destThumb, $newImgName . ".gif", array('type' => 'resizemin', 'size' => array('400', '400')));
                        $name = $newImgName . ".gif";
                    } else if ($ext == 'png') {
                        $result = $this->Upload->upload($file, $destLarge, $newImgName . ".png", array('type' => 'resizemin', 'size' => array('300', '450')));
                        $result = $this->Upload->upload($file, $destOriginal, $newImgName . ".png", NULL, array('png'));
                        $result = $this->Upload->upload($file, $destThumb, $newImgName . ".png", array('type' => 'resizemin', 'size' => array('400', '400')));
                        $name = $newImgName . ".png";
                    } else if ($ext == 'jpeg') {
                        $result = $this->Upload->upload($file, $destLarge, $newImgName . ".jpeg", array('type' => 'resizemin', 'size' => array('300', '450')));
                        $result = $this->Upload->upload($file, $destOriginal, $newImgName . ".jpeg", NULL, array('jpeg'));
                        $result = $this->Upload->upload($file, $destThumb, $newImgName . ".jpeg", array('type' => 'resizemin', 'size' => array('400', '400')));
                        $name = $newImgName . ".jpeg";
                    } else {
                        $result = $this->Upload->upload($file, $destLarge, $newImgName . ".jpg", array('type' => 'resizemin', 'size' => array('300', '450')));
                        $result = $this->Upload->upload($file, $destOriginal, $newImgName . ".jpg", NULL, array('jpg'));
                        $result = $this->Upload->upload($file, $destThumb, $newImgName . ".jpg", array('type' => 'resizemin', 'size' => array('400', '400')));
                        $name = $newImgName . ".jpg";
                    }
                    $tmp["Event"]["image_name"] = $name;
                }
            } else {
                unset($tmp["Event"]["image_name"]);
            }

            if (isset($tmp['Event']['option_to_show'][1]) && $tmp['Event']['option_to_show'][1] == 1 && isset($tmp['Event']['option_to_show'][3]) && $tmp['Event']['option_to_show'][3] == 1) {
                $tmp['Event']['option_to_show'] = 3;
            } elseif (isset($tmp['Event']['option_to_show'][1]) && $tmp['Event']['option_to_show'][1] == 1) {
                $tmp['Event']['option_to_show'] = 1;
            } elseif (isset($tmp['Event']['option_to_show'][3]) && $tmp['Event']['option_to_show'][3] == 1) {
                $tmp['Event']['option_to_show'] = 2;
            }

            if (!isset($tmp["Event"]["event_type"]) || $tmp["Event"]["event_type"] == 2 || empty($tmp["Event"]["event_type"])) {
                unset($tmp["Event"]["option_to_show"]);
            }
            # now save all data in event table
          
            if ($this->Event->save($tmp)) {
                #get event_id
                if (isset($tmp["Event"]["id"]) && !empty($tmp["Event"]["id"])) {
                    $event_id = $tmp["Event"]["id"];
                } else {
                    $event_id = $this->Event->getLastInsertID();
                }

                # now save Event Other Images
                if (!empty($this->data['EventImage']['image_name'][0]['name'])) {//pr($this->data['EventImage']['image_name']);die;
                    $this->loadModel('EventImage');
                    for ($i = 0; $i < count($this->data['EventImage']['image_name']); $i++) {

                        $this->EventImage->create();
                        $imgNameExt = pathinfo($this->data['EventImage']['image_name'][$i]['name']);
                        $ext = $imgNameExt['extension'];
                        $ext = strtolower($ext);
                        if ($ext == 'jpg' || $ext == 'jpeg' || $ext == 'png' || $ext == 'gif') {
                            $newImgName = "Secondary_" . microtime();
                            $tempFile = $this->data['EventImage']['image_name'][$i]['tmp_name'];
                            $destLarge = realpath('../webroot/img/EventImages/large/') . '/';
                            $destThumb = realpath('../webroot/img/EventImages/small/') . '/';
                            $destOriginal = realpath('../webroot/img/EventImages/original/') . '/';
                            $file = $this->data['EventImage']['image_name'][$i];
                            $file['name'] = $imgNameExt['filename'] . '.' . $ext;
                            if ($ext == 'gif') {
                                $result = $this->Upload->upload($file, $destLarge, $newImgName . ".gif", array('type' => 'resizemin', 'size' => array('300', '450')));
                                $result = $this->Upload->upload($file, $destOriginal, $newImgName . ".gif", NULL, array('gif'));
                                $result = $this->Upload->upload($file, $destThumb, $newImgName . ".gif", array('type' => 'resizemin', 'size' => array('400', '400')));
                                $name = $newImgName . ".gif";
                            } else if ($ext == 'png') {
                                $result = $this->Upload->upload($file, $destLarge, $newImgName . ".png", array('type' => 'resizemin', 'size' => array('300', '450')));
                                $result = $this->Upload->upload($file, $destOriginal, $newImgName . ".png", NULL, array('png'));
                                $result = $this->Upload->upload($file, $destThumb, $newImgName . ".png", array('type' => 'resizemin', 'size' => array('400', '400')));
                                $name = $newImgName . ".png";
                            } else if ($ext == 'jpeg') {
                                $result = $this->Upload->upload($file, $destLarge, $newImgName . ".jpeg", array('type' => 'resizemin', 'size' => array('300', '450')));
                                $result = $this->Upload->upload($file, $destOriginal, $newImgName . ".jpeg", NULL, array('jpeg'));
                                $result = $this->Upload->upload($file, $destThumb, $newImgName . ".jpeg", array('type' => 'resizemin', 'size' => array('400', '400')));
                                $name = $newImgName . ".jpeg";
                            } else {
                                $result = $this->Upload->upload($file, $destLarge, $newImgName . ".jpg", array('type' => 'resizemin', 'size' => array('300', '450')));
                                $result = $this->Upload->upload($file, $destOriginal, $newImgName . ".jpg", NULL, array('jpg'));
                                $result = $this->Upload->upload($file, $destThumb, $newImgName . ".jpg", array('type' => 'resizemin', 'size' => array('400', '400')));
                                $name = $newImgName . ".jpg";
                            }
                        }
                        $EventImages["EventImage"]["image_name"] = $name;
                        $EventImages["EventImage"]["event_id"] = $event_id;
                        $this->EventImage->save($EventImages);
                    }
                }

                # now save TicketPrices
                #Save data in event edited user
                $this->loadModel('EventEditedUser');
                $editedUsers = explode(',', $tmp['EventEditedUser']['user_email']);
                if (isset($this->params['data']['Event']['id']) && !empty($this->params['data']['Event']['id'])) {

                    $this->EventEditedUser->deleteAll(array('EventEditedUser.event_id' => $this->params['data']['Event']['id']));
                }
                foreach ($editedUsers as $eUsers) {
                    $this->EventEditedUser->create();
                    $save_eu["EventEditedUser"]["user_email"] = $eUsers;
                    $save_eu["EventEditedUser"]["event_id"] = $event_id;
                    $this->EventEditedUser->save($save_eu);
                }
                #Save Data in Event Dates Table
                $this->loadModel("EventDate");
                $Eventdate = $tmp["EventDate"]["start_date"];
               
                $Eventstarttime = $tmp["EventDate"]["start_time"];
                $Eventendtime = $tmp["EventDate"]["end_time"];
             
                if (isset($this->params['data']['Event']['id']) && !empty($this->params['data']['Event']['id'])) {

                    $this->EventDate->deleteAll(array('EventDate.event_id' => $this->params['data']['Event']['id']));
                }
              
                if ($tmp["Event"]["recurring_or_not"] == 0) {
                    sort($Eventdate);
                }
              
                for ($i = 0; $i < count($Eventdate); $i++) {
                    $this->EventDate->create();
                    $tmp_date["EventDate"]["event_id"] = $event_id;
                    $tmp_date['EventDate']['date'] = $Eventdate[$i];
                    $tmp_date['EventDate']['start_time'] = $Eventstarttime[$i];
                    $tmp_date['EventDate']['end_time'] = $Eventendtime[$i];


                    $this->EventDate->save($tmp_date);
                }
               
                $dateExtra[] = '';
                $dateExtra['rec_mode'] .= $tmp["Event"]["recurring_type"];
                if ($tmp["Event"]["recurring_type"] == "d") {
                    $dateExtra['daily_days'] .= $tmp["Event"]["daily_days"];
                } elseif ($tmp["Event"]["recurring_type"] == "w") {
                    $dateExtra['rept_days'] .= $tmp["Event"]["weekRepeatDay"];
                } elseif ($tmp["Event"]["recurring_type"] == "m") {
                    $dateExtra['month_mode'] .= $_POST['month_mode'];
                    if ($_POST['month_mode'] == "mode1") {
                        $dateExtra['month_day1'] .= $_POST['month_day1'];
                    } else {
                        $dateExtra['monthly_period'] .= $_POST['monthly_period'];
                        $dateExtra['monthly_pattern_day'] .= $_POST['monthly_pattern_day'];
                    }
                }

                $date_extra = serialize($dateExtra);

                $EventdateReverse = array_reverse($Eventdate);
              
                $this->Event->id = $event_id;
                $this->Event->saveField('start_date', $Eventdate[0]);
                $this->Event->saveField('end_date', $EventdateReverse[0]);
                $this->Event->saveField('edate_extra', $date_extra);

                #End Save Data in Event Date Table 
                # now save category
                $EventCategory = $tmp["EventCategory"];
               
                $this->loadModel("EventCategory");
                if (isset($this->params['data']['Event']['id']) && !empty($this->params['data']['Event']['id'])) {
                    $this->EventCategory->deleteAll(array('EventCategory.event_id' => $this->params['data']['Event']['id']));
                }
                foreach ($EventCategory as $tc) {
                 

                    if ($tc != 0) {
                        $save_tc["EventCategory"]["id"] = "";
                        $save_tc["EventCategory"]["category_id"] = $tc;
                        $save_tc["EventCategory"]["event_id"] = $event_id;
                        $this->EventCategory->save($save_tc);
                    }
                }
                # now save vibes
                $EventVibe = $tmp["EventVibe"];
                $this->loadModel("EventVibe");
                if (isset($this->params['data']['Event']['id']) && !empty($this->params['data']['Event']['id'])) {
                    $this->EventVibe->deleteAll(array('EventVibe.event_id' => $this->params['data']['Event']['id']));
                }
                foreach ($EventVibe as $ev) {

                    if ($ev != 0) {
                        $save_ev["EventVibe"]["id"] = "";
                        $save_ev["EventVibe"]["vibe_id"] = $ev;
                        $save_ev["EventVibe"]["event_id"] = $event_id;
                        $this->EventVibe->save($save_ev);
                        
                    }
                }
                # now save ticket price

                $this->loadModel("TicketPrice");
                $cnt = $this->TicketPrice->find('count', array('conditions' => array('TicketPrice.event_id' => $event_id)));

                if ($cnt != 0) {
                    $this->TicketPrice->deleteAll(array('TicketPrice.event_id' => $event_id));
                }
                $TicketPrice = $tmp["TicketPrice"];
                for ($i = 0; $i < count($TicketPrice['ticket_label']); $i++) {

                    $this->TicketPrice->create();
                    $save_tp["TicketPrice"]["ticket_label"] = $TicketPrice['ticket_label'][$i];
                    $save_tp["TicketPrice"]["ticket_price"] = $TicketPrice['ticket_price'][$i];
                    $save_tp["TicketPrice"]["event_id"] = $event_id;
                    $this->TicketPrice->save($save_tp);
                }
            if(empty($this->data['Event']["id"]))
                {
                    $upMsg = "Added Successfully"; 
                }
                else
                {
                    $upMsg = "Updated Successfully";
                }
                $this->Session->setflash($upMsg, "default", array("class" => "green"));

                $this->redirect(array("controller" => "Events", "action" => "list", 'admin' => true));
            }
        }
        if ($id) {

            $id = base64_decode($id);
            $event_detail = $this->Event->findById($id);
            $this->request->data = $event_detail;
            $this->request->data["Event"]["video"] = "http://www.youtube.com/watch?v=" . $event_detail["Event"]["video"];
            $save_add = $this->Address->find("list", array("conditions" => array("Address.user_id" => $event_detail["Event"]["user_id"]), "fields" => array("Address.name", "Address.name")));
            $this->set("saved_address", $save_add);
        }
    }

    /** @Created:    06-May-2014
     * @Method :     admin_list
     * @Author:      Prateek Jadhav
     * @Modified :   ---
     * @Purpose:     to list events in admin panel
     * @Param:       none
     * @Return:      none
     */
    public function admin_list() {
        $this->layout = 'admin/admin';
        $this->set('title_for_layout', 'ALIST Hub :: List Events');
        $this->loadModel("EventDate");
        $conditions = array();
        if (isset($this->params["named"]["page"])) {
            $this->request->data = $this->Session->read("event_list");
        } else {
            $this->Session->delete("event_list");
        }
        if ($this->data) {
            if (!empty($this->data["Event"]["title"])) {
                $conditions = array_merge($conditions, array("Event.title LIKE" => "%" . $this->data["Event"]["title"] . "%"));
            }
            if (!empty($this->data["Event"]["sub_title"])) {
                $conditions = array_merge($conditions, array("Event.sub_title LIKE" => "%" . $this->data["Event"]["sub_title"] . "%"));
            }
            if (!empty($this->data["Event"]["main_info_phone_no"])) {
                $conditions = array_merge($conditions, array("Event.main_info_phone_no LIKE" => "%" . $this->data["Event"]["main_info_phone_no"] . "%"));
            }
            if (!empty($this->data["Event"]["main_info_email"])) {
                $conditions = array_merge($conditions, array("Event.main_info_email LIKE" => "%" . $this->data["Event"]["main_info_email"] . "%"));
            }
            if (!empty($this->data["Event"]["created"])) {
                $conditions = array_merge($conditions, array("Event.created LIKE" => "%" . $this->data["Event"]["created"] . "%"));
            }
            if (!empty($this->data["Event"]["event_date"])) {
                $event_dates = $this->EventDate->find("list", array("conditions" => array("EventDate.date LIKE" => "%" . $this->data["Event"]["event_date"] . "%"), "fields" => array("EventDate.id", "EventDate.event_id")));
                if (!empty($event_dates)) {
                    $conditions = array_merge($conditions, array("Event.id" => $event_dates));
                }
            }

            if (!empty($this->data["Event"]["event_from"])) {
                if ($this->data["Event"]["event_from"] != "all") {
                    $conditions = array_merge($conditions, array("Event.event_from" => $this->data["Event"]["event_from"]));
                }
            }

            $allEventGreterThenNow = $this->EventDate->find("list", array("order" => "EventDate.date ASC", "fields" => array("EventDate.event_id", "EventDate.event_id")));

            if (isset($this->data["Event"]["direction"]) && $this->data["Event"]["direction"] == "DESC") {
                $allEventGreterThenNow = array_reverse($allEventGreterThenNow);
            }
            if (isset($this->data["Event"]["order"]) && $this->data["Event"]["order"] != "Event.start_date") {
                $order = array($this->data["Event"]["order"] => $this->data["Event"]["direction"]);
            } else {
                $order = 'Field(Event.id, ' . implode(',', $allEventGreterThenNow) . ')';
            }
           
            $this->paginate = array('conditions' => $conditions, 'limit' => $this->data["Event"]["limit"], 'order' => $order);
            $this->request->data = $this->data;
            $this->Session->write("event_list", $this->data);
        } else {
            $this->paginate = array('conditions' => $conditions, 'limit' => 10, 'order' => array('Event.created' => 'DESC'));
        }
       
        $this->set('events', $this->paginate('Event'));
    }

    /** @Created:    08-May-2014
     * @Method :     admin_view
     * @Author:      Prateek Jadhav
     * @Modified :   ---
     * @Purpose:     To view a event
     * @Param:       $event_id
     * @Return:      none
     */
    public function admin_view($event_id = null) {
        $this->layout = "admin/admin";
        $this->set('title_for_layout', 'ALIST Hub :: View Event');
        if ($event_id) {
            $event = $this->Event->find("first", array("conditions" => array("Event.id" => base64_decode($event_id)), "recursive" => 2)); #pr($event);die;
            if (!empty($event)) {
                $this->set('event', $event);
            } else {
                $this->Session->setFlash("Data not found", "default", array("class" => "red"));
                $this->redirect(array("controller" => "Events", "action" => "list"));
            }
        } else {
            $this->Session->setFlash("Direct access not allowed", "default", array("class" => "red"));
            $this->redirect(array("controller" => "Events", "action" => "list"));
        }
    }

    /** @Created:    08-May-2014
     * @Method :     admin_list_csv
     * @Author:      Prateek Jadhav
     * @Modified :   ---
     * @Purpose:     To view a event
     * @Param:       $event_id
     * @Return:      none
     */
    public function admin_list_csv() {
        $this->layout = 'admin/admin';
        $conditions = array();
        if ($this->Session->check("event_list")) {
            $this->request->data = $this->Session->read("event_list");
        }
        if ($this->data) {
            if (!empty($this->data["Event"]["title"])) {
                $conditions = array_merge($conditions, array("Event.title LIKE" => "%" . $this->data["Event"]["title"] . "%"));
            }
            if (!empty($this->data["Event"]["sub_title"])) {
                $conditions = array_merge($conditions, array("Event.sub_title LIKE" => "%" . $this->data["Event"]["sub_title"] . "%"));
            }
            $this->paginate = array('recursive' => 2, 'conditions' => $conditions, 'limit' => $this->data["Event"]["limit"], 'order' => array($this->data["Event"]["order"] => $this->data["Event"]["direction"]));
        } else {
            $this->paginate = array('recursive' => 2, 'conditions' => $conditions, 'limit' => 10, 'order' => array('Event.id' => 'ASC'));
        }
      
        $this->set('events', $this->paginate('Event'));
    }

    /** @Created:    12-May-2014
     * @Method :     calendar
     * @Author:      Prateek Jadhav
     * @Modified :   ---
     * @Purpose:     To view calendar on frontend
     * @Param:       $action
     * @Return:      none
     */
    public function calendar($action = NULL) {
       
        $this->layout = "front/home";
        $this->set('title_for_layout', 'ALIST Hub :: Calendar');
        $this->loadModel("MyCalendar");
        $this->loadModel("MyWpplugin");
        $this->loadModel("Region");
        $this->loadModel("Category");
        $this->loadModel("Vibe");
        $this->loadModel("User");
        $this->loadModel("Zip");
        $this->loadModel("Search");




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
            }
            if (!empty($this->data["EventVibe"]["id"])) {
                foreach ($this->data["EventVibe"]["id"] as $key => $value):
                    $vibeEvent[] = $key;
                endforeach;
                $save_search_data["Search"]["EventVibe"] = implode(",", $vibeEvent);
            }
            $this->Search->save($save_search_data);
        } else {
            // for fetch data from database
            if (!empty($existing_search_data)) {
                $this->request->data["Event"]["title"] = $existing_search_data["Search"]["title"];
                $this->request->data["Event"]["date"] = $existing_search_data["Search"]["date"];
                $this->request->data["Event"]["limit"] = $existing_search_data["Search"]["limit"];
                $this->request->data["Event"]["order"] = $existing_search_data["Search"]["order"];

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
                }

                if (!empty($existing_search_data["Search"]["EventVibe"])) {
                    $vibeEvent = explode(",", $existing_search_data["Search"]["EventVibe"]);
                    foreach ($vibeEvent as $ve) {
                        $event_vibe["EventVibe"]["id"][$ve] = "on";
                    }
                    $this->request->data["EventVibe"] = $event_vibe["EventVibe"];
                }
            }
        }



        $conditions = array();
        // all events should be greater then today
        $this->loadModel("EventDate");
        $now = date('Y-m-d');
        $allEventGreterThenNow = $this->EventDate->find("list", array("conditions" => array("EventDate.date >=" => $now), "fields" => array("EventDate.event_id", "EventDate.event_id")));
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



        if (isset($this->data["Event"]["order"])) {
            $order = $this->data["Event"]["order"];
        } else {
            $order = "Event.id ASC";
        }
        $this->Event->unBindModel(array('hasMany' => array("EventCategory", "EventVibe"), 'belongsTo' => array('User')), false);

        $this->Event->bindModel(array('hasOne' => array("EventCategory", "EventVibe")), false);
       

        $regions = $this->Region->find("all", array("conditions" => array(), "recursive" => -1));
        $categories = $this->Category->find("all", array("conditions" => array(), "recursive" => -1));
        $vibes = $this->Vibe->find("all", array("conditions" => array(), "recursive" => -1));
        $this->set(compact('regions', 'categories', 'vibes'));
        #lets find out which event is added as my calendar
        $my_calendar = $this->MyCalendar->find("list", array("conditions" => array("MyCalendar.user_id" => AuthComponent::user("id")), "fields" => array("MyCalendar.id", "MyCalendar.event_id")));
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
                    $conditions = array_merge($conditions, array('EventVibe.vibe_id' => $arrayVibe));
                }
            }
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

                    if (!empty($zip_detail)) {
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
            if ($events_show == "all") {
                $this->redirect(array("controller" => "Events", "action" => "all"));
                $ev = "all";
            } else
            if ($events_show == "FB") {
                $conditions = array_merge($conditions, array("Event.event_from" => "facebook"));
                $ev = "FB";
            } elseif ($events_show == "EF") {
                $this->redirect('/events/eventfulEvents');
                $ev = "EF";
            } else {
                $conditions = array_merge($conditions, array("Event.event_from" => "ALH"));
                $ev = "ALH";
            }
            $this->Cookie->write('ev', $ev);
        } else {
            if ($this->Cookie->read("ev") == 'FB') {
                $conditions = array_merge($conditions, array("Event.event_from" => "facebook"));
                $ev = "FB";
            } else {
                $conditions = array_merge($conditions, array("Event.event_from" => "ALH"));
                $ev = "ALH";
            }
        }

        if ($this->Cookie->check("ev")) {
            $ev = $this->Cookie->read("ev");
        } else {
            $conditions = array_merge($conditions, array("Event.event_from" => "ALH"));
            $ev = "ALH";
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
            if ($this->Session->check("calender_conditions")) {
                $conditions = $this->Session->read("calender_conditions");
            }
        } else if (!empty($conditions)) {
            $this->Session->write("calender_conditions", $conditions);
        }




        $this->paginate = array('conditions' => $conditions, 'limit' => $limit, 'order' => $order, 'group' => 'Event.id');

        $events = $this->paginate('Event');

        $this->set('events', $events);
    }

    /** @Created:    13-May-2014
     * @Method :     viewEvent
     * @Author:      Prateek Jadhav
     * @Modified :   ---
     * @Purpose:     To view calendar on frontend
     * @Param:       $event_id
     * @Return:      none
     */
    public function viewEvent($event_id = null) {
        $this->layout = "front/home";
        $this->loadModel("Zip");
        $this->loadModel("MyCalendar");
        $this->loadModel("EventFbattendUser");
        $this->loadModel("VisitEvent");
        if ($this->Session->read('Auth.User')) {
            $logUser = $this->User->find("first", array("conditions" => array("User.id" => AuthComponent::User("id"))));
            $this->set("logUser", $logUser);
        }

        if (AuthComponent::user("id")) {
            $visitEvent["VisitEvent"]["user_id"] = AuthComponent::user("id");
            $visitEvent["VisitEvent"]["event_id"] = base64_decode($event_id);
            $check_data = $this->VisitEvent->find("first", array("conditions" => array("VisitEvent.user_id" => AuthComponent::user("id"), "VisitEvent.event_id" => base64_decode($event_id))));
            if (!empty($check_data)) {
                $visitEvent["VisitEvent"]["id"] = $check_data["VisitEvent"]["id"];
                $visitEvent["VisitEvent"]["count"] = $check_data["VisitEvent"]["count"] + 1;
            }
            $this->VisitEvent->save($visitEvent);
        }


        $my_calendar = $this->MyCalendar->find("list", array("conditions" => array("MyCalendar.user_id" => AuthComponent::user("id")), "fields" => array("MyCalendar.id", "MyCalendar.event_id")));
        $this->set("my_calendar", $my_calendar);


        if ($event_id) {
            $event_id = base64_decode($event_id);
            $event = $this->Event->find("first", array("recursive" => "2", "conditions" => array("Event.id" => $event_id)));

            $dataevent["Event"]["id"] = $event["Event"]["id"];
            $dataevent["Event"]["count"] = $event["Event"]["count"] + 1;
            $this->Event->save($dataevent);

            $this->set('title_for_layout', $event["Event"]["title"]);
            if (isset($event["User"]["Brand"]["id"])) {
                $this->set("is_brand", $event["User"]["Brand"]["id"]);
            } else {
                $this->set("is_brand", 0);
            }



            $this->loadModel("SuscribeBrand");
            $my_sub = $this->SuscribeBrand->find("list", array("conditions" => array("SuscribeBrand.user_id" => AuthComponent::User("id")), "fields" => array("SuscribeBrand.id", "SuscribeBrand.brand_id")));
            $this->set("my_suscribe", $my_sub);
            if (!empty($event)) {

                if ($event["Event"]["event_from"] == "facebook") {

                   
                    $city = trim($event["Event"]["event_location"]);
                    $citys = explode(",", $city);
                  

                    $zip = $this->Zip->find("first", array("conditions" => array("Zip.city LIKE" => trim($citys[0]))));
                    $this->set("zip", $zip);
                 
                } else {
                    $zip = $this->Zip->findByZip($event["Event"]["cant_find_zip_code"]);
                    $this->set("zip", $zip);
                }

                $this->set("event", $event);
                $edited_user = array();
               
                foreach ($event["EventEditedUser"] as $ev) {
                    $edited_user[] = trim($ev["user_email"]);
                }
                $this->set("edited_user", $edited_user);
            } else {
                $this->Session->setFlash("Data not found", "default", array("class" => "red"));
                $this->redirect(array("controller" => "Events", "action" => "calendar"));
            }
        } else {
            $this->Session->setFlash("Direct access not allowed", "default", array("class" => "red"));
            $this->redirect(array("controller" => "Events", "action" => "calendar"));
        }
    }

    /** @Created:    13-May-2014 
     * @Method :     ticketGiveaway
     * @Author:      Prateek Jadhav
     * @Modified :   ---
     * @Purpose:     To register for giveaway ticket step first
     * @Param:       $event_id
     * @Return:      none
     */
    public function ticketGiveaway($event_id = null) {
        $this->layout = "front/home";
        $this->set("event_id", base64_decode($event_id));
        $this->loadModel("TicketGiveaway");
        if ($this->data) {
            if (isset($this->data["TicketGiveaway"]["event_id"])) {
                $ticketPresent = $this->TicketGiveaway->find("first", array("conditions" => array("TicketGiveaway.event_id" => $this->data["TicketGiveaway"]["event_id"], "TicketGiveaway.email" => $this->data["TicketGiveaway"]["email"])));
            }
            if (!empty($ticketPresent)) {
                // $this->set("message", "You have already applied Ticket Give Away for this Event.");
                $this->Session->setFlash("You have already applied Ticket Give Away for this Event.", "default", array("class" => "red"));
                $this->redirect($this->referer());
            } else {
                $this->set("message", "");
                if ($this->TicketGiveaway->save($this->data)) {

                    $ticketGiveaway_id = $this->TicketGiveaway->getLastInsertID();
                  

                    if (@$this->data['step2'] == 'step2') {

                        $this->sendGiveawayMail($this->data['TicketGiveaway']['id']);
                    }

                    $this->redirect(array("controller" => "Events", "action" => "ticketGiveawayDetail", base64_encode($ticketGiveaway_id)));
                } else {
                    $this->Session->setFlash("Unavle to save", "default", array("class" => "red"));
                    $this->redirect(array("controller" => "Events", "action" => "calendar"));
                }
            }
        } if ($event_id) {
            $this->set("event", $this->Event->findById(base64_decode($event_id)));
        } else {
            $this->Session->setFlash("Direct access not allowed", "default", array("class" => "red"));
            $this->redirect(array("controller" => "Events", "action" => "calendar"));
        }
    }

    /** @Created:    13-May-2014
     * @Method :     ticketGiveawayDetail
     * @Author:      Prateek Jadhav
     * @Modified :   ---
     * @Purpose:     To register for giveaway ticket step first
     * @Param:       $event_id
     * @Return:      none
     */
    public function ticketGiveawayDetail($giveaway_id = null) {
        $this->layout = "front/home";
        $this->loadModel("TicketGiveaway");
        $this->loadModel("Brand");
        $ipDetail = $this->ipDetail();
        $zip = $ipDetail->zipCode;
        $this->set("zip", $zip);

        $eventBrand = $this->TicketGiveaway->findById(base64_decode($giveaway_id));
        $this->set("branduser", $this->Brand->find("first", array("conditions" => array("Brand.user_id" => $eventBrand['Event']['user_id']))));

       
        if ($this->data) {

            if ($this->TicketGiveaway->save($this->data)) {
                $this->Session->setFlash("You are registred for giveaway ticket", "default", array("class" => "green"));
                // $this->redirect(array("controller" => "Events", "action" => "calendar"));
            } else {
                $this->Session->setFlash("unable to save", "default", array("class" => "green"));
                $this->redirect(array("controller" => "Events", "action" => "calendar"));
            }
        } else if ($giveaway_id) {
            $this->set("giveaway", $this->TicketGiveaway->findById(base64_decode($giveaway_id)));
        } else {

            //$this->Session->setFlash("Ticket Give Away Request Successfully Submitted.", "default", array("class" => "green"));
            if (AuthComponent::user("id")) {
                $this->redirect(array("controller" => "Events", "action" => "ticketGiveawayThankYou"));
            } else {
                $this->redirect(array("controller" => "Events", "action" => "activate"));
            }
        }
    }

    public function activate() {
        $this->layout = "front/home";
    }

    /** @Created:    27-july-2014
     * @Method :     ticketGiveawayThankYou
     * @Author:      Prateek Jadhav
     * @Modified :   ---
     * @Purpose:     Thank You Page For Ticket Giveaway
     * @Param:       none

     * @Return:      none
     */
    public function ticketGiveawayThankYou() {
        $this->layout = "front/home";
    }

    public function confirm($token = NULL, $id = NULL) {
        $this->autoRender = false;
        $this->loadModel('TicketGiveaway');
        if (!empty($token) && !empty($id)) {
            $this->TicketGiveaway->updateAll(array('TicketGiveaway.token' => NULL, 'TicketGiveaway.status' => 1), array('TicketGiveaway.id' => $id));
            $this->sendGiveawayMail($id);
            $this->redirect(array("controller" => "Events", "action" => "ticketGiveawayThankYou"));
        }
    }

    /** @Created:    16-May-2014
     * @Method :     admin_listGiveawayRequest
     * @Author:      Prateek Jadhav
     * @Modified :   ---
     * @Purpose:     View list of ticket giveaway request to admin
     * @Param:       $event_id
     * @Return:      none
     */
    public function admin_listGiveawayRequest($event_id = NULL) {
        $this->layout = "admin/admin";
        $this->set('title_for_layout', 'ALIST Hub :: List GiveAway Requests');
        $this->loadModel("TicketGiveaway");
        $conditions = array("Event.id" => base64_decode($event_id));
        $this->set("event_id", $event_id);
        $fields = array("TicketGiveaway.*", "Event.title", "Event.sub_title");
        if (isset($this->params["named"]["page"])) {
            $this->request->data = $this->Session->read("listGiveawayRequest");
        } else {
            $this->Session->delete("listGiveawayRequest");
        }
        $conditions = array_merge($conditions, array("TicketGiveaway.status" => 1));
        if ($this->data) {
            if (!empty($this->data["TicketGiveaway"]["first_name"])) {
                $conditions = array_merge($conditions, array("OR" => array("TicketGiveaway.first_name LIKE" => "%" . $this->data["TicketGiveaway"]["first_name"] . "%", "TicketGiveaway.last_name LIKE" => "%" . $this->data["TicketGiveaway"]["first_name"] . "%")));
            }
            if (!empty($this->data["TicketGiveaway"]["email"])) {
                $conditions = array_merge($conditions, array("TicketGiveaway.email LIKE" => "%" . $this->data["TicketGiveaway"]["email"] . "%"));
            }
            if (!empty($this->data["TicketGiveaway"]["zip"])) {
                $conditions = array_merge($conditions, array("TicketGiveaway.zip LIKE" => "%" . $this->data["TicketGiveaway"]["zip"] . "%"));
            }
            $this->paginate = array('fields' => $fields, 'conditions' => $conditions, 'limit' => $this->data["TicketGiveaway"]["limit"], 'order' => array($this->data["TicketGiveaway"]["order"] => $this->data["TicketGiveaway"]["direction"]));
            $this->request->data = $this->data;
            $this->Session->write("listGiveawayRequest", $this->data);
        } else {
            $this->paginate = array('fields' => $fields, 'conditions' => $conditions, 'limit' => 4, 'order' => array('TicketGiveaway.created' => 'DESC'));
        }
      
        $this->set('datas', $this->paginate('TicketGiveaway'));
    }

    /** @Created:    16-May-2014
     * @Method :     admin_viewGiveawayRequest
     * @Author:      Prateek Jadhav
     * @Modified :   ---
     * @Purpose:     View of ticket giveaway request to admin
     * @Param:       $ticketGiveaway_id
     * @Return:      none
     */
    public function admin_viewGiveawayRequest($ticketGiveaway_id = null) {
        $this->layout = "admin/admin";
        $this->set('title_for_layout', 'ALIST Hub :: View GiveAway Request');
        $this->loadModel("TicketGiveaway");
        if ($ticketGiveaway_id) {
            $ticketGiveaway_id = base64_decode($ticketGiveaway_id);
            $data = $this->TicketGiveaway->findById($ticketGiveaway_id);
            if (!empty($data)) {
                $this->set('data', $data);
            } else {
                $this->Session->setFlash("Data not found", "default", array("class" => "red"));
                $this->redirect(array("controller" => "Events", "action" => "listGiveawayRequest"));
            }
        } else {
            $this->Session->setFlash("direct access not allowed", "default", array("class" => "red"));
            $this->redirect(array("controller" => "Events", "action" => "listGiveawayRequest"));
        }
    }

    /** @Created:    16-May-2014
     * @Method :     admin_ticketGiveawayCsv
     * @Author:      Prateek Jadhav
     * @Modified :   ---
     * @Purpose:     Generate CSV of ticket giveaway request to admin
     * @Param:       none
     * @Return:      none
     */
    public function admin_ticketGiveawayCsv() {
        $this->layout = FALSE;
        $this->loadModel("TicketGiveaway");
        $conditions = array();
        if ($this->Session->check("listGiveawayRequest")) {
            $this->request->data = $this->Session->read("listGiveawayRequest");
        }
        $fields = array("TicketGiveaway.*", "Event.title", "Event.start_date", "Event.end_date");
        if ($this->data) {
            if (!empty($this->data["TicketGiveaway"]["first_name"])) {
                $conditions = array_merge($conditions, array("OR" => array("TicketGiveaway.first_name LIKE" => "%" . $this->data["TicketGiveaway"]["first_name"] . "%", "TicketGiveaway.last_name LIKE" => "%" . $this->data["TicketGiveaway"]["first_name"] . "%")));
            }
            if (!empty($this->data["TicketGiveaway"]["email"])) {
                $conditions = array_merge($conditions, array("TicketGiveaway.email LIKE" => "%" . $this->data["TicketGiveaway"]["email"] . "%"));
            }
            if (!empty($this->data["TicketGiveaway"]["zip"])) {
                $conditions = array_merge($conditions, array("TicketGiveaway.zip LIKE" => "%" . $this->data["TicketGiveaway"]["zip"] . "%"));
            }
            if (!empty($this->data["Event"]["title"])) {
                $conditions = array_merge($conditions, array("Event.title LIKE" => "%" . $this->data["Event"]["title"] . "%"));
            }
            $this->paginate = array('fields' => $fields, 'conditions' => $conditions, 'order' => array($this->data["TicketGiveaway"]["order"] => $this->data["TicketGiveaway"]["direction"]));
        } else {
            $this->paginate = array('fields' => $fields, 'conditions' => $conditions, 'order' => array('TicketGiveaway.created' => 'DESC'));
        }
      
        $this->set('datas', $this->paginate('TicketGiveaway'));
    }

    /**
     * @Created:     19-May-2014
     * @Method :     admin_isGive
     * @Author: 	Prateek Jadhav
     * @Modified :	
     * @Purpose:   	Function for changing is_give status 
     * @Param:     	$id,$status,$model
     * @Return:    	none
     */
    function admin_isGive($id = NULL, $status = NULL, $model = NULL) {
        $this->layout = "ajax";
        $this->autoRender = false;
        $this->loadModel($model);
        $id = base64_decode($id);
        $this->$model->id = $id;
        if ($status == 0) {
            $this->$model->saveField('is_give', 1);
            $this->giveTicketMail($id);
            echo 1;
        } else {
            $this->$model->saveField('is_give', 0);
            $this->notGiveTicketMail($id);
            echo 0;
        }
    }

    /**
     * @Created:     19-May-2014
     * @Method :     giveTicketMail
     * @Author: 	Prateek Jadhav
     * @Modified :	
     * @Purpose:   	Function for sending mail for give ticket 
     * @Param:     	$id
     * @Return:    	none
     */
    function giveTicketMail($id = null) {
        $this->layout = false;
        $this->loadModel("TicketGiveaway");
        $this->loadModel("EmailTemplate");
        $detail = $this->TicketGiveaway->findById($id); //pr($detail);die;
        $emailTemp = $this->EmailTemplate->find('first', array('conditions' => array('EmailTemplate.alias' => "won_giveaway")));
       
        $emailContent = utf8_decode($emailTemp['EmailTemplate']['description']);
        $data = str_replace(array('{USER_NAME}', '{ticket_number}', '{event}'), array($detail['TicketGiveaway']['first_name'], $detail["Event"]["title"] . "_" . $detail["TicketGiveaway"]["id"], $detail["Event"]["title"]), $emailContent);
     
        $this->set('mailData', $data);
        $this->Email->to = $detail['TicketGiveaway']['email'];
        $this->Email->subject = $emailTemp['EmailTemplate']['subject'];
        $this->Email->from = "aisthub@admin.com";
        $this->Email->template = "forgot";
        $this->Email->sendAs = 'html';
        if ($this->Email->send($data)) {
            return "done";
        } else {
            return "not done";
        }
    }

    /**
     * @Created:     19-May-2014
     * @Method :     notGiveTicketMail
     * @Author: 	Prateek Jadhav
     * @Modified :	
     * @Purpose:   	Function for sending mail for give ticket 
     * @Param:     	$id
     * @Return:    	none
     */
    function notGiveTicketMail($id = null) {
        $this->layout = false;
        $this->loadModel("TicketGiveaway");
        $this->loadModel("EmailTemplate");
        $detail = $this->TicketGiveaway->findById($id); //pr($detail);die;
        $emailTemp = $this->EmailTemplate->find('first', array('conditions' => array('EmailTemplate.alias' => "cancel_giveaway")));
        $emailContent = utf8_decode($emailTemp['EmailTemplate']['description']);
        $data = str_replace(array('{USER_NAME}', '{ticket_number}', '{event}'), array($detail['TicketGiveaway']['first_name'], $detail["Event"]["title"] . "_" . $detail["TicketGiveaway"]["id"], $detail["Event"]["title"]), $emailContent);
        $this->set('mailData', $data);
        $this->Email->to = $detail['TicketGiveaway']['email'];
        $this->Email->subject = $emailTemp['EmailTemplate']['subject'];
        $this->Email->from = "aisthub@admin.com";
        $this->Email->template = "forgot";
        $this->Email->sendAs = 'html';
        $this->Email->send($data);
    }

    /**
     * @Created:        26-May-2014
     * @Method :        suscribe
     * @Author: 	Prateek Jadhav
     * @Modified :	
     * @Purpose:   	Function for suscribe brand 
     * @Param:     	$event_id
     * @Return:    	1/2/3
     */
    public function suscribe($brand_id = null) {
        $this->layout = false;
        $this->autoRender = false;
        $this->loadModel("SuscribeBrand");
        if ($brand_id) {
            $user_id = AuthComponent::user("id");
            // check for existing
            $check = $this->SuscribeBrand->find("first", array("conditions" => array("SuscribeBrand.user_id" => $user_id, "SuscribeBrand.event_id" => $brand_id)));
            if (!empty($check)) {
                if ($this->SuscribeBrand->delete($check["SuscribeBrand"]["id"])) {
                    return 1;
                } else {
                    return 3;
                }
            } else {
                $data["SuscribeBrand"]["user_id"] = $user_id;
                $data["SuscribeBrand"]["event_id"] = $brand_id;
                if ($this->SuscribeBrand->save($data)) {
                    return 2;
                } else {
                    return 3;
                }
            }
        } else {
            return 3;
        }
    }

    /**
     * @Created:        20-May-2014
     * @Method :        myEvents
     * @Author: 	Sachin Thakur
     * @Modified :	
     * @Purpose:   	Function for listing of my events
     * @Param:     	None
     * @Return:    	None
     */
    function myEvent() {
        $this->layout = 'frontend';
        $id = $this->Auth->User('id');
        $eventData = $this->Event->find('all', array('conditions' => array('Event.user_id' => $id, 'Event.is_deleted' => 0), 'recursive' => -1, 'fields' => array('id', 'title', 'start_date', 'status')));
        $this->set('events', $eventData);
    }

    /** @Created:    12-May-2014
     * @Method :     myCalendar
     * @Author:      Prateek Jadhav
     * @Modified :   ---
     * @Purpose:     To view calendar on frontend
     * @Param:       $action
     * @Return:      none
     */
    public function myCalendar($action = NULL) {
        //pr($this->ipDetail());die;
        $this->layout = "front/home";
        $this->loadModel("MyCalendar");
        $this->loadModel("Region");
        $this->loadModel("Category");
        $this->loadModel("Vibe");
        $this->loadModel("Zip");
        $this->loadModel("Search");

        // code for save search
        // Save Search Only If User Is Logged In
        if (AuthComponent::user("id")) {
            // Check If Existing Search Data Exist
            $existing_search_data = $this->Search->find("first", array("conditions" => array("Search.user_id" => AuthComponent::user("id"), "Search.calendar_type" => "my_calendar")));
            // check and clear search
            if ($action && !empty($existing_search_data)) {
                $this->Search->delete($existing_search_data["Search"]["id"]);
                $existing_search_data = array();
            }
            if ($this->data) {
                // delete existing search data
                if (!empty($existing_search_data)) {
                    $this->Search->delete($existing_search_data["Search"]["id"]);
                }
                // for save data to database
                $save_search_data["Search"]["user_id"] = AuthComponent::user("id");
                $save_search_data["Search"]["calendar_type"] = "my_calendar";
                $save_search_data["Search"]["title"] = $this->data["Event"]["title"];
                $save_search_data["Search"]["order"] = $this->data["Event"]["order"];
                $save_search_data["Search"]["date"] = $this->data["Event"]["date"];
                $save_search_data["Search"]["limit"] = $this->data["Event"]["limit"];
                if (isset($this->data["Event"]["is_feature"])) {
                    $save_search_data["Search"]["is_feature"] = $this->data["Event"]["is_feature"];
                }

                $save_search_data["Search"]["event_show"] = $this->data["Event"]["event_show"];
                if (isset($this->data["Event"]["giveaway"])) {
                    $save_search_data["Search"]["giveaway"] = $this->data["Event"]["giveaway"];
                }
                if (!empty($this->data["EventCategory"]["id"])) {

                    foreach ($this->data["EventCategory"]["id"] as $key => $value):
                        $categoryEvent[] = $key;
                    endforeach;
                    $save_search_data["Search"]["EventCategory"] = implode(",", $categoryEvent);
                }
                if (!empty($this->data["EventVibe"]["id"])) {
                    foreach ($this->data["EventVibe"]["id"] as $key => $value):
                        $vibeEvent[] = $key;
                    endforeach;
                    $save_search_data["Search"]["EventVibe"] = implode(",", $vibeEvent);
                }
                $this->Search->save($save_search_data);
            } else {
                // for fetch data from database
                if (!empty($existing_search_data)) {
                    $this->request->data["Event"]["title"] = $existing_search_data["Search"]["title"];
                    $this->request->data["Event"]["date"] = $existing_search_data["Search"]["date"];
                    $this->request->data["Event"]["limit"] = $existing_search_data["Search"]["limit"];
                    $this->request->data["Event"]["order"] = $existing_search_data["Search"]["order"];

                    if (!empty($existing_search_data["Search"]["is_feature"])) {
                        $this->request->data["Event"]["is_feature"] = $existing_search_data["Search"]["is_feature"];
                    }

                    $this->request->data["Event"]["event_show"] = $existing_search_data["Search"]["event_show"];

                    if (!empty($existing_search_data["Search"]["giveaway"])) {
                        $this->request->data["Event"]["giveaway"] = $existing_search_data["Search"]["giveaway"];
                    }

                    if (!empty($existing_search_data["Search"]["EventCategory"])) {
                        $categoryEvent = explode(",", $existing_search_data["Search"]["EventCategory"]);
                        foreach ($categoryEvent as $ce) {
                            $event_category["EventCategory"]["id"][$ce] = "on";
                        }
                        $this->request->data["EventCategory"] = $event_category["EventCategory"];
                    }

                    if (!empty($existing_search_data["Search"]["EventVibe"])) {
                        $vibeEvent = explode(",", $existing_search_data["Search"]["EventVibe"]);
                        foreach ($vibeEvent as $ve) {
                            $event_vibe["EventVibe"]["id"][$ve] = "on";
                        }
                        $this->request->data["EventVibe"] = $event_vibe["EventVibe"];
                    }
                }
            }
        }

        if (isset($this->data["Event"]["order"])) {
            $order = $this->data["Event"]["order"];
        } else {
            $order = "Event.id ASC";
        }
        $this->Event->unBindModel(array('hasMany' => array("EventCategory", "EventVibe"), 'belongsTo' => array('User')), false);

        $this->Event->bindModel(array('hasOne' => array("EventCategory", "EventVibe")), false);
       

        $regions = $this->Region->find("all", array("conditions" => array(), "recursive" => -1));
        $categories = $this->Category->find("all", array("conditions" => array(), "recursive" => -1));
        $vibes = $this->Vibe->find("all", array("conditions" => array(), "recursive" => -1));
        $this->set(compact('regions', 'categories', 'vibes'));
        #lets find out which event is added as my calendar
        $my_calendar = $this->MyCalendar->find("list", array("conditions" => array("MyCalendar.user_id" => AuthComponent::user("id")), "fields" => array("MyCalendar.id", "MyCalendar.event_id")));
        $this->set("my_calendar", $my_calendar);

   
        $conditions = array();
        if (!isset($this->params['named']['page']) && !isset($this->params['named']['sort'])) {
            if ($this->Session->read('conditions')) {
                $this->Session->delete('conditions');
            }
        }
        $conditions = array_merge($conditions, array("Event.option_to_show" => array(1, 3)));
        if ($this->data) {
            if (isset($this->data["Event"]["giveaway"]) && $this->data["Event"]["giveaway"] == 1) {
                $conditions = array_merge($conditions, array("Giveaway.id !=" => ""));
                $this->set("giveaway", 1);
            } else {
                $this->set("giveaway", 0);
            }
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
         
        }
        $conditions = array_merge($conditions, array('Event.id' => $my_calendar));


        #Event Show Condition start

        if (isset($this->data["Event"]["event_show"]) && !empty($this->data["Event"]["event_show"])) {
            $events_show = $this->data["Event"]["event_show"];
            if ($events_show == "FB") {
                $conditions = array_merge($conditions, array("Event.event_from" => "facebook"));
                $ev = "FB";
            } elseif ($events_show == "EF") {
                $conditions = array_merge($conditions, array("Event.event_from" => "eventful"));
                $ev = "EF";
            } elseif ($events_show == "ALH") {
                $conditions = array_merge($conditions, array("Event.event_from" => "ALH"));
                $ev = "ALH";
            } else {
                $conditions = array_merge($conditions, array());
                $ev = "all";
            }
        } else {
            $conditions = array_merge($conditions, array());
            $ev = "all";
        }
        $this->set("event_show", $ev);


        #Event Show Condition End



        if (isset($this->data["Event"]["limit"]) && !empty($this->data["Event"]["limit"])) {
          
            $limit = $this->data["Event"]["limit"];
        } else {
            $limit = 10;
        }
        $this->set("limit", $limit);

        if (isset($this->params["named"]["page"])) {
            $conditions = $this->Session->read("my_calender_conditions");
        } else if (!empty($conditions)) {
            $this->Session->write("my_calender_conditions", $conditions);
        }

        $this->paginate = array('conditions' => $conditions, 'limit' => $limit, 'order' => $order, 'group' => 'Event.id');

        $this->set('events', $this->paginate('Event'));
    }

    /**
     * @Created:        20-May-2014
     * @Method :        eventStatus
     * @Author: 	Sachin Thakur
     * @Modified :	
     * @Purpose:   	Function to update the delete status
     * @Param:     	$eventId
     * @Return:    	None
     */
    function eventStatus($eventId = NULL) {
        $this->layout = 'frontend';
        $this->Event->updateAll(array('Event.is_deleted' => 1), array('Event.id' => $eventId));
        echo "Success";
        exit;
    }

    /**
     * @Created:        30-May-2014
     * @Method :        admin_addBanner
     * @Author: 	Prateek Jadhav
     * @Modified :	
     * @Purpose:   	Function to add/edit the banner for wordpress
     * @Param:     	$id
     * @Return:    	none
     */
    public function admin_addBanner($id = null) {
        $this->layout = "admin/admin";
        $this->set('title_for_layout', 'ALIST Hub :: Add Banner');
        $this->loadModel("BannerAdd");
        $this->set("events", $this->Event->find("list", array("conditions" => array("Event.status" => 1), "fields" => array("Event.id", "Event.title"))));
        if ($this->data) {
            $tmp = $this->data;
            #save flyer_image
            if (!empty($this->data['BannerAdd']['banner']["name"]) && is_array($this->data['BannerAdd']['banner'])) {
                $imgNameExt = pathinfo($this->data['BannerAdd']['banner']['name']);
                $ext = $imgNameExt['extension'];
                $ext = strtolower($ext);
                if ($ext == 'jpg' || $ext == 'jpeg' || $ext == 'png' || $ext == 'gif') {
                    $newImgName = 'Banner_' . time();
                    $tempFile = $this->data['BannerAdd']['banner']['tmp_name'];
                    $destLarge = realpath('../webroot/img/BannerAdd/large/') . '/';
                    $destThumb = realpath('../webroot/img/BannerAdd/small/') . '/';
                    $destOriginal = realpath('../webroot/img/BannerAdd/original/') . '/';
                    $file = $this->data['BannerAdd']['banner'];
                    $file['name'] = $imgNameExt['filename'] . '.' . $ext;
                    if ($ext == 'gif') {
                        $result = $this->Upload->upload($file, $destLarge, $newImgName . ".gif", array('type' => 'resizemin', 'size' => array('500', '400')));
                        $result = $this->Upload->upload($file, $destOriginal, $newImgName . ".gif", NULL, array('gif'));
                        $result = $this->Upload->upload($file, $destThumb, $newImgName . ".gif", array('type' => 'resizemin', 'size' => array('75', '75')));
                        $name = $newImgName . ".gif";
                    } else if ($ext == 'png') {
                        $result = $this->Upload->upload($file, $destLarge, $newImgName . ".png", array('type' => 'resizemin', 'size' => array('500', '400')));
                        $result = $this->Upload->upload($file, $destOriginal, $newImgName . ".png", NULL, array('png'));
                        $result = $this->Upload->upload($file, $destThumb, $newImgName . ".png", array('type' => 'resizemin', 'size' => array('75', '75')));
                        $name = $newImgName . ".png";
                    } else if ($ext == 'jpeg') {
                        $result = $this->Upload->upload($file, $destLarge, $newImgName . ".jpeg", array('type' => 'resizemin', 'size' => array('500', '400')));
                        $result = $this->Upload->upload($file, $destOriginal, $newImgName . ".jpeg", NULL, array('jpeg'));
                        $result = $this->Upload->upload($file, $destThumb, $newImgName . ".jpeg", array('type' => 'resizemin', 'size' => array('75', '75')));
                        $name = $newImgName . ".jpeg";
                    } else {
                        $result = $this->Upload->upload($file, $destLarge, $newImgName . ".jpg", array('type' => 'resizemin', 'size' => array('500', '400')));
                        $result = $this->Upload->upload($file, $destOriginal, $newImgName . ".jpg", NULL, array('jpg'));
                        $result = $this->Upload->upload($file, $destThumb, $newImgName . ".jpg", array('type' => 'resizemin', 'size' => array('75', '75')));
                        $name = $newImgName . ".jpg";
                    }
                    $tmp["BannerAdd"]["banner"] = $name;
                }
            } else {
                unset($tmp["BannerAdd"]["banner"]);
            }
            if ($this->BannerAdd->save($tmp)) {
                $this->Session->setFlash("Banner Saved", "default", array("class" => "green"));
                $this->redirect(array("controller" => "Events", "action" => "listBanner"));
            } else {
                $this->Session->setFlash("Unable to Saved Banner", "default", array("class" => "red"));
            }
        }
        if ($id) {
            $id = base64_decode($id);
            $this->request->data = $this->BannerAdd->findById($id);
        }
    }

    /**
     * @Created:        30-May-2014
     * @Method :        admin_listBanner
     * @Author: 	Prateek Jadhav
     * @Modified :	
     * @Purpose:   	Function to list/manage the banner for wordpress
     * @Param:     	$id
     * @Return:    	none
     */
    public function admin_listBanner() {
        $this->layout = "admin/admin";
        $this->set('title_for_layout', 'ALIST Hub :: Banner List');
        $this->loadModel("BannerAdd");
        $this->paginate = array('limit' => 10, 'order' => "Event.title", "fields" => array("BannerAdd.*", "Event.title", "User.username"));
        $this->set('banners', $this->paginate('BannerAdd'));
    }

    /**
     * @Created:        2-Jun-2014
     * @Method :        admin_addGiveaway
     * @Author: 	Prateek Jadhav
     * @Modified :	
     * @Purpose:   	Function to add/edit the giveaway
     * @Param:     	$id/none
     * @Return:    	none
     */
    public function admin_addGiveaway($id = NULL) {
        $this->layout = "admin/admin";
        $this->set('title_for_layout', 'ALIST Hub :: Add GiveAway');
        $this->loadModel("Giveaway");
        // set events
        $this->set("events", $this->Event->find("list", array("conditions" => array("Event.status" => 1), "fields" => array("Event.id", "Event.title"),"order" => "Event.title ASC")));
        if ($this->data) {
            if ($this->Giveaway->save($this->data)) {
                 if(empty($this->data['Giveaway']["id"]))
                {
                    $upMsg = "Added Successfully"; 
                }
                else
                {
                    $upMsg = "Updated Successfully";
                }
                
                $this->Session->setFlash($upMsg, "default", array("class" => "green"));
                $this->redirect(array("controller" => "Events", "action" => "listGiveaway"));
            } else {
                $this->Session->setFlash("Unable to save giveaway", "default", array("class" => "red"));
            }
        }
        if ($id) {
            $this->request->data = $this->Giveaway->findById(base64_decode($id));
        }
    }

    /*
     * @Created:        2-Jun-2014
     * @Method :        admin_listGiveaway
     * @Author: 	Prateek Jadhav
     * @Modified :	
     * @Purpose:   	Function to list/manage the giveaway
     * @Param:     	none
     * @Return:    	none
     */

    public function admin_listGiveaway() {
        $this->layout = 'admin/admin';
        $this->set('title_for_layout', 'ALIST Hub :: List Giveaway');
        $this->loadModel("Giveaway");
        $conditions = array();
        if (isset($this->params["named"]["page"])) {
            $this->request->data = $this->Session->read("giveaway_list");
        } else {
            $this->Session->delete("giveaway_list");
        }
        if ($this->data) {
            if (!empty($this->data["Event"]["title"])) {
                $conditions = array_merge($conditions, array("Event.title LIKE" => "%" . $this->data["Event"]["title"] . "%"));
            }
            $this->paginate = array('conditions' => $conditions, 'limit' => $this->data["Giveaway"]["limit"], 'order' => array($this->data["Giveaway"]["order"] => $this->data["Giveaway"]["direction"]));
            $this->request->data = $this->data;
            $this->Session->write("giveaway_list", $this->data);
        } else {
            $this->paginate = array('conditions' => $conditions, 'limit' => 10, 'order' => array('Event.title' => 'DESC'));
        }
     
        $this->set('giveaways', $this->paginate('Giveaway'));
    }

    /*
     * @Created:        10-Jun-2014
     * @Method :        fetch
     * @Author: 	Prateek Jadhav
     * @Modified :	
     * @Purpose:   	Function to fetch invities
     * @Param:     	email, password, provider,
     * @Return:    	none
     */

    public function fetch($list = null) {
        $this->layout = false;
        $this->autoRender = false;
        $this->loadModel("EmailTemplate");
       if(!empty($list))
       {
        
        $event = $this->Event->findById($this->Session->read('event_id'));
        $event["Event"]["event_steps"] = 4;
        $this->Event->save($event);
        
        $newList = explode(",",trim($list));
                           
         foreach($newList as $emailList)
           {
         
              $eList = array_reverse(explode(" ",trim($emailList)));
              $emailTrim = trim($eList[0]);
          
              $emailLeftTrim = array_reverse(explode('<',$emailTrim));
          
              $emailRightTrim = explode('>',$emailLeftTrim[0]);
          
              $emailAdd = $emailRightTrim[0];
              
              $url = "http://www.alisthub.com/".'Events/viewEvent/'.base64_encode($this->Session->read('event_id'));
              $activation = '<a style="background-color: #6A6A6A;border-radius: 2px 2px 2px 2px;color: #FFFFFF;display: inline-block;font-size: 14px;font-weight: bold;padding: 10px 19px;text-decoration: none;" target="_blank" href="' . $url . '">CLICK HERE TO CHECK EVENT DETAILS</a>';
          
             
                    $emailTemp = $this->EmailTemplate->find('first', array('conditions' => array('EmailTemplate.alias' => "share-event")));
                   
                    $emailContent = utf8_decode($emailTemp['EmailTemplate']['description']);
               
                    $data = str_replace(array('{SHARE_EVENT}'), array($activation), $emailContent);
                    
                    $this->set('mailData', $data);
                    $this->Email->to = $emailAdd;
                    $this->Email->subject = "Invitation for event " . $event["Event"]["title"];
                    $this->Email->from = "alisthub@admin.com";
                    $this->Email->template = "forgot";
                    $this->Email->sendAs = 'html';
                    $this->Email->send();
                    
           }  
     
       return "Event has been Successfully Shared with your selected Users.";
       }
       
       return "Event not shared Successfully because list was Empty.";
    }

    /*
     * @Created:        10-Jun-2014
     * @Method :        invite
     * @Author: 	Prateek Jadhav
     * @Modified :	
     * @Purpose:   	Function to send invitation to invities
     * @Param:     	none
     * @Return:    	none
     */

    public function invite() {
        $this->layout = false;
        $this->autoRender = false;
        $this->loadModel("EmailTemplate");
        if ($this->data) {
            $event = $this->Event->findById($this->Session->read('event_id'));
            $event["Event"]["event_steps"] = 4;
            $this->Event->save($event);
            $this->loadModel("MyList");
            $this->loadModel("ListEmail");
            $data["MyList"]["id"] = "";
            $data["MyList"]["list_name"] = $event["Event"]["title"] . "_" . $this->data["Event"]["provider"];
            $data["MyList"]["user_id"] = AuthComponent::user("id");
            $this->MyList->save($data);
            $my_list_id = $this->MyList->getLastInsertID();
            $this->set('mailData', $this->data["Event"]["message"]);

            $url = "http://" . $this->data["Event"]["message"];
            $activation = '<a style="background-color: #6A6A6A;border-radius: 2px 2px 2px 2px;color: #FFFFFF;display: inline-block;font-size: 14px;font-weight: bold;padding: 10px 19px;text-decoration: none;" target="_blank" href="' . $url . '">CLICK HERE TO CHECK EVENT DETAILS</a>';
            foreach ($this->data['IDs'] as $email) {
                if (trim($email) != "0") {

                    $emailTemp = $this->EmailTemplate->find('first', array('conditions' => array('EmailTemplate.alias' => "share-event")));
                    $emailContent = utf8_decode($emailTemp['EmailTemplate']['description']);
                    $data = str_replace(array('{SHARE_EVENT}'), array($activation), $emailContent);

                    $this->Email->to = $email;
                    $this->Email->subject = "Invitation for event " . $event["Event"]["title"];
                    $this->Email->from = "alisthub@admin.com";
                    $this->Email->template = "forgot";
                    $this->Email->sendAs = 'html';
                    $this->Email->send();
                    $abc["ListEmail"]["id"] = "";
                    $abc["ListEmail"]["my_list_id"] = $my_list_id;
                    $abc["ListEmail"]["email"] = $email;
                    $this->ListEmail->save($abc);
                } else {
                    
                }
            }
        }
        $this->Session->delete("event_id");
        $this->Session->delete("payment_id");
        $this->Session->setFlash("Invitation send", "default", array("class" => "green"));
        $this->redirect(array("controller" => "Users", "action" => "dashboard"));
    }

    /*
     * @Created:        10-Jun-2014
     * @Method :        inviteFromList
     * @Author: 	Prateek Jadhav
     * @Modified :	
     * @Purpose:   	Function to send invitation to invities
     * @Param:     	none
     * @Return:    	none
     */

    public function inviteFromList() {
        $this->layout = false;
        $this->autoRender = false;
        if ($this->data) {
            $event = $this->Event->findById($this->Session->read('event_id'));
            $event["Event"]["event_steps"] = 4;
            $this->Event->save($event);
            $this->loadModel("MyList");
            $this->loadModel("ListEmail");
            $list = $this->ListEmail->find("list", array("conditions" => array("ListEmail.my_list_id" => $this->data["Event"]["list"])));
            $this->set('mailData', $this->data["Event"]["message"]);
            foreach ($list as $email) {
                //echo "<br/>".$email;
                if (trim($email) != "0") {
                    $this->Email->to = $email;
                    $this->Email->subject = "Invitation for event " . $event["Event"]["title"];
                    $this->Email->from = "aisthub@admin.com";
                    $this->Email->template = "forgot";
                    $this->Email->sendAs = 'html';
                    if ($this->Email->send()) {
                        //echo "yes";
                    } else {
                        //echo "no";
                    }
                } else {
                    //echo "ok here";
                }
            }
        }
        $this->Session->setFlash("Invitation send", "default", array("class" => "green"));
        $this->redirect(array("controller" => "Users", "action" => "dashboard"));
    }

    /** @Created:    13-Jun-2014
     * @Method :     MyEventList
     * @Author:      Arjun Dev
     * @Modified :   ---
     * @Purpose:     To view event list on frontend
     * @Param:       $event_id
     * @Return:      none
     */
    public function MyEventList() {
        $this->layout = "front/home";
        $this->loadModel("Event");
        $this->loadModel("User");
        // lets find out which event is added as my calendar

        $user = $this->User->find("first", array("conditions" => array("User.id" => AuthComponent::user("id"))));
     

        $conditions = array();
        // now for sort by

        $order = "Event.id DESC";

     
        $conditions = array_merge($conditions, array("Event.user_id" => AuthComponent::user("id"), 'OR' => array(array("Event.event_from" => "ALH"), array("Event.event_from" => "facebook", "Event.fbevent_ownerid" => $user['User']['fbID']))));
       
        $this->paginate = array('recursive' => 2, 'conditions' => $conditions, 'limit' => 10, 'order' => $order);
      
        $this->set('events', $this->paginate('Event'));
    }

    /*
     * @Created:        23-Jun-2014
     * @Method :        delEventImg
     * @Author: 	Sachin Thakur
     * @Modified :	
     * @Purpose:   	Function to delete Image
     * @Param:     	none
     * @Return:    	none
     */

    function delEventImg($ImgId = NULL, $ImgName) {
        $this->layout = false;
        $this->autoRender = false;
        $this->loadModel("EventImage");
        if (!empty($ImgId)) {
            $id = base64_decode($ImgId);
           
            $this->EventImage->deleteAll(array('EventImage.id' => $id), false);
            unlink(WWW_ROOT . "img/EventImages/large" . $ImgName);
            unlink(WWW_ROOT . "img/EventImages/original" . $ImgName);
            unlink(WWW_ROOT . "img/EventImages/small" . $ImgName);
        }
        $this->redirect($this->referer());
    }

    /*
     * @Created:        24-Jun-2014
     * @Method :        findCity
     * @Author: 	Sachin Thakur
     * @Modified :	
     * @Purpose:   	Function to find city
     * @Param:     	none
     * @Return:    	none
     */

    function findCity($stateabb = NULL) {
        $this->layout = "ajax";
        $this->autoRender = false;
        $this->loadModel('Zip');
        $cities = $this->Zip->find('list', array('conditions' => array('Zip.State' => $stateabb), 'fields' => array('Zip.zip', 'Zip.city'), "group" => "Zip.city"));
        $cities = array_values($cities);
        $cities = json_encode($cities);
        return $cities;
    }

    /*
     * @Created:        24-Jun-2014
     * @Method :        function addToMyCalendar
     * @Author: 	Sachin Thakur
     * @Modified :	
     * @Purpose:   	Function to add to my calender
     * @Param:     	$event_id
     * @Return:    	none
     */

    public function addToMyCalendar($event_id = NULL) {
        $this->layout = FALSE;
        $this->autoRender = FALSE;
        $this->loadModel("MyCalendar");
        if ($event_id) {
            // check data
            $old_data = $this->MyCalendar->find("first", array("conditions" => array("MyCalendar.user_id" => AuthComponent::user("id"), "MyCalendar.event_id" => $event_id)));
           
            if (!empty($old_data)) {
                if ($this->MyCalendar->delete($old_data["MyCalendar"]["id"])) {
                    return 1;
                } else {
                    return 0;
                }
            } else {
                $data["MyCalendar"]["user_id"] = AuthComponent::user("id");
                $data["MyCalendar"]["event_id"] = $event_id;
                $data["MyCalendar"]["status"] = 1;
                $data["MyCalendar"]["id"] = ""; //pr($data);
                if ($this->MyCalendar->save($data)) {
                    return 2;
                } else {
                    return 0;
                }
            }
        } else {
            return 0;
        }
    }

    /*
     * @Created:        08-Jul-2014
     * @Method :        function addToMyWpplugin
     * @Author: 	Arjun Dev
     * @Modified :	
     * @Purpose:   	Function to add to my Wp Plugin
     * @Param:     	$event_id
     * @Return:    	none
     */

    public function addToMyWpplugin($event_id = NULL) {
        $this->layout = FALSE;
        $this->autoRender = FALSE;
        $this->loadModel("MyWpplugin");
        if ($event_id) {
            // check data
            $old_data = $this->MyWpplugin->find("first", array("conditions" => array("MyWpplugin.user_id" => AuthComponent::user("id"), "MyWpplugin.event_id" => $event_id)));
          
            if (!empty($old_data)) {
                if ($this->MyWpplugin->delete($old_data["MyWpplugin"]["id"])) {
                    return 1;
                } else {
                    return 0;
                }
            } else {
                $data["MyWpplugin"]["user_id"] = AuthComponent::user("id");
                $data["MyWpplugin"]["event_id"] = $event_id;
                $data["MyWpplugin"]["status"] = 1;
                $data["MyWpplugin"]["id"] = ""; //pr($data);
                if ($this->MyWpplugin->save($data)) {
                    return 2;
                } else {
                    return 0;
                }
            }
        } else {
            return 0;
        }
    }

    /*
     * @Created:        25-Jun-2014
     * @Method :        eventfulEvents
     * @Author: 	Arjun Dev
     * @Modified :	
     * @Purpose:   	Function to desplay eventful eventlist
     * @Param:     	none
     * @Return:    	none
     */

    function eventfulEvents($action = NULL) {

        $this->layout = "front/home";
        $this->loadModel("MyCalendar");
        $this->loadModel("MyWpplugin");
        $this->loadModel("Zip");
        $this->loadModel("Search");


        if (isset($this->data["Event"]["event_show"]) && $this->data["Event"]["event_show"] != "EF") {
            $this->redirect('/Events/calendar');
        }

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
            $save_search_data["Search"]["calendar_type"] = "eventful";
            $save_search_data["Search"]["title"] = $this->data["Event"]["keyword"];
            $save_search_data["Search"]["date"] = $this->data["Event"]["date"];

            $save_search_data["Search"]["distance"] = $this->data["Event"]["distance"];
            $save_search_data["Search"]["zip"] = $this->data["Event"]["city"];
            // $save_search_data["Search"]["event_show"] = $this->data["Event"]["event_show"];

            if (!empty($this->data["cat"])) {

                foreach ($this->data["cat"] as $key => $value):
                    $categoryEvent[] = $key;
                endforeach;
                $save_search_data["Search"]["EventCategory"] = implode(",", $categoryEvent);
            }

            $this->Search->save($save_search_data);
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





        #lets find out which event is added as my Calender
        $my_calendar = $this->MyCalendar->find("list", array("conditions" => array("MyCalendar.user_id" => AuthComponent::user("id")), "fields" => array("MyCalendar.id", "MyCalendar.ef_event_id")));
        $this->set("my_calendar", $my_calendar);

        #lets find out which event is added as my Wp plugin
        $my_wpplugin = $this->MyWpplugin->find("list", array("conditions" => array("MyWpplugin.user_id" => AuthComponent::user("id")), "fields" => array("MyWpplugin.id", "MyWpplugin.ef_event_id")));
        $this->set("my_wpplugin", $my_wpplugin);


        $ipDetail = $this->ipDetail();
        if (isset($this->data["Event"]["city"])) {
            $Zip = $this->data["Event"]["city"];
        } else {
            $Zip = $ipDetail->zipCode;
        }
        $zip_detail = $this->Zip->findByZip($Zip);
        if (isset($zip_detail['Zip']['city'])) {
            $zip_city = $zip_detail['Zip']['city'];
            $z = $Zip;
        } else {
            $zip_city = 'United States';
            $z = 'United States';
        }
        require_once "Eventful_new.php";

        $AppKey = "qGt8LCwr2CVprzVW";

        $eV = new Eventful($AppKey);
        if (isset($_GET['pages'])) {
            $pages = $_GET['pages'];
        } else {
            $pages = "";
        }
        if (!empty($this->data)) {
            if (isset($this->data["cat"])) {
                foreach ($this->data["cat"] as $CatKey => $Cat) {
                    $CatArray[] = $CatKey;
                }

                $CatSearch = implode(",", $CatArray);
            } else {
                $CatSearch = '';
            }
            $category = $CatSearch;
            $within = $this->data["Event"]["distance"];
            $location = $z;
            $keywords = $this->data["Event"]["keyword"];
            $date = $this->data["Event"]["date"];
        } else {
            $location = $z;
            $within = '30';
            $keywords = '';
            $date = '';
            $category = '';
            if (isset($_GET['pages'])) {
                $this->request->data["Event"]["city"] = $this->Session->read("con_zip");
            } else {
                $this->request->data["Event"]["city"] = $ipDetail->zipCode;
            }
        }
        if (isset($_GET['pages'])) {
            /*         $location = $this->Session->read("con_location");
              $within = $this->Session->read("con_within");
              $keywords = $this->Session->read("con_keywords");
              $date = $this->Session->read("con_date");
              $category = $this->Session->read("con_category");
              $Zip = $this->Session->read("con_zip");
              $zip_city = $this->Session->read("con_zip_city"); */
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
            );

            $cEvent = $eV->call('events/search', $evArgs);
        

            $this->set('eevent', $cEvent);
          
            #Categories List
            $catList = $eV->call('categories/list');
            $this->set('catList', json_decode(json_encode($catList), true));
          
        } else {
            die("<strong>Error logging into Eventful API</strong>");
        }
    }

    public function ticketGiveawayMultiple() {
        $this->layout = "front/home";
        $this->loadModel("TicketGiveaway");

        if ($this->data) {
            if (isset($this->data["Event"]["event_id_name"])) {
                $event_id_name = $this->data["Event"]["event_id_name"];
                $event_id_name = json_decode($event_id_name, TRUE);
                $this->set("events", $event_id_name);
            } else {
                $event_ids = $this->data["TicketGiveaway"]["event_id"];
                foreach ($event_ids as $event_id) {
                    if ($event_id != 0) {
                        $data = $this->data;
                        unset($data["TicketGiveaway"]["event_id"]);
                        $data["TicketGiveaway"]["event_id"] = $event_id;
                        $data["TicketGiveaway"]["id"] = "";
                        $data["TicketGiveaway"]["user_id"] = AuthComponent::user("id");
                        $this->TicketGiveaway->save($data);
                    }
                }
                $this->Session->setFlash("Your request has been submited", "default", array("class" => "green"));
                $this->redirect(array("controller" => "Events", "action" => "calendar"));
            }
        } else {
            $this->redirect(array("controller" => "Events", "action" => "calendar"));
        }
    }

    /** @Created:    12-May-2014
     * @Method :     myWpplugin
     * @Author:      Arjun Dev
     * @Modified :   ---
     * @Purpose:     To view wp-plugin on frontend
     * @Param:       $event_id
     * @Return:      none
     */
    public function myWpplugin() {
        $this->layout = "front/home";
        $this->set('title_for_layout', 'ALIST Hub :: WP-Plugin');
        $this->loadModel("MyCalendar");
        $this->loadModel("MyWpplugin");
        $this->loadModel("Region");
        $this->loadModel("Category");
        $this->loadModel("Vibe");
        if (isset($this->data["Event"]["order"])) {
            $order = $this->data["Event"]["order"];
        } else {
            $order = "Event.id ASC";
        }
        $this->Event->unBindModel(array('hasMany' => array("EventCategory", "EventVibe"), 'belongsTo' => array('User')), false);

        $this->Event->bindModel(array('hasOne' => array("EventCategory", "EventVibe")), false);
        

        $regions = $this->Region->find("all", array("conditions" => array(), "recursive" => -1));
        $categories = $this->Category->find("all", array("conditions" => array(), "recursive" => -1));
        $vibes = $this->Vibe->find("all", array("conditions" => array(), "recursive" => -1));
        $this->set(compact('regions', 'categories', 'vibes'));
        #lets find out which event is added as my calendar
        $my_calendar = $this->MyCalendar->find("list", array("conditions" => array("MyCalendar.user_id" => AuthComponent::user("id")), "fields" => array("MyCalendar.id", "MyCalendar.event_id")));
        $this->set("my_calendar", $my_calendar);

        #lets find out which event is added as my Wp plugin
        $my_wpplugin = $this->MyWpplugin->find("list", array("conditions" => array("MyWpplugin.user_id" => AuthComponent::user("id")), "fields" => array("MyWpplugin.id", "MyWpplugin.event_id")));
        $this->set("my_wpplugin", $my_wpplugin);

     
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

        if (!empty($this->data["Event"]["list"])) {
            if ($this->data["Event"]["list"] == '1') {
                $conditions = array_merge($conditions, array("Event.id" => $my_wpplugin));
            }
            if ($this->data["Event"]["list"] == '3') {
                $conditions = array_merge($conditions, array("Event.id !=" => $my_wpplugin));
            }
        }

        #Event Show Condition start

        if (isset($this->data["Event"]["event_show"]) && !empty($this->data["Event"]["event_show"])) {
            $events_show = $this->data["Event"]["event_show"];
            if ($events_show == "FB") {
                $conditions = array_merge($conditions, array("Event.event_from" => "facebook"));
                $ev = "FB";
            } elseif ($events_show == "EF") {
                $conditions = array_merge($conditions, array("Event.event_from" => "eventful"));
                $ev = "EF";
            } elseif ($events_show == "ALH") {
                $conditions = array_merge($conditions, array("Event.event_from" => "ALH"));
                $ev = "ALH";
            } else {
                $conditions = array_merge($conditions, array());
                $ev = "all";
            }
        } else {
            $conditions = array_merge($conditions, array());
            $ev = "all";
        }
        $this->set("event_show", $ev);


        #Event Show Condition End


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

        $this->paginate = array('conditions' => $this->Session->read('conditions'), 'limit' => $limit, 'order' => $order, 'group' => 'Event.id');

        $this->set('events', $this->paginate('Event'));
        
    }

    /** @Created:    06-May-2014
     * @Method :     admin_createEvent
     * @Author:      Prateek Jadhav
     * @Modified :   ---
     * @Purpose:     to add and modify events
     * @Param:       none
     * @Return:      none
     */
    public function editEvent($id = null) {

        $this->layout = 'front/home';
        $this->set('title_for_layout', 'ALIST Hub :: Edit Event');

        $this->loadModel('Category');
        $this->loadModel('Vibe');
        $this->loadModel('Zip');
        $this->loadModel('Address');
        $this->set("referer", $this->referer());


        $this->set("zip", $this->Zip->find("list", array("group" => array("Zip.state"), "fields" => array("Zip.state", "Zip.state_name"))));
        #Fetch Categories
        $categories = $this->Category->find('list', array("conditions" => array("Category.status" => 1), "fields" => array("Category.id", "Category.name"), "order" => "Category.name ASC"));
        #Fetch Vibes
        $vibes = $this->Vibe->find('list', array("conditions" => array("Vibe.status" => 1), "fields" => array("Vibe.id", "Vibe.name"), "order" => "Vibe.name ASC"));
        $this->set(compact('categories', 'vibes'));
      
        $address1 = '';
        $address2 = '';
        if ($this->data) {



            if (isset($this->data["Event"]["video"]) && !empty($this->data["Event"]["video"])) {
                $test_string = $this->data["Event"]["video"];
                $ts = explode("v=", $test_string);
                if (!empty($ts))
                    $this->request->data["Event"]["video"] = $ts[1];
            }
            // $this->request->data["Event"]["user_id"] = AuthComponent::user("id");
            if (!empty($this->request->data["Event"]["specify"])) {
                $specify = $this->request->data["Event"]["specify"];
                $AddressData = $this->Address->find('first', array('conditions' => array('Address.user_id' => $this->Auth->User('id'), 'Address.name' => $specify), 'fields' => array('billing_address_1', 'billing_address_2')));
                $address1 = $AddressData['Address']['billing_address_1'];
                $address2 = $AddressData['Address']['billing_address_2'];

                $this->request->data["Event"]["event_address"] = $address1 . "," . $address2;
            } else {
                $this->request->data["Event"]["specify"] = $this->data["Event"]["address_name"];
                $this->request->data["Event"]["event_address"] = $this->data["Event"]["event_address1"] . "," . $this->data["Event"]["event_address2"];
            }
      
            $tmp = $this->data; 
            #save flyer image
            if (!empty($this->data['Event']['flyer_image']['name'])) {
                $imgNameExt = pathinfo($this->data['Event']['flyer_image']['name']);
                $ext = $imgNameExt['extension'];
                $ext = strtolower($ext);
                if ($ext == 'jpg' || $ext == 'jpeg' || $ext == 'png' || $ext == 'gif') {
                    $newImgName = 'flyer_image_' . time();
                    $tempFile = $this->data['Event']['flyer_image']['tmp_name'];
                    $destLarge = realpath('../webroot/img/flyerImage/large/') . '/';
                    $destThumb = realpath('../webroot/img/flyerImage/small/') . '/';
                    $destOriginal = realpath('../webroot/img/flyerImage/original/') . '/';
                    $file = $this->data['Event']['flyer_image'];
                    $file['name'] = $imgNameExt['filename'] . '.' . $ext;
                    if ($ext == 'gif') {
                        $result = $this->Upload->upload($file, $destLarge, $newImgName . ".gif", array('type' => 'resizemin', 'size' => array('300', '450')));
                        $result = $this->Upload->upload($file, $destOriginal, $newImgName . ".gif", NULL, array('gif'));
                        $result = $this->Upload->upload($file, $destThumb, $newImgName . ".gif", array('type' => 'resizemin', 'size' => array('120', '120')));
                        $name = $newImgName . ".gif";
                    } else if ($ext == 'png') {
                        $result = $this->Upload->upload($file, $destLarge, $newImgName . ".png", array('type' => 'resizemin', 'size' => array('300', '450')));
                        $result = $this->Upload->upload($file, $destOriginal, $newImgName . ".png", NULL, array('png'));
                        $result = $this->Upload->upload($file, $destThumb, $newImgName . ".png", array('type' => 'resizemin', 'size' => array('120', '120')));
                        $name = $newImgName . ".png";
                    } else if ($ext == 'jpeg') {
                        $result = $this->Upload->upload($file, $destLarge, $newImgName . ".jpeg", array('type' => 'resizemin', 'size' => array('300', '450')));
                        $result = $this->Upload->upload($file, $destOriginal, $newImgName . ".jpeg", NULL, array('jpeg'));
                        $result = $this->Upload->upload($file, $destThumb, $newImgName . ".jpeg", array('type' => 'resizemin', 'size' => array('120', '120')));
                        $name = $newImgName . ".jpeg";
                    } else {
                        $result = $this->Upload->upload($file, $destLarge, $newImgName . ".jpg", array('type' => 'resizemin', 'size' => array('300', '450')));
                        $result = $this->Upload->upload($file, $destOriginal, $newImgName . ".jpg", NULL, array('jpg'));
                        $result = $this->Upload->upload($file, $destThumb, $newImgName . ".jpg", array('type' => 'resizemin', 'size' => array('120', '120')));
                        $name = $newImgName . ".jpg";
                    }
                    $tmp["Event"]["flyer_image"] = $name;
                }
            } else {
                unset($tmp["Event"]["flyer_image"]);
            }
            # now save Event Primary Image
            if (!empty($this->data['Event']['image_name']['name'])) {
                $imgNameExt = pathinfo($this->data['Event']['image_name']['name']);
                $ext = $imgNameExt['extension'];
                $ext = strtolower($ext);
                if ($ext == 'jpg' || $ext == 'jpeg' || $ext == 'png' || $ext == 'gif') {
                    $newImgName = 'EventImage' . time();
                    $tempFile = $this->data['Event']['image_name']['tmp_name'];
                    $destLarge = realpath('../webroot/img/EventImages/large/') . '/';
                    $destThumb = realpath('../webroot/img/EventImages/small/') . '/';
                    $destOriginal = realpath('../webroot/img/EventImages/original/') . '/';
                    $file = $this->data['Event']['image_name'];
                    $file['name'] = $imgNameExt['filename'] . '.' . $ext;
                    if ($ext == 'gif') {
                        $result = $this->Upload->upload($file, $destLarge, $newImgName . ".gif", array('type' => 'resizemin', 'size' => array('300', '450')));
                        $result = $this->Upload->upload($file, $destOriginal, $newImgName . ".gif", NULL, array('gif'));
                        $result = $this->Upload->upload($file, $destThumb, $newImgName . ".gif", array('type' => 'resizemin', 'size' => array('400', '400')));
                        $name = $newImgName . ".gif";
                    } else if ($ext == 'png') {
                        $result = $this->Upload->upload($file, $destLarge, $newImgName . ".png", array('type' => 'resizemin', 'size' => array('300', '450')));
                        $result = $this->Upload->upload($file, $destOriginal, $newImgName . ".png", NULL, array('png'));
                        $result = $this->Upload->upload($file, $destThumb, $newImgName . ".png", array('type' => 'resizemin', 'size' => array('400', '400')));
                        $name = $newImgName . ".png";
                    } else if ($ext == 'jpeg') {
                        $result = $this->Upload->upload($file, $destLarge, $newImgName . ".jpeg", array('type' => 'resizemin', 'size' => array('300', '450')));
                        $result = $this->Upload->upload($file, $destOriginal, $newImgName . ".jpeg", NULL, array('jpeg'));
                        $result = $this->Upload->upload($file, $destThumb, $newImgName . ".jpeg", array('type' => 'resizemin', 'size' => array('400', '400')));
                        $name = $newImgName . ".jpeg";
                    } else {
                        $result = $this->Upload->upload($file, $destLarge, $newImgName . ".jpg", array('type' => 'resizemin', 'size' => array('300', '450')));
                        $result = $this->Upload->upload($file, $destOriginal, $newImgName . ".jpg", NULL, array('jpg'));
                        $result = $this->Upload->upload($file, $destThumb, $newImgName . ".jpg", array('type' => 'resizemin', 'size' => array('400', '400')));
                        $name = $newImgName . ".jpg";
                    }
                    $tmp["Event"]["image_name"] = $name;
                }
            } else {
                unset($tmp["Event"]["image_name"]);
            }

            if (isset($tmp['Event']['option_to_show'][1]) && $tmp['Event']['option_to_show'][1] == 1 && isset($tmp['Event']['option_to_show'][3]) && $tmp['Event']['option_to_show'][3] == 1) {
                $tmp['Event']['option_to_show'] = 3;
            } elseif (isset($tmp['Event']['option_to_show'][1]) && $tmp['Event']['option_to_show'][1] == 1) {
                $tmp['Event']['option_to_show'] = 1;
            } elseif (isset($tmp['Event']['option_to_show'][3]) && $tmp['Event']['option_to_show'][3] == 1) {
                $tmp['Event']['option_to_show'] = 2;
            }

            if (!isset($tmp["Event"]["event_type"]) || $tmp["Event"]["event_type"] == 2 || empty($tmp["Event"]["event_type"])) {
                unset($tmp["Event"]["option_to_show"]);
            }
            # now save all data in event table
            
            if ($this->Event->save($tmp)) {
                #get event_id
                if (isset($tmp["Event"]["id"]) && !empty($tmp["Event"]["id"])) {
                    $event_id = $tmp["Event"]["id"];
                } else {
                    $event_id = $this->Event->getLastInsertID();
                }

                # save giveaway
                if (!empty($this->data["Giveaway"]["no_of_ticket"])) {
                    $this->loadModel("Giveaway");
                    if (empty($this->data["Giveaway"]["id"])) {
                        $giveaway["Giveaway"]["id"] = "";
                        $giveaway["Giveaway"]["user_id"] = AuthComponent::user("id");
                        $giveaway["Giveaway"]["event_id"] = $event_id;
                    } else {
                        $giveaway["Giveaway"]["id"] = $this->data["Giveaway"]["id"];
                    }
                    $giveaway["Giveaway"]["no_of_ticket"] = $this->data["Giveaway"]["no_of_ticket"];
                    $this->Giveaway->save($giveaway);
                }

                # now save Event Other Images
                if (!empty($this->data['EventImage']['image_name'][0]['name'])) {//pr($this->data['EventImage']['image_name']);die;
                    $this->loadModel('EventImage');
                    for ($i = 0; $i < count($this->data['EventImage']['image_name']); $i++) {

                        $this->EventImage->create();
                        $imgNameExt = pathinfo($this->data['EventImage']['image_name'][$i]['name']);
                        $ext = $imgNameExt['extension'];
                        $ext = strtolower($ext);
                        if ($ext == 'jpg' || $ext == 'jpeg' || $ext == 'png' || $ext == 'gif') {
                            $newImgName = "Secondary_" . microtime();
                            $tempFile = $this->data['EventImage']['image_name'][$i]['tmp_name'];
                            $destLarge = realpath('../webroot/img/EventImages/large/') . '/';
                            $destThumb = realpath('../webroot/img/EventImages/small/') . '/';
                            $destOriginal = realpath('../webroot/img/EventImages/original/') . '/';
                            $file = $this->data['EventImage']['image_name'][$i];
                            $file['name'] = $imgNameExt['filename'] . '.' . $ext;
                            if ($ext == 'gif') {
                                $result = $this->Upload->upload($file, $destLarge, $newImgName . ".gif", array('type' => 'resizemin', 'size' => array('300', '450')));
                                $result = $this->Upload->upload($file, $destOriginal, $newImgName . ".gif", NULL, array('gif'));
                                $result = $this->Upload->upload($file, $destThumb, $newImgName . ".gif", array('type' => 'resizemin', 'size' => array('400', '400')));
                                $name = $newImgName . ".gif";
                            } else if ($ext == 'png') {
                                $result = $this->Upload->upload($file, $destLarge, $newImgName . ".png", array('type' => 'resizemin', 'size' => array('300', '450')));
                                $result = $this->Upload->upload($file, $destOriginal, $newImgName . ".png", NULL, array('png'));
                                $result = $this->Upload->upload($file, $destThumb, $newImgName . ".png", array('type' => 'resizemin', 'size' => array('400', '400')));
                                $name = $newImgName . ".png";
                            } else if ($ext == 'jpeg') {
                                $result = $this->Upload->upload($file, $destLarge, $newImgName . ".jpeg", array('type' => 'resizemin', 'size' => array('300', '450')));
                                $result = $this->Upload->upload($file, $destOriginal, $newImgName . ".jpeg", NULL, array('jpeg'));
                                $result = $this->Upload->upload($file, $destThumb, $newImgName . ".jpeg", array('type' => 'resizemin', 'size' => array('400', '400')));
                                $name = $newImgName . ".jpeg";
                            } else {
                                $result = $this->Upload->upload($file, $destLarge, $newImgName . ".jpg", array('type' => 'resizemin', 'size' => array('300', '450')));
                                $result = $this->Upload->upload($file, $destOriginal, $newImgName . ".jpg", NULL, array('jpg'));
                                $result = $this->Upload->upload($file, $destThumb, $newImgName . ".jpg", array('type' => 'resizemin', 'size' => array('400', '400')));
                                $name = $newImgName . ".jpg";
                            }
                        }
                        $EventImages["EventImage"]["image_name"] = $name;
                        $EventImages["EventImage"]["event_id"] = $event_id;
                        $this->EventImage->save($EventImages);
                    }
                }

                # now save TicketPrices
                #Save data in event edited user
                $this->loadModel('EventEditedUser');
                $editedUsers = explode(',', $tmp['EventEditedUser']['user_email']);
                if (isset($this->params['data']['Event']['id']) && !empty($this->params['data']['Event']['id'])) {

                    $this->EventEditedUser->deleteAll(array('EventEditedUser.event_id' => $this->params['data']['Event']['id']));
                }
                foreach ($editedUsers as $eUsers) {
                    $this->EventEditedUser->create();
                    $save_eu["EventEditedUser"]["user_email"] = $eUsers;
                    $save_eu["EventEditedUser"]["event_id"] = $event_id;
                    $this->EventEditedUser->save($save_eu);
                }
                #Save Data in Event Dates Table
                $this->loadModel("EventDate");
                $Eventdate = $tmp["EventDate"]["start_date"];
               
                $Eventstarttime = $tmp["EventDate"]["start_time"];
                $Eventendtime = $tmp["EventDate"]["end_time"];
             
                if (isset($this->params['data']['Event']['id']) && !empty($this->params['data']['Event']['id'])) {

                    $this->EventDate->deleteAll(array('EventDate.event_id' => $this->params['data']['Event']['id']));
                }
              

                if ($tmp["Event"]["recurring_or_not"] == 0) {
                    sort($Eventdate);
                }
             
                for ($i = 0; $i < count($Eventdate); $i++) {
                    $this->EventDate->create();
                    $tmp_date["EventDate"]["event_id"] = $event_id;
                    $tmp_date['EventDate']['date'] = $Eventdate[$i];
                    $tmp_date['EventDate']['start_time'] = $Eventstarttime[$i];
                    $tmp_date['EventDate']['end_time'] = $Eventendtime[$i];


                    $this->EventDate->save($tmp_date);
                }
               
                $dateExtra[] = '';
                $dateExtra['rec_mode'] .= $tmp["Event"]["recurring_type"];
                if ($tmp["Event"]["recurring_type"] == "d") {
                    $dateExtra['daily_days'] .= $tmp["Event"]["daily_days"];
                } elseif ($tmp["Event"]["recurring_type"] == "w") {
                    $dateExtra['rept_days'] .= $tmp["Event"]["weekRepeatDay"];
                } elseif ($tmp["Event"]["recurring_type"] == "m") {
                    $dateExtra['month_mode'] .= $_POST['month_mode'];
                    if ($_POST['month_mode'] == "mode1") {
                        $dateExtra['month_day1'] .= $_POST['month_day1'];
                    } else {
                        $dateExtra['monthly_period'] .= $_POST['monthly_period'];
                        $dateExtra['monthly_pattern_day'] .= $_POST['monthly_pattern_day'];
                    }
                }

                $date_extra = serialize($dateExtra);

                $EventdateReverse = array_reverse($Eventdate);
               
                $this->Event->id = $event_id;
                $this->Event->saveField('start_date', $Eventdate[0]);
                $this->Event->saveField('end_date', $EventdateReverse[0]);
                $this->Event->saveField('edate_extra', $date_extra);

                #End Save Data in Event Date Table 
                # now save category
                $EventCategory = $tmp["EventCategory"];
               
                $this->loadModel("EventCategory");
                // delete all old values
                $this->EventCategory->deleteAll(array("EventCategory.event_id" => $event_id));
                foreach ($EventCategory as $tc) {
                  

                    if ($tc != 0) {
                        $save_tc["EventCategory"]["id"] = "";
                        $save_tc["EventCategory"]["category_id"] = $tc;
                        $save_tc["EventCategory"]["event_id"] = $event_id;
                        $this->EventCategory->save($save_tc);
                    }
                }
                # now save ticket price

                $this->loadModel("TicketPrice");
                $cnt = $this->TicketPrice->find('count', array('conditions' => array('TicketPrice.event_id' => $event_id)));

                if ($cnt != 0) {
                    $this->TicketPrice->deleteAll(array('TicketPrice.event_id' => $event_id));
                }
                $TicketPrice = $tmp["TicketPrice"];
                for ($i = 0; $i < count($TicketPrice['ticket_label']); $i++) {

                    $this->TicketPrice->create();
                    $save_tp["TicketPrice"]["ticket_label"] = $TicketPrice['ticket_label'][$i];
                    $save_tp["TicketPrice"]["ticket_price"] = $TicketPrice['ticket_price'][$i];
                    $save_tp["TicketPrice"]["event_id"] = $event_id;
                    $this->TicketPrice->save($save_tp);
                }


                # now save vibes
                $EventVibe = $tmp["EventVibe"]; #pr($EventVibe);die;
                $this->loadModel("EventVibe");
                $this->EventVibe->deleteAll(array("EventVibe.event_id" => $event_id));
                foreach ($EventVibe as $ev) {

                    if ($ev != 0) {
                        $save_ev["EventVibe"]["id"] = "";
                        $save_ev["EventVibe"]["vibe_id"] = $ev;
                        $save_ev["EventVibe"]["event_id"] = $event_id;
                        $this->EventVibe->save($save_ev);
                      
                    }
                }

                $userInfoName = explode(" ", $tmp["Event"]['main_info_name']);
                $UserFname = array_shift($userInfoName);
                $UserLname = implode(" ", $userInfoName);

                $userdata["User"]["id"] = AuthComponent::user("id");
                $userdata["User"]["first_name"] = $UserFname;
                $userdata["User"]["last_name"] = $UserLname;
                $userdata["User"]["phone_no"] = $tmp["Event"]['main_info_phone_no'];
              
                $this->User->save($userdata);

                $this->Session->setflash("Event Updated Successfully", "default", array("class" => "green"));
                $this->redirect($this->data["Event"]["referer"]);
            }
        }
        if ($id) {

            $id = base64_decode($id);
            $event_detail = $this->Event->findById($id);
            $this->request->data = $event_detail;
            if (!empty($event_detail["Event"]["cant_find_zip_code"])) {
                $zipcode = $this->Zip->findByZip($event_detail["Event"]["cant_find_zip_code"]);
                $this->set("zipcode", $zipcode);
            }
            $this->request->data["Event"]["video"] = "http://www.youtube.com/watch?v=" . $this->data["Event"]["video"];
            $save_add = $this->Address->find("list", array("conditions" => array("Address.user_id" => $event_detail["Event"]["user_id"]), "fields" => array("Address.name", "Address.name")));
            $this->set("saved_address", $save_add);
            // check user authentication
            if (AuthComponent::user("role_id") != 1) {
                if (AuthComponent::user("id") != $event_detail["Event"]["user_id"]) {
                    $this->redirect(array("controller" => "Users", "action" => "index"));
                }
            }
        }
    }

    /*
     * @Created:        24-Jun-2014
     * @Method :        function addToMyCalendarEf
     * @Author: 	Arjun Dev
     * @Modified :	
     * @Purpose:   	Function to add eventful event to my calender
     * @Param:     	$event_id
     * @Return:    	none
     */

    public function addToMyCalendarEf($ef_event_id = NULL) {

        $this->layout = FALSE;
        $this->autoRender = FALSE;
        $this->loadModel("MyCalendar");
        $this->loadModel("Event");
        $this->loadModel("User");
        $this->loadModel("EventDate");
        if ($ef_event_id) {
            // check data
            $old_data = $this->MyCalendar->find("first", array("conditions" => array("MyCalendar.user_id" => AuthComponent::user("id"), "MyCalendar.ef_event_id" => $ef_event_id)));
           
            if (!empty($old_data)) {
                if ($this->MyCalendar->delete($old_data["MyCalendar"]["id"])) {
                    return 1;
                } else {
                    return 0;
                }
            } else {

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
                     
                        if (isset($er["images"]["image"][0]["url"])) {
                            $img = $er["images"]["image"][0]["url"];
                        } elseif (isset($er["images"]["image"]["url"])) {
                            $img = $er["images"]["image"]["url"];
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
                        $event["Event"]["ticket_vendor_url"] = $er["url"];
                        $event["Event"]["website_url"] = $er["url"];
                        $event["Event"]["facebook_url"] = $er["url"];
                        $event["Event"]["flyer_image"] = $img;

                        $event["Event"]["short_description"] = $desc;
                        $event["Event"]["description"] = $desc;
                        $event["Event"]["status"] = '1';
                        $event["Event"]["event_from"] = "eventful";
                        $event["Event"]["ef_event_id"] = $evnt_id;
                       
                        $this->Event->create();
                        if ($this->Event->save($event)) {
                            $event_id = $this->Event->getLastInsertID();
                            $eventdate["EventDate"]["event_id"] = $event_id;
                            $eventdate["EventDate"]["date"] = $er["start_time"];
                            $this->EventDate->save($eventdate);
                        }
                    } else {
                        die("<strong>Error logging into Eventful API</strong>");
                    }
                } else {
                    $event_id = $eventetails['Event']['id'];
                }

                $data["MyCalendar"]["user_id"] = AuthComponent::user("id");
                $data["MyCalendar"]["event_id"] = $event_id;
                $data["MyCalendar"]["ef_event_id"] = $ef_event_id;
                $data["MyCalendar"]["status"] = 1;
                $data["MyCalendar"]["id"] = ""; //pr($data);
                if ($this->MyCalendar->save($data)) {
                    return 2;
                } else {
                    return 0;
                }
            }
        } else {
            return 0;
        }
    }

    /*
     * @Created:        23-Jul-2014
     * @Method :        function addToMyCalendarEf
     * @Author: 	Arjun Dev
     * @Modified :	
     * @Purpose:   	Function to add eventful event to my calender
     * @Param:     	$event_id
     * @Return:    	none
     */

    public function addToMyWppluginEf($ef_event_id = NULL) {

        $this->layout = FALSE;
        $this->autoRender = FALSE;
        $this->loadModel("MyWpplugin");
        $this->loadModel("Event");
        $this->loadModel("User");
        $this->loadModel("EventDate");
        $this->loadModel("EventImage");
        if ($ef_event_id) {
            // check data
            $old_data = $this->MyWpplugin->find("first", array("conditions" => array("MyWpplugin.user_id" => AuthComponent::user("id"), "MyWpplugin.ef_event_id" => $ef_event_id)));
           
            if (!empty($old_data)) {
                if ($this->MyWpplugin->delete($old_data["MyWpplugin"]["id"])) {
                    return 1;
                } else {
                    return 0;
                }
            } else {

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

                        if (isset($er["images"]["image"][0]["url"])) {
                            $img = $er["images"]["image"][0]["url"];
                            $EventImage[] = $er["images"]["image"];
                        } elseif (isset($er["images"]["image"]["url"])) {
                            $img = $er["images"]["image"]["url"];
                            $EventImage[] = $er["images"]["image"];
                        } else {
                            $img = 'no_image.jpeg';
                            $EventImage[0]["url"] = 'no_image.jpeg';
                        }
                        if (!empty($er["description"]) && $er["description"] != "Array") {
                            $desc = $er["description"];
                        } else {
                            $desc = '';
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
                        $event["Event"]["ticket_vendor_url"] = $er["url"];
                        $event["Event"]["website_url"] = $er["url"];
                        $event["Event"]["facebook_url"] = $er["url"];
                        $event["Event"]["flyer_image"] = $img;

                        $event["Event"]["short_description"] = $desc;
                        $event["Event"]["description"] = $desc;
                        $event["Event"]["status"] = '1';
                        $event["Event"]["event_from"] = "eventful";
                        $event["Event"]["ef_event_id"] = $evnt_id;
                     
                        $this->Event->create();
                        if ($this->Event->save($event)) {
                            $event_id = $this->Event->getLastInsertID();
                            $eventdate["EventDate"]["event_id"] = $event_id;
                            $eventdate["EventDate"]["date"] = $er["start_time"];
                            $this->EventDate->save($eventdate);
                            if (!empty($EventImage) && isset($EventImage)) {
                                foreach ($EventImage as $ev) {

                                    $save_img["EventImage"]["id"] = "";
                                    $save_img["EventImage"]["event_id"] = $event_id;
                                    $save_img["EventImage"]["image_name"] = $ev["url"];

                                    $this->EventImage->save($save_img);
                                  
                                }
                            }
                        }
                    } else {
                        die("<strong>Error logging into Eventful API</strong>");
                    }
                } else {
                    $event_id = $eventetails['Event']['id'];
                }

                $data["MyWpplugin"]["user_id"] = AuthComponent::user("id");
                $data["MyWpplugin"]["event_id"] = $event_id;
                $data["MyWpplugin"]["ef_event_id"] = $ef_event_id;
                $data["MyWpplugin"]["status"] = 1;
                $data["MyWpplugin"]["id"] = ""; 
                if ($this->MyWpplugin->save($data)) {
                    return 2;
                } else {
                    return 0;
                }
            }
        } else {
            return 0;
        }
    }

    /*
     * @Created:        24-July-2014
     * @Method :        function sendGiveawayMail
     * @Author: 	Prateek Jadhav
     * @Modified :	
     * @Purpose:   	Function for send giveaway mail to admin and applier
     * @Param:     	$giveaway_id
     * @Return:    	none
     */

    public function sendGiveawayMail($giveaway_id) {
        $this->layout = false;
        $this->autoRender = false;
        $this->loadModel("TicketGiveaway");
        $this->loadModel("EmailTemplate");
        $giveaway = $this->TicketGiveaway->findById($giveaway_id);

        if (isset($giveaway) && !empty($giveaway)) {
            #Start Code :: Activation Email
            $token = md5($giveaway["TicketGiveaway"]['email']) . time();
            $url = BASE_URL . "/Events/confirm/" . $token . "/" . $giveaway["TicketGiveaway"]['id'];
            $activation = '<a style="background-color: #6A6A6A;border-radius: 2px 2px 2px 2px;color: #FFFFFF;display: inline-block;font-size: 14px;font-weight: bold;padding: 10px 19px;text-decoration: none;" target="_blank" href="' . $url . '">CLICK HERE TO CONFIRM TICKET GIVEAWAY</a>';
            $this->request->data['TicketGiveaway']['id'] = $giveaway['TicketGiveaway']['id'];
            $this->request->data['TicketGiveaway']['token'] = $token;



            if ($this->TicketGiveaway->save($this->data)) {

                if ($giveaway["TicketGiveaway"]["status"] == 0) {
                    $emailTemp = $this->EmailTemplate->find('first', array('conditions' => array('EmailTemplate.alias' => "ticketgiveaway-confirmation")));
                    $emailContent = utf8_decode($emailTemp['EmailTemplate']['description']);
                    $data = str_replace(array('{USER_NAME}', '{CONFIRMATION}', '{url}'), array($giveaway["TicketGiveaway"]['first_name'], $activation, $url), $emailContent);


                    $this->set('mailData', $data);
                    //pr($data);die;
                    $subject = utf8_decode($emailTemp['EmailTemplate']['subject']);
                    $this->Email->to = $giveaway["TicketGiveaway"]["email"];
                    $this->Email->subject = $subject;
                    $this->Email->from = "alisthub@admin.com";
                    $this->Email->template = "forgot";
                    $this->Email->sendAs = 'html';
                    $this->Email->send($data);
                } else {
                    $emailTemp = $this->EmailTemplate->find('first', array('conditions' => array('EmailTemplate.alias' => "request-giveaway")));
                    $emailContent = utf8_decode($emailTemp['EmailTemplate']['description']);
                    $data = str_replace(array('{USER_NAME}', '{EVENT}'), array($giveaway['User']['username'], $giveaway["Event"]["title"]), $emailContent);
                    $this->set('mailData', $data);
                    $subject = str_replace(array('{EVENT}'), array($giveaway["Event"]["title"]), utf8_decode($emailTemp['EmailTemplate']['subject']));
                    //$emails = array($data["TicketGiveaway"]["email"],"aisthub@admin.com");

                    $this->Email->to = $giveaway["TicketGiveaway"]["email"];
                    $this->Email->subject = $subject;
                    $this->Email->from = "alisthub@admin.com";
                    $this->Email->template = "forgot";
                    $this->Email->sendAs = 'html';
                    $this->Email->send($data);
                }
               
            }
            #End Code :: Activation Email

            $emailTemp = $this->EmailTemplate->find('first', array('conditions' => array('EmailTemplate.alias' => "request-giveaway-admin")));
            $emailContent = utf8_decode($emailTemp['EmailTemplate']['description']);
            $data = str_replace(array('{USER_NAME}', '{EVENT}'), array($giveaway['User']['username'], $giveaway["Event"]["title"]), $emailContent);
            $this->set('mailData', $data);
            $subject = str_replace(array('{EVENT}'), array($giveaway["Event"]["title"]), utf8_decode($emailTemp['EmailTemplate']['subject']));
            //$emails = array($data["TicketGiveaway"]["email"],"aisthub@admin.com");
            $this->Email->to = "alisthub@mailinator.com";
            $this->Email->subject = $subject;
            $this->Email->from = "alisthub@admin.com";
            $this->Email->template = "forgot";
            $this->Email->sendAs = 'html';
            $this->Email->send($data);
        }
        return true;
    }

    /*
     * @Created:        28-July-2014
     * @Method :        function eventEmail
     * @Author: 	Prateek Jadhav
     * @Modified :	
     * @Purpose:   	Function for send event new/update mail to user
     * @Param:     	$event_id, $alias
     * @Return:    	none
     */

    public function eventEmail($event_id = NULL, $alias = NULL) {
        $this->layout = FALSE;
        $this->loadModel("EmailTemplate");
        $this->loadModel("Event");
        $event = $this->Event->findById($event_id);
        $emailTemp = $this->EmailTemplate->find('first', array('conditions' => array('EmailTemplate.alias' => $alias)));
        $emailContent = utf8_decode($emailTemp['EmailTemplate']['description']);
        $url = "http://" . $_SERVER["HTTP_HOST"] . "/Events/viewEvent/" . base64_encode($event["Event"]["id"]);
        $data = str_replace(array('{USER_NAME}', '{EVENT_TITLE}', '{URL}'), array(AuthComponent::user("username"), $event["Event"]["title"], $url), $emailContent);
        $this->set('mailData', $data);
        $this->Email->to = AuthComponent::user("email");
        $this->Email->subject = $emailTemp['EmailTemplate']['subject'];
        $this->Email->from = "aisthub@admin.com";
        $this->Email->template = "forgot";
        $this->Email->sendAs = 'html';
        $this->Email->send($data);
        return 1;
    }

    /*
     * @Created:        29-July-2014
     * @Method :        function addattend
     * @Author: 	Arjun Dev
     * @Modified :	
     * @Purpose:   	Function for add event attended user list in DB
     * @Param:     	
     * @Return:    	none
     */

    public function addattend() {

        $this->autoRender = false;
        $this->layout = "ajax";
        $this->loadModel("EventFbattendUser");

        if (!empty($_POST['data'])) {

            $eventetails = $this->EventFbattendUser->find("first", array("conditions" => array("EventFbattendUser.user_fbID" => $_POST['data']['eventattend']['id'], "event_id" => $_POST['data']['eventattend']['event_id'])));

            if (empty($eventetails["EventFbattendUser"])) {

                $user["EventFbattendUser"]["id"] = '';
                $user["EventFbattendUser"]["event_fbID"] = $_POST['data']['eventattend']['fbeventid'];
                $user["EventFbattendUser"]["user_fbID"] = $_POST['data']['eventattend']['id'];
                $user["EventFbattendUser"]["event_id"] = $_POST['data']['eventattend']['event_id'];
                $user["EventFbattendUser"]["user_name"] = $_POST['data']['eventattend']['name'];

                $user["EventFbattendUser"]["user_image"] = $_POST['data']['eventattend']['picture']['data']['url'];
               
                if ($this->EventFbattendUser->save($user)) {
                    return 1;
                } else {

                    return 0;
                }
            } else {
                return 0;
            }
        }
    }

    /*
     * @Created:        29-July-2014
     * @Method :        function addattendmult
     * @Author: 	Arjun Dev
     * @Modified :	
     * @Purpose:   	Function for add event attended multiple user list in DB
     * @Param:     	
     * @Return:    	none
     */

    public function addattendmult() {

        $this->autoRender = false;
        $this->layout = "ajax";
        $this->loadModel("EventFbattendUser");


        if (!empty($_POST["data"])) {
          
            foreach ($_POST["data"]["eventattend"]["data"] as $Edata) {
                $eventetails = $this->EventFbattendUser->find("first", array("conditions" => array("EventFbattendUser.user_fbID" => $Edata['id'], "event_id" => $_POST['data']['eventattend']['event_id'])));

                if (empty($eventetails["EventFbattendUser"])) {

                    $user["EventFbattendUser"]["id"] = '';
                    $user["EventFbattendUser"]["event_fbID"] = $_POST['data']['eventattend']['pageid'];
                    $user["EventFbattendUser"]["user_fbID"] = $Edata['id'];
                    $user["EventFbattendUser"]["event_id"] = $_POST['data']['eventattend']['event_id'];
                    $user["EventFbattendUser"]["user_name"] = $Edata['name'];

                    $user["EventFbattendUser"]["user_image"] = $Edata['picture']['data']['url'];
                  
                    $this->EventFbattendUser->save($user);
                }
                //         $_SESSION['event_attend'] = $_POST["data"]["eventattend"];
            }
            return 1;
        } else {
            return 0;
        }
    }

    /** @Created:    30-Jul-2014
     * @Method :     viewEventfulEvent
     * @Author:      Arjun Dev
     * @Modified :   ---
     * @Purpose:     To view Eventful Event Detail on frontend
     * @Param:       $event_id
     * @Return:      none
     */
    public function viewEventfulEvent($event_id = NULL) {
        $this->layout = "front/home";

        require_once "Eventful_new.php";

        $AppKey = "qGt8LCwr2CVprzVW";

        $eV = new Eventful($AppKey);

        $evLogin = $eV->login('sachint', 'sachint');
        if ($evLogin) {
            $evArgs = array(
                'id' => $event_id,
            );

            $cEvent = $eV->call('events/get', $evArgs);
            
            $this->set('event', json_decode(json_encode($cEvent), true));
           
        } else {
            die("<strong>Error logging into Eventful API</strong>");
        }
    }

    /*
     * @Created:        29-July-2014
     * @Method :        function addattend
     * @Author: 	Arjun Dev
     * @Modified :	
     * @Purpose:   	Function for Remove event attended user list in DB
     * @Param:     	
     * @Return:    	none
     */

    public function removeattend() {

        $this->autoRender = false;
        $this->layout = "ajax";
        $this->loadModel("EventFbattendUser");

        if (!empty($_POST['data'])) {
            $userfb_id = $_POST['data']['eventattremove'][1];
            $fbeventid = $_POST['data']['eventattremove'][0];
           
            if ($this->EventFbattendUser->deleteAll(array('EventFbattendUser.user_fbID' => $userfb_id, 'EventFbattendUser.event_fbID' => $fbeventid), false)) {
                return 1;
            } else {
                return 0;
            }
        }
    }

    /*
     * @Created:        29-July-2014
     * @Method :        function totalCount
     * @Author: 	Arjun Dev
     * @Modified :	
     * @Purpose:   	Function for count event attended user
     * @Param:     	
     * @Return:    	none
     */

    public function totalCount() {

        $this->autoRender = false;
        $this->layout = "ajax";
        $this->loadModel("Event");

        if (!empty($_POST['data'])) {

            $count = $_POST['data']['eventattendtotal']['summary']['count'];
            $event_id = $_POST['data']['eventattendtotal']['event_id'];
            $event["Event"]["id"] = $event_id;
            $event["Event"]["attand_count"] = $count;

            if ($this->Event->save($event)) {
                return 1;
            } else {
                return 0;
            }
        }
    }

    /*
     * @Created:        08-Aug-2014
     * @Method :        function fetchCategoryFromEventful
     * @Author: 	Prateek Jadhav
     * @Modified :	
     * @Purpose:   	Function for coordinate eventful categories with ALH
     * @Param:     	none
     * @Return:    	Done
     */

    public function fetchCategoryFromEventful() {
        $this->autoRender = FALSE;
        $this->layout = FALSE;
        require_once "Eventful_new.php";
        $AppKey = "qGt8LCwr2CVprzVW";
        $eV = new Eventful($AppKey);
        $catList = $eV->call('categories/list');
        $catList = json_decode(json_encode($catList), true);
        $this->loadModel("Category");

        //$this->Category->query('TRUNCATE TABLE categories;');
        foreach ($catList["category"] as $cl) {
            $check = $this->Category->findByName($cl["name"]);
            if (empty($check)) {
                $data["Category"]["id"] = "";
                $data["Category"]["eventfulID"] = $cl["id"];
                $data["Category"]["name"] = $cl["name"];
                $data["Category"]["status"] = 1;
                $this->Category->save($data);
            }
        }
        return "Done";
    }

    public function findVenue($venue = NULL, $where = NULL, $radius = NULL) {
        //API Key = AIzaSyDc-CDHimxuULDWJmFEJx2PzfAReXWIr4Q
        //$query = "https://maps.googleapis.com/maps/api/place/textsearch/json?query=Elverton%20Drive+in+%20Oakland&radius=500&key=AIzaSyDc-CDHimxuULDWJmFEJx2PzfAReXWIr4Q";
        $this->layout = FALSE;
        $venue = urlencode($venue);
        $where = urlencode($where . " USA");
        $radius = urlencode($radius);
        $api_key = "AIzaSyDc-CDHimxuULDWJmFEJx2PzfAReXWIr4Q";
        $query = "https://maps.googleapis.com/maps/api/place/textsearch/json?query=$venue+in+$where&radius=$radius&key=$api_key";
        $data = json_decode(file_get_contents($query), true);
        $this->set("data", $data);
    }

    /* s@Created:    19-aug-2014
     * @Method :    testeventdate
     * @Author:      arjun dev
     * @Modified :   ---
     * @Purpose:     
     * @Param:       $event_id
     * @Return:      none
     */

    function cal($id = null) {

        $this->layout = 'ajax';
        $this->loadModel("Event");

        $event_detail = $this->Event->find("first", array("conditions" => array("Event.id" => $id, "Event.recurring_or_not" => 0), "recursive" => 2));

        $this->request->data = $event_detail;
    }

    /* s@Created:    19-aug-2014
     * @Method :    testeventdate
     * @Author:      arjun dev
     * @Modified :   ---
     * @Purpose:     
     * @Param:       $event_id
     * @Return:      none
     */

    function caln($id = null) {

        $this->layout = 'ajax';
        $this->loadModel("Event");

        $event_detail = $this->Event->find("first", array("conditions" => array("Event.id" => $id, "Event.recurring_or_not" => 1), "recursive" => 2));

        $this->request->data = $event_detail;
    }

    /* s@Created:    19-aug-2014
     * @Method :    testeventdate
     * @Author:      arjun dev
     * @Modified :   ---
     * @Purpose:     
     * @Param:       $event_id
     * @Return:      none
     */

    function admin_cal($id = null) {

        $this->layout = FALSE;
        $this->loadModel("Event");

        $event_detail = $this->Event->find("first", array("conditions" => array("Event.id" => $id, "Event.recurring_or_not" => 0), "recursive" => 2));

        $this->request->data = $event_detail;
    }

    /* s@Created:    19-aug-2014
     * @Method :    testeventdate
     * @Author:      arjun dev
     * @Modified :   ---
     * @Purpose:     
     * @Param:       $event_id
     * @Return:      none
     */

    function admin_caln($id = null) {

        $this->layout = FALSE;
        $this->loadModel("Event");

        $event_detail = $this->Event->find("first", array("conditions" => array("Event.id" => $id, "Event.recurring_or_not" => 1), "recursive" => 2));

        $this->request->data = $event_detail;
    }

    /* @Created:    21-Aug-2014
     * @Method :    all
     * @Author:     Prateek Jadhav
     * @Modified :   ---
     * @Purpose:     
     * @Param:       $action/
     * @Return:      none
     */

    public function all($action = NULL) {
        $this->layout = "front/home";
        $this->set('title_for_layout', 'ALIST Hub :: Calendar');
        $this->loadModel("MyCalendar");
        $this->loadModel("MyWpplugin");
        $this->loadModel("Region");
        $this->loadModel("Category");
        $this->loadModel("Vibe");
        $this->loadModel("User");
        $this->loadModel("Zip");
        $this->loadModel("Search");
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
            }
            if (!empty($this->data["EventVibe"]["id"])) {
                foreach ($this->data["EventVibe"]["id"] as $key => $value):
                    $vibeEvent[] = $key;
                endforeach;
                $save_search_data["Search"]["EventVibe"] = implode(",", $vibeEvent);
            }
            $this->Search->save($save_search_data);
        } else {
            // for fetch data from database
            if (!empty($existing_search_data)) {
                $this->request->data["Event"]["title"] = $existing_search_data["Search"]["title"];
                $this->request->data["Event"]["date"] = $existing_search_data["Search"]["date"];
                $this->request->data["Event"]["limit"] = $existing_search_data["Search"]["limit"];
                $this->request->data["Event"]["order"] = $existing_search_data["Search"]["order"];

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
                }

                if (!empty($existing_search_data["Search"]["EventVibe"])) {
                    $vibeEvent = explode(",", $existing_search_data["Search"]["EventVibe"]);
                    foreach ($vibeEvent as $ve) {
                        $event_vibe["EventVibe"]["id"][$ve] = "on";
                    }
                    $this->request->data["EventVibe"] = $event_vibe["EventVibe"];
                }
            }
        }



        $conditions = array();
        // all events should be greater then today
        $this->loadModel("EventDate");
        $now = date('Y-m-d');
        $allEventGreterThenNow = $this->EventDate->find("list", array("conditions" => array("EventDate.date >=" => $now), "fields" => array("EventDate.event_id", "EventDate.event_id")));
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


        if (isset($this->data["Event"]["order"])) {
            $order = $this->data["Event"]["order"];
        } else {
            $order = "Event.id ASC";
        }
        $this->Event->unBindModel(array('hasMany' => array("EventCategory", "EventVibe"), 'belongsTo' => array('User')), false);

        $this->Event->bindModel(array('hasOne' => array("EventCategory", "EventVibe")), false);
       

        $regions = $this->Region->find("all", array("conditions" => array(), "recursive" => -1));
        $categories = $this->Category->find("all", array("conditions" => array(), "recursive" => -1));
        $vibes = $this->Vibe->find("all", array("conditions" => array(), "recursive" => -1));
        $this->set(compact('regions', 'categories', 'vibes'));
        #lets find out which event is added as my calendar
        $my_calendar = $this->MyCalendar->find("list", array("conditions" => array("MyCalendar.user_id" => AuthComponent::user("id")), "fields" => array("MyCalendar.id", "MyCalendar.event_id")));
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
                    $conditions = array_merge($conditions, array('EventVibe.vibe_id' => $arrayVibe));
                }
            }
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
            if ($events_show == "all") {
                $this->redirect(array("controller" => "Events", "action" => "all"));
            } else if ($events_show == "FB") {

                $ev = "FB";
            } elseif ($events_show == "EF") {
                $this->redirect('/events/eventfulEvents');
                $ev = "EF";
            } else {
                //$conditions = array_merge($conditions, array("Event.event_from" => "ALH"));
                $ev = "ALH";
            }
        } else {

            $ev = "ALH";
        }
        $conditions = array_merge($conditions, array("Event.event_from IN" => array("facebook", "ALH")));
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


        //pr($conditions);

        $this->paginate = array('conditions' => $conditions, 'limit' => $limit, 'order' => $order, 'group' => 'Event.id');
        $this->Event->unbindModel(array("hasMany" => array("EventImages", "EventEditedUser", "EventFbattendUser")));
      

        $countEvents = $this->Event->find('count', array("conditions" => $conditions, "group" => "Event.id"));
       
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



     
        ///////////////// now start fetching eventfull events as per conditions ///////////

        if (isset($this->data["Event"]["event_show"]) && $this->data["Event"]["event_show"] != "EF") {
            $this->redirect('/Events/calendar');
        }

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

        #lets find out which event is added as my Calender
        $my_calendar = $this->MyCalendar->find("list", array("conditions" => array("MyCalendar.user_id" => AuthComponent::user("id")), "fields" => array("MyCalendar.id", "MyCalendar.ef_event_id")));
        $this->set("my_calendar", $my_calendar);

        #lets find out which event is added as my Wp plugin
        $my_wpplugin = $this->MyWpplugin->find("list", array("conditions" => array("MyWpplugin.user_id" => AuthComponent::user("id")), "fields" => array("MyWpplugin.id", "MyWpplugin.ef_event_id")));
        $this->set("my_wpplugin", $my_wpplugin);

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
                    $search_date = "all";
                } else {
                    $search_date = "";
                }
            } else {
                $search_date = "";
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
            );

            $cEvent = $eV->call('events/search', $evArgs);
            $this->set('eevent', $cEvent);
            $catList = $eV->call('categories/list');
            $this->set('catList', json_decode(json_encode($catList), true));
        } else {
            die("<strong>Error logging into Eventful API</strong>");
        }
    }

    public function findWeekDay() {
        $this->layout = FALSE;
        $this->autoRender = FALSE;

     
        $days = array();
        $for = $_POST["data"]["dateDetail"]["2"];

        foreach ($for as $fr) {
            $start = strtotime($_POST["data"]["dateDetail"]["0"]);
            $end = strtotime($_POST["data"]["dateDetail"]["1"]);

            while ($start <= $end) {
                if (date('N', $start) == $fr)
                    $days[] = date("Y-m-d", $start);
                $start += 86400; //> incrementing one day   
            }
        }
        sort($days);
        return json_encode($days);
    }

    public function findNoOfDay() {
        $this->layout = FALSE;
        $this->autoRender = FALSE;
        $start = strtotime($_POST["data"]["dateDetail"]["0"]);
        $firstStart = strtotime($_POST["data"]["dateDetail"]["0"]);
        $end = strtotime($_POST["data"]["dateDetail"]["1"]);
        $interval = $_POST["data"]["dateDetail"]["2"];
        if ($interval > 30) {
            $interval = 30 - $interval;
            return 0;
        }


        $dt = array();
        while ($start <= $end) {

            if ($interval >= date('d', $firstStart)) {
                $dt[] = date('Y-m-' . $interval, $start);
            }

            $start = strtotime(date("Y-m-d", $start) . " +1 month");
            $int = strtotime(date('Y-m-' . $interval, $start));

            if ($end >= $int) {
                $dt[] = date('Y-m-' . $interval, $start);
            }
        }
        $dt = array_unique($dt);
       

        sort($dt);

        if ($interval > date('d', $end)) {
            $remove_date[] = date('Y-m-' . $interval, $end);
            $dt = array_diff($dt, $remove_date);
        }
        return json_encode($dt);
    }

    public function dayOfMounth() {
        $this->layout = FALSE;
        $this->autoRender = FALSE;
        $start = strtotime($_POST["data"]["dateDetail"]["0"]);
        $end = strtotime($_POST["data"]["dateDetail"]["1"]);

        $day = $_POST["data"]["dateDetail"]["3"];
        $interval = $_POST["data"]["dateDetail"]["2"];
        $start_date = date("Y-m-d", $start);
        $end_date = date("Y-m-d", $end);
        $dts = array();
        while ($start <= $end) {
            $month = date("F", $start);
            //echo $interval $start ."of". $month;die;
            //$endDate = date("Y-m-d", strtotime("third monday of april " . date("Y", strtotime("2014-04-01"))));
            $endDate = date("Y-m-d", strtotime($interval . " " . $day . " of " . $month . date("Y", $start)));
            $dts[] = $endDate;

            $start = strtotime(date("Y-m-1", $start) . " +1 month");
        }
        sort($dts);
        $min_date = min($dts);
        if ($min_date < $start_date) {
            $rem_date[] = $min_date;
            $dts = array_diff($dts, $rem_date);
        }
        $max_date = max($dts);
        if ($max_date > $end_date) {
            $rem_maxdate[] = $max_date;
            $dts = array_diff($dts, $rem_maxdate);
        }
       
        return json_encode($dts);
    }

    /* @Created:    09-Sep-2014
     * @Method :    findNoOfYear
     * @Author:     Arjun Dev
     * @Modified :   ---
     * @Purpose:     
     * @Param:       $action/
     * @Return:      none
     */

    public function findNoOfYear() {
        $this->layout = FALSE;
        $this->autoRender = FALSE;
        $start = strtotime($_POST["data"]["dateDetail"]["0"]);
        $end = strtotime($_POST["data"]["dateDetail"]["1"]);

        while ($start <= $end) {
            $nDate = date("Y-m-d", $start);
            $dtss[] = $nDate;
            $start = strtotime(date("Y-m-d", $start) . " +1 year");
        }


        sort($dtss);
        return json_encode($dtss);
    }

    /* @Created:    25-Sep-2014
     * @Method :    eventful_to_alh
     * @Author:     Arjun Dev
     * @Modified :   ---
     * @Purpose:    store eventful event to ALH database 
     * @Param:       
     * @Return:      none
     */

    public function eventfultoalh($ef_event_id = NULL) {
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
                   
                    if (isset($er["images"]["image"][0]["url"])) {
                        $img = $er["images"]["image"][0]["url"];
                    } elseif (isset($er["images"]["image"]["url"])) {
                        $img = $er["images"]["image"]["url"];
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




                        return 1;
                    }
                } else {
                    return 0;
                }
            } else {
                return 2;
            }
        } else {
            return 0;
        }
    }
    
    /* @Created:    09-Dec-2014
     * @Method :    marketing
     * @Author:     Arjun Dev
     * @Modified :   ---
     * @Purpose:    top nav marketing page. 
     * @Param:       
     * @Return:      none
     */

    public function marketing() {
         $this->layout = "front/home";
        
    }
    
    /* @Created:    09-Jan-2015
     * @Method :    marketingDetail
     * @Author:     Arjun Dev
     * @Modified :   ---
     * @Purpose:    top nav marketing detail page. 
     * @Param:       
     * @Return:      none
     */

    public function marketingDetail() {
         $this->layout = "front/home";
        
    }
    
    /* @Created:    09-Dec-2014
     * @Method :    email
     * @Author:     Arjun Dev
     * @Modified :   ---
     * @Purpose:    top nav email page. 
     * @Param:       
     * @Return:      none
     */

    public function email() {
         $this->layout = "front/home";
        
    }

}
