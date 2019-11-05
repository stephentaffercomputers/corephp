<?php 
//$json = file_get_contents('data.txt', FILE_USE_INCLUDE_PATH);
//$obj = json_decode($json);

ini_set('allow_url_fopen', '1');

$link = mysqli_connect("localhost", "leaseoff_db", "BTrsHGcg21vnwUpt", "leaseoff_db");

//$link = mysqli_connect("localhost", "warehous_root", "7Pt2iFKOy8WYeCwOh", "warehous_main");
$Path = file('https://leaseofficespace.net/office_4.jsonlines');

$print = '';
foreach ($Path as $Line){
    $Output = json_decode($Line);
    $latitude = $Output->location->lat;
    $longitude = $Output->location->long;

    $addr = explode(',', $Output->address);
   if(!empty($Output->price)) {
   $price = $Output->price[1].number_format($Output->price[0]);
   } else {
   $price = 'Negotiable';
  }

    $full_address = $Output->address;
    $title = $full_address;
    $space_number = $Output->listing_id;
    if(isset($Output->total_rentable_area[0]))
    {
        $space_available = $Output->total_rentable_area[0] . ' ' . $Output->total_rentable_area[1];
    }
    else
    {
        $space_available = $Output->total_lot_size[0] . ' ' . $Output->total_lot_size[1];
    }

    $RentalRate = 'Negotiable';
    $street_address = $Output->address;
    $city_name = $addr[1];
    $state_code = trim($addr[2]);
    $postal_code = trim($addr[3]);
    $property_type = $Output->property_type;
    $units = $Output->units;
//    $date = strototime(date('Y-m-d H:i:s'));
    $description = 'Below are units of';
    foreach($units as $unit)
    {
         $description .= '<div><h3>Title : ' .  $unit->title .'</h3>';
         $description .= '<p>Rental Rate : ' . $unit->rental_rate[0] . ' ' .$unit->rental_rate[1] .'</p>';
         $description .= '<p>Lease Term : ' . $unit->lease_term .'</p>';
         $description .= '<p>Lease Type : ' . $unit->lease_type .'</p>';
         $description .= '<p>Space Available : ' . $unit->space_available[0] . ' ' . $unit->space_available[1] .'</p>';
         $description .= '<p>Space Use : ' . $unit->space_use .'</p>';
         $description .= '<p>Space Type : ' . $unit->space_type .'</p>';
         $description .= '</div>';
    }
    if(isset($Output->address) && !empty($Output->address))
    {
        $agent_phone = $Output->contact->agents[0]->phone;
        $select_query = mysqli_query($link, "SELECT * FROM office_listing_new where Title='".$Output->address."'");
        if(mysqli_num_rows($select_query) > 0){
        //echo 'test'; exit;
        echo "<br>" . $update_sql = "UPDATE office_listing_new SET Title = '".$title."', SpaceNumber =  '".$space_number."', SpaceAvailable = '".$space_available."', RentalRate = '".$RentalRate."', MonthlyRate = '', StreetAddress = '".$street_address."', CityName = '".$city_name."', StateProvCode = '".$state_code."', PostalCode = '".$postal_code."', LotSize = '', LastUpdate = '', SpaceAvailableMax = '".$space_available."', SpaceAvailableMin = '".$space_available."', PropertySubType = 'Warehouse', PropertyType = '".$property_type."', RentalRateMin = '".$price."', SpaceAvailableTotal = '".$space_available."', Description = '".$description."', Latitude = '".$latitude."', Longitude = '".$longitude."', PhotoURL = '', PhotoURL2 = '', PhotoURL3 = '', PhotoURL4 = '', PhotoURL5 = '',ListingIsActive = 'y', AgentName = '', AgentEmail = '', AgentPhone = '".$agent_phone."', created_from = 'json' where Title = '".$title."'";
            $result = $link->query($update_sql);

        }
        else
        {
if(empty($title) || empty($space_number) || empty($space_available) || empty($RentalRate)) {
        echo $sql = "INSERT INTO warehouse_listing_failed (Title, SpaceNumber, SpaceAvailable, RentalRate, MonthlyRate, StreetAddress, CityName, StateProvCode, PostalCode, LotSize, LastUpdate, SpaceAvailableMax, SpaceAvailableMin, PropertySubType, PropertyType, RentalRateMin, SpaceAvailableTotal, Description, Latitude, Longitude, PhotoURL, PhotoURL2, PhotoURL3, PhotoURL4, PhotoURL5,ListingIsActive,AgentName,AgentEmail,AgentPhone,created_from) VALUES ('".$title."', '".$space_number."','".$space_available."','".$RentalRate."', '', '".$street_address."', '".$city_name."', '".$state_code."', '".$postal_code."', '', '','".$space_available."', '".$space_available."', 'Office', '".$property_type."', '".$price."', '".$space_available."', '".$description."', '".$latitude."' , '".$longitude."', '', '','', '', '', 'y' ,'', '', '".$agent_phone."','json')"; 

} else {
        echo $sql = "INSERT INTO office_listing_new (Title, SpaceNumber, SpaceAvailable, RentalRate, MonthlyRate, StreetAddress, CityName, StateProvCode, PostalCode, LotSize, LastUpdate, SpaceAvailableMax, SpaceAvailableMin, PropertySubType, PropertyType, RentalRateMin, SpaceAvailableTotal, Description, Latitude, Longitude, PhotoURL, PhotoURL2, PhotoURL3, PhotoURL4, PhotoURL5,ListingIsActive,AgentName,AgentEmail,AgentPhone,created_from) VALUES ('".$title."', '".$space_number."','".$space_available."','".$RentalRate."', '', '".$street_address."', '".$city_name."', '".$state_code."', '".$postal_code."', '', '','".$space_available."', '".$space_available."', 'Office', '".$property_type."', '".$price."', '".$space_available."', '".$description."', '".$latitude."' , '".$longitude."', '', '','', '', '', 'y' ,'', '', '".$agent_phone."','json')";  
}
            $result = $link->query($sql);
            //exit;
        ///echo $description;
        //echo "<pre>";
        //print_r($Output);
        //echo "</pre>";
        //exit;
        }
    }
}


?>