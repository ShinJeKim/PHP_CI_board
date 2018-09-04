<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.0/jquery.min.js"></script>
<script src="//code.jquery.com/jquery-1.11.0.min.js"></script>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>

<div class="container panel panel-default" style="padding-top: 10px;">
 <table class="table">
   <? foreach($itemList as $item): ?>
     <tr>
       <td colspan="2" style="text-align: right;">No.</td>
       <td><?=$item->idx?></td>
     </tr>
     <tr>
       <td colspan="2" style="text-align: right;">제목</td>
       <td><?=$item->title?></td>
     </tr>
     <tr>
       <td colspan="2" style="text-align: right;">내용</td>
       <td><?=$item->content?></td>
     </tr>
     <tr>
       <td colspan="2" style="text-align: right;">등록일</td>
       <td><?=nice_date(($item->regdate), 'Y-m-d')?></td>
     </tr>
     <tr>
       <td colspan="2" style="text-align: right;">조회수</td>
       <td><?=$item->view?></td>
     </tr>
   <?endforeach;?>
     <tr>
       <td >&nbsp;</td>
       <td colspan="2" style="text-align: right;">
        <a href="/testurl/updateView/<?=$item->idx?>"><button class="btn btn-primary">수정하기</button></a>
        <? if(($item->group_order_second) == 0){?>
        <a href="/testurl/writeReply/<?=$item->idx?>"><button class="btn btn-warning" id="reply_btn" >답글달기</button></a>
        <?} else{?>
        <?}?>
      </td>
     </tr>
</table>
</div>

<div class="container panel panel-default" style="padding-top: 10px;">
  <form method="POST" id="comment_form">
    <div class="form-group ">
      <input type="hidden" id="board_id" value="<?=$item->idx?>"/>
      <? if(empty($this->session->userdata('username'))){?>
      </div>
      <div class="form-group">
        <textarea name="comment_content" id="comment_content" class="form-control" placeholder="댓글은 로그인 후에 입력가능합니다." rows="5"></textarea>
      </div>
      <?} else{?>
      <div class="panel-heading">By <b><? echo $this->session->userdata('username')?></b></div>
      </div>
      <div class="form-group">
        <textarea name="comment_content" id="comment_content" class="form-control" placeholder="댓글을 입력해주세요" rows="5"></textarea>
      </div>
      <?}?>
    <div class="form-group" style="text-align:right;">
      <input type="hidden" name="comment_id" id="comment_id" value="0" />
      <input type="submit" name="submit" id="submit" class="btn btn-info" value="등록" />
    </div>
  </form>
  <span id="comment_message">
    <label class="text-success">댓글목록</label>
  </span>
  <br />
  <div id="display_comment" ></div>
</div>


<script>

$(document).ready(function(){

  <? if(!$this->session->userdata('logged_in')){?>// 로그인하지 않은 사용자가 코멘트 입력창 클릭시 로그인 페이지로 리다이렉트.
    $('#comment_content').click(function(){
      alert('로그인 후에 작성 가능합니다.');
      location.href = '/test_auth/login';
    });
  <?}?>



  $('#comment_form').on('submit', function(event){ // 코멘트 등록
    var board_id = $('#board_id').val();
    var comment_name = $('#comment_name').val();
    var comment_content = $('#comment_content').val().replace(/\n/g, '<br>');
    var comment_id = $('#comment_id').val();

    // var form_data = $(this).serialize();
    $.ajax({
       url:"/test_comment/add_comment",
       method:"POST",
       data:{
            board_id: board_id,
            comment_name: comment_name,
            comment_body: comment_content
            },
       dataType:"JSON",
       success:function(data){
              $('#comment_form')[0].reset();
              $('#comment_message').html(data.error);
              $('#comment_id').val('0');
              load_comment();
            },
       error: function(request, status, error){
              }
     })// ajax closed
  })// comment_form on submit closed

  load_comment();

  function load_comment(){ // 코멘트 불러오기
    var board_id = $('#board_id').val();
    $.ajax({
      url:"/test_comment/fetch_comment",
      method:"POST",
      data: {board_id: board_id},
      dataType:"JSON",
      success:function(data){
        $('#display_comment').html(data);
      },
      error: function(request, status, error){
      }
    })
  }// load_comment closed



  // function load_comment(){ // 코멘트 불러오기
  //   var board_id = $('#board_id').val();
  //   $.ajax({
  //     url:"/test_comment/fetch_comment",
  //     method:"POST",
  //     data: {board_id: board_id},
  //     dataType:"JSON",
  //     success:function(data){
  //       // $('#display_comment').html(data);
  //       $('#display_comment').append(data);
  //       console.log(data);
  //       // var comment_html = get_comment_html(data);
  //       // $('#display_comment').html(comment_html);
  //     },
  //     error: function(request, status, error){
  //       // alert("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
  //       console.log(request.responseText);
  //       //console.log(jQuery.parseJSON(request.responseText));
  //       console.log("error:"+error);
  //
  //       // console.log(json_decode(json_encode(request.responseText), TRUE));
  //       // console.log('확인!'+request.responseText[3].comment_name);
  //     }
  //   })
  // }// function load_comment closed



  // function get_comment_html(comment_array){ // 코멘트 리스트 불러오기
  // // comment_array : 코멘트 리스트의 data
  // // [0]:name | [1]:date | [2]:body | [3]:id
  //
  //   var html_data = ""; // ajax에 보낼 html_data를 담음
  //   var session_name = <? echo $this->session->userdata('username')?>; // 현재 세션의 username
  //
  //   for(var i=0; i<comment_array; i++){
  //     var name = comment_array[i].name; // 불러온 코멘트의 username
  //     var date = comment_array[i].date; // 불러온 코멘트의 date
  //     var body = comment_array[i].body; // 불러온 코멘트의 내용
  //     var id = comment_array[i].id; // 불러온 코멘트의 id
  //     var name_for_compare = name.replace(/['"]+/g, ''); // username의 따옴표 제거
  //
  //     console.log(comment_array.length);
  //
  //     if(session_name == name_for_compare){// 현재 세션의 유저네임과 코멘트의 유저네임이 같으면(본인이 작성한 코멘트) 수정/삭제 버튼 보여줌
  //       html_data += '<div class="panel panel-default">';
  //       html_data += '<div class="panel-heading" > By <b>'+name+'</b> on <i>'+date+'</i></div>';
  //       html_data += '<input type="hidden" name="comment_name" value="'+name+'">';
  //       html_data += '<input type="hidden" name="comment_id" value="'+id+'">';
  //       html_data += '<div class="panel-body">'+body+'</div>';
  //       html_data += '<div class="panel-footer" style="text-align:right;">';
  //       html_data += '<button id="btn_modify" name="btn_modify" class="btn">수정</button>  ';
  //       html_data += '<button id="btn_delete" name="btn_delete" class="btn">삭제</button>';
  //       html_data += '</div>';
  //       html_data += '</div>';
  //     }else{// 현재 세션의 유저네임과 코멘트의 유저네임이 다르면(본인이 작성하지 않은 코멘트) 수정/삭제 버튼 없음
  //       html_data += '<div class="panel panel-default">';
  //       html_data += '<div class="panel-heading" > By <b>'+name+'</b> on <i>'+date+'</i></div>';
  //       html_data += '<input type="hidden" name="comment_name" value="'+name+'">';
  //       html_data += '<input type="hidden" name="comment_id" value="'+id+'">';
  //       html_data += '<div class="panel-body">'+body+'</div>';
  //       html_data += '</div>';
  //     }
  //   }



    //$('#display_comment').html(html_data);

    // var html_data = '<div class="panel panel-default"><div class="panel-heading" > By <b>'+comment_array[0]+'</b>
    //                 on <i>'+comment_array[1]+'</i></div><input type="hidden" name="comment_name" value="'+comment_array[0]+'">
    //                 <input type="hidden" name="comment_id" value="'+comment_array[4]+'"><div class="panel-body">'+comment_array[2]+'</div>
    //                 <div class="panel-footer" style="text-align:right;"><button id="btn_modify" name="btn_modify" class="btn">수정</button>
    // 	              <button id="btn_delete" name="btn_delete" class="btn">삭제</button></div></div>';



  //} // function fetch_comment_html closed











  // function get_comment_html(comment_array){ // 코멘트 리스트 불러오기
  // // comment_array : 코멘트 리스트의 data
  // // [0]:name | [1]:date | [2]:body | [3]:id
  //
  //   var html_data = ""; // ajax에 보낼 html_data를 담음
  //   var session_name = <? echo $this->session->userdata('username')?>; // 현재 세션의 username
  //
  //
  //   var name = comment_array[0]; // 불러온 코멘트의 username
  //   var date = comment_array[1]; // 불러온 코멘트의 date
  //   var body = comment_array[2]; // 불러온 코멘트의 내용
  //   var id = comment_array[3]; // 불러온 코멘트의 id
  //   var name_for_compare = name.replace(/['"]+/g, ''); // username의 따옴표 제거
  //
  //   console.log(comment_array.length);
  //
  //   if(session_name == name_for_compare){// 현재 세션의 유저네임과 코멘트의 유저네임이 같으면(본인이 작성한 코멘트) 수정/삭제 버튼 보여줌
  //     html_data += '<div class="panel panel-default">';
  //     html_data += '<div class="panel-heading" > By <b>'+name+'</b> on <i>'+date+'</i></div>';
  //     html_data += '<input type="hidden" name="comment_name" value="'+name+'">';
  //     html_data += '<input type="hidden" name="comment_id" value="'+id+'">';
  //     html_data += '<div class="panel-body">'+body+'</div>';
  //     html_data += '<div class="panel-footer" style="text-align:right;">';
  //     html_data += '<button id="btn_modify" name="btn_modify" class="btn">수정</button>  ';
  //     html_data += '<button id="btn_delete" name="btn_delete" class="btn">삭제</button>';
  //     html_data += '</div>';
  //     html_data += '</div>';
  //   }else{// 현재 세션의 유저네임과 코멘트의 유저네임이 다르면(본인이 작성하지 않은 코멘트) 수정/삭제 버튼 없음
  //     html_data += '<div class="panel panel-default">';
  //     html_data += '<div class="panel-heading" > By <b>'+name+'</b> on <i>'+date+'</i></div>';
  //     html_data += '<input type="hidden" name="comment_name" value="'+name+'">';
  //     html_data += '<input type="hidden" name="comment_id" value="'+id+'">';
  //     html_data += '<div class="panel-body">'+body+'</div>';
  //     html_data += '</div>';
  //   }
  //
  //   //$('#display_comment').html(html_data);
  //
  //   // var html_data = '<div class="panel panel-default"><div class="panel-heading" > By <b>'+comment_array[0]+'</b>
  //   //                 on <i>'+comment_array[1]+'</i></div><input type="hidden" name="comment_name" value="'+comment_array[0]+'">
  //   //                 <input type="hidden" name="comment_id" value="'+comment_array[4]+'"><div class="panel-body">'+comment_array[2]+'</div>
  //   //                 <div class="panel-footer" style="text-align:right;"><button id="btn_modify" name="btn_modify" class="btn">수정</button>
	// 	// 	              <button id="btn_delete" name="btn_delete" class="btn">삭제</button></div></div>';
  //
  //
  //
  // } // function fetch_comment_html closed
















  // $('#btn_modify').click(function(){ // 코멘트 수정버튼 클릭시
  //   console.log('sdf');
  //
  //
  //   var ajax_comment_id = $('#comment_id').val();
  //   console.log(ajax_comment_id);
  //
  //   $.ajax({
  //       url:"/test_comment/add_update_comment",
  //       method:"POST",
  //       data: {ajax_comment_id: ajax_comment_id},
  //       dataType:"JSON",
  //       success:function(data){
  //         $('#display_comment').html(data);
  //       },
  //       error: function(request, status, error){
  //         alert("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
  //       }
  //     })
  // }); // btn_modify click function closed





  // $('#btn_delete').click(function(){ // 코멘트 삭제버튼 클릭시
  //
  // });





  // $('#reply_btn').click(function(){
  //   var board_id = $('#board_id').val();
  //   $.ajax({
  //     url:"/testurl/writeReply",
  //     method:"POST",
  //     data: {board_id: board_id},
  //     dataType:"JSON",
  //     success:function(data){
  //       alert('board_id= '+board_id);
  //     },
  //     error: function(request, status, error){
  //       alert("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
  //     }
  //   })
  // });


});// jquery closed

</script>
