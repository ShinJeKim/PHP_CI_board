
<? echo validation_errors(); ?>
<form method="post" action="/test_auth/signup" >
 <table>
   <tr>
     <td>이름</td>
     <td><input type="text" name="username" placeholder="10자리 이내" value="<? echo set_value('username'); ?>"/></td>
   </tr>
   <tr>
     <td>이메일</td>
     <td><input type="text" name="email" placeholder="email" value="<? echo set_value('email'); ?>"/></td>
   </tr>
   <tr>
     <td>비밀번호</td>
     <td><input type="password" name="pw" placeholder="password"/></td>
   </tr>
   <tr>
     <td>비밀번호 확인</td>
     <td><input type="password" name="pw_check" placeholder="check password"/></td>
   </tr>
   <tr>
     <td>&nbsp;</td>
     <td><button type="submit" class="btn btn-primary" >저장하기</button></td>
   </tr>
</table>
</form>
