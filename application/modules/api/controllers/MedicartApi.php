<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'modules/api/controllers/MyRest.php';

class MedicartApi extends MyRest {

    function __construct() {
        // Construct our parent class
        parent::__construct();
        $this->load->model(array('medicart_model'));
        //$this->methods['hospital_post']['limit'] = 1; //500 requests per hour per user/key
        // $this->methods['user_post']['limit'] = 100; //100 requests per hour per user/key
        // $this->methods['user_delete']['limit'] = 50; //50 requests per hour per user/key
    }

    function list_post() {


        $this->bf_form_validation->set_rules('lat', 'Lat', 'required|decimal');
        $this->bf_form_validation->set_rules('long', 'Long', 'required|decimal');
        $this->bf_form_validation->set_rules('q', 'q', 'trim|xss_clean');
        $this->bf_form_validation->set_rules('notin', 'notin', 'trim|xss_clean');


        if ($this->bf_form_validation->run() == FALSE) {
            // setup the input
            $message = $this->validation_post_warning();
            $response = array('status' => FALSE, 'msg' => $message);
            $this->response($response, 400);
        } else {


            $option['lat'] = isset($_POST['lat']) ? $this->input->post('lat') : '';
            $option['long'] = isset($_POST['long']) ? $this->input->post('long') : '';

            $option['search'] = isset($_POST['q']) ? $this->input->post('q') : '';

            $notIn = isset($_POST['notin']) ? $this->input->post('notin') : '';

            $option['notIn'] = explode(',', $notIn);
            
            $aoClumns = array("medicartOffer_id", "MIId", "offerCategory", "title", "image", "description","startDate", "endDate", "actualPrice", "discountPrice",   "medicartOffer_deleted", "modifyTime","by", "lat", "long","allowBooking","maximumBooking","phnNo");

            $medList = $this->medicart_model->getMedlists($option);
            
            
            if ($medList) {

                $finalResult = array();
                if (!empty($medList)) {
                    foreach ($medList as $row) {

                        $finalTemp = array();
                        $finalTemp[] = isset($row->medicartOffer_id) ? $row->medicartOffer_id : "";
                        $finalTemp[] = isset($row->medicartOffer_MIId) ? $row->medicartOffer_MIId : "";
                        $finalTemp[] = isset($row->medicartOffer_offerCategory) ? $row->medicartOffer_offerCategory : "";
                        $finalTemp[] = isset($row->medicartOffer_title) ? $row->medicartOffer_title : "";
                        $finalTemp[] = isset($row->medicartOffer_image) ? $row->medicartOffer_image : "";
                        $finalTemp[] = isset($row->medicartOffer_description) ? $row->medicartOffer_description : "";
                        //$finalTemp[] = isset($row->medicartOffer_maximumBooking) ? $row->medicartOffer_maximumBooking : "";
                        $finalTemp[] = isset($row->medicartOffer_startDate) ? $row->medicartOffer_startDate : "";
                        $finalTemp[] = isset($row->medicartOffer_endDate) ? $row->medicartOffer_endDate : "";
                        //$finalTemp[] = isset($row->medicartOffer_discount) ? $row->medicartOffer_discount : "";
                        //$finalTemp[] = isset($row->medicartOffer_ageDiscount) ? $row->medicartOffer_ageDiscount : "";
                        $finalTemp[] = isset($row->medicartOffer_actualPrice) ? $row->medicartOffer_actualPrice : "";
                        $finalTemp[] = isset($row->medicartOffer_discountPrice) ? $row->medicartOffer_discountPrice : "";
                        $finalTemp[] = isset($row->medicartOffer_deleted) ? $row->medicartOffer_deleted : "";
                        //$finalTemp[] = isset($row->medicartOffer_discount) ? $row->medicartOffer_discount : "";
                        $finalTemp[] = isset($row->modifyTime) ? $row->modifyTime : "";
                       // dump((isset($row->hospital_name) && $row->hospital_name != null && $row->hospital_name != ''));
                        
                        $by = "";
                        $lat = "";
                        $long = "";
                        $phnNo = "";
                        
                        $diagnostic_name = (isset($row->diagnostic_name) && $row->diagnostic_name != null && $row->diagnostic_name != '') ? $row->diagnostic_name : "" ;
                        $hospital_name = (isset($row->hospital_name) && $row->hospital_name != null && $row->hospital_name != '') ? $row->hospital_name : "";
                        
                        $diagnostic_phn = (isset($row->diagnostic_phn) && $row->diagnostic_phn != null && $row->diagnostic_phn != '') ? $row->diagnostic_phn : "" ;
                        $hospital_phn = (isset($row->hospital_phn) && $row->hospital_phn != null && $row->hospita_phn != '') ? $row->hospital_phn: "";
                        
                        $hospital_lat = (isset($row->hospital_lat) && $row->hospital_lat != null && $row->hospital_lat != '') ? $row->hospital_lat : "";
                        $diagnostic_lat = (isset($row->diagnostic_lat) && $row->diagnostic_lat != null && $row->diagnostic_lat != '') ? $row->diagnostic_lat : "";
                        
                        $hospital_long = (isset($row->hospital_long) && $row->hospital_long != null && $row->hospital_long != '') ? $row->hospital_long : "";
                        $diagnostic_long = (isset($row->diagnostic_long) && $row->diagnostic_long != null && $row->diagnostic_long != '') ? $row->diagnostic_long : '';
                        
                        if($hospital_name != "") $by= $hospital_name; elseif($diagnostic_name != ""){$by = $diagnostic_name;}
                        if($hospital_lat != "") $lat= $hospital_lat; elseif($diagnostic_lat != ""){$lat = $diagnostic_lat;}
                        if($hospital_long != "") $long= $hospital_long; elseif($diagnostic_long != ""){$long = $diagnostic_long;}
                        if($hospital_phn != "") $phnNo= $hospital_phn; elseif($diagnostic_phn != ""){$phnNo = $diagnostic_phn;}
                        
                        
                        $phnNo = str_replace('91','', $phnNo);
                        $phnNo = str_replace(' ','', $phnNo);
                        $phnNo = trim($phnNo);
                        $finalTemp[] = $by;
                        $finalTemp[] = $lat;
                        $finalTemp[] = $long;
                        
                        
                        
                        
                        
                        //$finalTemp[] = (isset($row->hospital_lat) && $row->hospital_lat != null && $row->hospital_lat != '') ? $row->hospital_lat : (isset($row->diagnostic_lat) && $row->diagnostic_lat != null && $row->diagnostic_lat != '') ? $row->diagnostic_lat : '' ;
                        //$finalTemp[] = (isset($row->hospital_long) && $row->hospital_long != null && $row->hospital_long != '') ? $row->hospital_long : (isset($row->diagnostic_long) && $row->diagnostic_long != null && $row->diagnostic_long != '') ? $row->diagnostic_long : ''  ;
                        $finalTemp[] = isset($row->medicartOffer_allowBooking) ? $row->medicartOffer_allowBooking : "";
                        $finalTemp[] = isset($row->medicartOffer_maximumBooking) ? $row->medicartOffer_maximumBooking : "";
                        $finalTemp[] = $phnNo;
                        $finalResult[] = $finalTemp;
                    }
                }
            }

            // $finalResult = $this->jsonify($finalResult);


            if (!empty($finalResult)) {
                $response1['msg'] = 'medicart offer found';
                $response1['status'] = TRUE;
                $response1['data'] = $finalResult;
                $response1['colName'] = $aoClumns;
                $this->response($response1, 200); // 200 being the HTTP response code
            } else {
                $response1['msg'] = 'No medicart offer is available at this range!';
                $response1['status'] = FALSE;
                $this->response($response1, 404);
            }
        }
    }
    
    function MedicartDitail_post() {


        $this->bf_form_validation->set_rules('medicartOffer_id', 'MedicartOffer id', 'required|is_natural_no_zero');
        


        if ($this->bf_form_validation->run() == FALSE) {
            // setup the input
            $message = $this->validation_post_warning();
            $response = array('status' => FALSE, 'msg' => $message);
            $this->response($response, 400);
        } else {

            $medicartOffer_id = isset($_POST['medicartOffer_Id']) ? $this->input->post('medicartOffer_id') : '';
            
            $aoClumns = array("medicartOffer_id", "MIId", "offerCategory", "title", "image", "startDate", "endDate", "description", "actualPrice", "discountPrice", "medicartOffer_deleted", "modifyTime","by","allowBooking","maximumBooking");

            $row = $this->medicart_model->getMedDetail($medicartOffer_id);
            
            
            if ($row) {

                $finalResult = array();
                if (!empty($row)) {
                    

                        $finalTemp = array();
                        $finalTemp[] = isset($row->medicartOffer_id) ? $row->medicartOffer_id : "";
                        $finalTemp[] = isset($row->medicartOffer_MIId) ? $row->medicartOffer_MIId : "";
                        $finalTemp[] = isset($row->medicartOffer_offerCategory) ? $row->medicartOffer_offerCategory : "";
                        $finalTemp[] = isset($row->medicartOffer_title) ? $row->medicartOffer_title : "";
                        $finalTemp[] = isset($row->medicartOffer_image) ? $row->medicartOffer_image : "";
                        
                        //$finalTemp[] = isset($row->medicartOffer_maximumBooking) ? $row->medicartOffer_maximumBooking : "";
                        $finalTemp[] = isset($row->medicartOffer_startDate) ? $row->medicartOffer_startDate : "";
                        $finalTemp[] = isset($row->medicartOffer_endDate) ? $row->medicartOffer_endDate : "";
                        $finalTemp[] = isset($row->medicartOffer_description) ? $row->medicartOffer_description : "";
                        //$finalTemp[] = isset($row->medicartOffer_discount) ? $row->medicartOffer_discount : "";
                        //$finalTemp[] = isset($row->medicartOffer_ageDiscount) ? $row->medicartOffer_ageDiscount : "";
                        $finalTemp[] = isset($row->medicartOffer_actualPrice) ? $row->medicartOffer_actualPrice : "";
                        $finalTemp[] = isset($row->medicartOffer_discountPrice) ? $row->medicartOffer_discountPrice : "";
                        $finalTemp[] = isset($row->medicartOffer_deleted) ? $row->medicartOffer_deleted : "";
                        //$finalTemp[] = isset($row->medicartOffer_discount) ? $row->medicartOffer_discount : "";
                        $finalTemp[] = isset($row->modifyTime) ? $row->modifyTime : "";
                       // dump((isset($row->hospital_name) && $row->hospital_name != null && $row->hospital_name != ''));
                        
                        $by = "";
                        $lat = "";
                        $long = "";
                        $phnNo = "";
                        
                        $diagnostic_name = (isset($row->diagnostic_name) && $row->diagnostic_name != null && $row->diagnostic_name != '') ? $row->diagnostic_name : "" ;
                        $hospital_name = (isset($row->hospital_name) && $row->hospital_name != null && $row->hospital_name != '') ? $row->hospital_name : "";
                        $diagnostic_phn = (isset($row->diagnostic_phn) && $row->diagnostic_phn != null && $row->diagnostic_phn != '') ? $row->diagnostic_phn : "" ;
                        $hospital_phn = (isset($row->hospital_phn) && $row->hospital_phn != null && $row->hospita_phn != '') ? $row->hospital_phn: "";
                        
                        //$hospital_lat = (isset($row->hospital_lat) && $row->hospital_lat != null && $row->hospital_lat != '') ? $row->hospital_lat : "";
                       // $diagnostic_lat = (isset($row->diagnostic_lat) && $row->diagnostic_lat != null && $row->diagnostic_lat != '') ? $row->diagnostic_lat : "";
                        
                        //$hospital_long = (isset($row->hospital_long) && $row->hospital_long != null && $row->hospital_long != '') ? $row->hospital_long : "";
                        //$diagnostic_long = (isset($row->diagnostic_long) && $row->diagnostic_long != null && $row->diagnostic_long != '') ? $row->diagnostic_long : '';
                        
                        if($hospital_name != "") $by= $hospital_name; elseif($diagnostic_name != ""){$by = $diagnostic_name;}
                        //if($hospital_lat != "") $lat= $hospital_lat; elseif($diagnostic_lat != ""){$lat = $diagnostic_lat;}
                        //if($hospital_long != "") $long= $hospital_long; elseif($diagnostic_long != ""){$long = $diagnostic_long;}
                        if($hospital_phn != "") $phnNo= $hospital_phn; elseif($diagnostic_phn != ""){$phnNo = $diagnostic_phn;}
                        
                        
                        $phnNo = str_replace('91','', $phnNo);
                        $phnNo = str_replace(' ','', $phnNo);
                        $phnNo = trim($phnNo);
                        
                        $finalTemp[] = $by;
                        //$finalTemp[] = $lat;
                        //$finalTemp[] = $long;
                        //$finalTemp[] = (isset($row->hospital_lat) && $row->hospital_lat != null && $row->hospital_lat != '') ? $row->hospital_lat : (isset($row->diagnostic_lat) && $row->diagnostic_lat != null && $row->diagnostic_lat != '') ? $row->diagnostic_lat : '' ;
                        //$finalTemp[] = (isset($row->hospital_long) && $row->hospital_long != null && $row->hospital_long != '') ? $row->hospital_long : (isset($row->diagnostic_long) && $row->diagnostic_long != null && $row->diagnostic_long != '') ? $row->diagnostic_long : ''  ;
                        $finalTemp[] = isset($row->medicartOffer_allowBooking) ? $row->medicartOffer_allowBooking : "";
                        $finalTemp[] = isset($row->medicartOffer_maximumBooking) ? $row->medicartOffer_maximumBooking : "";
                        $finalTemp[] = $phnNo;
                        $finalResult[] = $finalTemp;
                    
                }
            }

            // $finalResult = $this->jsonify($finalResult);


            if (!empty($finalResult)) {
                $response1['msg'] = 'medicart offer found';
                $response1['status'] = TRUE;
                $response1['data'] = $finalResult;
                $response1['colName'] = $aoClumns;
                $this->response($response1, 200); // 200 being the HTTP response code
            } else {
                $response1['msg'] = 'No medicart offer is available at this range!';
                $response1['status'] = FALSE;
                $this->response($response1, 404);
            }
        }
    }
    
    function addContect_post() {
        
        $this->bf_form_validation->set_rules('medicartOfferId', 'Medicart Offer Id', 'xss_clean|trim|required|numeric|is_natural_no_zero');
        $this->bf_form_validation->set_rules('name', 'name', 'xss_clean|trim|required|max_length[80]|callback__alpha_dash_space');
        $this->bf_form_validation->set_rules('mobileNo', 'Mobile No', 'xss_clean|trim|numeric|min_length[10]|max_length[10]');
        $this->bf_form_validation->set_rules('email', 'email', 'xss_clean|trim|valid_email|max_length[255]');
        

        if ($this->bf_form_validation->run($this) == FALSE) {
            // setup the input
            $response = array('status' => FALSE, 'message' => $this->validation_post_warning());
            $this->response($response, 400);
        } else {

            $medicartOfferId = isset($_POST['medicartOfferId']) ? $this->input->post('medicartOfferId') : '';
            $name = isset($_POST['name']) ? $this->input->post('name') : '';
            $mobileNo = isset($_POST['mobileNo']) ? $this->input->post('mobileNo') : '';
            $email = isset($_POST['email']) ? $this->input->post('email') : '';
            
            $data = array(
                'medicartContect_name'=>$name,
                'medicartContect_medicartOfferId'=>$medicartOfferId,
                'medicartContect_mobileNo'=>$mobileNo,
                'medicartContect_email'=>$email,
                'creationTime'=>time()
            );
            
            $isInsert = $this->medicart_model->add('qyura_medicartContect',$data);
            
            if($isInsert){
                $response = array('status' => TRUE, 'message' => 'Thanks for Contact us' );
                $this->response($response, 200);
            }
            else
            {
                $response = array('status' => FALSE, 'message' => 'Network Error .Please retry' );
                $this->response($response, 400);
            }
            
        }
    }
    
    
    function cartBook_post() {
        
        $this->bf_form_validation->set_rules('medicartOfferId', 'Medicart Offer Id', 'xss_clean|trim|required|numeric|is_natural_no_zero|callback__check_allowBooking');
        $this->bf_form_validation->set_rules('userId', 'User Id', 'xss_clean|trim|required|numeric|is_natural_no_zero|_user_check');
        $this->bf_form_validation->set_rules('preferredDate', 'Preferred Date', 'xss_clean|trim|required|max_length[11]|valid_date[y-m-d,-]|callback__check_date');
        $this->bf_form_validation->set_rules('message', 'Message', 'xss_clean|trim|required|max_length[255]');
       

        if ($this->bf_form_validation->run($this) == FALSE) {
            // setup the input
            $response = array('status' => FALSE, 'message' => $this->validation_post_warning());
            $this->response($response, 400);
        } else {

            $medicartOfferId = isset($_POST['medicartOfferId']) ? $this->input->post('medicartOfferId') : '';
            $usersId = isset($_POST['usersId']) ? $this->input->post('usersId') : '';
            $preferredDate = isset($_POST['preferredDate']) ? $this->input->post('preferredDate') : '';
            $message = isset($_POST['message']) ? $this->input->post('message') : '';
            
            $where =  array(
                'medicartBooking_usersId'=>$usersId,
                'medicartBooking_medicartOfferId'=>$medicartOfferId,
                'medicartBooking_deleted'=>0);
            
            $booking_check = $this->medicart_model->booking_check($where);
            
            if(!$booking_check)
            {
            
                $data = array(
                    'medicartBooking_medicartOfferId'=>$medicartOfferId,
                    'medicartBooking_usersId'=>$usersId,
                    'medicartBooking_preferredDate'=>$preferredDate,
                    'medicartBooking_message'=>$message,
                    'creationTime'=>time()
                );

                $isInsert = $this->medicart_model->add('qyura_medicartBooking',$data);

                if($isInsert){
                    $response = array('status' => TRUE, 'message' => 'Your booking request has been submitted successfully .We will get back to you shortly.' );
                    $this->response($response, 200);
                }
                else
                {
                    $response = array('status' => FALSE, 'message' => 'Network Error .Please retry' );
                    $this->response($response, 400);
                }
            }
            else {
                $response = array('status' => FALSE, 'message' => 'You have already booked this cart.' );
                $this->response($response, 200);
            }
            
        }
    }
    
    function _alpha_dash_space($str_in = '') {

        if (!preg_match("/^([-a-zA-Z_ ])+$/i", $str_in)) {
            $this->bf_form_validation->set_message('_alpha_dash_space', 'The %s field may only contain alpha characters, spaces, underscores, and dashes.');

            return FALSE;
        } else {
            return TRUE;
        }
    }
    
    function _check_date($str_in = '')
    {
        
        $medicartOfferId = isset($_POST['medicartOfferId'])?$this->input->post('medicartOfferId'):'';
        $medicartOfferData = $this->medicart_model->getSingleData(array('medicartOffer_id'=>$medicartOfferId,'medicartOffer_deleted'=>0),'medicartOffer_id,medicartOffer_startDate as startDate,medicartOffer_endDate as endDate');
        
        
        if($medicartOfferData == NULL)
        {
            $this->bf_form_validation->set_message('_check_date', 'Medicart offer is no more available for booking');
            return FALSE;
        }
        
        $prfDate  = strtotime($str_in);
        
        if ($medicartOfferData->startDate > $prfDate) {
            $this->bf_form_validation->set_message('_check_date', 'The {field} is valid since '.date('Y-m-d',$medicartOfferData->startDate) .'to '.date('Y-m-d',$medicartOfferData->endDate));

            return FALSE;
        }
        
        if($medicartOfferData->endDate < $prfDate)
        {
             $this->bf_form_validation->set_message('_check_date', 'The {field} is valid since '.date('Y-m-d',$medicartOfferData->startDate) .'to '.date('Y-m-d',$medicartOfferData->endDate));

            return FALSE;
        }
        
        return TRUE;
    }
    
    function _check_allowBooking($str_in= '')
    {
        $medicartOfferId = isset($_POST['medicartOfferId'])?$this->input->post('medicartOfferId'):'';
        $medicartOfferData = $this->medicart_model->getSingleData(array('medicartOffer_id'=>$medicartOfferId,'medicartOffer_deleted'=>0),'medicartOffer_id,medicartOffer_startDate as startDate,medicartOffer_endDate as endDate,qyura_medicartOffer.medicartOffer_allowBooking as allowBooking');
        
        
        if($medicartOfferData == NULL)
        {
            
            $this->bf_form_validation->set_message('_check_allowBooking', 'Medicart offer is no more available for booking');
            return FALSE;
        }
        
        if(!$medicartOfferData->allowBooking){
            $this->bf_form_validation->set_message('_check_allowBooking', 'This madicart is not allowed for booking');
            return FALSE;
        }
    }
    
    

}
