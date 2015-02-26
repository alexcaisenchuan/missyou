<?php

// 合法的常量名
define("REC_ADD", 1);       //添加记录
define("REC_FROM_ME", 2);   //查询发自我的
define("REC_TO_ME", 3);     //查询发给我的
    
class Action extends CI_Controller {
    public function __construct() {
        parent::__construct();
        
        $this->load->helper('form');
        $this->load->helper('url');
        
        $this->load->library('session');
        $this->load->library('form_validation');
        $this->load->library('session');
        
        $this->load->model('missyou/rec_model');
        $this->load->model('missyou/user_model');
    }
    
    //首页
    public function index() {
        $data['title'] = '首页';
        
        if ($this->_isLogin()) {
            //已经登录，跳转到首页
            redirect('/missyou/action/main_page', 'refresh');
        } else {
            //没有登录，显示登录页
            $this->load->view('templates/header', $data);
            $this->load->view('missyou/index');
            $this->load->view('templates/footer');
        }
    }
    
    //登录
    public function login() {
        $phonenum = $this->input->post('phonenum');
        
        $this->form_validation->set_rules('phonenum', '手机号', 'required');
        
        if ($this->form_validation->run() === FALSE) {
            //登录失败，回到首页
            $this->index();
        } else {
            $ret = $this->user_model->login($phonenum);
            if ($ret) {
                //保存session数据
                $userid = $ret['id'];
                $name = $ret['name'];
                $this->_save_session($phonenum, $name, $userid);
                
                //登录成功，跳转主页
                redirect('/missyou/action/main_page', 'refresh');
            } else {
                //登录失败，回到首页
                $this->index();
            }
        }
    }
    
    //登出
    public function logout() {
        $this->_unset_session();
        redirect('/missyou/action/index', 'refresh');
    }

    //注册
    public function register() {
        $phonenum = $this->input->post('phonenum');
        $name = $this->input->post('name');
        
        $this->form_validation->set_rules('phonenum', '手机号', 'required');
        $this->form_validation->set_rules('name', '名字', 'required');
        
        if ($this->form_validation->run() === FALSE) {
            //注册失败，返回首页
            $this->index();
        } else {
            $userid = $this->user_model->register($phonenum, $name);
            if ($userid) {
                //保存session数据
                $this->_save_session($phonenum, $name, $userid);
           
                //注册成功，跳转主页
                redirect('/missyou/action/main_page', 'refresh');
            } else {
                //注册失败，返回首页
                $this->index('注册失败');
            }
        }
    }

    //主界面
    public function main_page($hint = '') {
        if ($this->_isLogin()) {
            $data['title'] = '主页';
            $data['hint'] = $hint;
            $session_data = $this->_get_all_session();
            $data['name'] = $session_data['name'];
            //var_dump($session_data);

            $this->load->view('templates/header', $data);
            $this->load->view('missyou/main_page', $data);
            $this->load->view('templates/footer');
        } else {
            //没登陆，返回首页
            redirect('/missyou/action/index', 'refresh');
        }
    }
    
    //添加记录
    public function add() {
        $this->recAction(REC_ADD);
    }
    
    //我发出去的
    public function fromMe() {
        $this->recAction(REC_FROM_ME);
    }

    //我收到的
    public function toMe() {
        $this->recAction(REC_TO_ME);
    }
    
    //读取记录
    public function recAction($action) {
        $phonenum = $this->input->post('phonenum');       //指向目标用户
        $this->form_validation->set_rules('phonenum', '手机号', 'required');
        
        if ($this->form_validation->run() === FALSE) {
            //返回主页
            $this->main_page('数据错误');
        } else {
            $curr_userid = $this->_get_curr_userid();         //发自当前用户
            $dest_userid = $this->user_model->get_userid_by_phonenum($phonenum);
            
            $hint = FALSE;
            if ($curr_userid === FALSE) {
                //错误
                $hint = '请登录';
            } else if ($dest_userid === FALSE) {
                //错误
                $hint = '找不到用户';
            } else if ($dest_userid == $curr_userid) {
                //不能发给自己
                $hint = '不能是同一个人';
            } else {
                //成功
                if($action == REC_ADD) {
                    $hint = '添加成功';
                    $ret = $this->rec_model->add($curr_userid, $dest_userid);
                } else if($action == REC_FROM_ME) {
                    $ret = $this->rec_model->get($curr_userid, $dest_userid);
                    $this->show($ret);
                } else if($action == REC_TO_ME) {
                    $ret = $this->rec_model->get($dest_userid, $curr_userid);
                    $this->show($ret);
                } else {
                    $hint = '未知操作' . $action;
                }
            }
            
            if ($hint != FALSE) {
                //主页
                $this->main_page($hint);
            }
        }
    }

    //展示页
    public function show($show_data) {
        $data['title'] = '展示页';
        $data['data'] = $show_data;
        
        $this->load->view('templates/header', $data);
        $this->load->view('missyou/show', $data);
        $this->load->view('templates/footer');
    }
    
    //保存Session数据
    private function _save_session($phonenum, $name, $userid) {
        $newdata = array(
            'phonenum'  => $phonenum,
            'name'      => $name,
            'userid'    => $userid,
            'logged_in' => TRUE
        );
        
        //var_dump($newdata);
        
        $this->session->set_userdata($newdata);
    }
    
    //删除所有session数据
    private function _unset_session() {
        $array_items = array(
            'phonenum'  => '',
            'name'      => '',
            'userid'    => '',
            'logged_in' => ''
        );
        $this->session->unset_userdata($array_items);
    }
    
    //读取所有session数据
    private function _get_all_session() {
        return $this->session->all_userdata();
    }
    
    //获取当前登录的用户id
    private function _get_curr_userid() {
        return $this->session->userdata('userid');
    }

    //是否已经登录
    private function _isLogin() {
        $login = $this->session->userdata('logged_in');
        return $login;
    }
}

?>
