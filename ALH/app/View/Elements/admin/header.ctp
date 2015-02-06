<div class="center-block">
    <div class="logo"> <?php echo $this->Html->link($this->Html->image('../img/front/logo.png'), '/admin/Users/dashboard', array('escape' => false)); ?> </div>

    <?php if (!isset($_SESSION['Auth']['User']['id'])) { ?>
        <div class="signup-block">
            <?php echo $this->Html->link('Sign Up', 'javascript:void(0);', array('class' => 'signup-link',"data-toggle"=>"modal", "data-target"=>"#sign_up")); ?>
            <?php echo $this->Html->link('Sign In', 'javascript:void(0);', array('class' => 'signup-link last',"data-toggle"=>"modal", "data-target"=>"#sign_in")); ?>
        </div>
    <?php } else { ?>
        <div class="signup-block">
            <?php echo $this->Html->link('My Account', '/admin/Users/dashboard', array('class' => 'signup-link')); ?>
            <?php echo $this->Html->link('Log Out', '/admin/Users/logout', array('class' => 'signup-link last')); ?>
        </div>
    <?php } ?>
    <div class="clear"></div>
</div>
<div class="mobile-block">
    <div class="center-block"> <?php echo $this->Html->link('Nav 1', 'javascript:void(0);', array('class' => 'mobile-nav')); ?></div>
</div>
<nav class="nav-rw">
    <div class="nav">
        <div class="center-block">
            <ul style="float: left">
                <li>
                    <?php echo $this->Html->link("DASHBOARD", array("controller" => "Users", "action" => "dashboard", 'plugin' => false), array('escape' => false, 'class' => "")); ?> 
                </li>

                <li class="active-li dropdown"> <?php echo $this->html->link("MANAGEMENT","javascript:void(0);" ); ?>
                    <ul>
                        <li><?php echo $this->Html->link("USERS", array("controller" => "Users", "action" => "list", 'plugin' => false), array('escape' => false)); ?></li>
                        <li><?php echo $this->html->link("BRANDS", array("controller" => "Brands", "action" => "list")); ?></li>
                        <li><?php echo $this->Html->link('PERMISSION', '/admin/acl'); ?></li>
                        <li><?php echo $this->html->link("PAGES", array("controller" => "CmsPages", "action" => "list")); ?></li>
                        <li><?php echo $this->html->link("BANNERS", array("controller" => "CmsPages", "action" => "listBanner")); ?></li>
                        <li><?php echo $this->html->link("POINTS", array("controller" => "Points", "action" => "setPoint")); ?></li>
                    </ul>
                </li>

                <li class="active-li dropdown"> <?php echo $this->html->link("EVENT","javascript:void(0);" ); ?>
                    <ul>
                        <li><?php echo $this->Html->link("EVENTS", array("controller" => "Events", "action" => "list", 'plugin' => false), array('escape' => false, 'class' => "")); ?></li>
                        <li><?php echo $this->Html->link("CATEGORIES", array("controller" => "Categories", "action" => "list", 'plugin' => false), array('escape' => false)); ?></li>
                        <li><?php echo $this->Html->link("VIBES", array("controller" => "Categories", "action" => "listVibes", 'plugin' => false), array('escape' => false)); ?></li>
                        <li><?php echo $this->Html->link("REGIONS", array("controller" => "Regions", "action" => "list", 'plugin' => false), array('escape' => false)); ?></li>
                        <li><?php echo $this->Html->link("TICKET GIVEAWAY", array("controller" => "Events", "action" => "listGiveaway", 'plugin' => false), array('escape' => false)); ?></li>
                       
                    </ul>
                </li>

                <li class="active-li dropdown"> <?php echo $this->html->link("EMAIL","javascript:void(0);" ); ?>
                    <ul>
                        <li><?php echo $this->Html->link("EMAIL TEMPLATES", array("controller" => "CmsPages", "action" => "emailList", 'plugin' => false), array('escape' => false, 'class' => "")); ?></li>
                        <li> <?php echo $this->Html->link("LISTS", array("controller" => "MyLists", "action" => "list", 'plugin' => false), array('escape' => false)); ?></li>
                        <li> <?php echo $this->Html->link("NEWSLETTERS", array("controller" => "Newsletters", "action" => "list", 'plugin' => false), array('escape' => false)); ?></li>
                        <li><?php echo $this->Html->link("CAMPAIGNS", array("controller" => "Campaigns", "action" => "list", 'plugin' => false), array('escape' => false)); ?></li>
                </ul>
                </li>
                <li class="active-li dropdown"> <?php echo $this->html->link("MARKETING","javascript:void(0);" ); ?>
                    <ul>
                        <li><?php echo $this->Html->link("LIST OFFERS", array("controller" => "MyLists", "action" => "listOffer", 'plugin' => false), array('escape' => false, 'class' => "")); ?></li>
                        <li><?php echo $this->Html->link("SERVICES", array("controller" => "Services", "action" => "list", 'plugin' => false), array('escape' => false, 'class' => "")); ?></li>
                        <li><?php echo $this->Html->link("PACKAGES", array("controller" => "Packages", "action" => "list", 'plugin' => false), array('escape' => false, 'class' => "")); ?></li>
                        <li><?php echo $this->Html->link("SALES", array("controller" => "Sales", "action" => "list", 'plugin' => false), array('escape' => false, 'class' => "")); ?></li>
                        <li><?php echo $this->Html->link("VIRTUAL CURRENCY", array("controller" => "Packages", "action" => "virtualCurrencyPackages", 'plugin' => false), array('escape' => false, 'class' => "")); ?></li>
                    </ul>
                </li>

            </ul>
        </div>
    </div>
</nav>