<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Test_auth extends CI_Controller {

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
		$this->load->model('User_m', 'user');
		$this->load->library('form_validation', 'email');
		$this->load->helper('alert', 'form', 'url', 'date');
  }



	public function hash_verify($email, $pw){ // hash 암호 검증하기
		$hash = $this->user->select_hash($email);

		return ((password_verify($pw, $hash)) ? TRUE : FALSE);
	}



	public function signupVali(){ // 회원가입 폼 밸리데이션
		$this->form_validation->set_rules('username', '이름', 'required|max_length[10]');
		$this->form_validation->set_rules('email', '이메일', 'required|valid_email|is_unique[test_user.email]');
		$this->form_validation->set_rules('pw', '비밀번호', 'required|min_length[4]|max_length[10]');
		$this->form_validation->set_rules('pw_check', '비밀번호 확인', 'required|matches[pw]');
		// |/^[0-9A-Za-z~!@#$%^&*]{8,12}$
		// , 'regrex_match[/^[a-z]$/]'
		// $this->form_validation->set_rules('pw', '비밀번호', 'required|min_length[4]|max_length[10]');

		return (($this->form_validation->run()) ? TRUE : FALSE);
	}// signupVali closed



	public function signup(){ // 회원가입
		if(!empty($this->uri->segment(3))){
 			alert('잘못된 경로입니다.', '/testurl');
 		}else{
			if($_POST){
				if($this->signupVali()){
					$name = $this->input->post('username', TRUE);
	 	 			$email = $this->input->post('email', TRUE);
	 	 			$pw = $this->input->post('pw', TRUE);
		 		  $hash_pw = password_hash($pw, PASSWORD_DEFAULT);
					$regdate = date("Y-m-d");
		 		  $ip = $this->input->ip_address();

		 		  $this->user->user_insert($name, $email, $hash_pw, $regdate, $ip);

		 		  alert('회원가입에 성공하였습니다.', '/testurl');
				}else{
					// alert('이메일과 암호를 확인해주세요');
					// Todo: alert헬퍼 처리를 하면 CI의 밸리데이션 에러 기능을 사용할 수 없음.
					$this->load->view('main/common/header');
			 		$this->load->view('main/pages/test_signup');
				}
		  }else{
		 		$this->load->view('main/common/header');
		 		$this->load->view('main/pages/test_signup');
		 	}
		}
	}// signup closed



	public function login(){ // 로그인

		if(!empty($this->uri->segment(3))){
 			alert('잘못된 경로입니다.', '/testurl');
 		}else{
			if($_POST){
						$email = $this->input->post('email', TRUE);
						$pw = $this->input->post('pw', TRUE);
						$username['username'] = $this->user->get_username($email);

						if($this->hash_verify($email, $pw)){
							$dataArray = array(
														'username'=>$username['username'],
														'email'=>$email,
														'logged_in'=>TRUE
													);

							$this->session->set_userdata($dataArray);
							alert('로그인 되었습니다', '/testurl');
						}else{
							alert('비밀번호를 확인해주세요', '/test_auth/login');
						}
			}else{
				$this->load->view('main/common/header');
				$this->load->view('main/pages/test_login');
			}
		}

	}// login closed



	public function logout(){ // 로그아웃
		$this->session->sess_destroy();
		alert('로그아웃 되었습니다', '/testurl');
	}



	public function resetPassword(){ // 비밀번호 찾기

		if(!empty($this->uri->segment(3))){
 			alert('잘못된 경로입니다.', '/testurl');
 		}else{
			// if(isset($_POST['email']) && !empty($_POST['email']))
			if($_POST){
				$this->form_validation->set_rules('email', '이메일주소', 'required|valid_email');

				if($this->form_validation->run() == TRUE){
					$email = $this->input->post('email');
					$email_exists = $this->user->email_exists($email);

					if(!is_null($email_exists)){
						$this->sendResetPasswordEmail($email);
						alert('작성하신 이메일로 비밀번호 변경 링크가 발송되었습니다.', '/testurl');
					}else{
						alert('존재하지 않는 이메일입니다.', '/test_auth/resetPassword');
					}

				}else{
					// alert('유효한 이메일 주소를 입력해주세요.', '/test_auth/resetPassword');
					$this->load->view('main/common/header');
					$this->load->view('main/pages/test_send_email');
				}

			}else{
				$this->load->view('main/common/header');
				$this->load->view('main/pages/test_send_email');
			}

		}

	}// resetPassword closed




	public function sendResetPasswordEmail($email){ // 비밀번호 변경을 위한 이메일 보내기
		$this->load->library('email');
		$this->load->helper('url');
		$idx = $this->user->get_idx($email);

		$email_code = hash('md5', $email.'theteamsjjang');
		// Todo: 이메일값 안넘어가는 임시 해결책(비밀번호 업데이트 확인되면 바꿀것)
		// 복호화 가능한 암호화를 걸어 idx를 a링크에 넘긴다...
		// $idx_code = 암호화하기
		$this->email->from('board@board.com', 'Board Admin');
		$this->email->to($email);
		$this->email->subject('비밀번호를 변경해주세요.');

		$message = '<!DOCTYPE html><head><meta charset="UTF-8"></head><body>';
		//$message .= '<form method="post" action="/test_auth/resetPasswordForm">';
		$message .= '<div class="control-group" style="margin-top: 10px;">';
		$message .= '<input type="hidden" id="email" name="email" value='.$email.' >';
		//$message .= '<a href="http://www.seoulcodes.com/test_auth/getNewPassword/'.$email_code.'/'.$idx.'">';
		//$message .= '<button type="submit" id="submitBtn" class="btn btn-primary">비밀번호 변경하기</button></a></div>';
		$message .= '<p>비밀번호를 변경하려면<strong><a href="http://www.seoulcodes.com/test_auth/getNewPassword/'.$email_code.'/'.$idx.'">여기</a></strong>를 클릭해주세요.</p>';
		//$message .= '</form>';
		$message .= '</body></html>';
		// $message .= '<script>';
		// $message .=	'$(document).ready(function(){
		// 							var email = $("#email").val();
		// 							$("#submitBtn").click(function(){
		// 								$.ajax({
		// 						      url:"/test_auth/resetPasswordForm",
		// 						      method:"POST",
		// 						      data: {email: email},
		// 						      dataType:"JSON",
		// 						      success:function(data){
		// 						      },
		// 						      error: function(request, status, error){
		// 						      }
		// 						    })
		// 							});
		// 						});';
		// $message .= '</script>';

		$this->email->message($message);// 이메일 내용에 메시지를 넣음
		$this->email->send(); // 이메일 발송
		$this->user->set_send_email_time($email, date("Y-m-d h:i:s")); // 이메일 발송 시각을 저장
	}// sendResetPasswordEmail closed



		// // 새로운 비밀번호 저장하기
		// // 발송된 이메일 폼 데이터의 이메일을 받아옴
		// public function resetPasswordForm(){
		// 	$email = $this->input->post('email', TRUE); // 이메일 폼에서 넘어온 email 데이터
		//
		// 	return $email;
		// 	// $pw = $this->getNewPassword($hash_email);
		// 	// $hash_pw = password_hash($pw, PASSWORD_DEFAULT);
		// 	// $update = $this->user->update_password($email, $hash_pw);
		// 	//
		// 	// if($update){
		// 	// 	alert('새로운 비밀번호가 저장되었습니다. 로그인해주세요 :)', 'testurl');
		// 	// }else{
		// 	// 	alert('비밀번호를 확인해주세요', 'test_auth/resetPasswordForm/'.$hash_email.'');
		// 	// }
		// }



	// 뷰에서 변경된 패스워드를 받아오기
	public function getNewPassword(){
		$this->load->library('form_validation');
		$hash_email['hash_email'] = $this->uri->segment(3); // uri의 hash 암호화된 이메일
		$idx = $this->uri->segment(4);

		$current_time = date("Y-m-d h:i:s");
		$send_email_time = $this->user->get_send_email_time($idx);
		$minus_time = strtotime($current_time)-strtotime($send_email_time);
		$calculate_time = ceil($minus_time/60);

		if(is_null($hash_email)){
			alert('잘못된 경로로 들어오셨습니다.'.$hash_email, '../testurl');
		}else if($calculate_time > 30 ){
			alert('링크가 만료되었습니다. 이메일을 한 번 더 입력해주세요 :)', '/test_auth/resetPassword');
		}else{
			if(!$_POST){
				$this->load->view('main/common/header');
				$this->load->view('main/pages/test_reset_pw', $hash_email);
			}else{
				$idx = $this->input->post('idx', TRUE);
				$pw = $this->input->post('password', TRUE);
				$pw_check = $this->input->post('password_check', TRUE);
				$hash_email = $this->input->post('hash_email', TRUE);
				$hash_pw = password_hash($pw, PASSWORD_DEFAULT);
				$old_pw = $this->user->get_old_password($idx);

				$validation = $this->verifyResetPassword($pw, $pw_check);

				// if($validation ==  FALSE){
				// 	alert('비밀번호를 다시 한 번 확인해주세요.', '/test_auth/getNewPassword/'.$hash_email);
				//
				// 	// $this->load->view('main/common/header');
				// 	// $this->load->view('main/pages/test_reset_pw', $hash_email);
				// }else{
					$result = password_verify($pw, $old_pw);
					if($result){
						alert('기존 비밀번호와 다른 비밀번호를 입력해야 합니다.', '/test_auth/getNewPassword/'.$hash_email);
					}else{
						$this->user->update_password($idx, $hash_pw);
						alert('비밀번호가 성공적으로 변경되었습니다. 로그인해주세요 :)', '/testurl');
					}
					//	}
				}
			}
	}// getNewPassword closed



		// // 변경한 패스워드 밸리데이션
		// public function verifyResetPassword($pw, $pw_check){
		//
		// 	$this->form_validation->set_rules('password', '새로운 비밀번호', 'required|min_length[4]|max_length[10]');
		// 	$this->form_validation->set_rules('password_check', '비밀번호 확인', 'required|matches[pw]');
		//
		// 	 return (($this->form_validation->run()) ? TRUE : FALSE);
		// }// verifyResetPassword closed






		// // 뷰에서 변경된 패스워드를 받아옴
		// public function getNewPassword(){
		// 	$this->load->library('form_validation');
		// 	$hash_email = $this->uri->segment(3); // uri의 hash 암호화된 이메일
		// 	$email = $this->resetPasswordForm();
		//
		// 	if($hash_email == ''){
		// 		alert('잘못된 경로로 들어오셨습니다.', '../testurl');
		// 	}else{
		// 		if($_POST){
		// 			$pw = $this->input->post('password', TRUE);
		// 			$pw_check = $this->input->post('password_check', TRUE);
		// 			$this->resetPasswordForm($pw);
		// 			return $pw;
		// 			$this->form_validation->set_rules('pw', '비밀번호', array('required', 'min_length[4]', 'max_length[10]'));
		// 			$this->form_validation->set_rules('pw_check', '비밀번호 확인', 'required|matches[pw]');
		//
		// 			if($this->form_validation->run() == TRUE){
		// 				return $pw;
		// 			}else{
		// 				alert('비밀번호를 확인해주세요', 'test_auth/getNewPassword/'.$hash_email.'');
		// 			}
		// 		}else{
		// 			$this->load->view('main/common/header');
		// 			$this->load->view('main/pages/test_reset_pw');
		// 		}
		// 	}
		// }




	// // 새로운 비밀번호 저장하기
	// // reset_password_form
	// public function resetPasswordForm(){
	//
	// 	$hash_email = $this->uri->segment(3); // uri의 hash 암호화된 이메일
	// 	if($hash_email == ''){
	// 		alert('잘못된 경로로 들어오셨습니다.', 'testurl');
	// 	}else{
	// 		$email = $this->input->post('email', TRUE); // 이메일 폼에서 넘어온 email 데이터
	//
	// 		$this->load->view('main/common/header');
	// 		$this->load->view('main/pages/test_reset_pw');
	//
	// 		if($_POST['password']){
	// 			$pw = $this->input->post('password', TRUE);
	// 			$pw_check = $this->input->post('password_check', TRUE);
	//
	// 			$validation = $this->verifyResetPassword($pw, $pw_check);
	//
	// 				if($validation){
	// 					$hash_pw = password_hash($pw, PASSWORD_DEFAULT);
	// 					$this->user->update_password($email, $hash_pw);
	// 				}else{
	// 					alert('비밀번호를 확인해주세요', 'test_auth/resetPasswordForm/'.$hash_email.'');
	// 				}
	// 		}else{
	// 			alert('비밀번호를 다시 입력해주세요', 'test_auth/resetPasswordForm/'.$hash_email.'');
	// 		}
	// 	}
	// }












}
