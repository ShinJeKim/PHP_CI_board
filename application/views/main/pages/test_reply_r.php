
<? echo validation_errors(); ?>

<div class="container panel panel-default" style="padding-top: 10px;">
  <form method="post" action="/testurl/writeReply" style="margin-left: 10px;">
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
      <input type="hidden" name="idx" value="<?=$this->uri->segment(3);?>">
    </div>
  </form>
</div>




<!-- <? echo validation_errors(); ?>
<form method="post" action="/testurl/writeReply" class="form-horizontal" style="margin-left:20px;">
  <div class="control-group" >
    <label class="control-label" >title</label>
    <div class="controls">
      <input type="text" name="title" placeholder="제목">
    </div>
  </div>
  <div class="control-group">
    <label class="control-label" >content</label>
    <div class="controls">
      <textarea type="text" name="content" rows="5" placeholder="내용을 입력하세요"></textarea>
    </div>
  </div>
  <div class="control-group">
    <div class="controls">
      <button type="submit" class="btn btn-primary">등록하기</button>
      <input type="hidden" name="idx" value="<?=$this->uri->segment(3);?>">
    </div>
  </div>
</form>

 -->
