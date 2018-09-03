<?php 
/* Shared on httzip.com
 * Code by fb.com/httzip
 */

header('Content-Type: text/html; charset=utf-8');
class Event {

	public $post_id;
	public $access_token;
	public $list_answer;

	function __construct(){
	}
	
	public function check()
	{
		$curl = curl_init();
		curl_setopt_array($curl, array(
		CURLOPT_URL => "https://graph.facebook.com/".$this->post_id."/comments?fields=from,message,id&filter=stream&order=reverse_chronological&access_token=".$this->access_token."&limit=50&pretty=1",
		CURLOPT_RETURNTRANSFER => true,
		CURLOPT_TIMEOUT => 0,
		CURLOPT_SSL_VERIFYPEER => false,
		CURLOPT_SSL_VERIFYHOST => false
		));
		$get = curl_exec($curl);
		curl_close($curl);

		$decode = json_decode($get,JSON_UNESCAPED_UNICODE);
		if(!empty($decode['data'])){
			foreach ($decode['data'] as $data) {
				if(!empty($data['from']['id']))
				{
					$user_id = $data['from']['id'];
					$comment_id = $data['id'];
					$user_name = $data['from']['name'];
					$comment_content = $data['message'];
					if( strpos(file_get_contents("list.txt"),$comment_id) !== false) 
					{
						return false;
					}
					else
					{
						return 
							array($this->saveID($comment_id),
								$this->filterComment($comment_id,$comment_content),
								$this->reply($user_id,$comment_id));
					}
				}
				else{
					echo "Lỗi !!!";
				}
			}
	
		}else{
			return false;	
		}
	}
	public function saveID($id)
	{
		$list='list.txt';
		$file = fopen($list, 'a');
		fwrite($file,$id."\n");
		fclose($file);
		
	}
	public function filterComment($id,$comment)
	{

		if (strpos($comment, '1') !== false) {
		    return $this->inboxUser($id,0);
		}else if(strpos($comment, '2') !== false){
		if(strpos($comment, '2') !== false){
			 return array($this->inboxUser($id,1));
		}
		}else if(strpos($comment, '3') !== false){
			return $this->inboxUser($id,2);
		}else{
			return $this->inboxUser($id,3);
		}
	}
	public function inboxUser($id,$number)
	{
		$req = curl_init();
		curl_setopt_array($req, array(
			CURLOPT_URL => "https://graph.facebook.com/".$id."/private_replies?access_token=".$this->access_token."&message=".str_replace(" ","%20",$this->list_answer[$number])."&method=POST",
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_SSL_VERIFYPEER => false,
			CURLOPT_SSL_VERIFYHOST => false
		));
		$res = curl_exec($req);
		curl_close($req);
	}
	public function reply($user,$comment)
	{
		$reply = "https://graph.facebook.com/".$comment."/comments?message=Xin+Chào+@[".$user."]+Page+đã+inbox+tính+cách+của+bạn+như+thế+nào+rồi+nhé+!&method=POST&access_token=".$this->access_token;
		$curlrep = curl_init();
		curl_setopt_array($curlrep, array(
			CURLOPT_URL => $reply,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_SSL_VERIFYPEER => false,
			CURLOPT_SSL_VERIFYHOST => false
		));
		$res = curl_exec($curlrep);
		curl_close($curlrep);
	}

}
 ?>