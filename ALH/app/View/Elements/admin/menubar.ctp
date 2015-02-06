<script>
    $(document).ready(function(){
        $('.nav_links li').hover(function(){
            $(this).children('.sublinks').fadeToggle('fast');
        });
    });
</script>
<nav>
    <section class="nav_container">
        <ul class="nav_links">
            <li>
                <?php echo $this->Html->link("Dashboard", array("controller" => "Users", "action" => "dashboard", 'plugin' => false), array('escape' => false, 'class' => ""));?> 
            </li>
            
             <li>
                <?php
                    if($this->params['controller']=='Users' || $this->params['controller']=='Brands' || $this->params['controller']=='acl'){
                        echo $this->Html->link("Management", 'javascript::void(0);', array('escape' => false,'class'=>'active'));
                    }else{
                        echo $this->Html->link("Management", 'javascript::void(0);', array('escape' => false));
                     } ?>
                    
                 
                <ul class="sublinks">
                    <li><?php
                        echo $this->Html->link("Users", array("controller" => "Users", "action" => "list", 'plugin' => false), array('escape' => false));
                    ?>
                    </li>
                    <li><?php echo $this->html->link("Brands", array("controller" => "Brands", "action" => "list"));?></li>
                    <li><?php echo $this->Html->link('Permission', '/admin/acl');?></li>
                    <li><?php echo $this->html->link("Banners", array("controller" => "CmsPages", "action" => "listBanner"));?></li>
                    
                </ul>
            </li>
            
            
             <li>
                <?php   if($this->params['controller']=='Events' || $this->params['controller']=='Categories' || $this->params['controller']=='Vibes' || $this->params['controller']=='Regions'){
                        echo $this->Html->link("Events", 'javascript::void(0);', array('escape' => false,'class'=>'active'));
                    }else{
                         echo $this->Html->link("Events", 'javascript::void(0);', array('escape' => false));
                    }?>
                 
                <ul class="sublinks">
                    <li><?php echo $this->Html->link("Events", array("controller" => "Events", "action" => "list", 'plugin' => false), array('escape' => false, 'class' => ""));?></li>
                    <li> <?php echo $this->Html->link("Categories", array("controller" => "Categories", "action" => "list", 'plugin' => false), array('escape' => false)); ?></li>
                    <li><?php echo $this->Html->link("Vibes", array("controller" => "Categories", "action" => "listVibes", 'plugin' => false), array('escape' => false)); ?></li>
                    <li><?php echo $this->Html->link("Regions", array("controller" => "Regions", "action" => "list", 'plugin' => false), array('escape' => false)); ?></li>
                    <li><?php echo $this->Html->link("Ticket Giveaway", array("controller" => "Events", "action" => "listGiveaway", 'plugin' => false), array('escape' => false)); ?></li>
                   
                </ul>
            </li>
            
             <li>
                <?php
                if($this->params['controller']=='Email Template' || $this->params['controller']=='Lists'){
                        echo $this->Html->link("Email", 'javascript::void(0);', array('escape' => false,'class'=>'active'));
                    }else{
                        echo $this->Html->link("Email", 'javascript::void(0);', array('escape' => false));
                    }
                    ?>
                 
                <ul class="sublinks">
                    <li><?php echo $this->Html->link("Email Templates", array("controller" => "CmsPages", "action" => "emailList", 'plugin' => false), array('escape' => false, 'class' => ""));?></li>
                    <li> <?php echo $this->Html->link("Lists", array("controller" => "MyLists", "action" => "list", 'plugin' => false), array('escape' => false)); ?></li>
                    
                    <li><?php echo $this->Html->link("Compaigns", array("controller" => "Compaigns", "action" => "list", 'plugin' => false), array('escape' => false)); ?></li>
                </ul>
            </li>
            
            <li>
                <?php
                 if($this->params['controller']=='Services' || $this->params['controller']=='Packages'){
                        echo $this->Html->link("Marketing", 'javascript::void(0);', array('escape' => false,'class'=>'active'));
                 }else{
                        echo $this->Html->link("Marketing", 'javascript::void(0);', array('escape' => false));
                    }?>
                 
                <ul class="sublinks">
                    
                   
                    <li><?php echo $this->Html->link("Services", array("controller" => "Services", "action" => "list", 'plugin' => false), array('escape' => false, 'class' => ""));?></li>
                    <li><?php echo $this->Html->link("Packages", array("controller" => "Packages", "action" => "list", 'plugin' => false), array('escape' => false, 'class' => ""));?></li>
                  
                </ul>
            </li>
            
      
        <ul class="account_logout">
            <li>
                <?php
                echo $this->Html->link('Log Out', '/admin/Users/logout', array('class' => 'logout')
                );
                ?>
            </li>
            <li>
                <a class="my_acnt" href="#">My Account</a>
            </li>
        </ul>
    </section>
</nav>
<!--Navigation Ends-->

