<?php

class Message_m extends CI_Model{

  function __construct(){
    parent::__construct(); // model constructor 가져오기
    $this->message_table = 'test_message'; // test_message 테이블 가져오기
  }



  function set_message($message, $username, $guid){ // 메시지 insert
      $data = array(
        'message'           => (string)$message,
  			'message_username'	=> (string)$username,
        'message_guid'      => (string)$guid,
  			'message_timestamp'	=> time()
  		);
  		$this->db->insert('test_message', $data);


  }



	function get_messages($timestamp){ // 메시지 가져오기
    $t = $this->message_table;

		$this->db->where('message_timestamp >', $timestamp); // timestamp 이후의 메시지를 가져옴
		$this->db->order_by('message_timestamp', 'DESC');
		$this->db->limit(10);
		$query = $this->db->get($t);

		return array_reverse($query->result_array());
  }

}
?>
