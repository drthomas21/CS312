<?php
/**
 * 
 * @author drthomas
 * @property Array<Node> $arrNodes
 * @property Array<string> $arrExplored
 * @property Array<Array<Node>> $arrLayers
 */
class BFSSearch extends Search {
	private $arrExplored = array();
	private $arrLayers = array();
	
	public function __construct(array $arrNodes) {
		$this->arrNodes = array_values($arrNodes);
	}
	
	public function runSearch() {
		$count = 0;
		$this->arrExplored = array();
		$this->arrLayers[] = array($this->arrNodes[0]->getName());
		$this->arrExplored[] = $this->arrNodes[0]->getName();
		$arrQue = $this->arrNodes[0]->getNeighborNodes();
		$arrEdges = $this->arrNodes[0]->getEdges();
		if($arrEdges[0] instanceof IndirectEdge) {
			$this->doIndirectEdgeSearch($arrQue, $count);
		} else {
			$this->doDirectEdgeSearch($arrQue, $count);
		}
		
		return $this->arrLayers;
	}
	
	protected function doDirectEdgeSearch($arrQue, $count) {
		while(!empty($arrQue)) {
			$Node = array_shift($arrQue);
			if(!in_array($Node->getName(),$this->arrExplored)) {
				$arrNodes = $Node->getConnectedNodes();
				$arrTails = $Node->getNeighborNodes();
				if(!empty($arrNodes)) {
					$found = false;
					for($i = 0; $i < count($arrNodes); $i++) {
						$Item = $arrNodes[$i];
						if(!$found && in_array($Item->getName(),$this->arrLayers[$count])){
							$this->arrLayers[$count+1][] = $Node->getName();
							$found = true;
						}
					}
					if(!$found) {
						$count += 1;
						$this->arrLayers[$count+1] = array();
						$this->arrLayers[$count+1][] = $Node->getName();
					}
				}
				
				if(!empty($arrTails)) {
					for($i = 0; $i < count($arrTails); $i++) {
						$Item = $arrTails[$i];
				
						if(!in_array($Item->getName(),$this->arrExplored)) {
							$arrQue[] = $Item;
						}
					}
				}
				$this->arrExplored[] = $Node->getName();
			}
		}
	}
	
	protected function doIndirectEdgeSearch($arrQue, $count) {
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
				}
				$this->arrExplored[] = $Node->getName();
			}
		}
	}
}
