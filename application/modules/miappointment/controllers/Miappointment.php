<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Miappointment extends MY_Controller {

    public $error_message = '';

    public function __construct() {
        parent:: __construct();
        $this->load->model('miappointment_model', 'miappointment', 'common_model');
//        $this->load->library(array('api/ion_auth_api', 'bf_form_validation'));
//        $this->load->helper(array('url', 'language','common','string'));
//        $this->bf_bf_form_validation->set_error_delimiters($this->config->item('error_start_delimiter', 'api/auth_conf_api'), $this->config->item('error_end_delimiter', 'api/auth_conf_api'));
//        $this->lang->load('api/auth_api');
    }

    /**
     * @project Qyura
     * @method index
     * @description listing templeat
     * @access public
     * @return html
     */
    public function index() {
        $data = array();
        $this->load->super_admin_template('miAppList', $data, 'miAppScript');
    }

    /**
     * @project Qyura
     * @method getDignostiData
     * @description miAppList listing
     * @access public
     * @return json data for datatable
     */
    public function getDignostiData() {
        echo $this->miappointment->getDiagnostic();
    }

    /**
     * @project Qyura
     * @method getConsultingList
     * @description miAppList listing
     * @access public
     * @return json data for datatable
     */
    public function getConsultingList() {
        echo $this->miappointment->getConsultingList();
    }
    
    /**
     * @project Qyura
     * @method getHealthpkgList
     * @description miAppList listing
     * @access public
     * @return json data for datatable
     */
    public function getHealthpkgList(){
        echo $this->miappointment->getHealthpkgList();
    }

    /**
     * @project Qyura
     * @method getBloodBankDl
     * @description get records in listing using datatables
     * @access public
     * @return array
     */
    function getBloodBankDl() {
        echo $this->miappointment->fetchbloodBankDataTables();
    }

    /**
     * @project Qyura
     * @method detail
     * @description detail mi appointment
     * @access public
     * @param qtnId
     * @return html
     */
    public function detail($qtnId = '') {
        $data = array();
        $data['qtnDetail'] = $this->miappointment->getDetail($qtnId);
        $data['quotationTests'] = $this->miappointment->getQuotationTests($qtnId);
        $data['userDetail'] = $this->miappointment->getQuotationUserDetail($qtnId);
        $data['qtnAmount'] = $this->miappointment->qtTestTotalAmount($qtnId);
        $data['qtnId'] = $qtnId;
        $this->load->super_admin_template('miAppDetail', $data, 'miAppScript');
    }

    /**
     * @project Qyura
     * @method detail
     * @description detail consulting appointment
     * @access public
     * @param appointmentId
     * @return html
     */
    public function consultingDetail($appointmentId = '') {
        $data = array();
        $data['conDetail'] = $this->miappointment->getConsultingData($appointmentId);
        $data['reports'] = $this->miappointment->getConsultingReport($appointmentId);
        $this->load->super_admin_template('miConAppDetail', $data, 'miAppScript');
    }
    
    /**
     * @project Qyura
     * @method detail
     * @description detail health PKG appointment
     * @access public
     * @param appointmentId
     * @return html
     */
    public function healthPkgDetail($appointmentId = '') {
        $data = array();
        $data['conDetail'] = $this->miappointment->getHealthPkgDetail($appointmentId);
        $data['reports'] = $this->miappointment->getHealthPkgReport($appointmentId);
        $this->load->super_admin_template('miHealthAppDetail', $data, 'miAppScript');
    }

    /**
     * @project Qyura
     * @method add
     * @description detail consulting appointment
     * @access public
     * @param appointmentId
     * @return html
     */
    function add_appointment() {
        $data = array();
        $options = array('table' => 'qyura_city', 'order' => array('city_name' => 'asc'));
        $data['qyura_city'] = $this->common_model->customGet($options);
        $catOptions = array('table' => 'qyura_diagnosticsCat', 'order' => array('diagnosticsCat_catName' => 'asc'),'select'=>'diagnosticsCat_catName as catName,diagnosticsCat_catId as catId','where'=>array('diagnosticsCat_deleted'=>0));
        $data['catOptions'] = $this->common_model->customGet($catOptions);
        
        $spOptions = array('table' => 'qyura_specialities', 'order' => array('specialities_name' => 'asc'),'select'=>'specialities_name as speName,specialities_specialitiesCatId as speCatId','where'=>array('specialities_deleted'=>0));
        $data['spOptions'] = $this->common_model->customGet($spOptions);
        $data['title'] = "Add Miappointment";
        $data['allStates'] = $this->miappointment->fetchStates();
        $this->load->super_admin_template('addappointment', $data, 'addAppScript');
    }

    function getpatient() {
        $patient_email = $this->input->post("patient_email");
        $patient_mobile = $this->input->post("patient_mobile");

        $options = array(
            'select'=>'qyura_users.users_id as user_id,qyura_users.users_mobile as mobile,qyura_patientDetails.patientDetails_cityId as cityId,qyura_patientDetails.patientDetails_stateId as stateId,qyura_patientDetails.patientDetails_countryId as countryId,qyura_patientDetails.patientDetails_patientName as patientName,qyura_patientDetails.patientDetails_address as address,qyura_patientDetails.patientDetails_unqId as unqId,qyura_patientDetails.patientDetails_pin as pin,qyura_patientDetails.patientDetails_dob as dob,qyura_patientDetails.patientDetails_gender as gender',
            'table' => 'qyura_users',
            'where' => array('qyura_users.users_deleted' => 0, 'qyura_users.users_email' => $patient_email,'qyura_usersRoles.usersRoles_roleId' => 6),
            'or_where'=>array('qyura_users.users_mobile' => $patient_mobile),
            'join' => array(
                array('qyura_usersRoles', 'qyura_usersRoles.usersRoles_userId = qyura_users.users_id', 'left'),
                array('qyura_patientDetails', 'qyura_patientDetails.patientDetails_usersId = qyura_users.users_id', 'left'),
                array('qyura_country', 'qyura_country.country_id = qyura_patientDetails.patientDetails_countryId', 'left')
            ),
            'single'=>true
        );
        $data = $this->common_model->customGet($options);
//        print_r($data);exit;
        if(isset($data) && $data != null)
            echo json_encode($data);
        else
            echo 0;
    }

    function getMI() {
        $city_id = $this->input->post('city_id');
        $appointment_type = $this->input->post('appointment_type');
        $option = '';
        if ($appointment_type == 0) {
            $options = array(
                'table' => 'qyura_hospital',
                'where' => array('qyura_hospital.hospital_deleted' => 0, 'qyura_hospital.hospital_cityId' => $city_id),
            );
            $hospital = $this->common_model->customGet($options);

            if (isset($hospital) && $hospital != NULL) {
                $option .= '<option value="">Select Hospital</option>';
                foreach ($hospital as $hospi) {
                    $option .= '<option value="' . $hospi->hospital_id .','. $hospi->hospital_usersId. '">' . $hospi->hospital_name . '</option>';
                }
            } else {
                $option .= '<option value=""> Hospital not available. </option>';
            }
        } else {
            $options = array(
                'table' => 'qyura_diagnostic',
                'where' => array('qyura_diagnostic.diagnostic_deleted' => 0, 'qyura_diagnostic.diagnostic_cityId' => $city_id),
            );
            $diagnostic = $this->common_model->customGet($options);
            if (isset($diagnostic) && $diagnostic != NULL) {
                $option .= '<option value="">Select Diagnostic</option>';
                foreach ($diagnostic as $diagno) {
                    $option .= '<option value="' . $diagno->diagnostic_id .','. $diagno->diagnostic_usersId. '">' . $diagno->diagnostic_name . '</option>';
                }
            } else {
                $option .= '<option value=""> Diagnostic not available. </option>';
            }
        }
        echo $option;
    }

    public function get_timeSlot() {
        $mId = $this->input->post('miId');
        $quotation_id = $this->input->post('quotation_id');
        
        $timeSlotId = $this->input->post('timeSlotId');
        $data['timeSlots'] = $this->miappointment->getTimeSlot($mId,$quotation_id);
        dump($this->db->last_query());
        $data['timeSlotId']=$timeSlotId;
        
        if($data['timeSlots'])
        $dateTime = $data['timeSlots'][0]->quotation_dateTime;
        
        $data['mId']=  $mId;
        $data['quotation_id']= $quotation_id;
        $data['date'] = date('Y-m-d', $dateTime);
        $data['time'] = date('h:i A', $dateTime);
        $this->load->view('changetimeSlot', $data);
    }

    public function Save_timeSlot() {

        $mId = $this->input->post('miId');
        $mId = $this->input->post('quotation_id');
        $appointmentDate = $this->input->post('appointmentDate');
        $session = $this->input->post('session');
        $finalTime = $this->input->post('finalTime');
        echo strtotime("$appointmentDate $finalTime");
        
        $timeSlotArray = array(
            'quotation_timeSlotId' => $session,
            'quotation_dateTime' => strtotime("$appointmentDate $finalTime")
        );
        $this->db->where(array('quotation_MiId'=>$mId,'quotation_id'=>$quotation_id));
        $this->db->update('qyura_quotations', $timeSlotArray);
    }
    
    /**
     * @project Qyura
     * @method fetchCity
     * @description get city records by state
     * @access public
     * @param stateId
     * @return array
     */
    function fetchCity() {
        //echo "fdadas";exit;
        $stateId = $this->input->post('stateId');
        
        $cityData = $this->miappointment->fetchCity($stateId);
        
        $cityOption = '';
        $cityOption .='<option value=>Select Your City</option>';
        foreach ($cityData as $key => $val) {
            $cityOption .= '<option value=' . $val->city_id . '>' . strtoupper($val->city_name) . '</option>';
        }
        echo $cityOption;
        exit;
    }   
    
    /**
     * @project Qyura
     * @method addAppointmentSave
     * @description save appointment details
     * @access public
     * @param 
     * @return insert id
     */
    function addAppointmentSave(){
        
        //generol details
        $this->bf_form_validation->set_rules("input1","City", 'required|xss_clean');
        $this->bf_form_validation->set_rules("input2","Centre Type", 'required|xss_clean');
        $this->bf_form_validation->set_rules("input3","Hospital/Diagnostic", 'required|xss_clean');
        $this->bf_form_validation->set_rules("input4","Time Slot", 'required|xss_clean');
        $this->bf_form_validation->set_rules("input5","Appointment Type", 'required|xss_clean');
        $this->bf_form_validation->set_rules("input6","Date", 'required|xss_clean');
        $this->bf_form_validation->set_rules("input34","Time", 'required|xss_clean');
        $this->bf_form_validation->set_rules("input8","Appointment Status", 'required|xss_clean');
        //test or specialities
        $apoint_type = $this->input->post('input5');
        if($apoint_type == 0){
            $this->bf_form_validation->set_rules("input10","Specialities", 'required|xss_clean');
            $this->bf_form_validation->set_rules("input12","Doctor", 'required|xss_clean');
            $this->bf_form_validation->set_rules("input13","Remarks", 'required|xss_clean');
        }else{
            $total_test = $this->input->post('total_test');
            for($j=1;$j<=$total_test;$j++){
                $this->bf_form_validation->set_rules("input28_$j","Diagnostic Type $j ", 'required|xss_clean');
                $this->bf_form_validation->set_rules("input29_$j","Test Name $j ", 'required|xss_clean');
                $this->bf_form_validation->set_rules("input30_$j","Price $j", 'required|xss_clean');
                $this->bf_form_validation->set_rules("input31_$j","Instruction $j", 'required|xss_clean');
            }
        }
        //user
        $this->bf_form_validation->set_rules("input14","Patient Email", 'required|xss_clean');
        $this->bf_form_validation->set_rules("input15","Mobile Number ", 'required|xss_clean');
        $this->bf_form_validation->set_rules("input17","Name", 'required|xss_clean');
        $this->bf_form_validation->set_rules("input18","Country ", 'required|xss_clean');
        $this->bf_form_validation->set_rules("input19","State ", 'required|xss_clean');
        $this->bf_form_validation->set_rules("input32","City", 'required|xss_clean');
        $this->bf_form_validation->set_rules("input20","Zip", 'required|xss_clean');
        $this->bf_form_validation->set_rules("input21","Address", 'required|xss_clean');
        $this->bf_form_validation->set_rules("input35","DOB", 'required|xss_clean');
        $this->bf_form_validation->set_rules("input36","Gender", 'required|xss_clean');
        //payment 
        $this->bf_form_validation->set_rules("input22","Consulation Fee", 'required|xss_clean');
        $this->bf_form_validation->set_rules("input23","Other Fee", 'required|xss_clean');
        $this->bf_form_validation->set_rules("input24","Tax", 'required|xss_clean');
        $this->bf_form_validation->set_rules("input25","Total Amount ", 'required|xss_clean');
        $this->bf_form_validation->set_rules("input26","Payment Status", 'required|xss_clean');
        $this->bf_form_validation->set_rules("input27","Payment Mode", 'required|xss_clean');
        if ($this->bf_form_validation->run() == FALSE) {
            $responce = array('status' => 0, 'isAlive' => TRUE, 'errors' => ajax_validation_errors());
            echo json_encode($responce);
        } else {
            $qyura_doctorAppointment = $quotations = '';
            //User Deitails
            $user_id = $this->input->post('user_id');
            
            //insert new user
            if (empty($user_id)) {
            
                $email = $this->email = strtolower($this->input->post('input14'));
                $username = explode('@', $email);
                $username = $this->username = $username[0];
                $length = 10;
                $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
                $password = '';
                for ($i = 0; $i < $length; $i++) {
                    $password .= $characters[rand(0, strlen($characters) - 1)];
                }
                $user_mobile = $this->input->post('input15');
                $patient_id = $this->input->post('input16');
                $user_name = $this->input->post('input17');
                $user_country = $this->input->post('input18');
                $user_state = $this->input->post('input19');
                $user_city = $this->input->post('input32');
                $user_zip = $this->input->post('input20');
                $user_address = $this->input->post('input21');
                $user_dob = $this->input->post('input35');
                $user_gender = $this->input->post('input36');

                $optionAuotation = array(
                    'table' => 'qyura_users',
                    'data' => array(
                        'users_username' => $username,
                        'users_password' => $password,
                        'users_email' => $email,
                        'users_mobile' => $user_mobile,
                        'users_active' => 1,
                        'users_deleted' => 0,
                        'creationTime' => strtotime(date('Y-m-d H:i:s'))
                    )
                 );

                $user_id = $this->common_model->customInsert($optionAuotation);

                $optionAuotation = array(
                    'table' => 'qyura_patientDetails',
                    'data' => array(
                        'patientDetails_usersId' => $user_id,
                        'patientDetails_countryId' => $user_country,
                        'patientDetails_stateId' => $user_state,
                        'patientDetails_cityId' => $user_city,
                        'patientDetails_mobileNo' => $user_mobile,
                        'patientDetails_unqId' => 'PNT' . random_string('alnumnew', 6),
                        'patientDetails_patientName' => $user_name,
                        'patientDetails_address' => $user_address,
                        'patientDetails_pin' => $user_zip,
                        'patientDetails_dob' => $user_dob,
                        'patientDetails_gender' => $user_gender,
                        'patientDetails_deleted'=> 0,
                        'creationTime' => strtotime(date('Y-m-d H:i:s'))
                    )
                 );

                $patitentId = $this->common_model->customInsert($optionAuotation);

                $optionAuotation = array(
                    'table' => 'qyura_usersRoles',
                    'data' => array(
                        'usersRoles_userId' => $user_id,
                        'usersRoles_roleId' => 6,
                        'creationTime' => strtotime(date('Y-m-d H:i:s'))
                    )
                 );

                $rolesId = $this->common_model->customInsert($optionAuotation);
                $user_id = $user_id;
            }
            
            //Appointment Deitails
            $city = $this->input->post('input1');
            $centre_type = $this->input->post('input2');
            $id = $this->input->post('input3');
            $id = explode(',', $id);
            $h_d_id = $id[0];
            if ($id[1]){ $h_d_userid = $id[1]; }
            $time_id = $this->input->post('input4');
            $time_id = explode(',', $time_id);
            $timeslot_id = $time_id[0];
            if ($time_id[1]){ $time_session = $time_id[1]; }
            $apoint_type = $this->input->post('input5');
            $apoint_date = strtotime($this->input->post('input6'));
            //$apoint_unid = $this->input->post('input7');
            $apoint_status = $this->input->post('input8');
            $hms_id = $this->input->post('input9');
            $final_time = strtotime($this->input->post('input34'));
            //Amount Information
            $cons_fee = $this->input->post('input22');
            $othr_fee = $this->input->post('input23');
            $tax = $this->input->post('input24');
            $total_fee = $this->input->post('input25');
            $pay_status = $this->input->post('input26');
            $pay_mode = $this->input->post('input27');
            
            $family_member = $this->input->post('family_member');
            if($family_member == 1){
                $family_member_id = $this->input->post('input33');
            }else{
                $family_member_id = '';
            }
            //insert doctor appointment
            if($apoint_type == 0){
               
                $speciallity = $this->input->post('input10');
                $doc_id = $this->input->post('input12');
                $patient_remarks = $this->input->post('input13');
                if($centre_type == 0){$newType = 1;}else{$newType = 2;}
                
                $records_array1 = array('creationTime' => strtotime(date('Y-m-d H:i:s')),'doctorAppointment_payMode'=>$pay_mode,'doctorAppointment_payStatus'=>$pay_status,'doctorAppointment_totPayAmount'=>$total_fee,'doctorAppointment_tax'=>$tax,'doctorAppointment_otherFee'=>$othr_fee,'doctorAppointment_consulationFee'=>$cons_fee,'doctorAppointment_HMSId'=>$hms_id,'doctorAppointment_status'=>$apoint_status,'doctorAppointment_ptRmk'=>$patient_remarks,'doctorAppointment_doctorParentId'=>$h_d_id,'doctorAppointment_pntUserId'=>$user_id,'doctorAppointment_memberId'=>$family_member_id,'doctorAppointment_docType'=>$newType,'doctorAppointment_doctorUserId'=>$doc_id,'doctorAppointment_finalTiming'=>$final_time,'doctorAppointment_slotId'=>$timeslot_id,'doctorAppointment_session'=>$time_session,'doctorAppointment_date'=>$apoint_date,'doctorAppointment_specialitiesId'=>$speciallity);                
                $options = array(
                    'data' => $records_array1,
                    'table' => 'qyura_doctorAppointment'
                );
                $qyura_doctorAppointment = $this->common_model->customInsert($options);
                
                //create/insert unique id
                $where = array('doctorAppointment_id' => $qyura_doctorAppointment);
                $update_data['doctorAppointment_unqId'] =$docUnId= 'DOC'. $user_id . time();
                $options = array(
                    'table' => 'qyura_doctorAppointment',
                    'where' => $where,
                    'data' => $update_data
                );
                $update = $this->common_model->customUpdate($options);
                
                //insert data in transaction table
                $transaction_array1 = array('creationTime' => strtotime(date('Y-m-d H:i:s')),'user_id'=>$user_id,'order_no'=>$docUnId);
                $options = array(
                    'data' => $transaction_array1,
                    'table' => 'transactionInfo'
                );
                $doc_trasaction = $this->common_model->customInsert($options);
                
            }else{
                
                //insert diagnostics appointment
                $records_array2 = array('creationTime' => strtotime(date('Y-m-d H:i:s')),'quotation_MiId'=>$h_d_id,'quotation_userId'=>$user_id,'quotation_familyId'=>$family_member_id,'quotation_timeSlotId'=>$timeslot_id,'quotation_qtStatus'=>1,'quotation_dateTime'=>$apoint_date,'quotation_tex'=>$tax,'quotation_otherFee'=>$othr_fee,);
            
                $options = array(
                    'data' => $records_array2,
                    'table' => 'qyura_quotations'
                );
                $quotations = $this->common_model->customInsert($options);
                
                //create/insert unique id
                $where = array('quotation_id' => $quotations);
                $update_data['quotation_unqId'] =$quUnId= 'QU_' . $quotations . '_' . time();
                $options = array(
                    'table' => 'qyura_quotations',
                    'where' => $where,
                    'data' => $update_data
                );
                $update = $this->common_model->customUpdate($options);
                
                //insert data in transaction table
                $transaction_array2 = array('creationTime' => strtotime(date('Y-m-d H:i:s')),'user_id'=>$user_id,'order_no'=>$quUnId);
                $options = array(
                    'data' => $transaction_array2,
                    'table' => 'transactionInfo'
                );
                $digo_trasaction = $this->common_model->customInsert($options);
                
                $total_test = $this->input->post('total_test');
                for($i=1;$i<=$total_test;$i++){
                    
                    //insert multiple test 
                    $test_type = $this->input->post("input28_".$i);
                    $test_name = $this->input->post("input29_".$i);
                    $test_price = $this->input->post("input30_".$i);
                    $test_instruction = $this->input->post("input31_".$i);
                    
                    $records_array3 = array('creationTime' => strtotime(date('Y-m-d H:i:s')),'quotationDetailTests_quotationId'=>$quotations,'quotationDetailTests_diagnosticCatId'=>$test_type,'quotationDetailTests_MIprofileId'=>$h_d_id,'quotationDetailTests_testName'=>$test_name,'quotationDetailTests_date'=>$apoint_date,'quotationDetailTests_price'=>$test_price,'quotationDetailTests_instruction'=>$test_instruction);
                    $options = array(
                        'data' => $records_array3,
                        'table' => 'qyura_quotationDetailTests'
                    );
                    $quotationDetail = $this->common_model->customInsert($options);
                    echo "Details: ";print_r($quotationDetail);
                }
                //insert quotations booking 
                $records_array4 = array('creationTime' => strtotime(date('Y-m-d H:i:s')),'quotationBooking_quotationId'=>$quotations,'quotation_familyId'=>$family_member_id,'quotationBooking_userId'=>$user_id,'quotationBooking_orderId'=>$quUnId,'quotationBooking_amount'=>$total_fee,'	quotationBooking_qtTestId'=>$quotationDetail,'quotationBooking_bookStatus'=>$apoint_status);

                $options = array(
                    'data' => $records_array4,
                    'table' => 'qyura_quotationBooking'
                );
                $quotationBooking = $this->common_model->customInsert($options);
                $bookId = 'DIAD_' . $quotationBooking . '_' . time();
                $updateOption = array(
                    'data' => array(
                        'quotationBooking_orderId' => $bookId,
                    ),
                    'table' => 'qyura_quotationBooking',
                    'where' => array('quotationBooking_id' => $quotationBooking)
                );
                $isUpdate = $this->common_model->customUpdate($updateOption);
                
                //insert data in transaction table
                $transaction_array2 = array('creationTime' => strtotime(date('Y-m-d H:i:s')),'user_id'=>$user_id,'order_no'=>$bookId);
                $options = array(
                    'data' => $transaction_array2,
                    'table' => 'transactionInfo'
                );
                $digo_trasaction = $this->common_model->customInsert($options);
            }
            
            if ($qyura_doctorAppointment || $quotations) {
                
                $responce =  array('status'=>1,'msg'=>"Appointment created successfully",'url' =>"miappointment");
            }else
            {
                $error = array("TopError"=>"<strong>Something went wrong while updating your data... sorry.</strong>");
                $responce =  array('status'=>0,'isAlive'=>TRUE,'errors'=>$error);
            }
            echo json_encode($responce);
        }
    }
    
    /**
     * @project Qyura
     * @method appoint_timeSlot
     * @description get time slot related to hospitol or diagnostic
     * @access public
     * @param h_d_id,type
     * @return option
     */
    function appoint_timeSlot(){
        $h_d_id = $this->input->post('h_d_id');
        $type = $this->input->post('type');
        $option = '';
        if ($type == 0) {
            $options = array(
                'table' => 'qyura_hospitalTimeSlot',
                'where' => array('qyura_hospitalTimeSlot.hospitalTimeSlot_deleted' => 0,'qyura_hospitalTimeSlot.hospitalTimeSlot_hospitalId' => $h_d_id),
            );
            $hospitalTimeSlot = $this->common_model->customGet($options);
            
            if (isset($hospitalTimeSlot) && $hospitalTimeSlot != NULL) {
                $option .= '<option value="">Select Time Slot</option>';
                foreach ($hospitalTimeSlot as $hospi) {
                    $session = getSession($hospi->hospitalTimeSlot_sessionType);
                    $option .= '<option value="' . $hospi->hospitalTimeSlot_id .','. $hospi->hospitalTimeSlot_sessionType. '">' . $hospi->hospitalTimeSlot_startTime ." to ".$hospi->hospitalTimeSlot_endTime ." | ". $session. '</option>';
                }
            } else {
                $option .= '<option value=""> Hospital time slot not available. </option>';
            }
        } else {
            
            $options = array(
                'table' => 'qyura_diagnosticCenterTimeSlot',
                'where' => array('qyura_diagnosticCenterTimeSlot.diagnosticCenterTimeSlot_deleted' => 0,'qyura_diagnosticCenterTimeSlot.diagnosticCenterTimeSlot_diagnosticId' => $h_d_id),
            );
            $diagnostic = $this->common_model->customGet($options);
            
            if (isset($diagnostic) && $diagnostic != NULL) {
                $option .= '<option value="">Select Time Slot</option>';
                foreach ($diagnostic as $diagno) {
                    $session = getSession($diagno->diagnosticCenterTimeSlot_sessionType);
                    $option .= '<option value="' . $diagno->diagnosticCenterTimeSlot_id .','. $diagno->diagnosticCenterTimeSlot_sessionType.  '">' . $diagno->diagnosticCenterTimeSlot_startTime ." to ".$diagno->diagnosticCenterTimeSlot_endTime ." | ". $session. '</option>';
                }
            } else {
                $option .= '<option value=""> Diagnostic time slot not available. </option>';
            }
        }
        echo $option;
    }
    
    /**
     * @project Qyura
     * @method find_specialities
     * @description get specialities related to hospitol or diagnostic
     * @access public
     * @param h_d_id,type
     * @return option
     */
    function find_specialities(){
        $h_d_id = $this->input->post('h_d_id');
        $type = $this->input->post('type');
        $option = '';
        if ($type == 0) {
            $options = array(
                'table' => 'qyura_hospitalSpecialities',
                'where' => array('qyura_hospitalSpecialities.hospitalSpecialities_deleted' => 0,'qyura_hospitalSpecialities.hospitalSpecialities_hospitalId' => $h_d_id),
                'join' => array(
                    array('qyura_specialities', 'qyura_specialities.specialities_id = qyura_hospitalSpecialities.hospitalSpecialities_specialitiesId', 'left'),
                ),
                'group_by'=> 'qyura_specialities.specialities_id',
            );
            $hospitalSpecialities = $this->common_model->customGet($options);
            
            if (isset($hospitalSpecialities) && $hospitalSpecialities != NULL) {
                $option .= '<option value="">Select Specialities</option>';
                foreach ($hospitalSpecialities as $specialities) {
                    
                    $option .= '<option value="' . $specialities->specialities_id . '">' . $specialities->specialities_name .'</option>';
                }
            } else {
                $option .= '<option value=""> Currently there is no data found. </option>';
            }
        } else {
            $options = array(
                'table' => 'qyura_diagnosticSpecialities',
                'where' => array('qyura_diagnosticSpecialities.diagnosticSpecialities_deleted' => 0,'qyura_diagnosticSpecialities.diagnosticSpecialities_diagnosticId' => $h_d_id),
                'join' => array(
                    array('qyura_specialities', 'qyura_specialities.specialities_id = qyura_diagnosticSpecialities.diagnosticSpecialities_specialitiesId', 'left'),
                ),
                'group_by'=> 'qyura_specialities.specialities_id',
            );
            $diagnosticSpecialities = $this->common_model->customGet($options);
           // echo $this->db->last_query();exit;
            if (isset($diagnosticSpecialities) && $diagnosticSpecialities != NULL) {
                $option .= '<option value="">Select Specialities</option>';
                foreach ($diagnosticSpecialities as $specialities) {
                    
                    $option .= '<option value="' . $specialities->specialities_id . '">' . $specialities->specialities_name. '</option>';
                }
            } else {
                $option .= '<option value=""> Currently there is no data found. </option>';
            }
        }
        echo $option;
    }
    
    /**
     * @project Qyura
     * @method find_diago_test
     * @description get test related to hospitol or diagnostic
     * @access public
     * @param h_d_id,type
     * @return option
     */
    function find_diago_test(){
        $h_d_id = $this->input->post('h_d_id');
        $type = $this->input->post('type');
        $option = '';
        if ($type == 0) {
            $options = array(
                'table' => 'qyura_hospitalDiagnosticsCat',
                'where' => array('qyura_hospitalDiagnosticsCat.hospitalDiagnosticsCat_deleted' => 0,'qyura_hospitalDiagnosticsCat.hospitalDiagnosticsCat_hospitalId' => $h_d_id),
                'join' => array(
                    array('qyura_diagnosticsCat', 'qyura_diagnosticsCat.diagnosticsCat_catId = qyura_hospitalDiagnosticsCat.hospitalDiagnosticsCat_diagnosticsCatId', 'left'),
                ),
                'group_by'=> 'qyura_diagnosticsCat.diagnosticsCat_catId',
            );
            $hospitalTest = $this->common_model->customGet($options);
           
            if (isset($hospitalTest) && $hospitalTest != NULL) {
                $option .= '<option value="">Select Category</option>';
                foreach ($hospitalTest as $hospital) {
                    $option .= '<option value="' . $hospital->hospitalDiagnosticsCat_diagnosticsCatId . '">' . $hospital->	diagnosticsCat_catName .'</option>';
                }
            } else {
                $option .= '<option value=""> Currently there is no data found. </option>';
            }
        } else {
            $options = array(
                'table' => 'qyura_diagnosticsHasCat',
                'where' => array('qyura_diagnosticsHasCat.diagnosticsHasCat_deleted' => 0,'qyura_diagnosticsHasCat.diagnosticsHasCat_diagnosticId' => $h_d_id),
                'join' => array(
                    array('qyura_diagnosticsCat', 'qyura_diagnosticsCat.diagnosticsCat_catId = qyura_diagnosticsHasCat.diagnosticsHasCat_diagnosticsCatId', 'left'),
                ),
                'group_by'=> 'qyura_diagnosticsCat.diagnosticsCat_catId',
            );
            $diagnosticTest = $this->common_model->customGet($options);
            
            if (isset($diagnosticTest) && $diagnosticTest != NULL) {
                $option .= '<option value="">Select Category</option>';
                foreach ($diagnosticTest as $diagnostic) {
                    
                    $option .= '<option value="' . $diagnostic->diagnosticsHasCat_diagnosticsCatId . '">' . $diagnostic->diagnosticsCat_catName. '</option>';
                }
            } else {
                $option .= '<option value=""> Currently there is no data found. </option>';
            }
        }
        echo $option;
    }
    
    /**
     * @project Qyura
     * @method find_doctor
     * @description get doctor records related to hospitol or diagnostic
     * @access public
     * @param h_d_id,type,special_id
     * @return option
     */
    function find_doctor(){
        $h_d_id = $this->input->post('h_d_id');
        $type = $this->input->post('type');
        $special_id = $this->input->post('special_id');
        $option = '';
//      type = 0 = Hospitals
        if (isset($h_d_id) && isset($special_id)) {
            $doctors = $this->miappointment->getConsultantList($h_d_id,$special_id);
            //echo $this->db->last_query();exit;
            if (isset($doctors) && $doctors != NULL) {
                $option .= '<option value="">Select Doctor</option>';
                foreach ($doctors as $doctor) {
                    $option .= '<option value="' . $doctor['userId'] . '">' . $doctor['name']. '</option>';
                }
            } else {
                $option .= '<option value=""> Currently no doctor available in this speciality. </option>';
            }
        }
        echo $option;
    }
    
    function getMember(){
        $user_id = $this->input->post('user_id');
        $option = '';
        if (isset($user_id)) {
            $options = array(
                'table' => 'qyura_usersFamily',
                'where' => array('qyura_usersFamily.usersfamily_deleted' => 0, 'qyura_usersFamily.usersfamily_usersId' => $user_id),
            );
            $familyList = $this->common_model->customGet($options);
            
            if (isset($familyList) && $familyList != NULL) {
                $option .= '<option value="">Select Member</option>';
                foreach ($familyList as $family) {
                    $option .= '<option value="' . $family->usersfamily_id . '">' . $family->usersfamily_name. '</option>';
                }
            } else {
                $option .= '<option value=""> Currently no member registered with us. </option>';
            }
        }
        echo $option;
    }
}
