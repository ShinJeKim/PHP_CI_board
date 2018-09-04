<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Test_message extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */

	function __construct(){
    parent::__construct(); // 부모 controller 호출
	  $this->load->database(); // db 로드
		$this->load->model('Message_m','message_m'); // test_message테이블 모델 로드
	}



  public function index(){// 채팅 화면 로드
    $this->load->view('main/common/header');
    $this->load->view('main/pages/test_chat');
	}



  public function send_message(){ // 입력된 메시지 보내기
    // $message = $this->input->get('message', null);
		// $nickname = $this->input->get('nickname', '');
		// $guid = $this->input->get('guid', '');
    //
		// $this->Chat_model->add_message($message, $nickname, $guid);
    //
		// $this->_setOutput($message);


    $message = $this->input->get('message', null);
    $username = $this->input->get('username', '');
     // $username = $this->session->userdata('username');
     print_r($username);
    $guid = $this->input->get('guid', '');

    $this->message_m->set_message($message, $username, $guid);
    $this->set_message_output($message);

  }



  // public function get_messages(){ // 이전 메시지 가져오기
  //   // $timestamp = $this->input->post('timestamp', TRUE);
  //   $timestamp = $this->input->get('timestamp', null);
  //   $messages = $this->message_m->get_messages($timestamp);
  //   $this->set_message_output($messages);
  // }



  public function get_messages(){ // 기존의 메시지를 가져옴
		$timestamp = $this->input->get('timestamp', null); // timestamp를 가져와서
		$messages = $this->message_m->get_messages($timestamp); // timestamp 이후의 메시지를 가져옴
		$this->set_message_output($messages); // 메시지를 대화창에 띄워줌
	}



 public function set_message_output($data){ // 메시지를 대화창에 띄워주기
   /*
   1. Cache control: no-cache  캐쉬가 최신일때에도 실제서버에서 데이터를 직접 받아오고 싶은 경우
   2. must-revalidate  서버에 꼭 유효성 검사를 해야만 함. fresh한 캐쉬데이터만 사용하도록 함.
   3.
   */
   header('Cache-Control: no-cache, must-revalidate'); // 만료된 컨텐츠는 사용하지 못하도록 함.
   header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
   header('Content-type: application/json');
   echo json_encode($data);
 }









}
