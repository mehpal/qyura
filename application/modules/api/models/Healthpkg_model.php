<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Healthpkg_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function getHealpkgDetail($hospitalId,$healthPkgId) {
        $healthPkgId = isset($healthPkgId) ? $healthPkgId : '';
        
        $this->db->select('healthPackage_includesTest');
        $this->db->from('qyura_hospitalPackage');
        $this->db->join('qyura_healthPackage', 'qyura_healthPackage.healthPackage_id = qyura_hospitalPackage.hospitalPackage_healthPackageId', 'left');
      
        $this->db->where(array('hospitalPackage_hospitalId' => $hospitalId, 'healthPackage_deleted' => 0, 'hospitalPackage_healthPackageId' => $healthPkgId));
       // return $this->db->get()->result();
       // echo $this->db->get()->row()->healthPackage_includesTest;
        $response = explode('|',$this->db->get()->row()->healthPackage_includesTest);
        $finalResult = array();
      //  print_r($response); exit;
        if (!empty($response) && $response != '') {
                foreach ($response as $row) {
                $finalTemp = array();
                $finalTemp['testName'] = isset($row) && $row != '' ? $row : "";
                $finalResult[] = $finalTemp;
            }
            return $finalResult;
        } else {
            return $finalResult;
        }
    }
    
    
    public function getHosHelthPkg($hospitalId,$healthPkgId)
    {
        $this->db->select('healthPackage_id,healthPackage_packageTitle,healthPackage_packageId,healthPackage_packageTitle,healthPackage_expiryDateStatus,healthPackage_date,healthPackage_bestPrice,healthPackage_discountedPrice,healthPackage_description,healthPackage_deleted,qyura_healthPackage.modifyTime');
        $this->db->from('qyura_healthPackage');
        $this->db->join('qyura_hospitalPackage','qyura_hospitalPackage.hospitalPackage_healthPackageId = qyura_healthPackage.healthPackage_id', 'left');
        $this->db->where(array('hospitalPackage_hospitalId'=>$hospitalId, 'hospitalPackage_healthPackageId' =>$healthPkgId, 'healthPackage_deleted'=>0));
        $this->db->group_by('healthPackage_id');
        return $this->db->get()->result();
    }

}

?>
