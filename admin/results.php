<?php
ini_set("display_errors", "0"); error_reporting(0);

// FORCE THE USER TO USE SSL
if($_SERVER["HTTPS"] != "on")
{
    header("Location: https://" . $_SERVER["HTTP_HOST"] . $_SERVER["REQUEST_URI"]);
    exit();
}


// SECURE THE API WITH A PASSWORD
if ($_REQUEST['secret'] !== "pig990") { print "Invalid"; }
if ($_REQUEST['secret'] == "pig990") {


// Get the street name and locale from google maps API so we can use it	to scrape the zoopla website for propertyid
$house = $_REQUEST['house'];
$postcode = str_replace(" ", "", $_REQUEST['postcode']);

// Get the latitude & longitude of submitted postcode
$postcode = urlencode($postcode);
$query = 'http://maps.googleapis.com/maps/api/geocode/json?address=' . $postcode . '&sensor=false';
$result = json_decode(file_get_contents($query));

$lat = $result->results[0]->geometry->location->lat;
$lng = $result->results[0]->geometry->location->lng;

// Get the address based on returned lat & long
$address_url = 'http://maps.googleapis.com/maps/api/geocode/json?latlng=' . $lat . ',' . $lng . '&sensor=false';
$address_json = json_decode(file_get_contents($address_url));
$address_data = $address_json->results[0]->address_components;
foreach($address_data as $data):
$array[$data->types[0]] = $data->long_name;
endforeach;
$street = $array['route'];
$town = $array['locality'];
//echo $house." ".$array['route'].", ".$array['locality']. " " .strtoupper(str_replace("+", " ", $postcode))."<br>";


$context = stream_context_create(
    array(
        'http' => array(
            'follow_location' => true
        )
    )
);



// generate a string which searches zoopla, and returns a header which has the propertyid in it
$string = $house." ".$street.", ".$town.", ".$postcode;
$zoopla = "http://www.zoopla.co.uk/search/?section=house-prices&q=".urlencode($string);
$html = file_get_contents($zoopla, false, $context);
//extract the propertyid from the header

$array2 = $http_response_header;
$zooplacode = explode('yr=',$array2[4]);
$propertyid = $zooplacode[1];

//zoopla api key
$apikey = 'tfj69htyzg344yu2dzv634zc';

//get session id for valuation call
$api1 = 'http://api.zoopla.co.uk/api/v1/get_session_id?api_key='.$apikey;
$get_session_id = file_get_contents($api1);


//parse the xml response for the session id, we need this to get valuation
$p = xml_parser_create();
xml_parse_into_struct($p, $get_session_id, $vals, $index);
xml_parser_free($p);
$sid = $vals[7][value];
$api2 = 'http://api.zoopla.co.uk/api/v1/refine_estimate?property_id='.$propertyid.'&api_key='.$apikey.'&session_id='.$sid."&property_type=".$_REQUEST['property_type']."&tenure=".$_REQUEST['tenure']."&num_bedrooms=".$_REQUEST['num_bedrooms']."&num_bathrooms=".$_REQUEST['num_bathrooms']."&num_receptions=".$_REQUEST['num_receptions'];
$api2result = file_get_contents($api2);
$p2 = xml_parser_create();
xml_parse_into_struct($p2, $api2result, $vals, $index);
xml_parser_free($p2);

//get the information from zoopa and display it in an array for the website & app.
$confidence = $vals[3][value];
$estimate = $vals[7][value];
$lowestimate = $vals[9][value];
$upperestimate = $vals[19][value];

//remove a percentage from the homes value
$percent = 22.5;

$ppo = explode(".",($lowestimate-(($percent/100)*$lowestimate)));
$ppo = $ppo[0];
if (!empty($confidence)) {
$output = array($confidence, $lowestimate, $estimate, $ppo);
print_r($output);
} else { print "Invalid Response Received "; }
}
?>
