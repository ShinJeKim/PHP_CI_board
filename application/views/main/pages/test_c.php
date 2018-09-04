<? echo validation_errors(); ?>

<div class="container panel panel-default" style="padding-top: 10px;">
  <form method="post" action="/testurl/write" style="margin-left: 10px;">
      <div class="control-group">
        <label class="control-label" >제목</label>
        <div class="controls">
          <input class="form-control" type="text" name="title" value="<?echo set_value('title');?>">
        </div>
        <label class="control-label" >내용</label>
        <div class="controls">
          <input type="textarea" class="form-control" name="content" value="<?echo set_value('content');?>" style="height:200px; word-wrap:break-word;">
        </div>
      </div>
    <div style="margin-top: 10px;">
      <button type="submit" class="btn btn-primary">등록하기</button>
    </div>
  </form>
</div>
