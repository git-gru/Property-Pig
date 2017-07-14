<?php

include_once( 'class_connection.php' );

class commonFunctions extends DB_Connection {

    /******* GetServerUrl *******/
    function getServerUrl() {
       return "https://www.propertypig.co.uk/Appapi/";
    }
    function mailFunction($from, $to, $subject, $content)
    {
        $i = 0;
        $random_hash = md5(date('r', time()));
        $headers = "From:$from\nReply-To:$from ";
        $headers .= "\nContent-Type: text/html; boundary=\"PHP-alt-" . $random_hash . "\"";
        $mail = mail($to, $subject, $content, $headers, "-f $from");
        if ($mail) {
            $i = 1;
        }
        return $i;
    }
    function zoopla_property($permanentquestion_array)
    {
        $k=1;
        $house='';$postcode='';$property_type='';$num_bedrooms='';$num_bathrooms='';$num_receptions='';$tenure='';
        foreach( $permanentquestion_array as $val )
        {
            foreach( $val as $key => $item )
            {
                if($k==1)
                {
                    $house=$item;
                }
                if($k==2)
                {
                    $postcode=str_replace(" ", "", $item);
                }
                if($k==3)
                {
                    $num_bedrooms=$item;
                }
                if($k==4)
                {
                    $num_bathrooms=$item;
                }
                if($k==5)
                {
                    $num_receptions=$item;
                }
                if($k==6)
                {
                    $property_type=$item;
                }
                if($k==7)
                {
                    $tenure=$item;
                }
                $k++;
            }
        }


            // Get the latitude & longitude of submitted postcode
            $postcode = urlencode($postcode);
            $query = 'http://maps.googleapis.com/maps/api/geocode/json?address=' . $postcode . '&sensor=false';
            $result = json_decode(file_get_contents($query));

            $lat = $result->results[0]->geometry->location->lat;
            $lng = $result->results[0]->geometry->location->lng;


            // Get the address based on returned lat & long
            $address_url = 'http://maps.googleapis.com/maps/api/geocode/json?latlng=' . $lat . ',' . $lng . '&sensor=false';
            $address_json = @json_decode(file_get_contents($address_url));
            $address_data = $address_json->results[0]->address_components;
            foreach($address_data as $data):
                $array[$data->types[0]] = $data->long_name;
            endforeach;
            $street = $array['route'];
            $town = $array['locality'];

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
if (!$propertyid) {
 $string = $house.$line2.", ".$postcode;
                    $zoopla = "http://www.zoopla.co.uk/search/?section=house-prices&q=".urlencode($string);
                    $html = file_get_contents($zoopla, false, $context);
                    //extract the propertyid from the header
                    $array2 = $http_response_header;
                    $zooplacode = explode('yr=',$array2[4]);
                    $propertyid = $zooplacode[1];

}
if (!$propertyid) {
 $string = $house.$line2.$town", ".$postcode;
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

            $api2 = 'http://api.zoopla.co.uk/api/v1/refine_estimate?property_id='.$propertyid.'&api_key='.$apikey.'&session_id='.$sid."&property_type=".$property_type."&tenure=".$tenure."&num_bedrooms=".$num_bedrooms."&num_bathrooms=".$num_bathrooms."&num_receptions=".$num_receptions;
            $api2result = @file_get_contents($api2);
            if($api2result === FALSE) {
                return 'invalid';
           }
        else{
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
            if (!empty($confidence))
            {
                $output = array($confidence, $lowestimate, $estimate, $ppo);

            }
            else
            {
                $output="Invalid Response Received";
            }
            return $output;
        }


    }

    
}

?>
