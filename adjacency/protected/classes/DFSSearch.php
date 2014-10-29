<?php
/**
 * 
 * @author drthomas
 * @property Array<Node> $arrNodes
 * @property Array<string> $arrExplored
 * @property Array<Array<Node>> $arrLayers
 */
class DFSSearch extends Search {
	private $arrLayers = array();
	private $arrExplored = array();
	
	public function __construct(array $arrNodes) {
		$this->arrNodes = array_values($arrNodes);
	}
	
	public function runSearch() {
		$count = 0;
		$this->arrLayers = array();

		$this->doEdgeSearch($this->arrNodes[0], NULL);
		
		return $this->arrLayers;
	}
	
	protected function doEdgeSearch(Node $node, Node $referringNode = NULL) {
		$arrNeigh = $node->getNeighborNodes();
		$this->arrLayers[] = array($node, $referringNode);
		$this->arrExplored[] = $node->getName();
		if(!empty($arrNeigh)) {
			foreach($arrNeigh as $Item) {
				if(!in_array($Item->getName(),$this->arrExplored)) {
					$this->doEdgeSearch($Item, $node);
				}
			}
		}
	}
}
