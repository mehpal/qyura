<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'modules/api/controllers/MyRest.php';

class SpecialityApi extends MyRest {

    function __construct() {

        parent::__construct();
        $this->load->model(array('specialityApi_model'));
    }

    function specialityList_get() {
       
            $specialityList = $this->specialityApi_model->getSpecialityList();
           // print_r($healpkgDetail); exit;
            if (!empty($specialityList) && $specialityList !=NULL) {
                $response['specialityList'] = $specialityList;
                $response['status'] = TRUE;
                $response['msg'] = 'success';
                $this->response($response, 200); // 200 being the HTTP response code
            } else {
                $response['status'] = false;
                $response['msg'] = 'There is no speciality yet';
                $this->response($response, 400); // 200 being the HTTP response code
            }
      
    }

}
