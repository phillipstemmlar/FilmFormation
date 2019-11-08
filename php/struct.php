<?php

class Queue {

	private $head;
	public $length;
	
	public function __construct() {
		$this->head = null;
		$this->length = 0;
	}  
	public function __destruct(){
		$this->head = null;
		$this->length = 0;
	}

	public function push($obj, $key = 0)
	{
		$newNode = new Node($obj,$key);
		
		if($this->head == null){
			$this->head = newNode;
		}else{
			$curNode = $this->head;
			$prevNode = null;
			
			while($curNode != null && $curNode->key >= $key )
			{
				$prevNode = $curNode;
				$curNode = $curNode->next;
			}
			
			if($prevNode == null)
			{
				$newNode->next = $this->head;
				$this->head = $newNode;
			}else{
				$newNode->next = $curNode;
				$prevNode->next = $newNode;
			}
		}
		$this->length++;
	}
	
	public function pop()
	{
		if($this->head == null) { return null; }
		
		$res = $this->head;
		$this->head = $res->next;
		$res->next = null;
		
		$this->length--;
		return $res->data;
	}
	
	public function empty()
	{
		$this->head = null;
		$this->length = 0;
	}
	
	public function isEmpty()
	{
		return ($this->head == null);
	}	
}

class Node{
		public $next;
		public $data;
		public $key;
		
		public function __construct($Data, $key){
			$this->data = $Data;
			$this->next = null;
			$this->key = $key;
		}
	}

?>