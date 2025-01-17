<?php
    require('db.php');
    if(isset($_GET["rqst_id"])){
        $rqst_id = $_GET["rqst_id"];
        $query = "SELECT farmerrqst.weight, farmerrqst.date, farmerrqst.image1, farmerrqst.image2, farmerrqst.image3, farmerrqst.quality, farmerrqst.rdate, farmerrqst.status, users.user_id, users.user_name, users.user_crop, users.user_lat, users.user_lng FROM farmerrqst JOIN users ON farmerrqst.user_id = users.user_id WHERE rqst_id = $rqst_id";
        $result = mysqli_query($con, $query);
        if(!$result){
            die("FAILD!!".mysqli_error($con));
        }
        while($row = mysqli_fetch_assoc($result)){
            $user_id = $row['user_id'];
            $user_name = $row['user_name'];
            $user_crop = $row['user_crop'];
            $rqst_weight = $row['weight'];
            $rqst_date = $row['date'];
            $rqst_rdate = $row['rdate'];
            $img1 = $row['image1'];
            $img2 = $row['image2'];
            $img3 = $row['image3'];
            $lat = $row['user_lat'];
            $lng = $row['user_lng'];
            $rqst_quality = $row['quality'];
            if($rqst_quality == "P")
                $rqst_quality = "Poor";
            elseif($rqst_quality == "G")
                $rqst_quality = "Good";
            else
                $rqst_quality = "N/A";
            $rqst_status = $row['status'];
            if($rqst_status == "A")
                $rqst_status = "Approved";
            elseif($rqst_status == "N")
                $rqst_status = "New Request";
            elseif ($rqst_status == "C")
                $rqst_status = "Collected";
            elseif ($rqst_status == "R")
                $rqst_status = "Rejected";
            
            //get address using lat,lng
            $url = "https://maps.googleapis.com/maps/api/geocode/json?latlng=" . $lat . ',' . $lng . "&key=AIzaSyC5YjkAepzUoPgY5mmhMdPkOXx4cXY4cbs&sensor=false";

            $json = @file_get_contents($url);
            $data=json_decode($json);
            $status = $data->status;
            if($status=="OK"){
                $address =  $data->results[0]->formatted_address;
            }else{
                $address = "N/A";
            }
        }
    }
?>