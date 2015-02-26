<?php

class Install extends CI_Controller {
    function __construct() {
        parent::__construct();
        
        $this->load->helper('file');
        $this->load->helper('url');
        
        $this->load->model('missyou/install_model');
    }
    
    //安装
    function index() {
        //读取出所有行
        $lines = file("./application/controllers/missyou/install.sql");
        //echo $lines;
        $sqlstr = "";
        foreach($lines as $line) {
            $line = trim($line);
            if($line != "") {
                if(!($line{0} == "#" || $line{0}.$line{1} == "--")){
                    $sqlstr .= $line;  
                }
            }
        }
        $sqlstr = rtrim($sqlstr, ";");
        $sqls = explode(";", $sqlstr);
        //var_dump($sqls);
        
        //依次运行各条语句
        foreach($sqls as $sql) {
            $this->install_model->run_sql($sql);
            echo '运行语句成功 : ' . $sql . "<br />";
        }
        
        echo '安装完成! <br />';
        
        echo anchor('missyou', '跳到主页');
    }
}

?>
