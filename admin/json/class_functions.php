<?php

include_once( 'class_connection.php' );

class commonFunctions extends DB_Connection {

    /******* GetServerUrl *******/
    function getServerUrl() {
       return "https://www.propertypig.co.uk/admin/";
    }

    
}

?>