<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Testurl extends CI_Controller {

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
 		 $this->load->model('Board_m','board'); // Board_m 모델을 board에 로드
		 $this->load->library(array('form_validation', 'pagination'));
		 $this->load->helper(array('form', 'url', 'alert', 'date'));
   }


	 // public function index(){
		// $this->load->database(); // db 로드
		// $this->load->model('Board_m','board'); // Board_m 모델을 board에 로드
   //  $this->load->library('pagination'); // 페이지네이션 라이브러리 로드
	 //
   //  // 페이지네이션 설정
		// $config = array();
   //  $config['base_url'] = 'http://www.seoulcodes.com/testurl';
   //  $config['total_rows'] = $this->board->count_list();
		// //$config['use_page_numbers'] = TRUE;
		// //$config['page_query_string'] = TRUE;
		// $config['num_links'] = 3;
   //  $config['per_page'] = 10;
		// $config['uri_segment'] = 2;
		// // $config['first_link'] = 'First';
		// // $config['last_link'] = 'Last';
	 //
   //  $this->pagination->initialize($config);
		// if($this->uri->segment(2)){
		// 	$page = ($this->uri->segment(2));
		// }
		// else{
		// 	$page = 0;
		// }
		// // $page = $this->uri->segment(2,0);
   //  $board['dataList'] = $this->board->fetch_board_list($config['per_page'], $page);
		// $board['links'] = $this->pagination->create_links();
	 //
   //  // 데이터를 'dataList'로 뷰에 넘겨준다
   //  // $board['dataList'] = $this->board->get_board_list();
	 //
		// $this->load->view('main/common/header');
		// $this->load->view('main/pages/test', $board);
		// $this->load->view('main/common/footer');
	 // }


	 // public function index()
 		// {
		// 	$total_row = $this->board->count_list();
	 //
		// 	// 페이지네이션 설정
		// 	$config = array();
	 //    $config['base_url'] = 'http://www.seoulcodes.com/testurl/';
	 //    $config['total_rows'] = $total_row;
		// 	$config['use_page_numbers'] = TRUE;
		// 	$config['page_query_string'] = TRUE;
		// 	$config['num_links'] = 3;
	 //    $config['per_page'] = 10;
		// 	$config['uri_segment'] = 2;
		// 	// $config['first_link'] = 'First';
		// 	// $config['last_link'] = 'Last';
	 //
	 //    $this->pagination->initialize($config);
	 //
		// 	// $page = ($this->uri->segment(2)) ? $this->uri->segment(2) : 0;
	 //
		// 	if($this->uri->segment(2)){
		// 		$page = ($this->uri->segment(2));
		// 	}
		// 	else{
		// 		$page = 1;
		// 	}
	 //
		// 	// $page = $this->uri->segment(2,0);
	 //
		// 	// 데이터를 'dataList'로 뷰에 넘겨준다
	 //    $data['dataList'] = $this->board->fetch_board_list($config['per_page'], $page);
		// 	$data['links'] = $this->pagination->create_links();
	 //
	 //    // $board['dataList'] = $this->board->get_board_list();
	 //
		// 	$this->load->view('main/common/header');
		// 	$this->load->view('main/pages/test', $data);
		// 	$this->load->view('main/common/footer');
 		// }


	public function index()
	{
		$this->boardList();
	}



	// board list 가져오기
	public function boardList()
	{
    // 페이지네이션 설정
		$config = array();
    $config['base_url'] = 'http://www.seoulcodes.com/testurl/boardlist/';
    $config['total_rows'] = $this->board->count_list();
		//$config['use_page_numbers'] = TRUE;
		//$config['enable_query_strings']= TRUE;
		$config['num_links'] = 3;
    $config['per_page'] = 10;
		$config['uri_segment'] = 3;
		// $config['first_link'] = 'First';
		// $config['last_link'] = 'Last';

    $this->pagination->initialize($config);

		if($this->uri->segment(3)){
			$page = ($this->uri->segment(3));
		}
		else{
			$page = 0;
		}


		$page = $this->uri->segment(3,0);
    $board['dataList'] = $this->board->fetch_board_list($config['per_page'], $page);
		// $board['dataList'] = $this->board->fetch_board_list(10, $page);
		$board['links'] = $this->pagination->create_links();

		$this->load->view('main/common/header');
		$this->load->view('main/pages/test', $board);
	}// boardList closed



	// 작성한 새 글 insert
	public function write(){

		if(!empty($this->uri->segment(3))){
			alert('잘못된 경로입니다.', '/testurl');
		}else{
			if(!$this->session->userdata('logged_in')){
				alert('로그인 후에 작성 가능합니다. ', '../../test_auth/login');
			}else{
				if($_POST){
					$this->form_validation->set_rules('title', '제목', 'required|max_length[25]');
					$this->form_validation->set_rules('content', '내용', 'required');

					if ($this->form_validation->run() == FALSE){
						$this->load->view('main/common/header');
						$this->load->view('main/pages/test_c');
					}else{
						$title = $this->input->post('title', TRUE);
						$content = $this->input->post('content', TRUE);
						$email = $this->session->userdata('email');
						$ip = $this->input->ip_address();

						$this->board->board_insert($title, $content, $email, $ip);
						$this->board->board_idx_update();
						alert('글이 성공적으로 등록되었습니다', '/testurl');
						exit;
					}
				}
				else
				{
					$this->load->view('main/common/header');
					$this->load->view('main/pages/test_c');
				}
			}
		}
	}// write closed



	public function read(){ // 글 제목 클릭시 글 내용 읽어오기

		if(!empty($this->uri->segment(4))){ // uri에 지정되지 않은 segment가 있을 경우 접근 차단
			alert('잘못된 경로입니다.', '/testurl');
		}	else {
			$idx = $this->uri->segment(3);// uri에서 idx 가져오기
			$data['itemList'] = $this->board->board_select($idx);

			if (empty($data['itemList'])) {// uri에 지정된 글 idx가 아닌 값이 들어올 경우 접근 차단
				alert('잘못된 경로입니다.', '/testurl');
				exit;
			}
			$delete_flag = $this->board->get_delete_flag($idx);// 삭제된 글 구분 위해 delete_flag값 가져옴

			if($delete_flag == 1){// 1이면 삭제된 게시물, 0이면 존재하는 게시물
				alert('해당 게시물은 삭제되었습니다.', '/testurl');
			}else{
				//$board_id = $this->input->post('board_id', TRUE);
				$this->board->count_hits($idx);// 조회수 증가

				$this->load->view('main/common/header');
				$this->load->view('main/pages/test_r', $data);
			}

		}

	}// read closed



	public function updateView(){ // 글 수정을 위한 view 호출

		if(!empty($this->uri->segment(4))){
			alert('잘못된 경로입니다.', '/testurl');
		}else{
			$idx = $this->uri->segment(3);
			$data['itemList'] = $this->board->board_select($idx);

			if(empty($data['itemList'])){
				alert('잘못된 경로입니다.', '/testurl');
				exit;
			}

			if(!$this->session->userdata('logged_in')){
				alert('로그인 후에 수정 가능합니다. ', '../../test_auth/login');
			}else{
				$read_email = $this->board->email_select($idx);
				$sess_email = $this->session->userdata('email');

				if($read_email == $sess_email){
					$this->load->view('main/common/header');
					$this->load->view('main/pages/test_ud', $data);
				}else{
					// alert('글 작성자만 수정가능합니다.', '../'); // Todo: 글 뷰로 이동시키면 history에 글 뷰가 2개 들어가게 되어서 뒤로가기 버튼 2번 눌러야 함.
					alert('글 작성자만 수정가능합니다.', '/testurl/read/'.$idx);
				}

			}
		}

	}// updateView closed



	public function update(){// 글 수정 update

		if($_POST){
			$this->form_validation->set_rules('title', '제목', 'required|max_length[25]');
			$this->form_validation->set_rules('content', '내용', 'required');

			if ($this->form_validation->run() == FALSE){
				$this->load->view('main/common/header');
				$this->load->view('main/pages/test_c');
			}else{
				$idx = $this->input->post('idx', TRUE);
				$title = $this->input->post('title', TRUE);
				$content = $this->input->post('content', TRUE);

				$this->board->board_update($idx, $title, $content);

				alert('글이 성공적으로 수정되었습니다', '/testurl');
			}

		}else{
			$this->load->view('main/common/header');
			$this->load->view('main/pages/test_ud');
		}
	}// update closed



	public function delete(){// 글 삭제 delete

		$idx = $this->uri->segment(3);// url에서 idx 가져오기
		$deleted_title = '해당 게시물은 작성자에 의해 삭제되었습니다.';
		$deleted_content = '해당 게시물은 작성자에 의해 삭제되었습니다.';

		if(!empty($idx)){
			$parent_idx = $this->board->get_parent_idx($idx);

			if($idx == $parent_idx){//본인 idx와 부모 idx가 동일하면 원글.
				$this->board->delete_have_reply($idx, $deleted_title, $deleted_content);
				alert('게시물이 성공적으로 삭제되었습니다. ', '/testurl');
			}else{
				// 대댓글인지 판단하기 위해 group_order_second 값을 가져옴.
				$group_order_second = $this->board->get_group_order_second($idx);

				if($group_order_second > 0){// 대댓글일 경우(답글의 답글)
					$this->board->board_delete($idx);
					alert('게시물이 성공적으로 삭제되었습니다. ', '/testurl');
				}else{// 답글일 경우(원글의 답글)
					$group_order_first = $this->board->get_group_order_first($idx);
					$count_second_reply = $this->board->count_second_reply($parent_idx, $group_order_first);

					// 대댓글이 있는 답글일 경우
					// (parent_idx && group_order_first가 동일한 row가 2개 이상일 떄)
					if($count_second_reply>1){
						$this->board->delete_have_reply($idx, $deleted_title, $deleted_content);
						alert('게시물이 성공적으로 삭제되었습니다. ', '/testurl');
					}else{
						// 대댓글이 없는 답글일 경우. 바로 삭제.
						$this->board->board_delete($idx);
						alert('게시물이 성공적으로 삭제되었습니다. ', '/testurl');
					}
				}
			}

		}else{
			alert('삭제에 실패하였습니다. ', '/testurl');
		}

	}// delete closed



	public function writeReply(){// 계층형 답글 insert

		if(!$this->session->userdata('logged_in')){
			alert('로그인 후에 작성 가능합니다. ', '../../test_auth/login');
		}else{
			if(!empty($this->uri->segment(4))){
				alert('잘못된 경로입니다.', '/testurl');
			}else{
				if($_POST){
					// idx를 가져와서 parent_idx로 넣는다
					$parent_idx = $this->input->post('idx', TRUE);
					$title = $this->input->post('title', TRUE);
					$content = $this->input->post('content', TRUE);
					$email = $this->session->userdata('email');
					$ip = $this->input->ip_address();

					$get_parent_idx = $this->board->get_parent_idx($parent_idx);

					// 댓글인지 대댓글인지 구분하기 위해 원글의 idx값과 parent_idx값을 비교
					if($get_parent_idx == $parent_idx){
					//if) 원글의 idx와 parent_idx가 같으면 일반 댓글 insert

						$group_order_first = 1;
						$group_order_second = 0;

						// 댓글의 parent_idx에 원글의 idx(parent_idx)를 넣음
						$this->board->board_reply_insert($parent_idx,
																							$group_order_first,
																							$group_order_second,
																							$title,
																							$content,
																							$email,
																							$ip);

						// $this->board->insert_parent_idx($parent_idx);

						// 1. 동일한 parent_idx를 가지고 있는 row를 찾아서
						// 2. group_order_first>0 인 것에 모두 +1
						$this->board->first_ord_update($parent_idx);

						alert('답글이 성공적으로 등록되었습니다.', '/testurl');

					}else{ // 원글의 idx와 parent_idx가 다르면 대댓글 insert

						// 1. 대댓글의 parent_idx에 원글(댓글)의 parent_idx를 insert 해야 함
						// 2. 원글의 idx를 사용하여, 원글(댓글)의 group_order_first값을 가져와야 함
						// 3. 대댓글의 group_order_first에 원글(댓글)의 group_order_first를 insert해야 함
						// 4. 대댓글의 parent_idx와 group_order_first가 같은 row를 찾아서,
						//		해당 row의 group_order_second를 +1 해준다. 끝.

						$group_order_first = $this->board->get_group_order_first($parent_idx);
						$group_order_second = 1;
						$this->board->board_reply_insert($get_parent_idx,
																							$group_order_first,
																							$group_order_second,
																							$title,
																							$content,
																							$email,
																							$ip);

						$this->board->second_ord_update($get_parent_idx, $group_order_first);

						alert('답글이 성공적으로 등록되었습니다.', '/testurl');
					}
				}else{
					$this->load->view('main/common/header');
					$this->load->view('main/pages/test_reply_r');
				}
			}

		}

	}// writeReply closed



}// Testurl Class closed
