<a href="javascript:void(0);" onclick="javascript:window.print();" style="    background-color: #1273C4;
   border-radius: 3px;
   color: #FFFFFF;
   float: right;
   font-size: 14px;
   margin-right: 10px;
   margin-top: 16px;
   padding: 8px 20px;
   position: relative;">Print</a>
   <?php
//pr($data);
   echo $this->Html->css('front/style');
   echo $this->Html->css('front/media');
   echo $this->Html->script('jquery-1.10.2');
   ?>
<header class="header">
    <div class="logo"> <?php echo $this->Html->image('../img/front/logo.png'); ?> </div>
</header>
<section class="inner-content">
    <div class="center-block">
        <div class="repo-list-wrap" style="margin-bottom: 10px;">
            <h1>Invoice #: <?php echo $data["Payment"]["transaction_id"]; ?></h1>
            <div class="repo-list reli-II">
                <dl>

                    <dt>Transaction Id</dt>
                    <dd><?php echo $data["Payment"]["transaction_id"]; ?></dd>

                    <dt>Package Purchased</dt>
                    <dd><?php echo $data["Package"]["name"]; ?></dd>

                    <dt>Amount</dt>
                    <dd><?php echo "$" . $data["Payment"]["amount"]; ?></dd>

                    <dt>Status</dt>
                    <dd><?php
                        if ($data['Payment']['status'] == 0)
                            echo "In Progress";
                        else
                            echo "Completed";
                        ?></dd>

                    <dt>Billing Address</dt>
                    <dd><?php 
                    if(!empty($data["Payment"]["billing_address_1"]))
                    echo $data["Payment"]["billing_address_1"];
                    if(!empty($data["Payment"]["billing_address_2"]))
                    echo "<br>" . $data["Payment"]["billing_address_2"] ;
                    echo "<br>" . $data["Payment"]["city"] . "," . $data["Payment"]["state"] . "," . $data["Payment"]["zip"]; ?></dd>

                    <dt>Date Of Purchased</dt>
                    <dd><?php echo date("l, F d, Y", strtotime($data["Payment"]["created"])); ?></dd>



                </dl>

                <div class="clear"></div>

            </div>
        </div>
    </div>
</section>