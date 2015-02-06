<section id="cont_wrapper">
    <section class="content">
        <h1 class="main_heading">Welcome Admin</h1>
        <section class="content_info">
<!--	    <p class="red"><span>Error</span><a href="#"><img src="../../img/admin/red_cross.png" alt=""/></a></p>
            <p class="green"><span>Sucessfull</span><a href="#"><img src="../../img/admin/green_cross.png" alt=""/></a></p>-->
            <section class="tbldata">
                <div class="info_div">
                    <br/>
                    <h1 class="info_h1">Information</h1>
                    <br/>
                    <h4 class="info_h4">Number of users :</h4>
                    <br/>
                    <table width=94%; style="margin-left: 16px;">
                        <tr>
                            <td class="info_big_td">Number of guest users: </td>
                            <td class="info_small_td">
                                <?php
                                echo $guest;
                                //echo  $this->Html->link($new_provider,array('controller'=>'users','action'=>'approve_user',base64_encode(3),"new"),array('style'=>'color:red;','escape'=>false));
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="info_big_td">Number of members:</td>
                            <td class="info_small_td">
                                <?php
                                echo $member;
                                //echo  $this->Html->link($new_parent,array('controller'=>'users','action'=>'approve_user',base64_encode(4),"new"),array('style'=>'color:red;','escape'=>false));
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="info_big_td">Number of super member: </td>
                            <td class="info_small_td"><?php //echo $new_business;  ?>
                                <?php
                                echo $super_member;
                                //echo  $this->Html->link($new_business,array('controller'=>'users','action'=>'approve_user',base64_encode(0),"new"),array('style'=>'color:red;','escape'=>false));
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="info_big_td">Number of premium member: </td>
                            <td class="info_small_td"><?php //echo $edit_parent;  ?>
                                <?php
                                echo $premium_member;
                                //echo  $this->Html->link($edit_parent,array('controller'=>'users','action'=>'approve_user',base64_encode(4),"amended"),array('style'=>'color:red;','escape'=>false));
                                ?>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" style="width: 100%;"><hr></td>
                        </tr>
                        <tr>
                            <td class="info_total_big_td">Total Users: </td>
                            <td class="info_total_small_td"><?php echo $guest + $member + $super_member + $premium_member; ?>
                                <?php
                                //echo $premium_member;
                                //echo  $this->Html->link($edit_parent,array('controller'=>'users','action'=>'approve_user',base64_encode(4),"amended"),array('style'=>'color:red;','escape'=>false));
                                ?>
                            </td>
                        </tr>
                    </table>



                </div>

                <table width='60%' border="0" style="float: left;text-align:center;" cellspacing="15px" class="dashboard">
                    <tr>
                        <td width="20%">
                            <div class="icon">
                            <?php
                            //echo $this->Html->link($this->Html->image('admin/approval.png',array('height'=>'100px','width'=>'100px','escape'=>false), array('/admin/acl'),array('class'=>'active','escape'=>false)));
                            //echo  $this->Html->link($this->Html->image('admin/approval.png',array('height'=>'100px','width'=>'100px'), array('alt'=>"Permission", 'title'=>"Permission") ),array('/admin/acl'),array('escape'=>false), false); 
                            ?>
                            <a href="/admin/acl"><img width="60px" height="60px" alt="" src="/img/admin/approval.png"></a>
                            <?php
                            echo "<br/><b>" . $this->Html->link('Permission', '/admin/acl') . '</b>';
                            ?></div></td>
                        <td width="20%"><div class="icon"><?php
                            echo $this->Html->link($this->Html->image('admin/role.png', array('height' => '60px', 'width' => '60px'), array('alt' => "Role", 'title' => "Role")), array('controller' => 'users', 'action' => 'roleList'), array('escape' => false), false);
                            echo "<br/><b>" . $this->Html->link('Roles', array('controller' => 'users', 'action' => 'roleList'), array()) . '</b>';
                            ?></div></td>
                        <td width="20%"><div class="icon"><?php
                            echo $this->Html->link($this->Html->image('admin/users.png', array('height' => '60px', 'width' => '60px'), array('alt' => "Users", 'title' => "Users")), array('controller' => 'users', 'action' => 'list'), array('escape' => false), false);
                            echo "<br/><b>" . $this->Html->link('Users', array('controller' => 'users', 'action' => 'list'), array()) . '</b>';
                            ?></div></td>
                        <td width="20%"><div class="icon"><?php
                            echo $this->Html->link($this->Html->image('admin/cms.png', array('height' => '60px', 'width' => '60px'), array('alt' => "CMS", 'title' => "CMS")), array('controller' => 'CmsPages', 'action' => 'list'), array('escape' => false), false);
                            echo "<br/><b>" . $this->Html->link('CMS', array('controller' => 'CmsPages', 'action' => 'list'), array()) . '</b>';
                            ?></div></td>
                        <td width="20%"><div class="icon"><?php
                            echo $this->Html->link($this->Html->image('admin/email.png', array('height' => '60px', 'width' => '60px'), array('alt' => "Email Template", 'title' => "Email Template")), array('controller' => 'CmsPages', 'action' => 'emailList'), array('escape' => false), false);
                            echo "<br/><b>" . $this->Html->link('Email Template', array('controller' => 'CmsPages', 'action' => 'emailList'), array()) . '</b>';
                            ?></div></td>
                    </tr></table>
                <table width='60%' border="0" style="float: left;text-align: center;" cellspacing="15px" class="dashboard">
                    <tr>
                        
                        <td width="20%"><div class="icon">
			    <?php  echo $this->Html->link($this->Html->image('admin/services.png', array('height' => '60px', 'width' => '60px'), array('alt' => "Service", 'title' => "Services")), array('controller' => 'Services', 'action' => 'list'), array('escape' => false), false);
                            echo "<br/><b>" . $this->Html->link('Services', array('controller' => 'Services', 'action' => 'list'), array()) . '</b>';?>
			</div></td>
			<td width="20%"><div class="icon">
			    <?php  echo $this->Html->link($this->Html->image('admin/pakages.png', array('height' => '60px', 'width' => '60px'), array('alt' => "Package", 'title' => "Packages")), array('controller' => 'Packages', 'action' => 'list'), array('escape' => false), false);
                            echo "<br/><b>" . $this->Html->link('Packages', array('controller' => 'Packages', 'action' => 'list'), array()) . '</b>';?>
			</div></td>
                        <td width="20%"><div class="icon">
			    <?php  echo $this->Html->link($this->Html->image('admin/brands.png', array('height' => '60px', 'width' => '60px'), array('alt' => "Brands", 'title' => "Brands")), array('controller' => 'Brands', 'action' => 'list'), array('escape' => false), false);
                            echo "<br/><b>" . $this->Html->link('Brands', array('controller' => 'Brands', 'action' => 'list'), array()) . '</b>';?>
			</div></td>
                          <td width="20%"><div class="icon">
                          <?php
                            echo $this->Html->link($this->Html->image('admin/free_ticket.png', array('height' => '60px', 'width' => '60px'), array('alt' => "Ticket Giveaway", 'title' => "Ticket Giveaway")), array('controller' => 'Events', 'action' => 'listGiveaway'), array('escape' => false), false);
                            echo "<br/><b>" . $this->Html->link('Ticket Giveaway', array('controller' => 'Events', 'action' => 'listGiveaway'), array()) . '</b>';
                            ?></div></td>
                        <td width="20%"><div class="icon">
			    <?php  echo $this->Html->link($this->Html->image('admin/region.png', array('height' => '60px', 'width' => '60px'), array('alt' => "Region", 'title' => "Regions")), array('controller' => 'Regions', 'action' => 'list'), array('escape' => false), false);
                            echo "<br/><b>" . $this->Html->link('Regions', array('controller' => 'Regions', 'action' => 'list'), array()) . '</b>';?>
			</div></td>
                    </tr></table>
                <table width='60%' border="0" style="float: left;text-align: center;" cellspacing="15px" class="dashboard">
                    <tr>
                      
			<td width="20%"><div class="icon">
			    <?php  echo $this->Html->link($this->Html->image('admin/mylist.png', array('height' => '60px', 'width' => '60px'), array('alt' => "Lists", 'title' => "Lists")), array('controller' => 'MyLists', 'action' => 'list'), array('escape' => false), false);
                            echo "<br/><b>" . $this->Html->link('Lists', array('controller' => 'MyLists', 'action' => 'list'), array()) . '</b>';?>
			</div></td>
                        <td width="20%"><div class="icon">
			    <?php  echo $this->Html->link($this->Html->image('admin/newsletter.png', array('height' => '60px', 'width' => '60px'), array('alt' => "Newsletter", 'title' => "Newsletter")), array('controller' => 'Newsletters', 'action' => 'list'), array('escape' => false), false);
                            echo "<br/><b>" . $this->Html->link('Newsletter', array('controller' => 'Newsletters', 'action' => 'list'), array()) . '</b>';?>
			</div></td>
                         <td width="20%"><div class="icon">
			    <?php  echo $this->Html->link($this->Html->image('admin/main_banner.png', array('height' => '60px', 'width' => '60px'), array('alt' => "Banner", 'title' => "Banner")), array('controller' => 'CmsPages', 'action' => 'listBanner'), array('escape' => false), false);
                            echo "<br/><b>" . $this->Html->link('Banners', array('controller' => 'CmsPages', 'action' => 'listBanner'), array()) . '</b>';?>
			</div></td>
                        <td width="20%"><div class="icon">
			    <?php  echo $this->Html->link($this->Html->image('admin/offerPrice.png', array('height' => '60px', 'width' => '60px'), array('alt' => "Offer Price", 'title' => "Offer Price")), array('controller' => 'MyLists', 'action' => 'listOfferPrice'), array('escape' => false), false);
                            echo "<br/><b>" . $this->Html->link('Offer Price', array('controller' => 'MyLists', 'action' => 'listOfferPrice'), array()) . '</b>';?>
			</div></td>
                        <td width="20%"><div class="icon">
			    <?php  echo $this->Html->link($this->Html->image('admin/offer.png', array('height' => '60px', 'width' => '60px'), array('alt' => "Requested Offer", 'title' => "Requested Offer")), array('controller' => 'MyLists', 'action' => 'listOffer'), array('escape' => false), false);
                            echo "<br/><b>" . $this->Html->link('Requested Offer', array('controller' => 'MyLists', 'action' => 'listOffer'), array()) . '</b>';?>
			</div></td>
                        
                    </tr></table>
                <table width='60%' border="0" style="float: left;text-align: center;" cellspacing="15px" class="dashboard">
                    <tr>
                      
			<td width="20%"><div class="icon">
			    <?php  echo $this->Html->link($this->Html->image('admin/campaign.png', array('height' => '60px', 'width' => '60px'), array('alt' => "Campaigns", 'title' => "Campaigns")), array('controller' => 'Campaigns', 'action' => 'list'), array('escape' => false), false);
                            echo "<br/><b>" . $this->Html->link('Campaigns', array('controller' => 'Campaigns', 'action' => 'list'), array()) . '</b>';?>
			</div></td>
                        <td width="20%"><div class="icon">
			    <?php  echo $this->Html->link($this->Html->image('admin/points.png', array('height' => '60px', 'width' => '60px'), array('alt' => "Manage Points", 'title' => "Manage Points")), array('controller' => 'Points', 'action' => 'list'), array('escape' => false), false);
                            echo "<br/><b>" . $this->Html->link('Manage Points', array('controller' => 'Points', 'action' => 'list'), array()) . '</b>';?>
			</div></td>
                        <td width="20%"><div class="icon">
			    <?php  echo $this->Html->link($this->Html->image('admin/template.png', array('height' => '60px', 'width' => '60px'), array('alt' => "Manage Template", 'title' => "Manage Template")), array('controller' => 'Templates', 'action' => 'list'), array('escape' => false), false);
                            echo "<br/><b>" . $this->Html->link('Campaign Template', array('controller' => 'Templates', 'action' => 'list'), array()) . '</b>';?>
			</div></td>
                        <td width = "20%"></td>
                        <td width = "20%"></td>
                        
                        
                    </tr></table>
            </section>

            <section class="clr_bth"></section>
        </section>
    </section>
</section>
<section class="push"></section>
</section>
