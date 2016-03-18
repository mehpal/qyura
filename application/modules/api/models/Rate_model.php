<?php

if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Rate_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

   public function addRate($table,$data)
   {
        $data = $this->_filter_data($table, $data);

        $this->db->insert($table, $data);

        $id = $this->db->insert_id();
        
        return $id;
   }
   
   /**
     * rating_check
     *
     * @return bool
     
     * */
    public function rating_check($where = '') {
        

        if (empty($where)) {
            return FALSE;
        }

        

        return $this->db->where($where)
                        ->order_by("rating_id", "ASC")
                        ->limit(1)
                        ->count_all_results('qyura_ratings') > 0;
    }
}
?>
