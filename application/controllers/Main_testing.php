<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Main_testing extends CI_Controller {

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
		$this->load->database();
 		$this->load->model('Feed_m','feed');
 		$this->load->helper('alert');
  }

	public function index(){
		$this->blocker();
		$feed['dataFeed'] = $this->feed->get_feed_list();

		$this->load->view('main/common/header');
		$this->load->view('main/pages/main_test', $feed);
		$this->load->view('main/common/footer');
	}

	public function blocker(){// 특정 사용자 이외의 접근을 막음.
		$allowed_ip = '218.145.147.74';// 내 ip 입력하기

		if($this->input->ip_address() !== '218.145.147.74'){
			alert('잘못된 경로입니다.', '/main');
		}
	}


}
