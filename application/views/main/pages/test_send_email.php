<?php echo validation_errors(); ?>
<form method="post" action="/test_auth/resetPassword" class="form-horizontal" style="margin-left: 10px;">
  <div class="control-group">
    <label class="control-label" >이메일을 입력해주세요 :)</label>
    <div class="controls">
      <input name="email" type="text" placeholder="email" value="<?php echo set_value('email'); ?>">
    </div>
  </div>
  <div class="control-group" style="margin-top: 10px;">
    <button type="submit" class="btn btn-primary">전송</button>
  </div>
</form>
