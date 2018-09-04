<?php

class Comment_m extends CI_Model{

  function __construct(){
    parent::__construct(); // model constructor 가져오기
    $this->c_table = 'test_comment'; // test_comment 테이블 가져오기
  }


  // 코멘트 insert
  public function comment_insert($board_id, $comment_name, $comment_email, $comment_body, $comment_ip){
    $t = $this->c_table;

    $arrayData = array(
                  'board_id'=>$board_id,
                  'comment_name'=>$comment_name,
                  'comment_email'=>$comment_email,
                  'comment_body'=>$comment_body,
                  'comment_ip'=>$comment_ip
                );

    $this->db->insert($t, $arrayData);
  }


  // 코멘트 가져오기
  public function comment_select($board_id){
    $t = $this->c_table;

    $this->db->select('*');
    $this->db->from($t);
    $this->db->where('board_id', $board_id);
    $this->db->order_by('comment_id', 'DESC');
    $query = $this->db->get();

    return $query->result();
  }


  // // 코멘트의 reply 가져오기
  // public function comment_reply_select($parent_id){
  //   $t = $this->c_table;
  //
  //   $this->db->select('*');
  //   $this->db->from($t);
  //   $this->db->where('parent_comment_id',$parent_id);
  //   $query = $this->db->get();
  //
  //   return $query->result();
  // }
  //
  //
  // // 코멘트의 reply 개수 가져오기
  // public function comment_reply_count($parent_id){
  //   $t = $this->c_table;
  //
  //   $this->db->select('*');
  //   $this->db->from($t);
  //   $this->db->where('parent_comment_id',$parent_id);
  //   $query = $this->db->get();
  //
  //   return $query->num_rows();
  // }

}
?>
