<?php 
class CommonHelper extends AppHelper {
	#Get all Brand Categories
	function getbrandCat($brandId = NULL) {
		
		App::import("Model","Brand");
		$this->Brand= new Brand();
		$brands =  $this->Brand->find('first',array('conditions'=>array("Brand.id"=>$brandId),'recursive'=>2));
		$CatString = "";
	
		foreach($brands['BrandCategory'] as $bCat):
			 $CatString .= $bCat['Category']['name'].', '; 
		endforeach;
		return rtrim($CatString,', ');	
		
		
	}
	#Get all Brand Vibes
	function getbrandVibe($brandId = NULL){
		App::import("Model","Brand");
		$this->Brand= new Brand();
		$brands =  $this->Brand->find('first',array('conditions'=>array("Brand.id"=>$brandId),'recursive'=>2));
		$VibeString = "";
	
		foreach($brands['BrandVibe'] as $bVibe):
			 $VibeString .= $bVibe['Vibe']['name'].', '; 
		endforeach;
		return rtrim($VibeString,', ');	
	}
	
	#List count/click through rate calculation
	function listrate($list = NULL){
		 $TotalListEmail = count($list);
                                     if(!empty($list))
                                     {
                                       
                                       foreach($list as $OpnLst)
                                       {
                                        if($OpnLst['count']==1)
                                        {
                                            $OPEN[] = $OpnLst['id'];
                                        }
                                         if($OpnLst['click_through']==1)
                                        {
                                            $Click[] = $OpnLst['id'];
                                        }
					if($OpnLst['region_id']==1)
                                        {
                                            $Region1[] = $OpnLst['id'];
                                        }
					if($OpnLst['region_id']==2)
                                        {
                                            $Region2[] = $OpnLst['id'];
                                        }
					if($OpnLst['region_id']==3)
                                        {
                                            $Region3[] = $OpnLst['id'];
                                        }
					if($OpnLst['region_id']==4)
                                        {
                                            $Region4[] = $OpnLst['id'];
                                        }
					if($OpnLst['region_id']==5)
                                        {
                                            $Region5[] = $OpnLst['id'];
                                        }
                                       }
                                       
                                       
                                        if(isset($OPEN))
                                       {
                                       $TotalCount = count($OPEN);
                                       }
                                       else
                                       {
                                        $TotalCount = 0;
                                       }
                                       if(isset($Click))
                                       {
                                       $TotalClick = count($Click);
                                       }
                                       else
                                       {
                                        $TotalClick = 0;
                                       }
				        if(isset($Region1))
                                       {
                                       $TotalRegion1 = count($Region1);
                                       }
                                       else
                                       {
                                        $TotalRegion1 = 0;
                                       }
				        if(isset($Region2))
                                       {
                                       $TotalRegion2 = count($Region2);
                                       }
                                       else
                                       {
                                        $TotalRegion2 = 0;
                                       }
				        if(isset($Region3))
                                       {
                                       $TotalRegion3 = count($Region3);
                                       }
                                       else
                                       {
                                        $TotalRegion3 = 0;
                                       }
				        if(isset($Region4))
                                       {
                                       $TotalRegion4 = count($Region4);
                                       }
                                       else
                                       {
                                        $TotalRegion4 = 0;
                                       }
				       if(isset($Region5))
                                       {
                                       $TotalRegion5 = count($Region5);
                                       }
                                       else
                                       {
                                        $TotalRegion5 = 0;
                                       }
                                        $OpenPer = number_format(($TotalCount * 100)/$TotalListEmail, 2, '.', '')."%";
                                        $ClickPer = number_format(($TotalClick * 100)/$TotalListEmail, 2, '.', '')."%";
					$RegionPer1 = number_format(($TotalRegion1 * 100)/$TotalListEmail, 2, '.', '')."%";
					$RegionPer2 = number_format(($TotalRegion2 * 100)/$TotalListEmail, 2, '.', '')."%";
					$RegionPer3 = number_format(($TotalRegion3 * 100)/$TotalListEmail, 2, '.', '')."%";
					$RegionPer4 = number_format(($TotalRegion4 * 100)/$TotalListEmail, 2, '.', '')."%";
					$RegionPer5 = number_format(($TotalRegion5 * 100)/$TotalListEmail, 2, '.', '')."%";
                                    
				    
				    
				    
				     }
                                     else
                                     {
                                        $OpenPer = "N/A";
                                        $ClickPer = "N/A";
					$RegionPer1 = "N/A";
					$RegionPer2 = "N/A";
					$RegionPer3 = "N/A";
					$RegionPer4 = "N/A";
					$RegionPer5 = "N/A";
                                     }
				     $return = array("OpenRate" => $OpenPer, "ClickRate" => $ClickPer, "RegionPer1" => $RegionPer1, "RegionPer2" => $RegionPer2, "RegionPer3" => $RegionPer3, "RegionPer4" => $RegionPer4, "RegionPer5" => $RegionPer5);
		return $return;	
	}
} ?>