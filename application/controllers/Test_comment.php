<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Test_comment extends CI_Controller {

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
		$this->load->model('Comment_m','comment_m');
	}



	public function add_comment(){ // 코멘트 등록

	  $board_id = $this->input->post('board_id');
		$comment_name = $this->session->userdata('username');
	  $comment_email = $this->session->userdata('email');
	  $comment_body = $this->input->post('comment_body');
	  $comment_ip = $this->input->ip_address();

	  $this->comment_m->comment_insert($board_id,
	                                  $comment_name,
	                                  $comment_email,
	                                  $comment_body,
	                                  $comment_ip
	                                 );
	}// add_comment closed



	public function fetch_comment(){ // 코멘트 가져오기
	  $board_id = $this->input->post('board_id'); // ajax로 board_id(게시글 idx)를 가져옴
	  $result = $this->comment_m->comment_select($board_id); // 해당 게시글에 달린 코멘트를 받아옴

	  $output = '';

		foreach($result as $row)
		{
			// $comment_name = json_encode($row->comment_name, JSON_UNESCAPED_UNICODE);
			// $comment_date = json_encode($row->comment_date);
			// $comment_body = json_encode($row->comment_body, JSON_UNESCAPED_UNICODE);
			// $comment_id = json_encode($row->comment_id);

			$comment_name = $row->comment_name;
			$comment_date = $row->comment_date;
			$comment_body = $row->comment_body;
			$comment_id = $row->comment_id;

		 	$session_username = $this->session->userdata('username');
		 	//$username = $row->comment_name;
			if($session_username == $comment_name){ // 로그인 했으면 코멘트 입력란에 닉네임 보여줌
				$output .= '
				<div class="panel panel-default">
					<div class="panel-heading" > By <b>'.$comment_name.'</b> on <i>'.$comment_date.'</i></div>
					<input type="hidden" name="comment_name" value='.$comment_name.'>
					<input type="hidden" name="comment_id" value='.$comment_id.'>
					<div class="panel-body">'.$comment_body.'</div>
					<div class="panel-footer" style="text-align:right;">
						<button id="modify_btn_'.$comment_id.'" name="btn_modify" class="btn" value='.$comment_id.'>수정</button>
						<button id="delete_btn_'.$comment_id.'" name="delete_btn" class="btn" value='.$comment_id.'>삭제</button>
					</div>
				</div>
				';
			}else{ // 로그인 안했으면 코멘트 입력란의 닉네임 없음
				$output .= '
        <div class="panel panel-default">
          <div class="panel-heading">By <b>'.$comment_name.'</b> on <i>'.$comment_date.'</i></div>
					<input type="hidden" name="comment_name" value="'.$comment_name.'">
          <div class="panel-body">'.$comment_body.'</div>
        </div>
        ';
			}
		}


		$output .= '
		<script>

		$("[id^=modify]").on("click", function(event){ // 수정버튼 클릭시 해당 value값을 가져옴
			var clicked_comment_id = $(this).attr("value");
			console.log("clicked= "+clicked_comment_id);

			$.ajax({
						url:"/test_comment/add_update_comment",
						method:"POST",
						data: {clicked_comment_id: clicked_comment_id},
						dataType:"JSON",
						success:function(data){
							console.log("success  clicked_comment_id= "+clicked_comment_id);

							$("#display_comment").html(data);
						},
						error: function(request, status, error){
							alert("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
						}
					})
		});



		$("[id^=delete]").on("click", function(event){ // 삭제버튼 클릭시 해당 value값을 가져옴
			var clicked_comment_id = $(this).attr("value");
			console.log("clicked= "+clicked_comment_id);
		});


		</script>
		';

		// $("button[name="btn_modify"]").click(function(){ // 코멘트 수정버튼 클릭시
		// 	console.log("sdf");
		//
		// 	var clicked_comment_id = $(this).attr("value");
		// 	console.log("clicked= "+clicked_comment_id);
		//
		// 	$.ajax({
		// 			url:"/test_comment/add_update_comment",
		// 			method:"POST",
		// 			data: {clicked_comment_id: clicked_comment_id},
		// 			dataType:"JSON",
		// 			success:function(data){
		// 				console.log("success  clicked_comment_id= "+clicked_comment_id);
		//
		// 				$("#display_comment").html(data);
		// 			},
		// 			error: function(request, status, error){
		// 				alert("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
		// 			}
		// 		})
		// }); // btn_modify click function closed

	  // echo json_encode($output, JSON_UNESCAPED_UNICODE); // echo $output 을 했을 때에 인코딩 에러가 났음.
		echo json_encode($output, JSON_UNESCAPED_UNICODE);


		// foreach($result as $row)
	  //  {
    //   $comment_name = $row->comment_name;
    //   $comment_date = $row->comment_date;
    //   $comment_body = $row->comment_body;
    //   $comment_id = $row->comment_id;
		// 	//$session_username = $this->session->userdata('username');
		// 	$username = $row->comment_name;
		//
		// 	$comment_array = array(
		// 										$comment_name,
		// 										$comment_date,
		// 										$comment_body,
		// 										$comment_id
		// 									);
		//
		// }
		// echo $comment_array;

	}// fetch_comment closed



public function add_update_comment(){// 코멘트 수정 뷰 보여주기

	$board_id = $this->input->post('board_id');
	$result = $this->comment_m->comment_select($board_id);
	$clicked_comment_id = $this->input->post('clicked_comment_id');

	$output = '';
	foreach($result as $row)
	 {
		// $comment_name = json_encode($row->comment_name, JSON_UNESCAPED_UNICODE);
		// $comment_date = json_encode($row->comment_date);
		// $comment_body = json_encode($row->comment_body, JSON_UNESCAPED_UNICODE);
		// $comment_id = json_encode($row->comment_id);

		$comment_name = $row->comment_name;
		$comment_date = $row->comment_date;
		$comment_body = $row->comment_body;
		$comment_id = $row->comment_id;

		$session_username = $this->session->userdata('username');
		//$username = $row->comment_name;


		if( ($session_username == $comment_name) && ($clicked_comment_id == $comment_id)){ // 세션의 usernamer과 코멘트의 username이 동일하고, ajax_comment_id와 comment_id가 일치하면 수정뷰를 보여줌
			$output .= '
			<div class="panel panel-default">
				<div class="panel-heading" > By <b>'.$comment_name.'</b> on <i>'.$comment_date.'</i></div>
				<input type="hidden" name="comment_name" value="'.$comment_name.'">
				<input type="hidden" name="comment_id" value="'.$comment_id.'">
				<div class="panel-body"><textarea>'.$comment_body.'</textarea></div>
				<div class="panel-footer" style="text-align:right;">
					<button name="btn_finish_modify" class="btn">수정완료</button>
				</div>
			</div>
			';

		} else if($session_username == $username){ // 세션의 usernamer과 코멘트의 username이 동일하면 수정/삭제 버튼을 보여줌
			$output .= '
			<div class="panel panel-default">
				<div class="panel-heading" > By <b>'.$comment_name.'</b> on <i>'.$comment_date.'</i></div>
				<input type="hidden" name="comment_name" value="'.$comment_name.'">
				<input type="hidden" name="comment_id" value="'.$comment_id.'">
				<div class="panel-body">'.$comment_body.'</div>
				<div class="panel-footer" style="text-align:right;">
					<button name="btn_modify" class="btn">수정</button>
					<button name="btn_delete" class="btn">삭제</button>
				</div>
			</div>
			';

		} else{ // 자신이 쓴 코멘트가 아닐 경우 코멘트만 보여줌
			$output .= '
				<div class="panel panel-default">
					<div class="panel-heading">By <b>'.$comment_name.'</b> on <i>'.$comment_date.'</i></div>
					<input type="hidden" name="comment_name" value="'.$comment_name.'">
					<div class="panel-body">'.$comment_body.'</div>
				</div>
			';
		}
	}
	echo json_encode($output, JSON_UNESCAPED_UNICODE);



 }// update_comment closed














  //
  // // 코멘트의 reply 가져오기
  // public function get_reply_comment($parent_id = 0, $marginleft = 0){
  //
  // echo '11111 RRRRRR <br />';
  //
  // $result = $this->comment_m->comment_reply_select();
  // $count = $this->comment_m->comment_reply_count();
  //
  // echo '22222 RRRRRR $result = '.$result.' <br />';
  // echo '22222 RRRRRR $count = '.$count.' <br />';
  //
  //
  // $output = '';
  //
  //   if($parent_id == 0){
  //      $marginleft = 0;
  //   }else{
  //      $marginleft = $marginleft + 48;
  //   }
  //
  //
  // echo '33333 RRRRRR <br />';
  //
  //
  //   if($count>0){
  //     foreach ($result as $row) {
  //       $output .= '
  //         <div class="panel panel-default" style="margin-left:'.$marginleft.'px">
  //           <div class="panel-heading">By <b>'.$row["comment_name"].'</b> on <i>'.$row["comment_date"].'</i></div>
  //           <div class="panel-body">'.$row["comment_body"].'</div>
  //           <div class="panel-footer" align="right"><button type="button" class="btn btn-default reply" id="'.$row["comment_id"].'">Reply</button></div>
  //         </div>
  //         ';
  //       $output .= get_reply_comment($row["comment_id"], $marginleft);
  //     }
  //   }
  //
  // echo '44444 RRRRRR <br />';
  //
  // return $output;
  // }

}
