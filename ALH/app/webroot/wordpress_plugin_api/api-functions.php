<?php

	/*   

	Wordpress Plugin Functions File

	*/

	function authentication($key)
	{
		$sql = mysql_query("select * from users where `key`='$key'");
		$res = mysql_fetch_array($sql);
		$row = mysql_num_rows($sql);
		if($row>0)
		{
		return 1;
		}
		else
		{
		return 0;
		}
	}

	function eventlist($key,$sort,$event_id='',$srt,$date='',$limit,$start=0,$in,$show_AUE=1,$catSearch='',$vibSearch='')
	{
		$sql = mysql_query("select * from users where `key`='$key'");
		$res = mysql_fetch_assoc($sql);
		$row = mysql_num_rows($sql);
		$us_id = $res['id'];
		$table1 = "events";
		$table2 = "event_categories";
		$table3 = "event_vibes";
		$table4 = "my_wpplugins";
		$grp = '';
		
		if($row>0)
		{
		if($event_id!="")
		{
		$where = "and $table1.`id`='$event_id'";
		}
		else
		{
		     
		}

		if($srt!="")
		{
		
			$ssrt = str_replace("-", " ", $srt);
			$order = "Order By ".$table1.'.'.$ssrt;

		}
		

		if($limit!="")
		{
		
			$where = "limit ".$start.','.$limit;

		}
		if($in!="")
		{
		$within = " and $table1.id in($in)";
		}

		    date_default_timezone_set("Asia/Calcutta");
	 		
		    $today = date("Y-m-d");
		    $week = date("W", strtotime($today));
		    $month = date("m");
		    $year = date("Y");

		    if ($date == "today") {
		        $conditions = "and $table1.`start_date` LIKE '%" . $today . "%'";
		    } else if ($date == "week") {
		        $conditions = "and week($table1.`start_date`)+ =".$week;
		    } elseif ($date == "month") {
		        $conditions = "and month($table1.`start_date`) =".$month;
		    } elseif ($date == "year") {
		        $conditions = "and year($table1.`start_date`) =".$year;
		    } else {
			$conditions = "";
		    }
		$whereCat = '';
		
	        if($show_AUE==0)
		{	
		$user_con = "$table1.user_id=".$us_id." and $table1.`event_type`=1 and $table1.`option_to_show` in (2,3) and";
		}
		else
		{
		$whereCat .= "inner join $table4 ON  ($table1.`id` = $table4.`event_id` and $table4.`user_id` = $us_id) ";
		}
		
		
		if($catSearch!="")
		{
			$whereCat .= "inner join $table2 ON  ($table1.`id` = $table2.`event_id` and $table2.`category_id` in ($catSearch)) ";
		}
		if($vibSearch!="")
		{
			$whereCat .= "inner join $table3 ON  ($table1.`id` = $table3.`event_id` and $table3.`vibe_id` in ($vibSearch)) ";
		}
		if($catSearch!="" || $vibSearch!="")
		{
			$grp = "group by $table1.`id`";
			//$grp = "";
		}
		if($catSearch=='' && $vibSearch=='')
		{
		        $whereCat .= " where ";
		}
		else
		{
			$whereCat .= " and ";
		}
		
		//echo "select $table1.* from $table1 $whereCat $user_con $table1.`title` like '%$sort%' $conditions $within $order $grp $where";
		$sql_events = mysql_query("select $table1.* from $table1 $whereCat $user_con $table1.`title` like '%$sort%' $conditions $within  $grp $order $where ");
		while( $res_events = mysql_fetch_assoc($sql_events))
		{
		$evnt_img = "";
		$evnt_id = $res_events['id'];
		$sql_events_images = mysql_query("select image_name from event_images where `event_id`='$evnt_id'");
		while( $res_events_images = mysql_fetch_assoc($sql_events_images))
		{
		$evnt_img[] = $res_events_images['image_name'];
		}
		//echo "select name from categories where id in (select distinct category_id from event_categories where `event_id`='$evnt_id')";
		$sql_events_cat = mysql_query("select name from categories where id in (select distinct category_id from event_categories where `event_id`='$evnt_id')");
		while( $res_events_cat = mysql_fetch_assoc($sql_events_cat))
		{
			
		$evnt_cat[] = $res_events_cat['name'];
		}
		$sql_events_vib = mysql_query("select name from vibes where id in (select distinct vibe_id from event_vibes where `event_id`='$evnt_id')");
		while( $res_events_vib = mysql_fetch_assoc($sql_events_vib))
		{
		$evnt_vib[] = $res_events_vib['name'];
		}
		//echo "select * from event_dates where `event_id`='$evnt_id'";
		$sql_events_date = mysql_query("select * from event_dates where `event_id`='$evnt_id'");
		
		while( $res_events_date = mysql_fetch_assoc($sql_events_date))
		{
		$evnt_date[] = $res_events_date;
		$evnt_dates[$evnt_id][] = $res_events_date;
		}
		//print_r($evnt_date);
		$res_events['img'] = $evnt_img;
		$res_events['cat'] = $evnt_cat;
		$res_events['vib'] = $evnt_vib;
		$res_events['date'] = $evnt_date;
		$res_events['datessss'] = $evnt_dates;
		$event_list[] = $res_events;
		
		}

		return $event_list;
		}
		else
		{
		return 0;
		}
	}

	function event_banner($key,$event_id='',$sort,$limit,$start=0,$show_AUE=1)
	{

		$sql = mysql_query("select * from users where `key`='$key'");
		$res = mysql_fetch_assoc($sql);
		$row = mysql_num_rows($sql);
		$us_id = $res['id'];
		if($row>0)
		{

		if($event_id!="")
		{

			$Ewhere = "and a.event_id='$event_id'";

		}

		if($limit!="")
		{
		
			$Lwhere = "limit ".$start.','.$limit;

		} 

		 if($show_AUE==0)
		{	
		$user_con = "and b.user_id=".$us_id;
		}

		$sql_banners = mysql_query("select a.*,b.title,c.* from banners as a inner join events as b inner join banner_images as c where a.type=1 and a.id=c.banner_id and a.event_id=b.id and b.title like '%$sort%' $user_con $Ewhere $Lwhere");
		while( $res_banners = mysql_fetch_assoc($sql_banners))
		{
		$banner_list[] = $res_banners;
		}
		return $banner_list;
		}
		else
		{
		return 0;
		}
	}

	function event_banner_front($key,$event_id='',$limit='',$in='',$show_AUE=1)
	{

		$sql = mysql_query("select * from users where `key`='$key'");
		$res = mysql_fetch_assoc($sql);
		$row = mysql_num_rows($sql);
		$us_id = $res['id'];
		if($row>0)
		{
		
		$whr = "";
		if($in!="")
		{
		$whr .= "and c.banner_id in($in)";
		}

		if($event_id!="")
		{
		$whr .= "and a.event_id in($event_id)";
		}
		
		
		if($limit!="")
		{
		$lmt = "limit $limit";
		}
		else
		{
		$lmt = '';
		}

		if($show_AUE==0)
		{	
		$whr .= "and b.user_id=".$us_id;
		}
		date_default_timezone_set("Asia/Calcutta");
		$today = date("m/d/Y");
		$sql_banners = mysql_query("select a.*,b.title,c.* from banners as a inner join events as b inner join banner_images as c where a.type=1 and a.event_id=b.id and a.id=c.banner_id and  ((c.start_date <= '$today' AND c.end_date >= '$today' and c.is_show='1') or c.is_show='0') $whr order by a.id desc $lmt");
		while( $res_banners = mysql_fetch_assoc($sql_banners))
		{
		$banner_list[] = $res_banners;
		}
		return $banner_list;
		}
		else
		{
		return 0;
		}
	}
	
	function catvibelist($key)
	{

		$sql = mysql_query("select * from users where `key`='$key'");
		$res = mysql_fetch_assoc($sql);
		$row = mysql_num_rows($sql);
		$us_id = $res['id'];
		if($row>0)
		{
		$sql_cat = mysql_query("select * from categories where status='1'");
		while( $res_cat = mysql_fetch_assoc($sql_cat))
		{
		$catvib_list['categories'][] = $res_cat;
		}
		
		$sql_vib = mysql_query("select * from vibes where status='1'");
		while( $res_vib = mysql_fetch_assoc($sql_vib))
		{
		$catvib_list['vibes'][] = $res_vib;
		}
		
		return $catvib_list;
		}
		else
		{
		return 0;
		}
	}
?>
