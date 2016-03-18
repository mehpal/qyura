<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Common_model extends MY_Model {

    public function __construct() {
        parent::__construct();

        $this->load->config('ion_auth', TRUE);
        $this->load->helper('cookie');
        $this->load->helper('date');
        $this->lang->load('ion_auth_api');

        $this->identity_column = $this->config->item('identity', 'ion_auth');
        $this->store_salt = $this->config->item('store_salt', 'ion_auth');
        $this->salt_length = $this->config->item('salt_length', 'ion_auth');
        $this->join = $this->config->item('join', 'ion_auth');


        //initialize hash method options (Bcrypt)
        $this->hash_method = $this->config->item('hash_method', 'ion_auth');
        $this->default_rounds = $this->config->item('default_rounds', 'ion_auth');
        $this->random_rounds = $this->config->item('random_rounds', 'ion_auth');
        $this->min_rounds = $this->config->item('min_rounds', 'ion_auth');
        $this->max_rounds = $this->config->item('max_rounds', 'ion_auth');
    }

    /**
     * Hashes the password to be stored in the database.
     *
     * @return void
     * @author Developer
     * */
    public function hash_password($password, $salt = false) {
        if (empty($password)) {
            return FALSE;
        }

        //bcrypt
        if ($this->hash_method == 'bcrypt') {

            if ($this->random_rounds) {
                $rand = rand($this->min_rounds, $this->max_rounds);
                $rounds = array('rounds' => $rand);
            } else {
                $rounds = array('rounds' => $this->default_rounds);
            }

            $CI = & get_instance();

            $rounds['salt_prefix'] = '$2y$';
            $CI->load->library('frontbcrypt', $rounds);
            return $CI->frontbcrypt->hash($password);
        }


        if ($this->store_salt && $salt) {
            return sha1($password . $salt);
        } else {
            $salt = $this->salt();
            return $salt . substr(sha1($salt . $password), 0, -$this->salt_length);
        }
    }

    /**
     * This function takes a password and validates it
     * against an entry in the users table.
     *
     * @return void
     * @author Mathew
     * */
    public function hash_password_db($id, $password) {
        if (empty($id) || empty($password)) {
            return FALSE;
        }

        // $this->trigger_events('extra_where');

        $query = $this->db->select('users_id,users_password, users_salt')
                ->where('users_id', $id)
                ->limit(1)
                ->get('qyura_users');

        $hash_password_db = $query->row();

        if ($query->num_rows() !== 1) {
            return FALSE;
        }

        // bcrypt
        if ($this->hash_method == 'bcrypt') {
            $CI = & get_instance();
            $CI->load->library('frontbcrypt', null);

            if ($CI->frontbcrypt->verify($password, $hash_password_db->users_password)) {
                return TRUE;
            }
            return FALSE;
        }



        if ($this->store_salt) {
            return sha1($password . $hash_password_db->users_salt);
        } else {
            $salt = substr($hash_password_db->users_password, 0, $this->salt_length);

            return $salt . substr(sha1($salt . $password), 0, -$this->salt_length);
        }
    }

    /**
     * Generates a random salt value.
     *
     * @return void
     * @author developer
     * */
    public function salt() {
        return substr(md5(uniqid(rand(), true)), 0, $this->salt_length);
    }

    /**
     * encrypt value
     *
     * @return void
     * @author developer
     * */
    public function encryptPassword($password) {
        $salt = $this->store_salt ? $this->salt() : FALSE;
        return $this->hash_password($password, $salt);
    }

    /**
     * decript value.
     *
     * @return void
     * @author Mathew
     * */
    public function decryptPassword($id, $password) {

        return $this->hash_password_db($id, $password);
    }

    //Clear session data
    public function clearSessionData() {
        foreach ($this->session->userdata as $sess_var) {
            unset($sess_var);
        }
    }

    //Make the ID encrypted
    public function id_encrypt($str) {
        return $str * 55;
    }

    //Make the ID decrypted
    public function id_decrypt($str) {
        return $str / 55;
    }

    //Password 
    public function password_encrip($str) {
        return $str * 55;
    }

    function fetchStates() {
        $this->db->select('state_id,state_statename');
        $this->db->from('qyura_state');
        $this->db->order_by("state_statename", "asc");
        return $this->db->get()->result();
    }

    function fetchCity($stateId = NULL) {
        $this->db->select('city_id,city_name');
        $this->db->from('qyura_city');
        $this->db->where('city_stateid', $stateId);
        $this->db->order_by("city_name", "asc");
        return $this->db->get()->result();
    }
    
    function getUserRoles($con,$single=true)
    {
        $this->db->select('usersRoles_userId as userId,usersRoles_roleId as roleId,usersRoles_parentRole as parentRole,usersRoles_parentId as parentId');
        $this->db->from('qyura_usersRoles');
        $this->db->where($con);
        if($single)
        return  $this->db->get()->row();
        else
        return  $this->db->get()->result();
    }

}
