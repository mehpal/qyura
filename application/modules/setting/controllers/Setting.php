<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Setting extends MY_Controller {

     public function __construct() {
       parent:: __construct();
       $this->load->model('Setting_model');
      
   }
   function index(){
        
        $data['allStates'] = $this->Setting_model->fetchStates();
        $data['users'] = $users = $this->Setting_model->getAdmin(63);
        $statId = 0;
        if(!empty($users[0]->patientDetails_stateId) && isset($users[0]->patientDetails_stateId)){
            $statId = $users[0]->patientDetails_stateId;
        }
        
        $option = array(
           'table' => 'qyura_city',
           'where' => array('city_stateid' => $statId)
       );

      $data['cityData'] =  $this->common_model->customGet($option);
        
        $data['title'] = 'Setting';
        $this->load->super_admin_template('settingsView',$data, 'settingScript');
   }
   
   function fetchCity() {
        //echo "fdadas";exit;
        $stateId = $this->input->post('stateId');
        $cityData = $this->Setting_model->fetchCity($stateId);
        $cityOption = '';
        $cityOption .='<option value=>Select Your City</option>';
        foreach ($cityData as $key => $val) {
            $cityOption .= '<option value=' . $val->city_id . '>' . strtoupper($val->city_name) . '</option>';
        }
        echo $cityOption;
        exit;
    }
    
      function config($userId){
        
       $password =$this->input->post('users_password');
       $cPassword =$this->input->post('cnfPassword');
       if(!empty($password) && !empty($cPassword) && $password == $cPassword){
          $user = array(
           'users_mobile' => $this->input->post('users_mobile'),
           'users_password' => $this->common_model->encryptPassword($password)
         );  
       }else{
           
            $user = array(
           'users_mobile' => $this->input->post('users_mobile')
           );   
       }
      
       $option = array(
           'table' => 'qyura_users',
           'data' => $user,
           'where' => array('users_id' => $userId)
       );

       $this->common_model->customUpdate($option);
       
        $details = array(
           'patientDetails_patientName' => $this->input->post('user_name'),
           'patientDetails_countryId' => $this->input->post('setting_countryId'),
           'patientDetails_stateId' => $this->input->post('setting_stateId'),
           'patientDetails_cityId' => $this->input->post('setting_cityId'),
           'patientDetails_pin' => $this->input->post('zip'),
           'patientDetails_address' => $this->input->post('address'),
           'patientDetails_dob' => strtotime($this->input->post('dob'))
       );
        
       $option = array(
           'table' => 'qyura_patientDetails',
           'data' => $details,
           'where' => array('patientDetails_usersId' => $userId)
       );

      $response =  $this->common_model->customUpdate($option);
      if($response){
          $this->session->set_flashdata('message','Your profile successfully update.');
          redirect('setting');
      }else{
          $this->session->set_flashdata('error','Your profile failed to update.');
          redirect('setting');
      }
       
    }
    
}