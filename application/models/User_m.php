<?php

class User_m extends CI_Model{

  function __construct(){
    parent::__construct(); // model constructor 가져오기
    $this->user_table = 'test_user'; // test_user 테이블 가져오기
  }



  function user_insert($name, $email, $pw, $regdate, $ip){// 회원가입 폼 insert
    $t = $this->user_table;

    $arrayData = array(
                  'username'=>$name,
                  'email'=>$email,
                  'password'=>$pw,
                  'regdate'=>$regdate,
                  'userip'=>$ip
                  );

    $this->db->insert($t, $arrayData);
  }



  function select_hash($email){// 로그인을 위한 hash 비밀번호 가져오기
    $t = $this->user_table;

    $this->db->select('password');
    $this->db->from($t);
    $this->db->where('email',$email);

    $query = $this->db->get();

    return $query ->row()->password;
  }



  function email_exists($email){// 비밀번호 변경을 위한 회원 이메일 계정 존재여부 확인
    $t = $this->user_table;

    $this->db->select('username');
    $this->db->from($t);
    $this->db->where('email', $email);

    $query = $this->db->get();

    // return (($query->num_rows()->username) ? TRUE : FALSE );
    // return (($query->num_rows()->username) ? 1 : 0 );
    // return ($result->num_rows() === 1 && $row->email) ? $row->username : false;
    return $query->row()->username;
  }



  function get_old_password($idx){// 기존 비밀번호와 새 비밀번호를 비교
    $t = $this->user_table;

    $this->db->select('password');
    $this->db->where('idx', $idx);
    $this->db->from($t);

    $query = $this->db->get();

    return $query->row()->password;
  }



  function update_password($idx, $hash_pw){// 비밀번호 변경하기
    $t = $this->user_table;

    // $idx = '41';
    // $hash_pw = '$2y$10$rR2b9lZk';
    // $time = date("Y-m-d h:i:s");

    $this->db->where('idx', $idx);
    $this->db->set('password', $hash_pw);
    $this->db->update($t);

  }



  function get_idx($email){ // 이메일 값을 넣어 idx값 가져오기
    $t = $this->user_table;

    $this->db->where('email', $email);
    $this->db->select('idx');
    $this->db->from($t);

    $query = $this->db->get();

    return $query->row()->idx;
  }



  function get_username($email){ // 이메일 값을 넣어 username값 가져오기
    $t = $this->user_table;

    $this->db->where('email', $email);
    $this->db->select('username');
    $this->db->from($t);

    $query = $this->db->get();

    return $query->row()->username;
  }



  function set_send_email_time($email, $timestamp){ // 비밀번호 변경 이메일 발송시각을 저장
    $t = $this->user_table;

    $this->db->where('email', $email);
    $this->db->set('send_email_time', $timestamp);
    $this->db->update($t);
  }



  function get_send_email_time($idx){ // 비밀번호 변경 이메일 발송시각을 가져옴
    $t = $this->user_table;

    $this->db->where('idx', $idx);
    $this->db->select('send_email_time');
    $this->db->from($t);

    $query = $this->db->get();

    return $query->row()->send_email_time;
  }



  // // 로그인
  // function user_login($email, $pw){
  //   $t = $this->user_table;
  //
  //   $arrayData = array(
  //                 'email'=>$email,
  //                 'password'=>$pw
  //                 );
  //
  //   $this->db->select('email', 'password');
  //   $this->db->from($t);
  //   $this->db->where('email',$email);
  //   $this->db->where('password',$pw);
  //
  //   $query = $this->db->get();
  //
  //   if ($query -> num_rows() > 0) {
  //     return $query -> row();
  //   }else {
  //     return FALSE;
  //   }
  // }



}

?>
