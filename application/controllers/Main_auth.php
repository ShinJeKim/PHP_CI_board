<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Main_auth extends CI_Controller {

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
 		$this->load->model('Board_user','user_m');
    $this->load->library('form_validation', 'email');
		$this->load->helper('alert', 'form', 'url', 'date');
  }


	public function signupVali(){ // 회원가입 폼 밸리데이션
		$this->form_validation->set_rules('email', '이메일', 'required|valid_email|is_unique[board_user.user_email]',
																				array(
																					'valid_email'=>'올바른 이메일 주소를 입력해주세요',
																					'is_unique[board_user.user_email]'=>'이미 가입한 이메일주소입니다. 다른 이메일 주소를 입력해주세요.'
																				)
																			);
		// $this->form_validation->set_rules('password', '비밀번호', 'required|min_length[4]|max_length[10]',
		// 																		array(
		// 																			'min_length'=>'{field}는 {param}이상 이어야 합니다.',
		// 																			'max_length'=>'비밀번호는 4-10자리 이어야 합니다.'
		// 																		)
		// 																	);
		// $this->form_validation->set_rules('password_check', '비밀번호 확인', 'required|matches[password]');

		$this->form_validation->set_message('required', '필수항목입니다.');

		// |/^[0-9A-Za-z~!@#$%^&*]{8,12}$
		// , 'regrex_match[/^[a-z]$/]'
		// $this->form_validation->set_rules('pw', '비밀번호', 'required|min_length[4]|max_length[10]');

		return (($this->form_validation->run()) ? TRUE : FALSE);
	}


  public function signUp(){// 회원가입
    if(!empty($this->uri->segment(3))){
 			alert('잘못된 경로입니다.', '/main_testing');
 		}else{
			if($_POST){
				if($this->signupVali()){
	 	 			$email = $this->input->post('email', TRUE);
	 	 			$pw = $this->input->post('password', TRUE);
		 		  $hash_pw = password_hash($pw, PASSWORD_DEFAULT);
		 		  $ip = $this->input->ip_address();

					$arrayData = array(
												'email'=>$email,
												'pw'=>$hash_pw,
												'ip'=>$ip
												);
		 		  $this->user_m->set_userdata($arrayData);

		 		  alert('회원가입에 성공하였습니다. 로그인 해주세요 :)', '/main_auth/login');
				}else{
					// alert('이메일과 암호를 확인해주세요');
					// Todo: alert헬퍼 처리를 하면 CI의 밸리데이션 에러 기능을 사용할 수 없음.
					$this->load->view('main/common/header');
			 		$this->load->view('main/pages/signup');
					$this->load->view('main/common/footer');
				}
		  }else{
		 		$this->load->view('main/common/header');
		 		$this->load->view('main/pages/signup');
				$this->load->view('main/common/footer');
		 	}
  	}
	}

	public function hash_verify($email, $pw){ // hash 암호 검증하기
		$hash = $this->user_m->get_hash_password($email);

		return ((password_verify($pw, $hash)) ? TRUE : FALSE);
	}


	public function login(){// 로그인

		if(!empty($this->uri->segment(3))){
 			alert('잘못된 경로입니다.', '/main_testing');
 		}else{
			if($_POST){
				$email = $this->input->post('email', TRUE);
				$pw = $this->input->post('password', TRUE);
				echo "post";

				if($this->hash_verify($email, $pw)){
					$dataArray = array(
												'email'=>$email,
												'logged_in'=>TRUE
											);

					$this->session->set_userdata($dataArray);
					alert('로그인 되었습니다', '/main_testing');
				}else{
					alert('비밀번호를 확인해주세요', '/main_auth/login');
				}
		  }else{
				$this->load->view('main/common/header');
				$this->load->view('main/pages/login');
				$this->load->view('main/common/footer');
		 	}
  	}


  }



}
