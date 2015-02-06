<!--Content Wrapper Starts-->
<section id="cont_wrapper">
    <!--Content Starts-->
    <section class="content">
        <!--Main Heading Starts-->
        <h1 class="main_heading"> <?php echo $this->Html->link('Services', '/admin/Services/add'); ?></h1>
        <!--Main Heading Ends-->
        <!--Conetnt Info Starts Here-->
        <section class="content_info">
            <ul class="form">
                <li>
                    <label>Type :</label><?php echo ($service['Service']['type']==1) ? 'Service Package' : "Promo Package";?>
                </li>
                <li>
                    <label>Shows On :</label><?php if ($service['Service']['shown_on']==0){echo "None";}elseif($service['Service']['shown_on']==1){echo "ALH Services";}else{echo "Promotional Packages";}?>
                </li>
                <li>
                    <label>Name :</label><?php echo $service['Service']['name']; ?>
                </li>
                <li>
                    <label>Slug :</label><?php echo $service['Service']['slug']; ?>
                </li>
                <li>
                    <label>Description :</label><?php echo $service['Service']['description']; ?>
                </li>
                <li>
                    <label>SKU :</label><?php echo $service['Service']['sku']; ?>
                </li>
                <li>
                    <label>UPC :</label><?php echo $service['Service']['upc']; ?>
                </li>
                <li>
                    <label>Price :</label>$ <?php echo $service['Service']['price']; ?>
                </li>
<li>
                    <label>Override Price :</label>$ <?php echo $service['Service']['override_price']; ?>
                </li>
                <li>
                    <label>Meta Description :</label><?php echo $service['Service']['meta_description']; ?>
                </li>
<li>
                    <label>Meta Keyword :</label><?php echo $service['Service']['meta_keyword']; ?>
                </li>
<li>
                    <label>Status :</label><?php echo ($service['Service']['status']==0) ? 'Not Active' : "Active";?>
                </li>
                <li>
                    <label>Created :</label><?php echo $service['Service']['created']; ?>
                </li>
                <li>
                    <label>Modified :</label><?php echo $service['Service']['modified']; ?>
                </li>



                <li>
<span class="blu_btn_lt">
    <input type="reset" id="ServiceReset" class="blu_btn_rt" onclick="javascript:window.back();" value="Go Back"></span>
                </li>
            </ul>
            <section class="clr_bth"></section>
        </section>
        <!--Conetnt Info Ends Here-->
    </section>
    <!--Content Ends-->
</section>
<!--Content Wrapper Ends-->