<?php

class Rec_model extends CI_Model {
    function __construct() {
        parent::__construct();
        $this->load->database();
    }
    
    function add($from, $to, $lat = 0, $lon = 0) {
        date_default_timezone_set("PRC");  
        $add_time = date("Y-m-d H:i:s");   //例输出：2010-03-06 11:51:29  
        
        $data = array(
            'from_id' => $from,
            'to_id' => $to,
            'add_time' => $add_time,
            'latitude' => $lat,
            'longitude' => $lon
        );
        
        return $this->db->insert('rec', $data);
    }
    
    function get($from, $to) {
        $data = array(
            'from_id' => $from,
            'to_id' => $to
        );
        
        $query = $this->db->get_where('rec', $data);
        return $query->result_array();
    }
}

?>
