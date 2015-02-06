<?php

export();

function export() {

    include('openinviter.php');
    $inviter = new OpenInviter();
    $oi_services = $inviter->getPlugins();
    if (isset($_GET['provider_box'])) {
        if (isset($oi_services['email'][$_GET['provider_box']]))
            $plugType = 'email';
        elseif (isset($oi_services['social'][$_GET['provider_box']]))
            $plugType = 'social';
        else
            $plugType = '';
    } else
        $plugType = '';
//echo "jefwe";die;


    $ers = array();
    $oks = array();
    $import_ok = false;
    $done = false;

   
    if (count($ers) == 0) {
        $inviter->startPlugin($_GET['provider_box']);
        $internal = $inviter->getInternalError();
        if ($internal)
            $ers['inviter'] = $internal;
        elseif (!$inviter->login($_GET['email_box'], $_GET['password_box'])) {
            $internal = $inviter->getInternalError();
            $ers['login'] = ($internal ? $internal : "Login failed. Please check the email and password you have provided and try again later !");
        } elseif (false === $contacts = $inviter->getMyContacts())
            $ers['contacts'] = "Unable to get contacts !";
        else {
            $import_ok = true;
            $step = 'send_invites';
            $_GET['oi_session_id'] = $inviter->plugin->getSessionID();
            $_GET['message_box'] = '';
        }
    }
    if (!empty($ers)) {
        //echo "1";
        //return $ers;
        echo json_encode($ers);
    } else {
        echo json_encode($contacts);
        //return $contacts;
        //echo "2";
    }
    //die;
}

function ers($ers) {
    if (!empty($ers)) {
        $contents = "<table cellspacing='0' cellpadding='0' style='border:1px solid red;' align='center'><tr><td valign='middle' style='padding:3px' valign='middle'><img src='images/ers.gif'></td><td valign='middle' style='color:red;padding:5px;'>";
        foreach ($ers as $key => $error)
            $contents.="{$error}<br >";
        $contents.="</td></tr></table><br >";
        return $contents;
    }
}

//export();
//return $contacts;
//print_r($contacts);
//print_r($_GET);
?>