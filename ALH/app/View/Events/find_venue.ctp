<?php
if ($data["status"] == "OK") {
    ?>
    <ul>
        <?php
        //pr($data["results"]);
        $i = 0;
        foreach ($data["results"] as $dt) {
            $i++;
            $address_detail = explode(",", $dt["formatted_address"]);

            $new_address_detail = array_reverse($address_detail);

            if (isset($new_address_detail[0])) {
                $country = $new_address_detail[0];
            } else {
                $country = "";
            }
            if (isset($new_address_detail[1])) {
                $state_arr = explode(" ", trim($new_address_detail[1]));
                if (isset($state_arr[0])) {
                    $state = $state_arr[0];
                } else {
                    $state = "";
                }
                if (isset($state_arr[1])) {
                    $zipcode = $state_arr[1];
                } else {
                    $zipcode = "";
                }
            } else {
                $state = "";
                $zipcode = "";
            }

            if (isset($new_address_detail[2])) {
                $city = $new_address_detail[2];
            } else {
                $city = "";
            }

            if (isset($new_address_detail[3])) {
                $full_address = $new_address_detail[3];
            } else {
                $full_address = "";
            }
            ///////////////////////////////////////////
            if ($dt["geometry"]["location"]["lat"]) {
                $lat = $dt["geometry"]["location"]["lat"];
                $lng = $dt["geometry"]["location"]["lng"];
            } else {
                // united states lat lng
                $lat = "38.8833";
                $lng = "77.0167";
            }
            if ($dt["name"]) {
                $name = $dt["name"];
            } else {
                $name = "";
            }
            ?>
            <li>
                <!--h5><?php //echo $i;      ?></h5-->
                <p>
                    <?php
                    if ($dt["icon"]) {
                        echo $this->Html->image($dt["icon"], array("style" => "height:50px; width:50px;", "alt" => $i));
                    }
                    ?>


                    <a href="javascript:void(0);" onclick='javascript:getDetail(<?php echo '"' . $name . '","' . $full_address . '","' . $city . '","' . $state . '","' . $country . '","' . $lat . '","' . $lng . '","' . $zipcode . '"'; ?>);'><?php echo $dt["formatted_address"]; ?></a>
                    <br><?php echo $dt["name"]; ?>
                </p>
                <a class ="add-more-tt" href="javascript:void(0);" onclick='javascript:getDetail(<?php echo '"' . $name . '","' . $full_address . '","' . $city . '","' . $state . '","' . $country . '","' . $lat . '","' . $lng . '","' . $zipcode . '"'; ?>);'>Select</a>
            </li>
            <hr>
        <?php } ?>
    </ul>
    <?php
} else {
    echo $data["status"];
}