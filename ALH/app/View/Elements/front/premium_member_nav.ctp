<nav class="nav-rw">
    <div class="nav top-navigation">
        <div class="center-block">
            <ul>
                <li class="active-li dropdown">
                    <?php echo $this->Html->link('Calendar', BASE_URL, array('class' => 'calendar_icon')); ?>
                </li>
                <li>
                    <?php
                        if ($this->Session->check('Auth'))
                        {
                            echo $this->Html->link('Create', BASE_URL."/events/createEvent", array('class' => 'create_icon'));
                        }
                        else
                        {
                            echo $this->Html->link('Create', "javascript:void(0);", array('class' => 'create_icon', "data-target"=>"#sign_in", "data-toggle"=>"modal"));
                        }
                    ?>
                </li>
                <li>
                     <?php
                        if ($this->Session->check('Auth'))
                        {
                            echo $this->Html->link('Manage', BASE_URL."/users/viewProfile", array('class' => 'manage_icon'));
                        }
                        else
                        {
                            echo $this->Html->link('Manage', "javascript:void(0);", array('class' => 'manage_icon', "data-target"=>"#sign_in", "data-toggle"=>"modal"));
                        }
                    ?>
                </li>
                <li>
                     <?php echo $this->Html->link('Marketing', BASE_URL."/Events/marketing", array('class' => 'marketing_icon')); ?>
                </li>
                <li>
                     <?php echo $this->Html->link('Email', BASE_URL."/Events/email", array('class' => 'email_icon')); ?>
                </li>
            </ul>
            
            <div class="search_events top_news" style="padding:10px 11px 0 !important;float:right;">
                <input placeholder="Enter Your Email" type="email" id = "newsletter_email_top" required = "required"/>
                <span style = "display:none; float: none !important;" class="loader" id="load_newsletter_top"><?php echo $this->html->image('/img/admin/loader.gif'); ?></span>
                <input type="submit" value="Subscribe" class="mhrn_button" onclick="javascript:newsletterTopnav();"/>
            </div>
        </div>

    </div>
</nav>