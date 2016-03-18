<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class SpecialityApi_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function getSpecialityList() {
        
        $this->db->select('specialities_id id, specialities_name name, CONCAT("assets/specialityImages/1x","/",specialities_img) img, creationTime as created');
        $this->db->from('qyura_specialities');
        $this->db->where(array('specialities_deleted' => 0));
        $this->db->order_by('specialities_id', 'ASC');
        return $this->db->get()->result();
        
    }
    
    
   

}

?>
