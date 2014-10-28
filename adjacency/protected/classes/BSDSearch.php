<?php
/**
 * 
 * @author drthomas
 * @property Array<Node> $arrNodes
 * @property Array<string> $arrExplored
 * @property Array<Array<Node>> $arrLayers
 */
class BSDSearch extends Search {
	private $arrExplored = array();
	private $arrLayers = array();
	
	public function __construct(array $arrNodes) {
		$this->arrNodes = array_values($arrNodes);
	}
	
	public function runSearch() {
		$count = 0;
		$this->arrExplored = array();
		$this->arrLayers[$count] = array($this->arrNodes[0]->getName());
		$this->arrExplored[] = $this->arrNodes[0]->getName();
		$arrQue = $this->arrNodes[0]->getNeighborNodes();
		
		while(!empty($arrQue)) {
			$Node = array_shift($arrQue);
			if(!in_array($Node->getName(),$this->arrExplored)) {				
				$arrNeigh = $Node->getNeighborNodes();
				if(!empty($arrNeigh)) {
					$found = false;
					for($i = 0; $i < count($arrNeigh); $i++) {
						$Item = $arrNeigh[$i];
						if(!$found && in_array($Item->getName(),$this->arrLayers[$count])){
							$this->arrLayers[$count+1][] = $Node->getName();
							$found = true;
						}
						
						if(!in_array($Item->getName(),$this->arrExplored)) {
							$arrQue[] = $Item;
						}
					}
					if(!$found) {
						$count += 1;
						$this->arrLayers[$count+1] = array();
						$this->arrLayers[$count+1][] = $Node->getName();
					}
				} else {
					throw new BSDSearchNoNeighborException($Node);
				}
				$this->arrExplored[] = $Node->getName();
			} 
		}
		
		return $this->arrLayers;
	}
}

class BSDSearchNoNeighborException extends Exception {
	public function __construct(Node $node, $code = 1, $previous = NULL) {
		parent::__construct("Node {$Node->getName()} does not have any neighbors", $code, $previous);
	}
}