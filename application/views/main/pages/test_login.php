<form method="post" action="/test_auth/login" class="form-horizontal" style="margin-left: 10px;">
  <div class="control-group " >
    <label class="control-label" >Email</label>
    <div class="controls">
      <input name="email" type="text" placeholder="Email">
    </div>
  </div>
  <div class="control-group">
    <label class="control-label" >Password</label>
    <div class="controls">
      <input name="pw" type="password" placeholder="Password" >
    </div>
  </div>
  <div class="control-group" style="margin-top: 10px;">
      <button type="submit" class="btn btn-primary">로그인</button>
      <a href="/test_auth/resetPassword"><button type="button" class="btn btn-link">비밀번호 찾기</button></a>
  </div>
</form>
