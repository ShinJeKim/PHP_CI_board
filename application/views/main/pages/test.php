
<div style="text-align: right">
  <?
	 if($this->session->userdata('logged_in')){
	?>
		<a href="/test_auth/logout"><button class="btn btn-primary">로그아웃</button></a>
	<?
	} else {
	?>
		<a href="/test_auth/login"><button class="btn btn-primary">로그인</button></a>
		<a href="/test_auth/signup"><button class="btn btn-primary">회원가입</button></a>
	<?
	}
	?>
</div>

<a href="/testurl/write"><button class="btn btn-info">작성하기</button></a>

<div>
  <form>
    <table class="table" style="margin-top:10px;">
      <thead>
        <th style="text-align: left" >No.</th>
        <th style="text-align: center" >제목</th>
        <th style="text-align: center" >등록일</th>
        <th style="text-align: center" >조회수</th>
      </thead>
        <? foreach($dataList as $item): ?>
          <?if(($item->delete_flag) == 1){?>
            <tr>
              <td style="text-align: left; " name="idx"><?=$item->idx?></td>
              <td style="text-align: left; " ><?=$item->title?></td>
              <td style="text-align: center; " ><?=nice_date(($item->regdate), 'Y-m-d')?></td>
              <td style="text-align: center; " ><?=$item->view?></td>
            </tr>
          <?}else if(($item->parent_idx) === ($item->idx)){?>
              <tr>
                <td style="text-align: left; " name="idx"><?=$item->idx?></td>
                <td style="text-align: left; " ><a href="/testurl/read/<?=$item->idx?>"><?=$item->title?></a></td>
                <td style="text-align: center; " ><?=nice_date(($item->regdate), 'Y-m-d')?></td>
                <td style="text-align: center; " ><?=$item->view?></td>
              </tr>
          <?}else if(($item->group_order_second) == 0){?>
            <tr>
              <td style="text-align: left; " name="idx"><?=$item->idx?></td>
              <td style="text-align: left; padding-left:30px; " ><a href="/testurl/read/<?=$item->idx?>">└ RE: <?=$item->title?></a></td>
              <td style="text-align: center; " ><?=nice_date(($item->regdate), 'Y-m-d')?></td>
              <td style="text-align: center; " ><?=$item->view?></td>
            </tr>
          <?} else{?>
            <tr>
              <td style="text-align: left; " name="idx"><?=$item->idx?></td>
              <td style="text-align: left; padding-left:60px; " ><a href="/testurl/read/<?=$item->idx?>">└ RE: <?=$item->title?></a></td>
              <td style="text-align: center; " ><?=nice_date(($item->regdate), 'Y-m-d')?></td>
              <td style="text-align: center; " ><?=$item->view?></td>
            </tr>
          <?}?>
        <? endforeach; ?>
      </thead>
    </table>
  </form>
  <div class="pagination">
    <? echo $links; ?>
  </div>
</div>


<!-- <script>
$(document).ready(function(){
  $('button').click(function(){
    var idx = $('#idx').val();

    $.ajax({
       url:"/testurl/read/"+idx,
       method:"POST",
       data:{idx: idx},
       dataType:"JSON",
       success:function(data){

            },
       error: function(request, status, error){
              }
     })// ajax closed
  })

});// jquery closed

</script> -->
