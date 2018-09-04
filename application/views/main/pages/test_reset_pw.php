
<form method="post" action="/test_auth/getNewPassword" name="form" class="form-horizontal" style="margin-left: 10px;">
  <div class="control-group">
    <label class="control-label" >새로운 비밀번호(4-10자리, 영 대/소문자, 특수문자 !@#$%^&* 가능)</label>
    <div class="controls">
      <input name="password" id="password" type="password" placeholder="your new password">
    </div>
    <label class="control-label" >비밀번호 확인</label>
    <div class="controls">
      <input name="password_check" id="password_check" type="password" placeholder="Check new password">
    </div>
  </div>
  <div class="control-group" style="margin-top: 10px;">
    <!-- <button type="submit" class="btn btn-primary">변경하기</button> -->
    <input type="button" value="변경하기" onclick="validate(this.form)"/>
  </div>
  <input type="hidden" name="idx" value=<?=$this->uri->segment(4);?>>
  <input type="hidden" name="hash_email" value=<?=$this->uri->segment(3);?>>
</form>


<script>
  function validate(){
    var regular = '^[A-Za-z0-9]{4,10}$';

    console.log('regular= '+regular);
    console.log('value = '+document.form.password.value);

    if( document.form.password.value == ""){
      alert('비밀번호를 입력해주세요.');
      document.form.password.focus();
    }else if( document.form.password_check.value == ""){
      alert('비밀번호 확인란을 입력해주세요.');
      document.form.password_check.focus();
    }else if( !regular.test(document.form.password.value)){
      alert('4-10자리, 영 대/소문자, 특수문자 !@#$%^&* 만 가능합니다.');
      document.form.password.focus();
    }else if( document.form.password.value != document.form.password_check.value){
      alert('비밀번호와 비밀번호 확인란의 값이 같아야 합니다.');
      document.form.password_check.focus();
    }



    // var password = document.getElementById('password');
    // var password_check = document.getElementById('password_check');
    //
    // if( password == ""){
    //   alert('비밀번호를 입력해주세요.');
    //   document.form.password.focus() ;
    // }else if( password_check == ""){
    //   alert('비밀번호 확인란을 입력해주세요.');
    //   document.form.password_check.focus() ;
    // }

    //form.submit();

  }// function validate closed

</script>
