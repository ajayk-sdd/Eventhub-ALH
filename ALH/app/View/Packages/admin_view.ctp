<!--Content Wrapper Starts-->
<section id="cont_wrapper">
    <!--Content Starts-->
    <section class="content">
        <!--Main Heading Starts-->
        <h1 class="main_heading"> <?php echo $this->Html->link('Packages', '/admin/Packages/add'); ?></h1>
        <!--Main Heading Ends-->
        <!--Conetnt Info Starts Here-->
        <section class="content_info">
            <ul class="form">
                
                <li>
                    <label>Name :</label><?php echo $package['Package']['name']; ?>
                </li>
                <li>
                    <label>Slug :</label><?php echo $package['Package']['slug']; ?>
                </li>
                <li>
                    <label>Description :</label><?php echo $package['Package']['description']; ?>
                </li>
                <li>
                    <label>SKU :</label><?php echo $package['Package']['sku']; ?>
                </li>
                <li>
                    <label>UPC :</label><?php echo $package['Package']['upc']; ?>
                </li>
                <li>
                    <label>Price :</label>$ <?php echo $package['Package']['price']; ?>
               
                <li>
                    <label>Meta Description :</label><?php echo $package['Package']['meta_description']; ?>
                </li>
                <li>
                    <label>Meta Keyword :</label><?php echo $package['Package']['meta_keyword']; ?>
                </li>
                <li>
                    <label>Services :</label>
                        <?php $data = "";
                            foreach($package['Service'] as $ser):
                                $data .= $ser['name'];
                                $data .= ",";
                                
                              endforeach;
                              $data = rtrim($data,',');
                              echo $data;
                        ?>
                </li>
                <li>
                   <label>Status :</label><?php echo ($package['Package']['status']==0) ? 'Not Active' : "Active";?>
                </li>
                <li>
                    <label>Created :</label><?php echo $package['Package']['created']; ?>
                </li>
                <li>
                    <label>Modified :</label><?php echo $package['Package']['modified']; ?>
                </li>



                <li>
                <span class="blu_btn_lt">
                    <input type="reset" id="PackageReset" class="blu_btn_rt" onclick="javascript:history.back();" value="Go Back"></span>
                </li>
            </ul>
            <section class="clr_bth"></section>
        </section>
        <!--Conetnt Info Ends Here-->
    </section>
    <!--Content Ends-->
</section>
<!--Content Wrapper Ends-->