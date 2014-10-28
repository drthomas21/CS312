<?php
class DirectEdge extends Edge {
	public function __construct($name = "") {
		$this->name = $name;
		$this->arrNodes = array();
	}
	
	public function getNextNode(Node $node) {
		$Found = null;
		if(!empty($this->arrNodes) && count($this->arrNodes) == 2) {
			if($this->arrNodes[0]->isEqual($node)) {
				$Found = $this->arrNodes[1];
			}
		}
	
		return $Found;
	}
	
	public function getOtherNode(Node $node) {
		$Found = null;
		if(!empty($this->arrNodes) && count($this->arrNodes) == 2) {
			if($this->arrNodes[0]->isEqual($node)) {
				$Found = $this->arrNodes[1];
			} elseif($this->arrNodes[1]->isEqual($node)) {
				$Found = $this->arrNodes[0];
			}
		}
	
		return $Found;
	}
}