<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Medicart extends MY_Controller {

    public function __construct() {
        parent:: __construct();
        $this->load->model('medicart_model');
        $this->load->library('form_validation');
        $this->load->library('datatables');
    }

    function index() {
        $option = array(
            'select' => 'city_id,city_name',
            'table' => 'qyura_city',
            'order_by' => array("city_name", "asc")
        );
        $data['allCity'] = $this->medicart_model->customGet($option);
        $data['title'] = 'Medicart';
        $this->load->super_admin_template('medicartOfferListing', $data, 'medicartScript');
    }

    function getMedicartDl() {

        echo $this->medicart_model->fetchMedicartDataTables();
    }

    function getMedicartEnquiriesDl() {

        echo $this->medicart_model->fetchMedicartEnquiriesDataTables();
    }

    function getMedicartBookingDl() {

        echo $this->medicart_model->fetchMedicartBookingDataTables();
    }

    function bookingRequest() {
        $option = array(
            'select' => 'city_id,city_name',
            'table' => 'qyura_city',
            'order_by' => array("city_name", "asc")
        );
        $data['allCity'] = $this->medicart_model->customGet($option);
        $data['title'] = 'Medicart booking';
        $this->load->super_admin_template('bookingRequestListing', $data, 'medicartScript');
    }

    function enquiries() {
        $option = array(
            'select' => 'city_id,city_name',
            'table' => 'qyura_city',
            'order_by' => array("city_name", "asc")
        );
        $data['allCity'] = $this->medicart_model->customGet($option);
        $data['title'] = 'Medicart enquiries';
        $this->load->super_admin_template('enquiryListing', $data, 'medicartScript');
    }

    function addOffer() {
        $option = array(
            'select' => 'city_id,city_name',
            'table' => 'qyura_city',
            'order_by' => array("city_name", "asc")
        );
        $data['allCity'] = $this->medicart_model->customGet($option);
        $option = array(
            'select' => 'offerCat_id,offerCat_name',
            'table' => 'qyura_offerCat',
            'where' => array('offerCat_deleted' => 0),
            'order_by' => array("offerCat_name", "asc")
        );
        $data['allOffetCategory'] = $this->medicart_model->customGet($option);
        $data['title'] = 'add Offer';
        $this->load->super_admin_template('addOffer', $data, 'medicartScript');
    }

    function getHospital() {
        //echo "fdadas";exit;
        $cityId = $this->input->post('cityId');
        $hosData = $this->medicart_model->fetchHospital($cityId);

        $hosOption = '';
        $hosOption .='<option value=>Select Hospital</option>';
        if (!empty($hosData)) {
            foreach ($hosData as $key => $val) {
                $hosOption .= '<option value=' . $val->hospital_usersId . '>' . strtoupper($val->hospital_name) . '</option>';
            }
        }
        echo $hosOption;
        exit;
    }

    function getDiagno() {
        //echo "fdadas";exit;
        $cityId = $this->input->post('cityId');
        $diagnoData = $this->medicart_model->fetchDiagnostic($cityId);
        $diOption = '';
        $diOption .='<option value=>Select Diagnostic</option>';
        if (!empty($diagnoData)) {
            foreach ($diagnoData as $key => $val) {
                $diOption .= '<option value=' . $val->diagnostic_usersId . '>' . strtoupper($val->diagnostic_name) . '</option>';
            }
        }
        echo $diOption;
        exit;
    }

    function saveOffer() {

        $this->bf_form_validation->set_rules('medicartOffer_cityId', 'City Name', 'required|trim|numeric');
        $this->bf_form_validation->set_rules('medicartOffer_MIId', 'MI Name', 'required|trim|numeric');
        $this->bf_form_validation->set_rules('medicartOffer_OfferId', 'Offer Id', 'required|trim|is_unique[qyura_medicartOffer.medicartOffer_OfferId]');
        $this->bf_form_validation->set_rules('medicartOffer_offerCategory', 'Offer Caregory', 'required|trim');
        $this->bf_form_validation->set_rules('medicartOffer_title', 'Title', 'required|trim');
        $this->bf_form_validation->set_rules('medicartOffer_description', 'Description', 'required|trim');
        $this->bf_form_validation->set_rules('medicartOffer_allowBooking', 'allow Booking', 'required|trim');
        $this->bf_form_validation->set_rules('medicartOffer_maximumBooking', 'Maximum Booking', 'required|trim|numeric');
        $this->bf_form_validation->set_rules('medicartOffer_startDate', 'Start Date', 'required|trim');
        $this->bf_form_validation->set_rules('medicartOffer_endDate', 'End Date', 'required|trim');
        $this->bf_form_validation->set_rules('medicartOffer_discount', 'Discount', 'required|trim');
        $this->bf_form_validation->set_rules('medicartOffer_ageDiscount', 'Age Discount', 'required|trim');
        $this->bf_form_validation->set_rules('medicartOffer_actualPrice', 'Actual Price', 'required|trim|numeric');
        $this->bf_form_validation->set_rules('medicartOffer_discountPrice', 'Discount Price', 'required|trim|numeric');
        $this->bf_form_validation->set_rules('miType', 'MI Type', 'required|trim');
        if (empty($_FILES['avatar_file']['name'])) {
            $this->bf_form_validation->set_rules('avatar_file', 'File', 'required');
        }
        if ($this->bf_form_validation->run() === False) {
            $option = array(
                'select' => 'city_id,city_name',
                'table' => 'qyura_city',
                'order_by' => array("city_name", "asc")
            );
            $data['allCity'] = $this->medicart_model->customGet($option);
            $option = array(
                'select' => 'offerCat_id,offerCat_name',
                'table' => 'qyura_offerCat',
                'where' => array('offerCat_deleted' => 0),
                'order_by' => array("offerCat_name", "asc")
            );
            $data['allOffetCategory'] = $this->medicart_model->customGet($option);
            $data['title'] = 'add Offer';
            $this->load->super_admin_template('addOffer', $data, 'medicartScript');
        } else {

            $imagesname = '';
            if ($_FILES['avatar_file']['name']) {
                $path = realpath(FCPATH . 'assets/Medicart/');
                $upload_data = $this->input->post('avatar_data');
                $upload_data = json_decode($upload_data);
                $original_imagesname = $this->uploadImageWithThumb($upload_data, 'avatar_file', $path, 'assets/Medicart/', './assets/Medicart/thumb/', 'medicart');

                if (empty($original_imagesname)) {
                    $option = array(
                        'select' => 'city_id,city_name',
                        'table' => 'qyura_city',
                        'order_by' => array("city_name", "asc")
                    );
                    $data['allCity'] = $this->medicart_model->customGet($option);
                    $option = array(
                        'select' => 'offerCat_id,offerCat_name',
                        'table' => 'qyura_offerCat',
                        'where' => array('offerCat_deleted' => 0),
                        'order_by' => array("offerCat_name", "asc")
                    );
                    $data['allOffetCategory'] = $this->medicart_model->customGet($option);
                    $data['title'] = 'add Offer';
                    $this->session->set_flashdata('valid_upload', $this->error_message);
                    $this->load->super_admin_template('addOffer', $data, 'medicartScript');
                    return false;
                } else {
                    $imagesname = $original_imagesname;
                }
            }

            $offerData = array(
                'medicartOffer_MIId' => $this->input->post('medicartOffer_MIId'),
                'medicartOffer_offerCategory' => $this->input->post('medicartOffer_offerCategory'),
                'medicartOffer_title' => $this->input->post('medicartOffer_title'),
                'medicartOffer_image' => $imagesname,
                'medicartOffer_description' => $this->input->post('medicartOffer_description'),
                'medicartOffer_allowBooking' => $this->input->post('medicartOffer_allowBooking'),
                'medicartOffer_maximumBooking' => $this->input->post('medicartOffer_maximumBooking'),
                'medicartOffer_startDate' => strtotime($this->input->post('medicartOffer_startDate')),
                'medicartOffer_endDate' => strtotime($this->input->post('medicartOffer_endDate')),
                'medicartOffer_discount' => $this->input->post('medicartOffer_discount'),
                'medicartOffer_ageDiscount' => $this->input->post('medicartOffer_ageDiscount'),
                'medicartOffer_actualPrice' => $this->input->post('medicartOffer_actualPrice'),
                'medicartOffer_OfferId' => $this->input->post('medicartOffer_OfferId'),
                'medicartOffer_cityId' => $this->input->post('medicartOffer_cityId'),
                'medicartOffer_discountPrice' => $this->input->post('medicartOffer_discountPrice'),
                'medicartOffer_deleted' => 0,
                'creationTime' => strtotime(date("Y-m-d H:i:s")),
                'status' => 1
            );
            $option = array(
                'table' => 'qyura_medicartOffer',
                'data' => $offerData
            );
            $response = $this->medicart_model->customInsert($option);
            if ($response) {
                $this->session->set_flashdata('message', 'Record has been saved successfully!');
                redirect('medicart/addOffer');
            } else {
                $this->session->set_flashdata('error', 'Failed to saved records!');
                redirect('medicart/addOffer');
            }
        }
    }

    function getImageBase64Code($img) {
        $img = str_replace('data:image/png;base64,', '', $img);
        $img = str_replace(' ', '+', $img);
        $img = str_replace('[removed]', '', $img);
        $data = base64_decode($img);
        return $data;
    }

    function check_email() {

        $users_email = $this->input->post('users_email');
        //echo $users_email;exit;
        $email = $this->Ambulance_model->fetchEmail($users_email);
        echo $email;
        exit;
    }

    function editOffer($offerId) {
        $option = array(
            'select' => 'city_id,city_name',
            'table' => 'qyura_city',
            'order_by' => array("city_name", "asc")
        );
        $data['allCity'] = $this->medicart_model->customGet($option);
        $option = array(
            'select' => 'offerCat_id,offerCat_name',
            'table' => 'qyura_offerCat',
            'where' => array('offerCat_deleted' => 0),
            'order_by' => array("offerCat_name", "asc")
        );
        $data['allOffetCategory'] = $this->medicart_model->customGet($option);

        $data['offerData'] = $detailData = $this->medicart_model->getMedDetail($offerId);
        
        $template_option = '';
        
        if($detailData->miType == 1){  // 1 for diagnostic
            
            $cityId = $detailData->medicartOffer_cityId;
            $diagnoData = $this->medicart_model->fetchDiagnostic($cityId);
            
            $template_option .='<option value=>Select Diagnostic</option>';
            if (!empty($diagnoData)) {
                $selected="";
                foreach ($diagnoData as $key => $val) {
                    ($detailData->medicartOffer_MIId == $val->diagnostic_usersId) ? $selected="selected" : $selected=""; 
                    $template_option .= '<option '.$selected.' value=' . $val->diagnostic_usersId . '>' . strtoupper($val->diagnostic_name) . '</option>';
                }
            }
   
        }elseif($detailData->miType == 2){
            
                $cityId = $detailData->medicartOffer_cityId;
                $hosData = $this->medicart_model->fetchHospital($cityId);
                
                $template_option .='<option value=>Select Hospital</option>';
                if (!empty($hosData)) {
                    $selected="";
                    foreach ($hosData as $key => $val) {
                        ($detailData->medicartOffer_MIId == $val->hospital_usersId) ? $selected="selected" : $selected="";
                        $template_option .= '<option '.$selected.' value=' . $val->hospital_usersId . '>' . strtoupper($val->hospital_name) . '</option>';
                    }
                }
        }
        
        $data['options'] = $template_option;
        //echo $template_option;
        
        //dump($data['offerData']);
        //exit();
        
        $data['title'] = 'Edit Offer';
        $this->load->super_admin_template('medicartEditOffer', $data, 'medicartScript');
    }

    function saveEditOffer() {
        
        $id = $this->input->post('offerId');

        $this->bf_form_validation->set_rules('medicartOffer_cityId', 'City Name', 'required|trim|numeric');
        $this->bf_form_validation->set_rules('medicartOffer_MIId', 'MI Name', 'required|trim|numeric');
        $this->bf_form_validation->set_rules('medicartOffer_offerCategory', 'Offer Caregory', 'required|trim');
        $this->bf_form_validation->set_rules('medicartOffer_title', 'Title', 'required|trim');
        $this->bf_form_validation->set_rules('medicartOffer_description', 'Description', 'required|trim');
        $this->bf_form_validation->set_rules('medicartOffer_allowBooking', 'allow Booking', 'required|trim');
        $this->bf_form_validation->set_rules('medicartOffer_maximumBooking', 'Maximum Booking', 'required|trim|numeric');
        $this->bf_form_validation->set_rules('medicartOffer_startDate', 'Start Date', 'required|trim');
        $this->bf_form_validation->set_rules('medicartOffer_endDate', 'End Date', 'required|trim');
        $this->bf_form_validation->set_rules('medicartOffer_discount', 'Discount', 'required|trim');
        $this->bf_form_validation->set_rules('medicartOffer_ageDiscount', 'Age Discount', 'required|trim');
        $this->bf_form_validation->set_rules('medicartOffer_actualPrice', 'Actual Price', 'required|trim|numeric');
        $this->bf_form_validation->set_rules('medicartOffer_discountPrice', 'Discount Price', 'required|trim|numeric');
        $this->bf_form_validation->set_rules('miType', 'MI Type', 'required|trim');
         if ($this->bf_form_validation->run() === False) {
           
              $option = array(
                'select' => 'city_id,city_name',
                'table' => 'qyura_city',
                'order_by' => array("city_name", "asc")
            );
            $data['allCity'] = $this->medicart_model->customGet($option);
            $option = array(
                'select' => 'offerCat_id,offerCat_name',
                'table' => 'qyura_offerCat',
                'where' => array('offerCat_deleted' => 0),
                'order_by' => array("offerCat_name", "asc")
            );
            $data['allOffetCategory'] = $this->medicart_model->customGet($option);
            $data['offerData'] = $this->medicart_model->getMedDetail($id);
            $data['title'] = 'Edit Offer';
            $this->load->super_admin_template('medicartEditOffer', $data, 'medicartScript');
            
        }else{
        
            $imagesname = '';
            if ($_FILES['avatar_file']['name'] && !empty($_FILES['avatar_file']['name'])) {
                $path = realpath(FCPATH . 'assets/Medicart/');
                $upload_data = $this->input->post('avatar_data');
                $upload_data = json_decode($upload_data);
                $original_imagesname = $this->uploadImageWithThumb($upload_data, 'avatar_file', $path, 'assets/Medicart/', './assets/Medicart/thumb/', 'medicart');

                if (empty($original_imagesname)) {
                    $option = array(
                        'select' => 'city_id,city_name',
                        'table' => 'qyura_city',
                        'order_by' => array("city_name", "asc")
                    );
                    $data['allCity'] = $this->medicart_model->customGet($option);
                    $option = array(
                        'select' => 'offerCat_id,offerCat_name',
                        'table' => 'qyura_offerCat',
                        'where' => array('offerCat_deleted' => 0),
                        'order_by' => array("offerCat_name", "asc")
                    );
                    $data['allOffetCategory'] = $this->medicart_model->customGet($option);
                    $data['title'] = 'Edit Offer';
                    $data['offerData'] = $this->medicart_model->getMedDetail($id);
                    $this->session->set_flashdata('valid_upload', $this->error_message);
                    $this->load->super_admin_template('medicartEditOffer', $data, 'medicartScript');
                    return false;
                } else {
                    $imagesname = $original_imagesname;
                }
            }

        $offerData = array(
            'medicartOffer_MIId' => $this->input->post('medicartOffer_MIId'),
            'medicartOffer_offerCategory' => $this->input->post('medicartOffer_offerCategory'),
            'medicartOffer_title' => $this->input->post('medicartOffer_title'),
            'medicartOffer_description' => $this->input->post('medicartOffer_description'),
            'medicartOffer_allowBooking' => $this->input->post('medicartOffer_allowBooking'),
            'medicartOffer_maximumBooking' => $this->input->post('medicartOffer_maximumBooking'),
            'medicartOffer_startDate' => strtotime($this->input->post('medicartOffer_startDate')),
            'medicartOffer_endDate' => strtotime($this->input->post('medicartOffer_endDate')),
            'medicartOffer_discount' => $this->input->post('medicartOffer_discount'),
            'medicartOffer_ageDiscount' => $this->input->post('medicartOffer_ageDiscount'),
            'medicartOffer_actualPrice' => $this->input->post('medicartOffer_actualPrice'),
            //'medicartOffer_OfferId' => $this->input->post('medicartOffer_OfferId'),
            'medicartOffer_cityId' => $this->input->post('medicartOffer_cityId'),
            'medicartOffer_discountPrice' => $this->input->post('medicartOffer_discountPrice'),
            'modifyTime' => strtotime(date("Y-m-d H:i:s"))
        );
        if(!empty($imagesname)){
           $offerData['medicartOffer_image'] = $imagesname;
        }

        $where = array(
            'medicartOffer_id' => $id
        );
        $option = array(
            'table'=> 'qyura_medicartOffer',
            'where' => $where,
            'data'=> $offerData
        );
        $response = $this->medicart_model->customUpdate($option);
        if ($response) {
                $this->session->set_flashdata('message', 'Record has been updated successfully!');
                redirect('medicart/editOffer/'.$id);
            } else {
                $this->session->set_flashdata('error', 'Failed to updated records!');
                redirect('medicart/editOffer/'.$id);
            }
    }
   }

}
