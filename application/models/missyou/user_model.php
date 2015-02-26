<?php

class User_model extends CI_Model {
    function __construct() {
        parent::__construct();
        $this->load->database();
    }
    
    //注册
    function register($phonenum, $name) {
        $data = array(
            'phonenum' => $phonenum
        );
        
        $query = $this->db->get_where('users', $data);
        if ($query->num_rows() > 0) {
            //已经有此电话号码了
            return FALSE;
        }
        
        $data = array(
            'phonenum' => $phonenum,
            'name' => $name
        );
        
        if($this->db->insert('users', $data)) {
            return $this->db->insert_id();
        } else {
            return FALSE;
        }
    }
    
    //登录
    function login($phonenum) {
        $data = array(
            'phonenum' => $phonenum
        );
        
        $query = $this->db->get_where('users', $data);
        $ret = $query->row_array();
        //var_dump($ret);
        
        return $ret;
    }
    
    //根据手机号查询用户id
    function get_userid_by_phonenum($phonenum) {
        $data = array(
            'phonenum' => $phonenum
        );
        
        $query = $this->db->get_where('users', $data);
        $ret = $query->row_array();
        $userid = FALSE;
        if($ret) {
            $userid = $ret['id'];
        }
        return $userid;
    }
}

?>
