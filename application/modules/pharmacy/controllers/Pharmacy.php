<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Pharmacy extends MY_Controller {

    public function __construct() {
        parent:: __construct();
        $this->load->library('form_validation');
        $this->load->model('Pharmacy_model');
        $this->load->library('datatables');
        $this->load->helper('string');
    }

    function index() {
        $data = array();
        $data['allStates'] = $this->Pharmacy_model->fetchStates();
        $data['pharmacyData'] = $this->Pharmacy_model->fetchpharmacyData();
        $data['pharmacyId'] = 0;
        $data['title'] = 'All Pharmacy';
        $this->load->super_admin_template('pharmacyListing', $data, 'pharmacy_script');
    }

    function getPharmacyDl() {

        echo $this->Pharmacy_model->fetchPharmacyDataTables();
    }

    function addPharmacy() {
        $data = array();
        $data['allStates'] = $this->Pharmacy_model->fetchStates();
        $data['title'] = 'Add Pharmacy';
        $this->load->super_admin_template('addPharmacy', $data, 'pharmacy_script');
        //$this->load->view('addPharmacy',$data);
    }

    /**
     * @project Qyura
     * @method checkExisting
     * @description get records in listing using datatables
     * @access public
     * @return array
     */
    function checkExisting() {
        $emailId = $this->input->post('emailId');
        $this->Pharmacy_model->checkExisting($emailId);
    }

    function detailPharmacy($pharmacyId = '') {
        $data = array();
        $data['pharmacyData'] = $this->Pharmacy_model->fetchpharmacyData($pharmacyId);
        $data['allCountry'] = $this->Pharmacy_model->fetchCountry();
        $data['allCities'] = $this->Pharmacy_model->fetchCity($data['pharmacyData'][0]->pharmacy_stateId);
        $data['allStates'] = $this->Pharmacy_model->fetchStates($data['pharmacyData'][0]->pharmacy_stateId);

        $data['pharmacyId'] = $pharmacyId;
        $data['showStatus'] = 'none';
        $data['detailShow'] = 'block';
        $data['title'] = 'Pharmacy Detail';
        //  $this->load->view('pharmacyDetail',$data);
        $this->load->super_admin_template('pharmacyDetail', $data, 'pharmacy_script');
    }

    function fetchCity() {
        //echo "fdadas";exit;
        $stateId = $this->input->post('stateId');
        $cityData = $this->Pharmacy_model->fetchCity($stateId);
        $cityOption = '';
        $cityOption .='<option value=>Select Your City</option>';
        foreach ($cityData as $key => $val) {
            $cityOption .= '<option value=' . $val->city_id . '>' . strtoupper($val->city_name) . '</option>';
        }
        echo $cityOption;
        exit;
    }

    function SavePharmacy() {
        // print_r($_POST);exit;
        $this->load->library('form_validation');
        $this->bf_form_validation->set_rules('pharmacy_name', 'Pharmacy Name', 'required|trim|required');

        $this->bf_form_validation->set_rules('pharmacy_countryId', 'Pharmacy Country', 'required|trim');
        $this->bf_form_validation->set_rules('pharmacy_stateId', 'Pharmacy StateId', 'required|trim');
        $this->bf_form_validation->set_rules('pharmacy_cityId', 'Pharmacy City', 'required|trim');

        //$this->bf_form_validation->set_rules('pharmacy_phn[]', 'Pharmacy Mobile No', 'required|trim');
        $this->bf_form_validation->set_rules('pharmacy_zip', 'Pharmacy Zip', 'required|trim|min_length[6]|max_length[6]');
        $this->bf_form_validation->set_rules('pharmacy_address', 'Pharmacy Address', 'required|trim');
        

        $this->bf_form_validation->set_rules('pharmacy_mmbrTyp', 'Membership Type', 'required|trim');
        $this->bf_form_validation->set_rules('users_email', 'Users Email', 'required|valid_email|trim|callback__checkUserExist');
        $this->bf_form_validation->set_rules('userId', 'Users Id', 'trim');
        $this->bf_form_validation->set_rules('isValid', 'isValid Id', 'trim');
        $this->bf_form_validation->set_rules('isEmergency', 'Emergency', 'trim|required');
        $this->bf_form_validation->set_rules('isManual', 'Manual', 'trim');
        $this->bf_form_validation->set_rules('pharmacyType', 'Pharmacy Type', 'trim');
        
        
        

        if ($this->bf_form_validation->run() === FALSE) {
            $data = array();
            $data['allStates'] = $this->Pharmacy_model->fetchStates();
            $this->load->super_admin_template('addPharmacy', $data, 'pharmacy_script');
        } else {

            $imagesname = "";
            if ($_FILES['avatar_file']['name']) {
                $path = realpath(FCPATH . 'assets/pharmacyImages/');
                $upload_data = $this->input->post('avatar_data');
                $upload_data = json_decode($upload_data);
                $original_imagesname = $this->uploadImageWithThumb($upload_data, 'avatar_file', $path, 'assets/pharmacyImages/', './assets/pharmacyImages/thumb/', 'pharmacy');

                if (empty($original_imagesname)) {
                    $data['allStates'] = $this->Pharmacy_model->fetchStates();
                    $this->session->set_flashdata('valid_upload', $this->error_message);
                    $this->load->super_admin_template('addPharmacy', $data, 'pharmacy_script');
                    return false;
                } else {
                    $imagesname = $original_imagesname;
                }
            }

            //echo $imagesname;exit;
            $pharmacy_phn = $this->input->post('pharmacy_phn');
            $pre_number = $this->input->post('pre_number');
            //$countPnone = $this->input->post('countPnone');
            
            

            $finalNumber = '';
            for ($i = 0; $i < count($pharmacy_phn); $i++) {
                if ($pharmacy_phn[$i] != '' && $pre_number[$i] != '') {
                    if ($i == count($pharmacy_phn) - 1)
                        $finalNumber .= $pre_number[$i] . ' ' . $pharmacy_phn[$i];
                    else
                        $finalNumber .= $pre_number[$i] . ' ' . $pharmacy_phn[$i] . '|';
                }
            }


            $pharmacy_name = $this->input->post('pharmacy_name');
            $countryId = $this->input->post('pharmacy_countryId');
            $stateId = $this->input->post('pharmacy_stateId');
            $cityId = $this->input->post('pharmacy_cityId');
            $pharmacy_address = $this->input->post('pharmacy_address');
            $pharmacy_cntPrsn = $this->input->post('pharmacy_cntPrsn');
            $pharmacy_mmbrTyp = $this->input->post('pharmacy_mmbrTyp');
            $isEmergency = $this->input->post('isEmergency');
            $pharmacy_zip = $this->input->post('pharmacy_zip');

            $insertData = array(
                'pharmacy_name' => $pharmacy_name,
                'pharmacy_countryId' => $countryId,
                'pharmacy_stateId' => $stateId,
                'pharmacy_cityId' => $cityId,
                'pharmacy_address' => $pharmacy_address,
                'pharmacy_cntPrsn' => $pharmacy_cntPrsn,
                'pharmacy_mmbrTyp' => $pharmacy_mmbrTyp,
                'pharmacy_zip' => $pharmacy_zip,
                'pharmacy_27Src' => $isEmergency,
                'pharmacy_img' => $imagesname,
                'creationTime' => strtotime(date("Y-m-d H:i:s")),
                'pharmacy_phn' => $finalNumber,
                'pharmacy_lat' => $this->input->post('lat'),
                'pharmacy_long' => $this->input->post('lng'),
                'pharmacy_type' => $this->input->post('pharmacyType')
            );
            
            $userId = $this->input->post('userId');
            if($userId == ''){
                
            $users_email = $this->input->post('users_email');
            $users_password = random_string('alnum');
            
            $pharmacyInsert = array(
                'users_email' => $users_email,
                'users_password' => $this->common_model->encryptPassword($users_password),
                'users_ip_address' => $this->input->ip_address(),
                'users_username'=> $users_password,
                'creationTime' => strtotime(date("Y-m-d H:i:s"))
            );
                $pharmacy_usersId = $this->Pharmacy_model->insertPharmacyUser($pharmacyInsert);
                $usersRoles_parentId = 0;
                $usersRoles_parentRole = 0;
            } else {
                $pharmacy_usersId = $userId;
                $usersRoles_parentId = $userId;
                $usersRoles_parentRole = ROLE_HOSPITAL;
            }
            
            if ($pharmacy_usersId) {

                $insertusersRoles = array(
                    'usersRoles_userId' => $pharmacy_usersId,
                    'usersRoles_roleId' => ROLE_PHARMACY,
                    'usersRoles_parentId' => $usersRoles_parentId,
                    'usersRoles_parentRole'=>$usersRoles_parentRole,
                    'creationTime' => strtotime(date("Y-m-d H:i:s"))
                );

                $this->Pharmacy_model->insertUsersRoles($insertusersRoles);

                $insertData['pharmacy_usersId'] = $pharmacy_usersId;
                $pharmacyId = $this->Pharmacy_model->insertPharmacy($insertData);
                $this->session->set_flashdata('message', 'Data inserted successfully !');
            }
            
            redirect('pharmacy/addPharmacy');
        }
    }

    function uploadImages($imageName, $folderName, $newName) {
        $path = realpath(FCPATH . 'assets/' . $folderName . '/');
        $config['upload_path'] = $path;
        //echo $config['upload_path']; 
        $config['allowed_types'] = 'gif|jpg|png';
        $config['max_size'] = '5000';
        $config['max_width'] = '1024';
        $config['max_height'] = '768';
        $config['file_name'] = $newName;


        $this->load->library('upload', $config);
        $this->upload->initialize($config);
        $this->upload->do_upload($imageName);
        $this->upload->display_errors();
        return TRUE;
    }

    function getImageBase64Code($img) {
        $img = str_replace('data:image/png;base64,', '', $img);
        $img = str_replace(' ', '+', $img);
        $img = str_replace('[removed]', '', $img);
        $data = base64_decode($img);
        return $data;
    }

    /* function check_email(){
      $user_table_id = '';
      $users_email = $this->input->post('users_email');
      if(isset($_POST['user_table_id'])){
      $user_table_id = $this->input->post('user_table_id');
      }
      $email = $this->Pharmacy_model->fetchEmail($users_email,$user_table_id);
      echo $email;
      exit;
      } */

    function check_email() {
        $user_table_id = '';
        $users_email = $this->input->post('users_email');
        if (isset($_POST['user_table_id'])) {
            $user_table_id = $this->input->post('user_table_id');
        }
        $email = $this->Pharmacy_model->fetchEmail($users_email, $user_table_id);

        if ($email == 1)
            echo $email;
        else {
            $select = array('users_id');
            $where = array('users_email' => $users_email,
                'users_deleted' => 0);
            $return = $this->Pharmacy_model->fetchTableData($select, 'qyura_users', $where);
            $data = 0;
            if (!empty($return)) {
                $data = $return[0]->users_id;
                echo $data;
            } else {
                echo $data;
            }
        }
        exit;
    }

    function saveDetailPharmacy($pharmacyId) {

        $this->bf_form_validation->set_rules('pharmacy_name', 'Pharmacy Name', 'required|trim');

        $this->bf_form_validation->set_rules('pharmacy_address', 'Pharmacy Address', 'required|trim');
        $this->bf_form_validation->set_rules('users_email', 'Users Email', 'required|valid_email|trim');

        $this->bf_form_validation->set_rules('pharmacy_cntPrsn', 'Pharmacy Contact Person', 'required|trim');


        $this->bf_form_validation->set_rules('pharmacy_countryId', 'Country Name', 'required|trim');
        $this->bf_form_validation->set_rules('pharmacy_stateId', 'State Name', 'required|trim');
        $this->bf_form_validation->set_rules('pharmacy_cityId', 'City Name', 'required|trim');
        $this->bf_form_validation->set_rules('pharmacy_zip', 'Zip Code', 'required|trim|max_length[6]|min_length[6]');
        if ($this->bf_form_validation->run() === FALSE) {
            $data = array();
            $data['pharmacyData'] = $this->Pharmacy_model->fetchpharmacyData($pharmacyId);
            $data['allCountry'] = $this->Pharmacy_model->fetchCountry();
            $data['allCities'] = $this->Pharmacy_model->fetchCity($data['pharmacyData'][0]->pharmacy_stateId);
            $data['allStates'] = $this->Pharmacy_model->fetchStates($data['pharmacyData'][0]->pharmacy_stateId);

            $data['pharmacyId'] = $pharmacyId;
            $data['showStatus'] = 'none';
            $data['detailShow'] = 'block';
            $data['title'] = 'Pharmacy Detail';
            //  $this->load->view('pharmacyDetail',$data);
            $this->load->super_admin_template('pharmacyDetail', $data, 'pharmacy_script');
        } else {
            $pharmacy_phn = $this->input->post('pharmacy_phn');
            $pre_number = $this->input->post('pre_number');
            //$countPnone = $this->input->post('countPnone');

            $finalNumber = '';
            for ($i = 0; $i < count($pharmacy_phn); $i++) {
                if ($pharmacy_phn[$i] != '' && $pre_number[$i] != '') {

                    if ($i == count($pharmacy_phn) - 1)
                        $finalNumber .= $pre_number[$i] . ' ' . $pharmacy_phn[$i];
                    else
                        $finalNumber .= $pre_number[$i] . ' ' . $pharmacy_phn[$i] . '|';
                }
            }

            $updatePharmacy = array(
                'pharmacy_name' => $this->input->post('pharmacy_name'),
                'pharmacy_cityId' => $this->input->post('pharmacy_cityId'),
                'pharmacy_countryId' => $this->input->post('pharmacy_countryId'),
                'pharmacy_stateId' => $this->input->post('pharmacy_stateId'),
                'pharmacy_zip' => $this->input->post('pharmacy_zip'),
                'pharmacy_type' => $this->input->post('pharmacy_type'),
                'pharmacy_address' => $this->input->post('pharmacy_address'),
                'pharmacy_phn' => $finalNumber,
                'pharmacy_cntPrsn' => $this->input->post('pharmacy_cntPrsn'),
                'pharmacy_mmbrTyp' => $this->input->post('pharmacy_mmbrTyp'),
                'pharmacy_27Src' => $this->input->post('isEmergency'),
                'pharmacy_lat' => $this->input->post('lat'),
                'pharmacy_long' => $this->input->post('lng'),
                'modifyTime' => strtotime(date("Y-m-d H:i:s"))
            );

            $where = array(
                'pharmacy_id' => $pharmacyId
            );
            $response = '';
            $response = $this->Pharmacy_model->UpdateTableData($updatePharmacy, $where, 'qyura_pharmacy');
            if ($response) {
                $updateUserdata = array(
                    'users_email' => $this->input->post('users_email'),
                    'modifyTime' => strtotime(date("Y-m-d H:i:s"))
                );
                $whereUser = array(
                    'users_id' => $this->input->post('user_tables_id')
                );
                $response = $this->Pharmacy_model->UpdateTableData($updateUserdata, $whereUser, 'qyura_users');
                if ($response) {
                    $this->session->set_flashdata('message', 'Data updated successfully !');
                    redirect("pharmacy/detailPharmacy/$pharmacyId");
                }
            }
        }
    }

    /**
     * @project Qyura
     * @method editUploadImage
     * @description update details page image profile
     * @access public
     * @return boolean
     */
    function editUploadImage() {

        if ($_POST['avatar_file']['name']) {
            $path = realpath(FCPATH . 'assets/pharmacyImages/');
            $upload_data = $this->input->post('avatar_data');
            $upload_data = json_decode($upload_data);

             if($upload_data->width > 120){
            $original_imagesname = $this->uploadImageWithThumb($upload_data, 'avatar_file', $path, 'assets/pharmacyImages/', './assets/pharmacyImages/thumb/', 'pharmacy');

            if (empty($original_imagesname)) {
                $response = array('state' => 400, 'message' => $this->error_message);
            } else {

                $option = array(
                    'pharmacy_img' => $original_imagesname,
                    'modifyTime' => strtotime(date("Y-m-d H:i:s"))
                );
                $where = array(
                    'pharmacy_id' => $this->input->post('avatar_id')
                );
                $response = $this->Pharmacy_model->UpdateTableData($option, $where, 'qyura_pharmacy');
                if ($response) {
                    $response = array('state' => 200, 'message' => 'Successfully update avtar');
                } else {
                    $response = array('state' => 400, 'message' => 'Failed to update avtar');
                }
            }
            }else{
               $response = array('state' => 400, 'message' => 'Height and Width must exceed 150px.');  
            }
            echo json_encode($response);
        } else {
            $response = array('state' => 400, 'message' => 'Please select avtar');
            echo json_encode($response);
        }
    }

    function getUpdateAvtar($id) {
        if (!empty($id)) {
            $data['pharmacyData'] = $this->Pharmacy_model->fetchpharmacyData($id);
            //  print_r($data); exit;
            echo "<img src='" . base_url() . "assets/pharmacyImages/" . $data['pharmacyData'][0]->pharmacy_img . "'alt='' class='logo-img' />";
            exit();
        }
    }

    function createCSV() {
        $pharmacy_stateId = $this->input->post('pharmacy_stateId');
        $pharmacy_cityId = $this->input->post('pharmacy_cityId');

        if ($pharmacy_stateId != '' && $pharmacy_stateId != null)
            $pharmacy_stateId = $this->input->post('pharmacy_stateId');
        if ($pharmacy_cityId != '' && $pharmacy_cityId != null)
            $pharmacy_cityId = $this->input->post('pharmacy_cityId');

        $where = array('pharmacy_deleted' => 0, 'pharmacy_cityId' => $pharmacy_cityId, 'pharmacy_stateId' => $pharmacy_stateId);
        // print_r($where); exit;
        $array[] = array('Image Name', 'Pharmacy Name', 'City', 'Phone Number', 'Address');
        $data = $this->Pharmacy_model->createCSVdata($where);

        $arrayFinal = array_merge($array, $data);

        array_to_csv($arrayFinal, 'PharmacyDetail.csv');
        return True;
        exit;
    }

    function pharmacyBackgroundUpload($pharmacyId) {
        if (isset($_FILES["file"]["name"])) {

            $temp = explode(".", $_FILES['file']["name"]);
            $microtime = round(microtime(true));
            $imageName = "pharmacy";
            $newfilename = "" . $imageName . "_" . $microtime . '.' . end($temp);
            $uploadData = $this->uploadImages('file', 'pharmacyImages', $newfilename);
            if ($uploadData) {
                $imageName = $uploadData['imageData']['file_name'];

                $data = array('pharmacy_background_img' => $newfilename);
                $where = array('pharmacy_id' => $pharmacyId);
                $response = $this->Pharmacy_model->UpdateTableData($data, $where, 'qyura_pharmacy');
                if ($response) {
                    $result = array('status' => 200, 'messsage' => "successfully update image");
                    echo json_encode($result);
                }
            } else {
                $result = array('status' => 400, 'messsage' => $uploadData['error']);
                echo json_encode($result);
            }
        }
    }

    function getBackgroundImage($pharmacyId) {
        $select = array('pharmacy_background_img');
        $where = array('pharmacy_id' => $pharmacyId);

        $response = $this->Pharmacy_model->fetchTableData($select, 'qyura_pharmacy', $where);
        if ($response) {
            echo $image = base_url() . 'assets/pharmacyImages/' . $response[0]->pharmacy_background_img;
        }
        exit;
    }

    function map($id) {
        $option = array(
            'table' => 'qyura_pharmacy',
            'select' => 'pharmacy_lat,pharmacy_long,pharmacy_address,pharmacy_name,pharmacy_img',
            'where' => array('pharmacy_id' => $id)
        );
        $data['mapData'] = $this->common_model->customGet($option);
        $data['title'] = 'Pharmacy Map';
        $this->load->super_admin_template('map', $data, 'pharmacy_script');
    }

    function _checkUserExist($email = '') {
        $query = 'SELECT count(users_id) as isExit FROM qyura_users INNER JOIN qyura_usersRoles ON qyura_users.users_id = qyura_usersRoles.usersRoles_userId WHERE users_email = "' . $email . '" AND usersRoles_roleId = ' . ROLE_HOSPITAL . ' AND (SELECT count(users_id) FROM qyura_users INNER JOIN qyura_usersRoles ON qyura_users.users_id = qyura_usersRoles.usersRoles_userId WHERE users_email = "' . $email . '" AND usersRoles_roleId = ' . ROLE_PHARMACY . ' ) > 0 ';

        $data = $this->db->query($query)->result();

        if ($data[0]->isExit == 1) {

            $this->bf_form_validation->set_message('_checkUserExist', 'Pharmacy already exist');
            return false;
        } elseif ($data[0]->isExit == 0) {

            $query2 = 'SELECT count(users_id) userId, hospital_address, hospital_lat, hospital_long FROM qyura_users INNER JOIN qyura_usersRoles ON qyura_users.users_id = qyura_usersRoles.usersRoles_userId INNER JOIN qyura_hospital ON qyura_hospital.hospital_usersId = qyura_users.users_id WHERE users_email = "' . $email . '" AND usersRoles_roleId = ' . ROLE_HOSPITAL . ' ';
            $getData = $this->db->query($query2)->result();

            if (isset($getData) && $getData != NULL) {
                return true;
            } else {
                return true;
            }
        }
    }

}
