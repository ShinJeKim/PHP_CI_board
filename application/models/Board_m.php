<?php

class Board_m extends CI_Model{

  function __construct(){
    parent::__construct(); // model constructor 가져오기
    $this->board_table = 'test_board'; // test_board 테이블 가져오기
  }


  // 조회수 count
  public function count_hits($idx){
    $t = $this->board_table;

    $this->db->where('idx', $idx);
    $this->db->set('view', 'view+1', FALSE);
    $this->db->update($t);
  }


  // 페이징을 위한 boad list 전체 개수 카운트
  public function count_list(){
    $result = $this->db->count_all('test_board');

    try{
      $result = ($result) ? $result : false;
      if($result){
        return $result;
      }else{
        return 0;
      }
    }catch(Exception $e){
      return 0;
    }
  }


  // board list 페이지네이션 하여 가져오기
  public function fetch_board_list($limit, $start){
    $t = $this->board_table;

    $this->db->limit($limit, $start);
    //$this->db->order_by('idx', 'DESC');
    $this->db->order_by('parent_idx', 'DESC');
    $this->db->order_by('group_order_first', 'ASC');
    $query = $this->db->get($t);

    // try{
    //   $query = ($query) ? $dataList : false;
    //   if($query->num_rows() > 0){
    //     foreach($query->result() as $row){
    //       $dataList[] = $row;
    //     }
    //     return $dataList;
    // }catch(Exception $e){
    //   return 0;
    // }

    if($query->num_rows() > 0){
      foreach($query->result() as $row){
        $dataList[] = $row;
      }
      return $dataList;
    }
    return false;
  }


  // 새 글 insert
  public function board_insert($title, $content, $email, $ip){
    $t = $this->board_table;

    // insert_id(); 함수를 사용하면 된다.

    $arrayData = array(
                  'title'=>$title,
                  'content'=>$content,
                  'email'=>$email,
                  'ip'=>$ip
                );

    $this->db->insert($t, $arrayData);
  }



  // 원글의 parent_idx에 원글의 idx를 삽입
  public function board_idx_update(){
    $t = $this->board_table;

    $last_id = $this->db->insert_id();
    $this->db->where('idx', $last_id);
    $this->db->set('parent_idx', $last_id);
    $this->db->update($t);
  }


  // 원글의 idx를 답글의 parent_idx에다가 set
  // public function insert_parent_idx($idx){
  //   $t = $this->board_table;
  //
  //   $this->db->where('idx', $idx);
  //   $this->db->set('parent_idx', $idx);
  //   $this->db->update($t);
  // }


  // 답글 insert
  public function board_reply_insert($parent_idx, $group_order_first, $group_order_second, $title, $content, $email, $ip){
    $t = $this->board_table;

    $arrayData = array(
                  'parent_idx'=>$parent_idx,
                  'group_order_first'=>$group_order_first,
                  'group_order_second'=>$group_order_second,
                  'title'=>$title,
                  'content'=>$content,
                  'email'=>$email,
                  'ip'=>$ip
                );

    $this->db->where('parent_idx', $parent_idx);
    $this->db->where('group_order_first >', '0');
    //$this->db->set('group_order_first', '1', FALSE);
    $this->db->insert($t, $arrayData);
  }


  // 답글 정렬을 위한 group_order_first+1
  public function first_ord_update($parent_idx){
    $t = $this->board_table;

    $this->db->where('parent_idx', $parent_idx);
    $this->db->where('group_order_first >', '0');
    $this->db->set('group_order_first', 'group_order_first+1', FALSE);
    $this->db->update($t);
  }



  // 댓글인지 대댓글인지 판단하기 위해 parent_idx 값을 가져옴
  public function get_parent_idx($idx){
    $t = $this->board_table;

    $this->db->select('parent_idx');
    $this->db->from($t);
    $this->db->where('idx', $idx);

    $query = $this->db->get();

    return $query->row()->parent_idx;
  }



  // 대댓글을 grouping하기 위해 group_order_first 값을 가져옴
  public function get_group_order_first($parent_idx){
    $t = $this->board_table;

    $this->db->select('group_order_first');
    $this->db->where('idx', $parent_idx);
    $this->db->from($t);

    $query = $this->db->get();

    return $query->row()->group_order_first;
  }



  // 대댓글인지 판단하기 위해 해당 idx를 가진 row의 group_order_second 값을 가져옴
  public function get_group_order_second($idx){
    $t = $this->board_table;

    $this->db->select('group_order_second');
    $this->db->where('idx', $idx);
    $this->db->from($t);

    $query = $this->db->get();

    return $query->row()->group_order_second;
  }



  // 대댓글 order를 위한 group_order_second+1 작업
  public function second_ord_update($parent_idx, $group_order_first){
    $t = $this->board_table;

    $this->db->where('parent_idx', $parent_idx);
    $this->db->where('group_order_first', $group_order_first);
    $this->db->where('group_order_second >', '0');
    $this->db->set('group_order_second', 'group_order_second+1', FALSE);
    $this->db->update($t);
  }





  // // 답글 select
  // public function board_reply_select($idx){
  //   $t = $this->board_table;
  //   $this->db->select('*');
  //   $this->db->from($t);
  //   $this->db->where('idx', $idx);
  //   $this->db->where('parent_idx !=', '0');
  //   $query = $this->db->get();
  //
  //   return $query->result();
  // }


  // 글 읽기 select
  public function board_select($idx){
    $t = $this->board_table;
    $this->db->select('*');
    $this->db->from($t);
    $this->db->where('idx', $idx);
    $query = $this->db->get();

    return $query->result();
  }


  // 글 수정&삭제 권한 설정을 위한 글 작성자 이메일 select
  public function email_select($idx){
    $t = $this->board_table;
    $this->db->select('email');
    $this->db->from($t);
    $this->db->where('idx', $idx);

    $query = $this->db->get();

    return $query->row()->email;
  }


  // 글 수정 update
  public function board_update($idx, $title, $content){
    $t = $this->board_table;
    $dataArray = array(
                  'title'=>$title,
                  'content'=>$content
                  );

    $this->db->where('idx', $idx);
    $this->db->update($t, $dataArray);

  }


  // 글 삭제 delete
  public function board_delete($idx){
    $t = $this->board_table;

    $this->db->where('idx', $idx);
    $this->db->delete($t);

  }



  // 글 삭제시 댓글과 대댓글 모두 삭제하기 위해 개수 카운트
  public function count_reply(){
    $t = $this->board_table;

    $this->db->select('idx');
    $this->db->from($t);
    $this->db->where('parent_idx', $idx);

    $query = $this->db->get();

    return $query->num_rows();
  }


  // 답글에 달린 대댓글이 있는지 없는지 판단하기 위해
  // parent_idx && group_order_first가 동일한 row의 개수를 반환
  // 결과값이 2이상일 경우 대댓글 존재, 아니면 대댓글 없음.
  public function count_second_reply($parent_idx, $group_order_first){
    $t = $this->board_table;

    $this->db->select('idx');
    $this->db->from($t);
    $this->db->where('parent_idx', $parent_idx);
    $this->db->where('group_order_first', $group_order_first);

    $query = $this->db->get();

    return $query->num_rows();
  }



  // 댓글이 있는 글 삭제 시도시
  // title, content를 update하고 delete_flag를 1로 변경.
  public function delete_have_reply($idx, $title, $content){
    $t = $this->board_table;
    $dataArray = array(
                  'title'=>$title,
                  'content'=>$content,
                  'delete_flag'=>'1'
                  );

    $this->db->where('idx', $idx);
    $this->db->update($t, $dataArray);
  }



  // 글의 답글이 존재할 때 답글을 모두 삭제
  public function delete_all_reply($idx){
    $t = $this->board_table;

    $this->db->where('parent_idx', $idx);
    $this->db->delete($t);
  }


  // 글의 삭제 여부를 판단하기 위해 delete_flag 값을 가져옴.
  public function get_delete_flag($idx){
    $t = $this->board_table;

    $this->db->select('delete_flag');
    $this->db->from($t);
    $this->db->where('idx', $idx);

    $query = $this->db->get();

    return $query->row()->delete_flag;
  }


}

?>
