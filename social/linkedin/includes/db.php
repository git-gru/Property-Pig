<?php

class DB {

    function __construct() {
        $this->dbConnect();
        $this->userTable = 'users';
    }

    function dbConnect() {
        // database configuration
        $dbServer = 'localhost';
        $dbUsername = 'pp_j9f349imc9';
        $dbPassword = '~;lWBT35GD;{';
        $dbName     = 'pp_0cd249u34';
        $conn = new mysqli($dbServer, $dbUsername, $dbPassword, $dbName);
        if ($conn) {
            $this->db = $conn;
        } else {
            die("Database conection error: " . $conn->connect_error);
        }
    }

    function checkUser($userdata) {
        $oauth_uid = $userdata->id;
        $email = $userdata->emailAddress;
        $check = $this->db->query("SELECT * FROM `pig_user` WHERE `lk_id` = '" . $oauth_uid . "' || `email` = '" . $email . "'");
        if (mysqli_num_rows($check) > 0) {
            $result = $check->fetch_array(MYSQLI_ASSOC);
            $uname = $userdata->firstName . " " . $userdata->lastName;
            $query = "UPDATE pig_user SET `Name` = '" . $uname . "', `email` = '" . $userdata->emailAddress . "', `Address` = '" . $userdata->location->name . "', 	`Address1` = '" . $userdata->location->country->code . "', `lk_id` = '" . $oauth_uid . "' WHERE `user_id` = " . $result['user_id'];
            $this->db->query($query);
            return $result['user_id'];
        } else {
            $uname = $userdata->firstName . " " . $userdata->lastName;
            //$userdata->id
            $query = "INSERT INTO pig_user(Name,email,Address,Address1,lk_id,userStatus) VALUES ('" . $uname . "','" . $userdata->emailAddress . "','" . $userdata->location->name . "','" . $userdata->location->country->code . "','" . $userdata->id . "',1)";
            $this->db->query($query);
            return $this->db->insert_id;
        }
    }

}

?>
