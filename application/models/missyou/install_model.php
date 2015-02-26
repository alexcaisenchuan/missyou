<?php

class Install_model extends CI_Model {
    function __construct() {
        parent::__construct();
        $this->load->database();
    }
    
    function run_sql($sql) {
        $result = $this->db->query($sql);
        var_dump($result);
    }
}

?>
