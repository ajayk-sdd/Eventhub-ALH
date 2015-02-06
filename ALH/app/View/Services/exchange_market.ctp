<?php
//pr($datas);
?>


<div class="center-block">
    <div class="em-sec">
        <h1>Exchange Market</h1>
        <?php echo $this->Session->flash(); ?>
       
        <?php
        foreach ($packageData as $data) {
            ?>
            <div class="col-3">
                <div class="package-box">
                    <h3><?php echo $data["Package"]["name"]; ?></h3>
                    <div>
                        <p class="package-desc"><?php echo $data["Package"]["description"]; ?></p>
                        <ul>
                            <?php
                            foreach ($data["Service"] as $service):
                                echo "<li>" . $service["name"] . "</li>";
                                ?>
                            <?php endforeach; ?>
                        </ul>

                        <div class="clear"></div>
                        <input type="text" readonly class="rate-box" value="$ <?php echo $data["Package"]["price"]; ?>">
                        <input type="text" readonly class="rate-box" value="<?php echo $data["Package"]["point"]; ?> Points">
                        <?php echo $this->html->link("Add to cart", 'javascript:void(0);', array("class" => "btn-addToCart","rel"=>$data['Package']['id'],'price'=>$data['Package']['price'])); ?>
                           <span class="loader" id="load_<?php echo $data['Package']['id'];?>"><?php echo $this->html->image('/img/admin/loader.gif');?></span>

                    </div>
                </div>
            </div>
            <?php
        }
        ?>

        <div class="col-3">
            <form name="event-detail" class="event-form" action="#" method="post">
                <p class="para-1">Not seeing anything here that works for what you need?  Build your own promotional package by choosing from our wide range of services on our</p>

                <?php echo $this->html->link("Promotional Material Ala Carte Page", array("controller" => "Services", "action" => "alacartePromotionalService"), array("class" => "proMed")); ?>
                <?php echo $this->html->link("Skip this & Continue", array("controller" => "Events", "action" => "shareEvent"), array("class" => "anc_link")); ?>
                <?php echo $this->html->link("Get Started", array("controller" => "Services", "action" => "alacartePromotionalService"), array("class" => "anc_link")); ?>

            </form>
        </div>
        <div class="clear"></div>
      
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function(){
        $(".btn-addToCart").click(function() {
      
        var packageId = $(this).attr("rel");
        $('#load_'+packageId).show();
        var price = $(this).attr("price");
        jQuery.ajax({
            url: '/Services/addToCart/' + packageId + '/'+price,
            success: function(data) {
                if(data == 0){
                    $('#load_'+packageId).html("Added to cart !");
                }else if(data==1){
                    $('#load_'+packageId).html("Not added !");
                }
                else{
                    $('#load_'+packageId).html("Already added !");
                }
            }
        });
    });
    });
</script>
