<?php

class Doctor extends MY_Controller {

     public function __construct() {
       parent:: __construct();
     
       $this->load->model('Doctor_model');
       $this->load->library('datatables');
      
   }
   function index(){
      $data = array();
        $data['allStates'] = $this->Doctor_model->fetchStates();
        $data['doctorData'] = $this->Doctor_model->fetchDoctorData();
        $data['speciality'] = $this->Doctor_model->fetchSpeciality();
       // $data['hospital']=$this->Doctor_model->fetchHospital();
       // print_r($data['hospitalData'] );exit;
        $data['doctorId'] = 0;
        $data['title'] = 'List Doctor';
        $this->load->super_admin_template('doctorListing', $data, 'doctorScript');
        //$this->load->view('doctorListing',$data);
   }
   
   function addDoctor(){
       $data = array();
        $data['allStates'] = $this->Doctor_model->fetchStates();
        $data['speciality'] = $this->Doctor_model->fetchSpeciality();
        $data['degree'] = $this->Doctor_model->fetchDegree();
        $data['hospital'] = $this->Doctor_model->fetchHospital();
        $data['doctorId'] = 0;
        $data['title'] = 'Add Doctor';
        $this->load->super_admin_template('addDoctor', $data, 'doctorScript');
       //$this->load->view('addDoctor',$data);
   }
   
   function saveDoctor(){
     // print_r($_POST['doctorSpecialities_specialitiesId']);
       
      // print_r($_POST);// $_POST['doctors_cityId'];
      //exit;
        // $this->bf_form_validation->set_rules('doctors_unqId','Unique id', 'required|trim');
        $this->bf_form_validation->set_rules('doctors_fName', 'Doctors First Name', 'required|trim');
      
        $this->bf_form_validation->set_rules('doctors_lName', 'Doctors Last Name', 'required|trim');
        $this->bf_form_validation->set_rules('doctors_dob', 'Date of Birth', 'required|trim');
        
        $this->bf_form_validation->set_rules('doctors_stateId', 'State', 'required|trim');
        $this->bf_form_validation->set_rules('doctors_cityId', 'City', 'required|trim');
        
        $this->bf_form_validation->set_rules('doctors_pinn', 'Pin', 'required|trim|numeric');
       $this->bf_form_validation->set_rules('doctor_addr','Address', 'required|trim');
       $this->bf_form_validation->set_rules('doctors_consultaionFee','Consultaion Fees','required|trim|numeric');
       $this->bf_form_validation->set_rules('users_mobile','User Mobile','required|trim|numeric');
       $this->bf_form_validation->set_rules('users_email','Users Email','required|valid_email|trim');
       $this->bf_form_validation->set_rules('users_password', 'Password', 'trim|required|matches[cnfPassword]');
        $this->bf_form_validation->set_rules('cnfPassword', 'Password Confirmation', 'trim|required');
        
        if ($this->bf_form_validation->run($this) === FALSE) {
           
            $data = array();
             $data['allStates'] = $this->Doctor_model->fetchStates();
             $data['speciality'] = $this->Doctor_model->fetchSpeciality();
             $data['degree'] = $this->Doctor_model->fetchDegree();
             $data['hospital'] = $this->Doctor_model->fetchHospital();
             $data['doctorId'] = 0;
             $data['title'] = 'Add Doctor';
             $this->load->super_admin_template('addDoctor', $data, 'doctorScript');
             return false;
             //$this->load->view('addDoctor',$data);
         }
         else {
             $imagesname='';
              if ($_FILES['avatar_file']['name']) {
                $path = realpath(FCPATH . 'assets/doctorsImages/');
                $upload_data = $this->input->post('avatar_data');
                $upload_data = json_decode($upload_data);
                $original_imagesname = $this->uploadImageWithThumb($upload_data, 'avatar_file', $path, 'assets/doctorsImages/', './assets/doctorsImages/thumb/','doctor');

                if (empty($original_imagesname)) {
                    $data = array();
                    $data['allStates'] = $this->Doctor_model->fetchStates();
                    $data['speciality'] = $this->Doctor_model->fetchSpeciality();
                    $data['degree'] = $this->Doctor_model->fetchDegree();
                    $data['hospital'] = $this->Doctor_model->fetchHospital();
                    $data['doctorId'] = 0;
                    $data['title'] = 'Add Doctor';
                    $this->load->super_admin_template('addDoctor', $data, 'doctorScript');
                    return false;
                } else {
                    $imagesname = $original_imagesname;
                }
                 }
         
              $doctors_phn = $this->input->post('doctors_phn');
              $pre_number = $this->input->post('preNumber');
              $midNumber = $this->input->post('midNumber');
                 
              $finalNumber = '';
              for($i= 0;$i < count($pre_number) ;$i++) {
                  if($doctors_phn[$i] != '' && $pre_number[$i] !='' && $midNumber[$i] != '') {            
                   
                    if($i == count($pre_number)-1)
                       $finalNumber .= $pre_number[$i].' '.$midNumber[$i].' '.$doctors_phn[$i];
                    else        
                       $finalNumber .= $pre_number[$i].' '.$midNumber[$i].' '.$doctors_phn[$i].'|'; 
                }
                    
                }
              $doctors_mobile_number = $this->input->post('doctors_mobile');
              $pre_mobile_number = $this->input->post('preMobileNumber');
              $finalMobileNumber = '';
              $checkbox = 1;
              for($i= 0;$i < count($pre_mobile_number) ;$i++) {
                  if($doctors_mobile_number[$i] != '' && $pre_mobile_number[$i] !='') {
                      if($i == count($pre_mobile_number)-1){
                            if(isset($_POST['checkbox'.$checkbox]) == 1)
                            $finalMobileNumber .= $pre_mobile_number[$i].' '.$doctors_mobile_number[$i].'*'.$checkbox;  
                            else
                            $finalMobileNumber .= $pre_mobile_number[$i].' '.$doctors_mobile_number[$i].'*'.'0'; 
                      }else{
                        if(isset($_POST['checkbox'.$checkbox]) == 1)
                            $finalMobileNumber .= $pre_mobile_number[$i].' '.$doctors_mobile_number[$i].'*'.$checkbox.'|';  
                        else
                         $finalMobileNumber .= $pre_mobile_number[$i].' '.$doctors_mobile_number[$i].'*'.'0'.'|'; 
                      }
                }
                 $checkbox ++;   
                }
                  $users_email_status = $this->input->post('users_email_status');
                 if($users_email_status == ''){
                    $users_email = $this->input->post('users_email');
                    $users_password = md5($this->input->post('users_password'));
                    $doctorsInsert = array(
                       'users_email' => $users_email,
                        'users_password'=> $users_password,
                       'users_ip_address' => $this->input->ip_address(),
                        'users_mobile' => $this->input->post('users_mobile'),
                        'creationTime' => strtotime(date("Y-m-d H:i:s"))
                    );
                   $doctors_usersId = $this->Doctor_model->insertDoctorUser($doctorsInsert); 
                 }
                else {
                    $doctors_usersId = $users_email_status;
                }
               if($doctors_usersId){
                   $insertusersRoles = array(
                      'usersRoles_userId' => $doctors_usersId,
                      'usersRoles_roleId' => 4,
                      'usersRoles_parentId' => 0,
                      'creationTime' => strtotime(date("Y-m-d H:i:s"))
                  );
                  $this->Doctor_model->insertUsersRoles($insertusersRoles);
               }
               $doctors_fName = $this->input->post('doctors_fName');
                $doctors_lName = $this->input->post('doctors_lName');
                $doctors_dob = $this->input->post('doctors_dob');
                $doctors_phn = $finalNumber;
                $doctors_mobile = $finalMobileNumber;
                 
                $doctors_countryId = $this->input->post('doctors_countryId');
                $doctors_stateId = $this->input->post('doctors_stateId');
                $isEmergency = $this->input->post('isEmergency');
                $doctors_cityId = $this->input->post('doctors_cityId');
                
                $doctors_pin = $this->input->post('doctors_pinn');
                $doctors_lat = $this->input->post('lat');
                $doctors_long = $this->input->post('lng');
                $doctors_consultaionFee = $this->input->post('doctors_consultaionFee');
                $doctors_27Src = $this->input->post('doctors_27Src');
                $doctor_addr = $this->input->post('doctor_addr');
                
                $doctorsinserData = array(
                    
                    'doctors_fName' => $doctors_fName,
                    'doctors_lName' => $doctors_lName,
                    'doctors_dob' => strtotime($doctors_dob),
                    'doctors_phn' => $doctors_phn,
                    'doctors_mobile' => $doctors_mobile,
                    'doctors_countryId' => $doctors_countryId,
                    'doctors_stateId' => $doctors_stateId,
                    'doctors_27Src' => $isEmergency,
                    'doctors_cityId' => $doctors_cityId,
                    'doctors_pin' => $doctors_pin,
                    'doctors_lat' => $doctors_lat,
                    'doctors_long' => $doctors_long,
                    'doctors_consultaionFee' => $doctors_consultaionFee,
                    'doctors_27Src' => $doctors_27Src,
                    'doctors_img' => $imagesname,
                    'creationTime' => strtotime(date('Y-m-d')),
                    'doctors_unqId' => $this->input->post('doctors_unqId'),
                    'doctors_userId' => $doctors_usersId,
                    'doctors_unqId' => 'DOC'.round(microtime(true)),
                    'doctor_addr' => $doctor_addr
                    
                );
              $doctorsProfileId = $this->Doctor_model->insertDoctorData($doctorsinserData,'qyura_doctors');
              $specialitiesIds = $this->input->post('doctorSpecialities_specialitiesId');
             
              foreach($specialitiesIds as $key => $val){
                  $doctorSpecialities = array(
                      'doctorSpecialities_doctorsId' => $doctorsProfileId,
                      'doctorSpecialities_specialitiesId' => $val,
                      'creationTime' => strtotime(date('Y-m-d'))
                  );
                 $this->Doctor_model->insertDoctorData($doctorSpecialities,'qyura_doctorSpecialities');
                 unset($doctorSpecialities);
              }
              
              $doctorAcademic_degreeId = $this->input->post('doctorAcademic_degreeId');
               $doctorSpecialities_specialitiesCatId = $this->input->post('doctorSpecialities_specialitiesCatId');
              
              for($i=0;$i < count($doctorAcademic_degreeId);$i++){
                   /* here one more table insertion needed for academic image load on qyura_doctorAcademicImage table,
                    *  but write now it is not here
                    */
                  if($doctorAcademic_degreeId[$i] != '' && $doctorSpecialities_specialitiesCatId[$i] != ''){
                      $doctorAcademicData = array(
                          'doctorAcademic_degreeId' => $doctorAcademic_degreeId[$i],
                          'doctorSpecialities_specialitiesCatId' => $doctorSpecialities_specialitiesCatId[$i],
                          'doctorAcademic_doctorsId' => $doctorsProfileId,
                          'creationTime' => strtotime(date('Y-m-d'))
                      );
                      
                      $this->Doctor_model->insertDoctorData($doctorAcademicData,'qyura_doctorAcademic');
                      unset($doctorAcademicData);
                  }
                   
              }
              $countsProfessionalExpCount = $this->input->post('ProfessionalExpCount');
              
               for($i=1;$i <= $countsProfessionalExpCount;$i++){
                   /* here one more table insertion needed for academic image load on qyura_doctorAcademicImage table,
                    *  but write now it is not here
                    */
                  if(isset($_POST['professionalExp_start'.$i]))
                      $professionalExp_start = strtotime($_POST['professionalExp_start'.$i]);
                  
                  if(isset($_POST['professionalExp_end'.$i]))
                      $professionalExp_end =  strtotime($_POST['professionalExp_end'.$i]); 
                  
                   if(isset($_POST['professionalExp_hospitalId'.$i]))
                      $professionalExp_hospitalId = $_POST['professionalExp_hospitalId'.$i]; 
                   
                  $doctorSpecialities_specialitiesId = '';
                  if(isset($_POST['doctorSpecialities_specialitiesId'.$i]))
                     $doctorSpecialities_specialitiesId =  $_POST['doctorSpecialities_specialitiesId'.$i]; 
                 
                  foreach($doctorSpecialities_specialitiesId as $key => $val){
                      $dataProfessional = array(
                        'professionalExp_usersId' => $doctorsProfileId,
                        'professionalExp_hospitalId' => $professionalExp_hospitalId,
                        'professionalExp_specialitiesCatId' => $val,
                        'professionalExp_start' => $professionalExp_start,
                        'professionalExp_end' => $professionalExp_end,
                        'creationTime' => strtotime(date('Y-m-d')) 
                      );
                     
                       $this->Doctor_model->insertDoctorData($dataProfessional,'qyura_professionalExp');
                      
                  }
               
              }
             //exit; 
               
              $this->session->set_flashdata('message','Data inserted successfully !');
                  redirect('doctor/addDoctor');
           }
      // exit;
   }
   

    function fetchCity (){
       //echo "fdadas";exit;
        $stateId = $this->input->post('stateId');
        $cityData = $this->Doctor_model->fetchCity($stateId);
        $cityOption = '';
        $cityOption .='<option value=>Select Your City</option>';
        foreach($cityData as $key=>$val ) {
            $cityOption .= '<option value='.$val->city_id.'>'. strtoupper($val->city_name).'</option>';
        }
       echo $cityOption;
        exit;
    }
    
    function fetchHospitalSpeciality(){
        $data = array();
        $hospitalId = $this->input->post('hospitalId');
        $data = $this->Doctor_model->fetchHospitalSpeciality($hospitalId);
         $Speciality = '';
        //$Speciality .='<option value=>Select Your Speciality</option>';
        foreach($data as $key=>$val){
            $Speciality .= '<option value='. $val->specialities_id.'>'. $val->specialities_name.'</option>';
        }
        echo $Speciality;exit;
    }
    
    /*function check_email(){
        $user_table_id = '';
        $users_email = $this->input->post('users_email');
        if(isset($_POST['user_table_id'])){
          $user_table_id = $this->input->post('user_table_id');
        }
        $email = $this->Doctor_model->fetchEmail($users_email,$user_table_id);
        echo $email;
        exit;
    }*/
    
        function check_email(){
       $user_table_id = '';
        $users_email = $this->input->post('users_email');
        if(isset($_POST['user_table_id'])){
          $user_table_id = $this->input->post('user_table_id');
        }
        $email = $this->Doctor_model->fetchEmail($users_email,$user_table_id);
       
        if($email == 1)
        echo $email;
        else{
            $select = array('users_id');
            $where = array('users_email'=> $users_email,
                'users_deleted'=>0);
            $return = $this->Doctor_model->fetchTableData($select,'qyura_users',$where);
            $data = 0;
            if(!empty($return)){
                $data = $return[0]->users_id;
                echo $data;
            }else{
                echo $data;
            }
        }
        exit;
    }
    
      function createCSV(){
       
        $doctorSpecialities_specialitiesId ='';
       
       if(isset($_POST['doctorSpecialities_specialitiesId']))
        $doctorSpecialities_specialitiesId = $this->input->post('doctorSpecialities_specialitiesId');
       
       
       // $where=array('doctors_deleted'=> 0,'doctorSpecialities_specialitiesId'=> $doctorSpecialities_specialitiesId);
       $where = '';
        $array[]= array('Image Name','Doctor Name','Doctor ID','Address','Speciality','Exprience','Date Of Joining','Phone Number','Mobile Number');
        $data = $this->Doctor_model->createCSVdata($where);
       
        $arrayFinal = array_merge($array,$data);
       
        array_to_csv($arrayFinal,'DoctorDetail.csv');
        return True;
        exit;
    }
}   

