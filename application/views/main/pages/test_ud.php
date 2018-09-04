<? echo validation_errors(); ?>
<div class="container panel panel-default" style="padding-top: 10px;">
  <form method="post" action="/testurl/update" style="margin-left: 10px;">
    <? foreach($itemList as $item): ?>
      <input type="hidden" name="idx" value="<?=$item->idx?>" >
      <div class="control-group">
        <label class="control-label" >제목</label>
        <div class="controls">
          <input class="form-control" type="text" name="title" value="<?=$item->title?>">
        </div>
        <label class="control-label" >내용</label>
        <div class="controls">
          <input type="textarea" class="form-control" name="content" value="<?=$item->content?>" style="height:200px; word-wrap:break-word;">
        </div>
      </div>
    <?endforeach;?>
    <div style="margin-top: 10px;" >
      <a href="/testurl/update"><button type="submit" class="btn btn-primary">수정</button></a>
    </div>
  </form>
  <div style="margin: 10px;">
    <a href="/testurl/delete/<?=$item->idx?>"><button class="btn btn-danger">삭제</button></a>
  </div>
</div>
