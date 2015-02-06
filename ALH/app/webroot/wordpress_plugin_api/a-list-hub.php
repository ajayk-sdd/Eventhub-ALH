<?php
	/*   

	Wordpress Plugin API Main File

	*/

	include 'config.php';
	include 'api-functions.php';

	if(isset($_GET['action']))
		{
			$action = $_GET['action'];
		}
	else
		{
			$action = "";
		}

	if(isset($_GET['key']))
		{
			$key = $_GET['key'];
		}
	else
		{
			$key = "";
		}

	if($action!="" && $action=="auth")
		{ 
			$access['succ'] = authentication($key);
			echo json_encode($access);
		}

	if($action!="" && $action=="eventlist")
		{ 

			$list = eventlist($key,$_GET['sort'],$_GET['event_id'],$_GET['srt'],$_GET['date'],$_GET['limit'],$_GET['start'],$_GET['in'],$_GET['show_all'],$_GET['cat_search'],$_GET['vibe_search']);
			echo json_encode($list);
		}


		if($action!="" && $action=="banners")
		{ 

			$banners = event_banner($key,$_GET['event_id'],$_GET['sort'],$_GET['limit'],$_GET['start'],$_GET['show_all']);
			echo json_encode($banners);
		}
		
		if($action!="" && $action=="banners_front")
		{ 

			$banners_front = event_banner_front($key,$_GET['event_id'],$_GET['limit'],$_GET['in'],$_GET['show_all']);
			echo json_encode($banners_front);
		}
		
		if($action!="" && $action=="catvibelist")
		{ 

			$catvibelist = catvibelist($key);
			echo json_encode($catvibelist);
		}
?>
