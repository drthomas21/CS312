<?php
/**
 * 
 * @author drthomas
 * @property string $name
 * @property Array<Edge> $arrEdges
 */
class Node {
	private $name = "";
	private $arrEdges = array();
	private $id = 0;
	
	public function __construct($name) {
		$this->name = $name;
		$this->arrEdges = array();
		$this->id = rand();
	}
	
	/**
	 * 
	 * @param Edge $object
	 * @return number $index
	 */
	public function addEdge(Edge $object) {
		$this->arrEdges[] = $object;
		return count($this->arrEdges)-1;
	}
	
	/**
	 * 
	 * @return string $name
	 */
	public function getName() {
		return $this->name;
	}
	
	/**
	 * 
	 * @param string $name
	 */
	public function setName($name) {
		if(is_string($name)) {
			$this->name = $name;
		}
	}
	
	/**
	 * 
	 * @return Array<Edge> $edges
	 */
	public function getEdges() {
		return $this->arrEdges;
	}
	
	/**
	 * For directed graphs, returns only tail nodes
	 * @return Array<Node> $nodes
	 */
	public function getNeighborNodes() {
		$arrNodes = array();
		if(!empty($this->arrEdges)) {
			foreach($this->arrEdges as $Edge) {
				$temp = $Edge->getNextNode($this);
				if(!empty($temp)) {
					$arrNodes[] = $temp;
				}
			}
		}
		
		return $arrNodes;
	}
	
	/**
	 * 
	 * @return Array<Node> $nodes
	 */
	public function getConnectedNodes() {
		$arrNodes = array();
		if(!empty($this->arrEdges)) {
			foreach($this->arrEdges as $Edge) {
				$temp = $Edge->getOtherNode($this);
				if(!empty($temp)) {
					$arrNodes[] = $temp;
				}
			}
		}
		
		return $arrNodes;
	}
	
	public function getHash() {
		return sha1($this->name . count($this->arrEdges) . $this->id);
	}
	
	public function isEqual(Node $other) {
		return $this->getHash() == $other->getHash();
	}
}