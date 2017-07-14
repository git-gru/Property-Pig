<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Home extends CI_Controller {

    /**
     * Index Page for this controller.
     *
     * Maps to the following URL
     *      http://example.com/index.php/welcome
     *  - or -  
     *      http://example.com/index.php/welcome/index
     *  - or -
     * Since this controller is set as the default controller in 
     * config/routes.php, it's displayed at http://example.com/
     *
     * So any other public methods not prefixed with an underscore will
     * map to /index.php/welcome/<method_name>
     * @see http://codeigniter.com/user_guide/general/urls.html
     */
    public function __construct() {
        parent::__construct();
        // Load form helper library
        $this->load->helper(array('form', 'cookie'));
        // Load form validation library
        $this->load->library('form_validation');
        // Load session library
        $this->load->library('session');
        //Encrypt
        $this->load->library('encrypt');
        //Email
        $this->load->library('email');
        //load model
        $this->load->model('common_model', 'user');

        $this->load->helper('pdf_helper');
    }

    public function index($data = array()) {
        $data['pageTitle'] = 'home';

        if ($this->session->userdata('user_login')) {
           
            $this->schedule();
        } else { 
            $this->load->view('home', $data);
        }
    }

    public function signin() {
        $data = '';
        $this->form_validation->set_rules('username', 'username', 'trim|xss_clean|required');
        $this->form_validation->set_rules('password', 'Password', 'trim|xss_clean|required');
        $this->form_validation->set_rules('company', 'Company', 'trim|xss_clean|required');

        $username = $this->input->post('username');
        $password = $this->input->post('password');
        $company = $this->input->post('company');


        if ($this->form_validation->run() == FALSE) {
            $data['message'] = 'Invalid Username / Password';
            $this->index();
        } else {
            $query = getObj('users', array('username' => $username, 'password' => md5($password), 'company' => $company, 'status' => 1,"rid !="=>5));

            if ($query != '') {
                $remember = $this->input->post('remember');
                if ($remember) {
                    $cookie = array(
                        'name' => 'username',
                        'value' => $username,
                        'expire' => '86500',
                    );
                    $this->input->set_cookie($cookie);
                    $cookie1 = array(
                        'name' => 'password',
                        'value' => $password,
                        'expire' => '86500',
                    );
                    $this->input->set_cookie($cookie1);
                    $cookie2 = array(
                        'name' => 'company',
                        'value' => $company,
                        'expire' => '86500',
                    );
                    $this->input->set_cookie($cookie2);
                } else {
                    delete_cookie("username");
                    delete_cookie("password");
                    delete_cookie("company");
                }
                $this->session->set_userdata('user_login', array(
                    'id' => $query['id'],
                    'rid' => $query['rid'],
                    'username' => $query['username']
                ));
                $data['pageTitle'] = 'Home';
                $data['pageHeading'] = 'Home';
                $data['viewPage'] = 'dashboard';
                $data['pend_list_technician'] = getListBySQL("SELECT *,gourbane_job.sdate as start_date,gourbane_jobmeta.meta_key FROM `gourbane_job` left join gourbane_jobmeta on gourbane_jobmeta.job_id=gourbane_job.id left join users on users.id=gourbane_job.user_id WHERE gourbane_jobmeta.meta_key='technicians' and gourbane_job.edate <= CURDATE()");
                // $this->load->view('template', $data);
                redirect('home/schedule');
            } else {
                $data['invalid'] = "Invalid Username / Password";
                $this->load->view('home', $data);
            }
        }
    }

    public function forgotpassword() {
        $this->load->view('forgotpassword');
    }

    public function forgotpassword_mail() {
        $data = '';
        $this->form_validation->set_rules('email', 'email', 'trim|xss_clean|required');
        $mailid = $this->input->post('email');

        $data['details'] = getObj('users', array('email' => $mailid));
        $decrypt_pass = $data['details']['encrypt_data'];
        $pass = $this->encrypt->decode($decrypt_pass);


        if ($this->form_validation->run() == FALSE) {
            $data['message'] = 'Invalid Username / Password';
            $this->index();
        } else {
            $query = getObj('users', array('email' => $mailid));
            if ($query != '') {

                //$this->load->view('home',$data);//

                $link = base_url('/') . 'home/';
                $subject = "Registration";
                //////////////////////////////////new mail//////////////////////////////////// 
                $txt = "<b><i>Hello , Your Password to Login&nbsp;&nbsp;" . $pass . "<br>";
                ////////////////////////////////////////////////////////////////////////////////////////////////// 

                $headers = "MIME-Version: 1.0" . "\r\n";
                $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";

                // More headers
                $headers .= 'From: <FSManagement@example.com>' . "\r\n";
                $headers .= 'Cc: myboss@example.com' . "\r\n";

                if (mail($mailid, $subject, $txt, $headers)) {

                    //Alert::success('A link has been send to your mail with your password!');
                    $data['success'] = 'A mail has been send with your password!';
                    $this->load->view('forgotpassword', $data);
                    //return Redirect::to('User_Forgot_Password/'.$cat)->withErrors($message);
                    //return Redirect::to('/admin/View-HouseKeeper');
                } else {
                    echo error;
                    //Alert::error('An error has occured. Please try again later!');
                    $message['unsuccess'] = 'An error has occured. Please try again later!';
                    $this->load->view('forgotpassword', $data);

                    //return Redirect::to('/admin/View-HouseKeeper');
                    //return Redirect::to('User_Forgot_Password/'.$cat)->withErrors($message);    
                }
                //////////////////////////////
                //print_r(  $data['pass']);
                //die();
            } else {
                $data['invalid'] = "Invalid Username / Password";
                $this->load->view('forgotpassword', $data);
            }
        }
    }

    public function logout() {
        $this->session->unset_userdata('user_login');
        $this->load->view('home');
    }

    public function addUser() {

        $data = '';
        if ($this->session->userdata('user_login')) {
            $data['pend_list_technician'] = getListBySQL("select t1.*,t2.* from gourbane_notification as t1 inner join users as t2 on t2.id=t1.user_id where t1.user_id='".$this->session->userdata['user_login']['id']."' and t1.status='0'");
            if ($this->input->post()) { //if form is submitted
                $message = '';
                $emessage = '';
                $this->form_validation->set_rules('name', 'FirstName', 'trim|required');
                $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
                //$this->form_validation->set_rules('company', 'Company', 'trim|required');
                $this->form_validation->set_rules('uname', 'Username', 'trim|required');
                $this->form_validation->set_rules('password', 'Password', 'trim|required');
                $uname = $this->input->post('uname');
                $email = $this->input->post('email');
                $e_exists = $this->email_exists($email);
                $u_exists = $this->uname_exists($uname);
                if ($this->form_validation->run() == FALSE) {
                    
                } else {
                    $from_email = "nihaps@gmail.co";
                    $name = $this->input->post('name');
                    $company = $this->input->post('company');
                    $company = '';
                    $ids = $this->session->userdata['user_login']['id'];
                    $get_company = getListBySQL("select company from users where id=$ids");
                    if (!empty($get_company)) {
                        $company = $get_company[0]['company'];
                    }
                    $rid = $this->input->post('role');
                    $password = md5($this->input->post('password'));
                    $designation = "";
                    @$designation = $this->input->post('designation');
                    $parentId = $this->session->userdata['user_login']['id'];

                    $date = date('Y-m-d H:i:s');
                    $encrypt_pass = $this->encrypt->encode($this->input->post('password'));
                    $decrcrypt_pass = $this->encrypt->decode($encrypt_pass);
                    $msg = 'You are added in FSManagement website . Your username is ' . $uname . ' and password is ' . $decrcrypt_pass;

                    if ($u_exists == 1 && $e_exists == 1) {
                        if (insert2db('users', array('firstname' => $name, 'username' => $uname, 'password' => $password, 'email' => $email, 'company' => $company, 'rid' => $rid, 'status' => 1, 'parentId' => $parentId, 'registered' => $date, 'encrypt_data' => $encrypt_pass, 'designation' => $designation))) {

                            //////////File Upload Start////////////////////
                            $lid = $this->db->insert_id();

                            if ($_FILES['avatar']['name'] != '') {
                                $uploadDetails = array();
                                $filename = random_string('numeric', 10);
                                $config['upload_path'] = 'uploads/avatar/';
                                $config['allowed_types'] = 'gif|jpeg|jpg|png';
                                $config['file_name'] = $filename;

                                $this->load->library('upload');
                                $this->upload->initialize($config);
                                if (!$this->upload->do_upload('avatar')) {
                                    $message .= $this->upload->display_errors();
                                } else {
                                    $uploadDetails[] = $this->upload->data();
                                    update2db('users', array('avatar' => $uploadDetails[0]['file_name']), array('id' => $lid));
                                }
                            }

                            ////////////////File Upload END////////////////
                            $subject = "FCManagement  Membership";
                            $from = "FCManagement";
                            $random_hash = md5(date('r', time()));
                            $headers = "From:$from\nReply-To:$from ";
                            $headers .= "\nContent-Type: text/html; boundary=\"PHP-alt-" . $random_hash . "\"";
                            $mail = mail($email, $subject, $msg, $headers);
                            $message.="<br/> User Added Sucessfully";
                        }
                    } else {
                        if ($u_exists == 2)
                            $emessage = 'Username already exist!!';
                        else
                            $emessage = 'Email already exist!!';
                    }
                }
                $data['message'] = $message;
                $data['emessage'] = $emessage;
                //$this->listUser($type,$data);
            }
            $data['pageTitle'] = 'Register';
            $data['pageHeading'] = 'Register';
            $data['viewPage'] = 'register';
            $session_data = $this->session->userdata('user_login');
            $data['username'] = $session_data['username'];
            $data['pend_list_technician'] = getListBySQL("SELECT *,gourbane_job.sdate as start_date,gourbane_jobmeta.meta_key FROM `gourbane_job` left join gourbane_jobmeta on gourbane_jobmeta.job_id=gourbane_job.id left join users on users.id=gourbane_job.user_id WHERE gourbane_jobmeta.meta_key='technicians' and gourbane_job.edate <= CURDATE()");
            $this->load->view('template', $data);
        }
        else {
            $this->load->view('home');
        }
    }

    function email_exists($email) {
        $email = $this->input->post('email');
        $query = getField('users', 'email', array('email' => $email));
        if ($query == '') {
            return 1;
        } else {
            return 2;
        }
    }

    function uname_exists($uname) {
        $query = getField('users', 'username', array('username' => $uname));
        if ($query == '') {
            return 1;
        } else {
            return 2;
        }
    }

    public function dashboard() {
        if ($this->session->userdata('user_login')) {
            
            $data['pageTitle'] = 'Home';
            $data['pageHeading'] = 'Home';
            $data['viewPage'] = 'dashboard';
            $data['pend_list_technician'] = getListBySQL("select t1.*,t2.* from gourbane_notification as t1 inner join users as t2 on t2.id=t1.user_id where t1.user_id='".$this->session->userdata['user_login']['id']."' and t1.status='0'");
            $this->load->view('template', $data);
        } else {
            $this->load->view('home');
        }
    }

    public function jobs() {
        $data = '';
        if ($this->session->userdata('user_login')) {
            $data['pageTitle'] = 'Job';
            $data['pageHeading'] = 'Job';
            $data['viewPage'] = 'job';
            $data['pend_list_technician'] = getListBySQL("select t1.*,t2.* from gourbane_notification as t1 inner join users as t2 on t2.id=t1.user_id where t1.user_id='".$this->session->userdata['user_login']['id']."' and t1.status='0'");
            $session_data = $this->session->userdata('user_login');
            $data['username'] = $session_data['username'];
            $user_id = $session_data['id'];
            $data['countries'] = getList('gourbane_countries', '');
            $data['techs'] = getList('users', array('rid' => 5));

            if ($this->uri->segment('3') != '') {
                $tid = $this->uri->segment('3');
                $today = date("Y-m-d");
                $bfr_7day = date('Y-m-d', strtotime('-7 days'));
                $month_start = date('Y-m-01', strtotime(date('Y-m-d')));
                $month_end = date('Y-m-t', strtotime(date('Y-m-d')));
                if ($tid == 1) {
                    $st = "DATEDIFF('$today',sdate)>=";
                    $en = "DATEDIFF('$today',edate)<=";
                    $data['listjob'] = getList('gourbane_job', array("$st" => 0, "$en" => 0), '', '', '', '*', '', '', '', array('firstname', 'lastname'));
                } else if ($tid == 2) {
                    $mstart = "sdate BETWEEN '$bfr_7day' AND '$today'";
                    $mend = "edate BETWEEN '$bfr_7day' AND '$today'";
                    $data['listjob'] = getListDate('gourbane_job', array("$mstart" => " "), array("$mend" => " "));
                } else if ($tid == 3) {
                    $mstart = "sdate BETWEEN '$month_start' AND '$month_end'";
                    $mend = "edate BETWEEN '$month_start' AND '$month_end'";
                    $data['listjob'] = getListDate('gourbane_job', array("$mstart" => " "), array("$mend" => " "));
                } else if ($tid == 4) {
                    $st = "DATEDIFF('$today',sdate)>=";
                    $en = "DATEDIFF('$today',edate)<=";
                    $data['listjob'] = getList('gourbane_job', array("$st" => 0, "$en" => 0), '', '', '', '*', '', '', '');
                } else if ($tid == 5) {
                    $st = "sdate > '$today'";
                    $data['listjob'] = getList('gourbane_job', array("$st" => " "), '', '', '', '*', '', '', '');
                }
            } else {
                $data['listjob'] = getList('gourbane_job', array('user_id' => $user_id), '', '', '', '*', '', '', '');
                foreach ($data['listjob'] as $key => $value) {
                    $data['listjob'][$key]['machine'] = getList('gourbane_machine', array('customer' => $data['listjob'][$key]['customer']), '', '', '', 'model');
                }
            }
            foreach ($data['listjob'] as $key => $value) {
                $data['listjob'][$key]['metadata'] = getJobMeta($value['id']);
            }
            $this->chat();
            $this->load->view('template', $data);
        } else {
            $this->load->view('home');
        }
    }
    
    
    /**add new job code*/
    public function add_new_job()
    {
         $data = '';
        if ($this->session->userdata('user_login')) 
        {
            $data['pageTitle'] = 'Job';
            $data['pageHeading'] = 'Job';
            $data['viewPage'] = 'add_new_job';
            $data['pend_list_technician'] = getListBySQL("select t1.*,t2.* from gourbane_notification as t1 inner join users as t2 on t2.id=t1.user_id where t1.user_id='".$this->session->userdata['user_login']['id']."' and t1.status='0'");
            $session_data = $this->session->userdata('user_login');
            $data['username'] = $session_data['username'];
            $user_id = $session_data['id'];
            $data['countries'] = getList('gourbane_countries', '');
            $data['techs'] = getList('users', array('rid' => 5));

            if ($this->uri->segment('3') != '') {
                $tid = $this->uri->segment('3');
                $today = date("Y-m-d");
                $bfr_7day = date('Y-m-d', strtotime('-7 days'));
                $month_start = date('Y-m-01', strtotime(date('Y-m-d')));
                $month_end = date('Y-m-t', strtotime(date('Y-m-d')));
                if ($tid == 1) {
                    $st = "DATEDIFF('$today',sdate)>=";
                    $en = "DATEDIFF('$today',edate)<=";
                    $data['listjob'] = getList('gourbane_job', array("$st" => 0, "$en" => 0), '', '', '', '*', '', '', '', array('firstname', 'lastname'));
                } else if ($tid == 2) {
                    $mstart = "sdate BETWEEN '$bfr_7day' AND '$today'";
                    $mend = "edate BETWEEN '$bfr_7day' AND '$today'";
                    $data['listjob'] = getListDate('gourbane_job', array("$mstart" => " "), array("$mend" => " "));
                } else if ($tid == 3) {
                    $mstart = "sdate BETWEEN '$month_start' AND '$month_end'";
                    $mend = "edate BETWEEN '$month_start' AND '$month_end'";
                    $data['listjob'] = getListDate('gourbane_job', array("$mstart" => " "), array("$mend" => " "));
                } else if ($tid == 4) {
                    $st = "DATEDIFF('$today',sdate)>=";
                    $en = "DATEDIFF('$today',edate)<=";
                    $data['listjob'] = getList('gourbane_job', array("$st" => 0, "$en" => 0), '', '', '', '*', '', '', '');
                } else if ($tid == 5) {
                    $st = "sdate > '$today'";
                    $data['listjob'] = getList('gourbane_job', array("$st" => " "), '', '', '', '*', '', '', '');
                }
            } else {
                $data['listjob'] = getList('gourbane_job', array('user_id' => $user_id), '', '', '', '*', '', '', '');
                foreach ($data['listjob'] as $key => $value) {
                    $data['listjob'][$key]['machine'] = getList('gourbane_machine', array('customer' => $data['listjob'][$key]['customer']), '', '', '', 'model');
                }
            }
            foreach ($data['listjob'] as $key => $value) {
                $data['listjob'][$key]['metadata'] = getJobMeta($value['id']);
            }
            $this->chat();
            $this->load->view('template', $data);
        } else {
            $this->load->view('home');
        }
   
    }
    
    /*add new job code**/

    public function addJob() {

        $data = '';
        if ($this->session->userdata('user_login')) {
            $data['pend_list_technician'] = getListBySQL("select t1.*,t2.* from gourbane_notification as t1 inner join users as t2 on t2.id=t1.user_id where t1.user_id='".$this->session->userdata['user_login']['id']."' and t1.status='0'");
            if ($this->input->post()) { //if form is submitted
                $message = '';
                $this->form_validation->set_rules('jtitle', 'Job Title', 'trim|required');
                $this->form_validation->set_rules('customer', 'Customer', 'trim|required');
                $this->form_validation->set_rules('fname', 'Firstname', 'trim|required');
                $this->form_validation->set_rules('lname', 'Lastname', 'trim|required');
                $this->form_validation->set_rules('phone', 'Phone', 'trim|required');
                $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
                $this->form_validation->set_rules('shouse1', 'Address', 'trim|required');
                $this->form_validation->set_rules('technicians', 'Technician', 'trim|required');
                $this->form_validation->set_rules('equipment', 'Equipment', 'trim|required');
                $this->form_validation->set_rules('brand', 'Brand name', 'trim|required');


                $customer = $this->input->post('customer');
                $agrement = $this->input->post('agrement');
                $firstname = $this->input->post('fname');
                $lastname = $this->input->post('lname');
                $email = $this->input->post('email');
                $phone = $this->input->post('phone');
                $ad_address = $this->input->post('ad_address');

                $sdate1 = $this->input->post('sdate');
                $date = date_create($sdate1);
                $sdate = date_format($date, "Y-m-d");
                $edate1 = $this->input->post('edate');
                $date = date_create($edate1);
                $edate = date_format($date, "Y-m-d");


                $date1 = date_create($this->input->post('cname_s_date'));
                $cname_s_date = date_format($date1, "Y-m-d");

                $date2 = date_create($this->input->post('cname_e_date'));
                $cname_e_date = date_format($date2, "Y-m-d");


                $session_data = $this->session->userdata('user_login');
                $user_id = $session_data['id'];

                $srNo = $this->input->post('srNo');
                $brand = $this->input->post('brand');
                $equipment = $this->input->post('equipment');
                if ($this->form_validation->run() == FALSE) {
                    
                } else {
                    /* if (insert2db('gourbane_job', array('customer' => $customer, 'user_id' => $user_id, 'firstname' => $firstname, 'lastname' => $lastname, 'email' => $email, 'phone' => $phone, 'sdate' => $sdate, 'edate' => $edate, 'contract_s_date' => $cname_s_date, 'contract_e_date' => $cname_e_date, 'ad_address' => $ad_address))) {
                     */
                    $time_zone = $this->getTimeZoneFromIpAddress();
                    date_default_timezone_set($time_zone);
                    $current_date_time = date('Y-m-d H:i:s');
                    if (insert2db('gourbane_job', array('customer' => $customer, 'user_id' => $user_id, 'firstname' => $firstname, 'lastname' => $lastname, 'email' => $email, 'phone' => $phone, 'contract_s_date' => $cname_s_date, 'contract_e_date' => $cname_e_date, 'ad_address' => $ad_address, 'added' => $current_date_time))) {
                        $lid = $this->db->insert_id();
                        $this->add_notification($lid);
                        //////// For metakey insertion///////
                        $meta_list = array('jtitle', 'location1', 'shouse1', 'saddress1', 'scity1', 'state1', 'lat1', 'lng1', 'location2', 'shouse2', 'saddress2', 'scity2', 'state2', 'lat2', 'lng2', 'location3', 'lat3', 'lng3', 'shouse3', 'saddress3', 'scity3', 'state3', 'sarea', 'szip', 'jobDesc', 'technicians', 'tnotes', 'tcompletion', 'equipment', 'priority');
                        $datas = $this->input->post();
                        foreach ($datas as $index => $val) {
                            if (in_array($index, $meta_list)) {
                                insert2db('gourbane_jobmeta', array('job_id' => $lid, 'meta_key' => $index, 'meta_value' => $val));
                            }
                        }
                        insert2db('gourbane_jobmeta', array('job_id' => $lid, 'meta_key' => 'jstatus', 'meta_value' => 'Pending'));
                        $machId = $this->input->post('machId');
                        if ($machId == '') {
                            insert2db('gourbane_machine', array('customer' => $customer, 'model' => $equipment, 'serialNo' => $srNo, 'brand' => $brand));
                            $machId = $this->db->insert_id();
                        }
                        insert2db('gourbane_jobmeta', array('job_id' => $lid, 'meta_key' => 'machineId', 'meta_value' => $machId));

                        ///////Location Insert/////////////
                        $locId1 = $this->input->post('locId1');
                        $locId2 = $this->input->post('locId2');
                        $locId3 = $this->input->post('locId3');
                        if ($locId1 == '' && $this->input->post('shouse1') != '') {
                            $title = $this->input->post('shouse1');
                            $address1 = $this->input->post('saddress1');
                            $address2 = $this->input->post('scity1');
                            $state = $this->input->post('state1');
                            $lat = $this->input->post('lat1');
                            $lat1 = explode(",", $lat);
                            $latiude = @$lat1[0];
                            $long = @$lat1[0];
                            insert2db('gourbane_location', array('customer' => $customer, 'title' => $title, 'address1' => $address1, 'address2' => $address2, 'state' => $state, 'Latitude' => $latiude, 'Longitude' => $long));
                        }
                        if ($locId2 == '' && $this->input->post('shouse2') != '') {
                            $title = $this->input->post('shouse2');
                            $address1 = $this->input->post('saddress2');
                            $address2 = $this->input->post('scity2');
                            $state = $this->input->post('state2');
                            $lat = $this->input->post('lat2');
                            $lat1 = explode(",", $lat);
                            $latiude = @$lat1[0];
                            $long = @$lat1[1];
                            insert2db('gourbane_location', array('customer' => $customer, 'title' => $title, 'address1' => $address1, 'address2' => $address2, 'state' => $state, 'Latitude' => $latiude, 'Longitude' => $long));
                        }
                        if ($locId3 == '' && $this->input->post('shouse3') != '') {
                            $title = $this->input->post('shouse3');
                            $address1 = $this->input->post('saddress3');
                            $address2 = $this->input->post('scity3');
                            $state = $this->input->post('state3');
                            $lat = $this->input->post('lat3');
                            $lat1 = explode(",", $lat);
                            $latiude = @$lat1[0];
                            $long = @$lat1[1];
                            insert2db('gourbane_location', array('customer' => $customer, 'title' => $title, 'address1' => $address1, 'address2' => $address2, 'state' => $state, 'Latitude' => $latiude, 'Longitude' => $long));
                        }

                        //////// For Upload file///////
                        if ($_FILES['service']['name'] != '') {
                            $uploadDetails = array();
                            $filename = random_string('numeric', 10);
                            $config['upload_path'] = 'uploads/';
                            $config['allowed_types'] = 'doc|docx|pdf|jpeg|jpg|png';
                            $config['file_name'] = $filename;

                            $this->load->library('upload');
                            $this->upload->initialize($config);
                            if (!$this->upload->do_upload('service')) {
                                $message .= $this->upload->display_errors();
                            } else {
                                $uploadDetails[] = $this->upload->data();
                                update2db('gourbane_job', array('agrement' => $uploadDetails[0]['file_name']), array('id' => $lid));
                            }
                        } else {
                            update2db('gourbane_job', array('agrement' => $this->input->post('old_doc')), array('id' => $lid));
                        }

                        $message.="<br/> Job Added Sucessfully";
                    }
                }
                $data['message'] = $message;
            }
            $this->jobs();
        } else {
            $this->load->view('home');
        }
    }

    
    public function add_notification($lid)
    {
        //$lid
        $get_users=getListBySQL("select id from users where id !=1");
        if(!empty($get_users))
        {
            foreach ($get_users as $users) 
            {
                if($users['id']!=$this->session->userdata['user_login']['id'])
                {
                    insert2db('gourbane_notification', array('job_id' => $lid, 'user_id' => $users['id'], 'time' => date('Y-m-d h:i:s'),'status' =>'0'));
                }
                
            }
        }
        
    }
    
    
    
    public function editJob($lid) {

        $data = '';
        if ($this->session->userdata('user_login')) {
            $data['pend_list_technician'] = getListBySQL("select t1.*,t2.* from gourbane_notification as t1 inner join users as t2 on t2.id=t1.user_id where t1.user_id='".$this->session->userdata['user_login']['id']."' and t1.status='0'");
            if ($this->input->post()) { //if form is submitted
              
                
                
                $message = '';
                $this->form_validation->set_rules('jtitle', 'Job Title', 'trim|required');
                $this->form_validation->set_rules('customer', 'Customer', 'trim|required');
                $this->form_validation->set_rules('fname', 'Firstname', 'trim|required');
                $this->form_validation->set_rules('lname', 'Lastname', 'trim|required');
                $this->form_validation->set_rules('phone', 'Phone', 'trim|required');
                $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
                $this->form_validation->set_rules('saddress1', 'Address', 'trim|required');
                $this->form_validation->set_rules('technicians', 'Technician', 'trim|required');
                $this->form_validation->set_rules('equipment', 'Equipment', 'trim|required');
                $this->form_validation->set_rules('priority', 'Priority', 'trim|required');
                $this->form_validation->set_rules('brand', 'Brand name', 'trim|required');


                $customer = $this->input->post('customer');
                $agrement = $this->input->post('agrement');
                $firstname = $this->input->post('fname');
                $lastname = $this->input->post('lname');
                $email = $this->input->post('email');
                $phone = $this->input->post('phone');
                $ad_address = $this->input->post('ad_address');


                $sdate1 = $this->input->post('sdate');
                $date = date_create($sdate1);
                $sdate = date_format($date, "Y-m-d");
                $edate1 = $this->input->post('edate');
                $date = date_create($edate1);
                $edate = date_format($date, "Y-m-d");

                $session_data = $this->session->userdata('user_login');
                $user_id = $session_data['id'];

                if ($this->form_validation->run() == FALSE) {
                   
                } else {
                    
                   if (update2db('gourbane_job', array('customer' => $customer, 'firstname' => $firstname, 'lastname' => $lastname, 'email' => $email, 'phone' => $phone, 'ad_address' => $ad_address), array('id' => $lid))) 
                   {
                        /* if (update2db('gourbane_job', array('customer' => $customer, 'firstname' => $firstname, 'lastname' => $lastname, 'email' => $email, 'phone' => $phone, 'sdate' => $sdate, 'edate' => $edate,'ad_address' => $ad_address), array('id' => $lid))) { */
                        //////// For metakey updation///////
                        /*
                          $meta_list = array('jtitle', 'saddress', 'shouse', 'scity', 'state', 'sarea', 'szip', 'jobDesc', 'jstatus', 'startT', 'endT', 'jrepeat', 'duration', 'technicians', 'tnotes', 'tcompletion', 'equipment', 'priority');
                         */

                        $meta_list = array('jtitle', 'location1', 'shouse1', 'saddress1', 'scity1', 'state1', 'location2', 'shouse2', 'saddress2', 'scity2', 'state2', 'location3', 'shouse3', 'saddress3', 'scity3', 'state3', 'sarea', 'szip', 'jobDesc', 'technicians', 'tnotes', 'tcompletion', 'equipment', 'priority');
                        $datas = $this->input->post();
                        foreach ($datas as $index => $val) {
                            if (in_array($index, $meta_list)) {
                                update2db('gourbane_jobmeta', array('meta_value' => $val), array('job_id' => $lid, 'meta_key' => $index));
                                echo "<br/>".$this->db->last_query();
                                
                                
                                
                                
                            }
                        }
                        die("die");

                        //////// For Upload file///////
                        if ($_FILES['service']['name'] != '') {
                            $uploadDetails = array();
                            $filename = random_string('numeric', 10);
                            $config['upload_path'] = 'uploads/';
                            $config['allowed_types'] = 'doc|docx|pdf|jpeg|jpg|png';
                            $config['file_name'] = $filename;

                            $this->load->library('upload');
                            $this->upload->initialize($config);
                            if (!$this->upload->do_upload('service')) {
                                $message .= $this->upload->display_errors();
                            } else {
                                $uploadDetails[] = $this->upload->data();
                                update2db('gourbane_job', array('agrement' => $uploadDetails[0]['file_name']), array('id' => $lid));
                            }
                        }

                        $message = "User Updated Sucessfully";
                    }
                    else
                    {
                        $data['message'] = "Error found.";
                    }
                    
                }
                $data['message'] = $message;
            }
            $data['pageTitle'] = 'Job';
            $data['pageHeading'] = 'Job';
            $data['viewPage'] = 'job_edit';

            $session_data = $this->session->userdata('user_login');
            $data['username'] = $session_data['username'];
            $user_id = $session_data['id'];
            $data['techs'] = getList('users', array('rid' => 5));
            $data['listjob'] = getList('gourbane_job', array('id' => $lid));
            $data['metadata'] = getJobMeta($lid);
            if (!empty($data['metadata'])) {
                $data['machine_id'] = $data['metadata']['machineId'];
            } else {
                $data['machine_id'] = '';
            }
            $data['machine_data'] = getList("gourbane_machine", array("id" => $data['machine_id']));
            $data['pend_list_technician'] = getListBySQL("SELECT *,gourbane_job.sdate as start_date,gourbane_jobmeta.meta_key FROM `gourbane_job` left join gourbane_jobmeta on gourbane_jobmeta.job_id=gourbane_job.id left join users on users.id=gourbane_job.user_id WHERE gourbane_jobmeta.meta_key='technicians' and gourbane_job.edate <= CURDATE()");
            $this->load->view('template', $data);
        } else {
            $this->load->view('home');
        }
    }

    public function editJob_scheduled($lid) {

        $data = '';
        if ($this->session->userdata('user_login')) {
            $data['pend_list_technician'] = getListBySQL("select t1.*,t2.* from gourbane_notification as t1 inner join users as t2 on t2.id=t1.user_id where t1.user_id='".$this->session->userdata['user_login']['id']."' and t1.status='0'");
            if ($this->input->post()) { //if form is submitted
                $message = '';
                $this->form_validation->set_rules('jtitle', 'Job Title', 'trim|required');
                $this->form_validation->set_rules('customer', 'Customer', 'trim|required');
                $this->form_validation->set_rules('fname', 'Firstname', 'trim|required');
                $this->form_validation->set_rules('lname', 'Lastname', 'trim|required');
                $this->form_validation->set_rules('phone', 'Phone', 'trim|required');
                $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');
                $this->form_validation->set_rules('saddress1', 'Address', 'trim|required');
                // $this->form_validation->set_rules('scity', 'City', 'trim|required');
                // $this->form_validation->set_rules('state', 'State', 'trim|required');
                // $this->form_validation->set_rules('szip', 'Zip code', 'trim|required');
                // $this->form_validation->set_rules('sdate', 'Start Date', 'trim|required');
                // $this->form_validation->set_rules('edate', 'End Date', 'trim|required');
                $this->form_validation->set_rules('technicians', 'Technician', 'trim|required');
                $this->form_validation->set_rules('equipment', 'Equipment', 'trim|required');
                $this->form_validation->set_rules('priority', 'Priority', 'trim|required');
                $this->form_validation->set_rules('brand', 'Brand name', 'trim|required');


                $customer = $this->input->post('customer');
                $agrement = $this->input->post('agrement');
                $firstname = $this->input->post('fname');
                $lastname = $this->input->post('lname');
                $email = $this->input->post('email');
                $phone = $this->input->post('phone');
                $ad_address = $this->input->post('ad_address');


                $sdate1 = $this->input->post('sdate');
                $date = date_create($sdate1);
                $sdate = date_format($date, "Y-m-d");
                $edate1 = $this->input->post('edate');
                $date = date_create($edate1);
                $edate = date_format($date, "Y-m-d");

                $session_data = $this->session->userdata('user_login');
                $user_id = $session_data['id'];

                if ($this->form_validation->run() == FALSE) {
                    
                } else {
                    if (update2db('gourbane_job', array('customer' => $customer, 'firstname' => $firstname, 'lastname' => $lastname, 'email' => $email, 'phone' => $phone, 'ad_address' => $ad_address), array('id' => $lid))) {
                        /* if (update2db('gourbane_job', array('customer' => $customer, 'firstname' => $firstname, 'lastname' => $lastname, 'email' => $email, 'phone' => $phone, 'sdate' => $sdate, 'edate' => $edate,'ad_address' => $ad_address), array('id' => $lid))) { */
                        //////// For metakey updation///////
                        /*
                          $meta_list = array('jtitle', 'saddress', 'shouse', 'scity', 'state', 'sarea', 'szip', 'jobDesc', 'jstatus', 'startT', 'endT', 'jrepeat', 'duration', 'technicians', 'tnotes', 'tcompletion', 'equipment', 'priority');
                         */

                        $meta_list = array('jtitle', 'location1', 'shouse1', 'saddress1', 'scity1', 'state1', 'location2', 'shouse2', 'saddress2', 'scity2', 'state2', 'location3', 'shouse3', 'saddress3', 'scity3', 'state3', 'sarea', 'szip', 'jobDesc', 'technicians', 'tnotes', 'tcompletion', 'equipment', 'priority');
                        $datas = $this->input->post();
                        foreach ($datas as $index => $val) {
                            if (in_array($index, $meta_list)) {
                                update2db('gourbane_jobmeta', array('meta_value' => $val), array('job_id' => $lid, 'meta_key' => $index));
                            }
                        }

                        //////// For Upload file///////
                        if ($_FILES['service']['name'] != '') {
                            $uploadDetails = array();
                            $filename = random_string('numeric', 10);
                            $config['upload_path'] = 'uploads/';
                            $config['allowed_types'] = 'doc|docx|pdf|jpeg|jpg|png';
                            $config['file_name'] = $filename;

                            $this->load->library('upload');
                            $this->upload->initialize($config);
                            if (!$this->upload->do_upload('service')) {
                                $message .= $this->upload->display_errors();
                            } else {
                                $uploadDetails[] = $this->upload->data();
                                update2db('gourbane_job', array('agrement' => $uploadDetails[0]['file_name']), array('id' => $lid));
                            }
                        }

                        $message = "User Updated Sucessfully";
                        sleep(15);
                        redirect("home/schedule", "refresh");
                    }
                }
                $data['message'] = $message;
            }
            $data['pageTitle'] = 'Job';
            $data['pageHeading'] = 'Job';
            $data['viewPage'] = 'job_edit';

            $session_data = $this->session->userdata('user_login');
            $data['username'] = $session_data['username'];
            $user_id = $session_data['id'];
            $data['techs'] = getList('users', array('rid' => 5));
            $data['listjob'] = getList('gourbane_job', array('id' => $lid));
            $data['metadata'] = getJobMeta($lid);
            if (!empty($data['metadata'])) {
                $data['machine_id'] = $data['metadata']['machineId'];
            } else {
                $data['machine_id'] = '';
            }
            $data['machine_data'] = getList("gourbane_machine", array("id" => $data['machine_id']));
            $data['pend_list_technician'] = getListBySQL("SELECT *,gourbane_job.sdate as start_date,gourbane_jobmeta.meta_key FROM `gourbane_job` left join gourbane_jobmeta on gourbane_jobmeta.job_id=gourbane_job.id left join users on users.id=gourbane_job.user_id WHERE gourbane_jobmeta.meta_key='technicians' and gourbane_job.edate <= CURDATE()");
            $this->load->view('template', $data);
        } else {
            $this->load->view('home');
        }
    }

    public function viewJob($lid) {

        $data = '';
        if ($this->session->userdata('user_login')) {
            $data['pend_list_technician'] = getListBySQL("select t1.*,t2.* from gourbane_notification as t1 inner join users as t2 on t2.id=t1.user_id where t1.user_id='".$this->session->userdata['user_login']['id']."' and t1.status='0'");
            $data['pageTitle'] = 'Job';
            $data['pageHeading'] = 'Job';
            $data['viewPage'] = 'job_view';

            $session_data = $this->session->userdata('user_login');
            $data['username'] = $session_data['username'];
            $user_id = $session_data['id'];
            $data['techs'] = getList('users', array('rid' => 5));
            $data['listjob'] = getList('gourbane_job', array('id' => $lid));
            $data['metadata'] = getJobMeta($lid);
            $data['pend_list_technician'] = getListBySQL("SELECT *,gourbane_job.sdate as start_date,gourbane_jobmeta.meta_key FROM `gourbane_job` left join gourbane_jobmeta on gourbane_jobmeta.job_id=gourbane_job.id left join users on users.id=gourbane_job.user_id WHERE gourbane_jobmeta.meta_key='technicians' and gourbane_job.edate <= CURDATE()");
            $this->load->view('innerheader');
            $this->load->view('innerfooter');
            // $this->chat();
            $this->load->view('job_view', $data);
        } else {
            $this->load->view('home');
        }
    }

    public function schedule() {
   
       
        
        if ($this->session->userdata('user_login')) {
            $data['pend_list_technician'] = getListBySQL("select t1.*,t2.* from gourbane_notification as t1 inner join users as t2 on t2.id=t1.user_id where t1.user_id='".$this->session->userdata['user_login']['id']."' and t1.status='0'");
            $data['pageTitle'] = 'Schedule';
            $data['pageHeading'] = 'Schedule';
            $data['viewPage'] = 'schedule';
            $session_data = $this->session->userdata('user_login');
            $data['techs'] = getList('users', array('rid' => 5));
            $data['countries'] = getList('gourbane_countries', '');
            $user_id = $session_data['id'];
            $ids = '';
            $pending_jobs_id = getListBySQL("SELECT DISTINCT(job_id) from gourbane_jobmeta where meta_key='jstatus' and (meta_value='Pending' or meta_value='rescheduled')");
            if (!empty($pending_jobs_id)) {
                foreach ($pending_jobs_id as $value) {
                    $ids = $ids . $value['job_id'] . ",";
                }
                $id = rtrim($ids, ',');
                $data['pending_jobs'] = getListBySQL("SELECT * from gourbane_job where id IN($id) and user_id='$user_id'");
            } else {
                $id = '';
                $data['pending_jobs'] = '';
            }


            $pen_id = '';
            if (!empty($data['pending_jobs'])) {
                foreach ($data['pending_jobs'] as $pen_value) {
                    $pen_id = $pen_value['id'];
                    $data['pending_job_list'][] = getListBySQL("SELECT * from gourbane_jobmeta where job_id IN($pen_id)");
                    $data['pending_job_list']['customer'][] = $pen_value['customer'];
                    $data['customer_id'][] = $pen_value['id'];
                    $data['tech_name'][] = getListBySQL("SELECT firstname from gourbane_jobmeta inner join users on users.id=gourbane_jobmeta.meta_value  where job_id IN($pen_id) and meta_key='technicians'");
                    $data['job_name'][] = getListBySQL("SELECT meta_value from gourbane_jobmeta where job_id IN($pen_id) and meta_key='jtitle'");
                }
            } else {
                $data['pending_job_list'] = '';
                $data['pending_job_list']['customer'][] = '';
                $data['pending_job_list']['customer_id'][] = '';
            }





            $schedule_jobs = getListBySQL("SELECT DISTINCT(job_id) from gourbane_jobmeta where meta_key='jstatus' and meta_value!='Pending'");
            if (!empty($schedule_jobs)) {
                foreach ($schedule_jobs as $jobs) {
                    $job_id = $jobs['job_id'];
                    $job['job_id'][] = $job_id;
                    $job_datas = getListBySQL("SELECT sdate,edate from gourbane_job where id='$job_id'");
                    if (!empty($job_datas)) {
                        $job['sdate'][] = str_replace(" ", "T", $job_datas[0]['sdate']);
                        $job['edate'][] = str_replace(" ", "T", $job_datas[0]['edate']);
                    } else {
                        $job['sdate'][] = '';
                        $job['edate'][] = '';
                    }
                    $get_job_title = getListBySQL("SELECT meta_value from gourbane_jobmeta where job_id='$job_id' and meta_key='jtitle'");
                    $job['job_title'][] = $get_job_title[0]['meta_value'];
                    $get_status = getListBySQL("SELECT meta_value from gourbane_jobmeta where job_id='$job_id' and meta_key='jstatus'");
                    $job['status'][] = $get_status[0]['meta_value'];
                    if ($get_status[0]['meta_value'] == 'assigned') {
                        $job['color'][] = '#058ec3';
                        $job['border_color'][] = '#CDCDCD';
                        $job['icon'][] = 'calendar';
                    } else {
                        $job['color'][] = '#058ec3';
                        $job['border_color'][] = '#D80E0E';
                        $job['icon'][] = 'ban';
                    }
                }
            } else {
                $job = '';
            }
            $data['job'] = $job;

            $data['technicians'] = getListBySQL("SELECT users.firstname,users.designation FROM `gourbane_jobmeta` left join users on users.id=gourbane_jobmeta.meta_value where meta_key='technicians' group by meta_value");
            $data['list_technician'] = getListBySQL("SELECT *,gourbane_job.sdate as start_date,gourbane_jobmeta.meta_key FROM `gourbane_job` left join gourbane_jobmeta on gourbane_jobmeta.job_id=gourbane_job.id left join users on users.id=gourbane_job.user_id WHERE gourbane_jobmeta.meta_key='technicians'");
            $data['listjob'] = getList('gourbane_job', array('user_id' => $user_id), '', '', '', '*', '', '', '', array('firstname', 'lastname'));
            $data['list_equipments'] = getList('gourbane_jobmeta', array('meta_key' => 'equipment'));
            if (!empty($data['list_technician'])) {
                foreach ($data['list_technician'] as $value) {
                    $data['b_color'][$value['job_id']] = getList("gourbane_jobmeta", array('job_id' => $value['job_id'], "meta_key" => 'jstatus'));
                    $data['st_time'][$value['job_id']] = getList("gourbane_jobmeta", array('job_id' => $value['job_id'], "meta_key" => 'startT'));
                    $data['ed_time'][$value['job_id']] = getList("gourbane_jobmeta", array('job_id' => $value['job_id'], "meta_key" => 'endT'));
                }
            }
            foreach ($data['listjob'] as $key => $value) {
                $data['listjob'][$key]['metadata'] = getJobMeta($value['id']);
            }

            $this->load->view('innerheader',$data);
            //$this->load->view('schedule');
            $this->load->view('schedule', $data);
            $this->load->view('innerfooter');
        } else {
            $this->load->view('home');
        }
        
        
        
        
        
        
        
        
        
        
    }

    public function map() { /*     * ***Completed job not need to show**** */
        if ($this->session->userdata('user_login')) {
            $data['pageTitle'] = 'Map';
            $data['pageHeading'] = 'Map';
            $data['viewPage'] = 'map';
           
            $data['pend_list_technician'] = getListBySQL("select t1.*,t2.* from gourbane_notification as t1 inner join users as t2 on t2.id=t1.user_id where t1.user_id='".$this->session->userdata['user_login']['id']."' and t1.status='0'");
            $session_data = $this->session->userdata('user_login');
            $data['username'] = $session_data['username'];
            $user_id = $session_data['id'];
            $data['techs'] = getList('users', array('rid' => 5));
            $data['listjob'] = getList('gourbane_job', array('user_id' => $user_id));

            //$data['listjob'] = getList('gourbane_job', array('user_id' => $user_id), '', '', '', '*', '', '', '', array('firstname', 'lastname'));
            foreach ($data['listjob'] as $key => $value) {
                $data['listjob'][$key]['metadata'] = getJobMeta($value['id']);
            }

            $data['pend_list_technician'] = getListBySQL("SELECT *,gourbane_job.sdate as start_date,gourbane_jobmeta.meta_key FROM `gourbane_job` left join gourbane_jobmeta on gourbane_jobmeta.job_id=gourbane_job.id left join users on users.id=gourbane_job.user_id WHERE gourbane_jobmeta.meta_key='technicians' and gourbane_job.edate <= CURDATE()");
             
            /*technisions map view*/
            $data['technician_map_view']=$this->get_technisian_location();
            //echo "<pre>";print_r( $data['technician_map_view']);
            /*technisions map view end here*/
           
            $this->load->view('innerheader');
            $this->load->view('innerfooter');
            $this->chat();
            $this->load->view('map', $data);
        } else {
            $this->load->view('home');
        }
    }

    public function customer() {
        if ($this->session->userdata('user_login')) {
            $data['pageTitle'] = 'Customer List';
            $data['pageHeading'] = 'Customer List';
            $data['viewPage'] = 'customers';
            $data['pend_list_technician'] = getListBySQL("select t1.*,t2.* from gourbane_notification as t1 inner join users as t2 on t2.id=t1.user_id where t1.user_id='".$this->session->userdata['user_login']['id']."' and t1.status='0'");
            $session_data = $this->session->userdata('user_login');
            $user_id = $session_data['id'];
            $data['listjob'] = getList('gourbane_job', array('user_id' => $user_id, 'status' => '0'));
            $this->chat();
            $this->load->view('template', $data);
        } else {
            $this->load->view('home');
        }
    }

    public function site() {
        if ($this->session->userdata('user_login')) {
            $data['pageTitle'] = 'Site';
            $data['pageHeading'] = 'Site';
            $data['viewPage'] = 'site';
            $data['pend_list_technician'] = getListBySQL("select t1.*,t2.* from gourbane_notification as t1 inner join users as t2 on t2.id=t1.user_id where t1.user_id='".$this->session->userdata['user_login']['id']."' and t1.status='0'");
            $session_data = $this->session->userdata('user_login');
            $user_id = $session_data['id'];
            $data['listjob'] = getList('gourbane_job', array('user_id' => $user_id, 'status' => '0'));
            $this->chat();
            $this->load->view('template', $data);
        } else {
            $this->load->view('home');
        }
    }

    public function machines() {
        if ($this->session->userdata('user_login')) {
            $data['pend_list_technician'] = getListBySQL("select t1.*,t2.* from gourbane_notification as t1 inner join users as t2 on t2.id=t1.user_id where t1.user_id='".$this->session->userdata['user_login']['id']."' and t1.status='0'");
            $data['pageTitle'] = 'Machine';
            $data['pageHeading'] = 'Machine';
            $data['viewPage'] = 'machine';
            $session_data = $this->session->userdata('user_login');
            $user_id = $session_data['id'];
            $data['listmachine'] = getList('gourbane_machine');

            foreach ($data['listmachine'] as $key => $value) {
                $data['listmachine'][$key]['sDate'] = getField('gourbane_job ', 'contract_s_date', array('customer' => $data['listmachine'][$key]['customer']), 'id desc');
                $data['listmachine'][$key]['eDate'] = getField('gourbane_job ', 'contract_e_date', array('customer' => $data['listmachine'][$key]['customer']), 'id desc');
            }

            $this->chat();
            $this->load->view('template', $data);
        } else {
            $this->load->view('home');
        }
    }

    public function invoice() {
        if ($this->session->userdata('user_login')) {
            $data['pageTitle'] = 'Invoice';
            $data['pageHeading'] = 'Invoice';
            $data['viewPage'] = 'invoice';
            $data['pend_list_technician'] = getListBySQL("select t1.*,t2.* from gourbane_notification as t1 inner join users as t2 on t2.id=t1.user_id where t1.user_id='".$this->session->userdata['user_login']['id']."' and t1.status='0'");
            $this->load->view('template', $data);
        } else {
            $this->load->view('home');
        }
    }

    public function report() {
        if ($this->session->userdata('user_login')) {
            $data['pageTitle'] = 'Report';
            $data['pageHeading'] = 'Report';
            $data['viewPage'] = 'report';
            $data['pend_list_technician'] = getListBySQL("select t1.*,t2.* from gourbane_notification as t1 inner join users as t2 on t2.id=t1.user_id where t1.user_id='".$this->session->userdata['user_login']['id']."' and t1.status='0'");
            $this->load->view('template', $data);
        } else {
            $this->load->view('home');
        }
    }

    /* search customer code here */

    public function search() {
        $customer_list = getList("gourbane_job", '', '', '', 'customer', '*', array("customer" => $_REQUEST['keyword']), '', '', array('firstname', 'lastname'));
        if (!empty($customer_list)) {
            $res = "<ul class='s_result'>";
            foreach ($customer_list as $values) {
                $res.="<li><a href='javascript:void(0);' onclick='return assign_val(" . $values['id'] . ")'>" . $values['customer'] . "</a></li>";
            }
            $res.= "</ul>";
        } else {
            //$res="<ul class='s_result'><li><a href=''>No Results Found</a></li></ul>";
            $res = "";
        }
        echo $res;
    }

    /* search customer code end here */

    /* get_customer_details code here */

    public function get_customer_details() {
        $lid = $_REQUEST['id'];
        $data['techs'] = getList('users', array('rid' => 5));
        $data['listjob'] = getList('gourbane_job', array('id' => $lid));
        $name = getField('gourbane_job', 'customer', array('id' => $lid));
        $data['count'] = countRecordNum('gourbane_machine', array('customer' => $name));
        $data['lcount'] = countRecordNum('gourbane_location', array('customer' => $name));
        $data['machines'] = getList('gourbane_machine', array('customer' => $name));
        $data['locations'] = getList('gourbane_location', array('customer' => $name));

        $data['metadata'] = getJobMeta($lid);
        //echo "<pre>";print_r($data);
        header('Content-type: application/json');
        exit(json_encode($data));
    }

    public function get_machine_details() {
        $id = $_REQUEST['id'];
        $data['machinVals'] = getList('gourbane_machine', array('id' => $id));
        header('Content-type: application/json');
        exit(json_encode($data));
    }

    public function get_loc_details() {
        $id = $_REQUEST['id'];
        $data['locnVals'] = getList('gourbane_location', array('id' => $id));
        header('Content-type: application/json');
        exit(json_encode($data));
    }

    /* get_customer_details code end here */


    /* get report sample function */

    public function viewJob1($lid) {

        $data = '';
        if ($this->session->userdata('user_login')) {
            $data['pageTitle'] = 'Job';
            $data['pageHeading'] = 'Job';
            $data['viewPage'] = 'job_view_dummy';

            $session_data = $this->session->userdata('user_login');
            $data['username'] = $session_data['username'];
            $user_id = $session_data['id'];
            $data['techs'] = getList('users', array('rid' => 5));
            $data['listjob'] = getList('gourbane_job', array('id' => $lid));
            $data['metadata'] = getJobMeta($lid);
            $data['pend_list_technician'] = getListBySQL("select t1.*,t2.* from gourbane_notification as t1 inner join users as t2 on t2.id=t1.user_id where t1.user_id='".$this->session->userdata['user_login']['id']."' and t1.status='0'");
            $this->load->view('template', $data);
        } else {
            $this->load->view('home');
        }
    }

    public function jobPdf($lid) {

        $data = '';
        if ($this->session->userdata('user_login')) {
            $pdf_val = getField('gourbane_jobmeta', 'meta_value', array('job_id' => $lid, 'meta_key' => 'reportPdf'));
            $data['pageTitle'] = 'Job';
            $data['pageHeading'] = 'Job';
            $data['viewPage'] = 'job_view';

            $session_data = $this->session->userdata('user_login');
            $data['username'] = $session_data['username'];
            $user_id = $session_data['id'];
            $data['techs'] = getList('users', array('rid' => 5));
            $data['listjob'] = getList('gourbane_job', array('id' => $lid));
            $data['metadata'] = getJobMeta($lid);
            $data['pend_list_technician'] = getListBySQL("select t1.*,t2.* from gourbane_notification as t1 inner join users as t2 on t2.id=t1.user_id where t1.user_id='".$this->session->userdata['user_login']['id']."' and t1.status='0'");


            $this->load->view('innerheader');
            $this->load->view('innerfooter');
            $this->load->view('job_view', $data);
        } else {
            $this->load->view('home');
        }
    }

    public function chat() {
        $session_data = $this->session->userdata('user_login');
        $uid = $session_data['id'];
        $data['uid'] = $uid;
        $data['allUsers'] = getList('users', array('status' => 1), '', '', 'username');
        foreach ($data['allUsers'] as $key => $value) {
            $data['allUsers'][$key]['numrows'] = countRecordNum('user_chat', array('to_id' => $uid, 'from_id' => $data['allUsers'][$key]['id'], 'seen_status' => 0));
            $data['allUsers'][$key]['logImg'] = getField('users', 'avatar', array('id' => $uid));
            $data['allUsers'][$key]['chatImg'] = getField('users', 'avatar', array('id' => $data['allUsers'][$key]['id']));
        }

        $data['allChat'] = getList('user_chat', array('from_id' => $uid), '', '', 'chat_id desc', '', '', '', array('to_id' => $uid));
        $data['allMsg'] = countRecordNum('user_chat', array('to_id' => $uid, 'seen_status' => 0));
        $this->load->view('chatWindow', $data);
    }

    public function chatId() {
        $tid = $_POST['tid'];
        $session_data = $this->session->userdata('user_login');
        $uid = $session_data['id'];
        $data['uid'] = $uid;
        $data['tid'] = $tid;

        $data['allUsers'] = getList('users', array('status' => 1), '', '', 'username');
        $data['allChat'] = getList('user_chat', array('from_id' => $uid), '', '', 'chat_id', '', '', '', array('to_id' => $uid));
        $data['allMsg'] = countRecordNum('user_chat', array('to_id' => $uid, 'seen_status' => 0));

        $data['logImg'] = getField('users', 'avatar', array('id' => $uid));
        $data['chatImg'] = getField('users', 'avatar', array('id' => $tid));
        // update2db('user_chat', array('seen_status' => 1), array('from_id' => $uid,'to_id' => $tid));
        $this->load->view('chatWindow2', $data);
    }

    public function addChat() {
        $session_data = $this->session->userdata('user_login');
        $fid = $session_data['id'];
        $tid = $_POST['cid'];
        $message = $_POST['keyword'];
        $time = date("Y-m-d h:i:sa");
        if (insert2db('user_chat', array('from_i