<?php
/**
 * 
 * @author drthomas
 * @property Array<Node> $arrNodes
 * @property string $name 
 */
abstract class Edge {
	protected $arrNodes = array();
	protected $name = "";
	protected $id;
	
	/**
	 * 
	 * @param Node $object
	 * @throws EdgeNodesAreFullException
	 */
	public function addNode(Node $object) {
		if(count($this->arrNodes) < 2) {
			$this->arrNodes[] = $object;
			$object->addEdge($this);
		} else {
			throw new EdgeNodesAreFullException();
		}
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
	 * @return Array<Node> $nodes
	 */
	public function getNodes() {
		return $this->arrNodes;
	}
	
	public function getHash() {
		return sha1($this->name . count($this->arrNodes) . $this->id);
	}
	
	public function isEqual(Edge $other) {
		return $this->getHash == $other->getHash();
	}
	
	/**
	 * 
	 * @param Node $head
	 * @return Node $tail
	 */
	abstract function getNextNode(Node $head);
	
	/**
	 * 
	 * @param Node $node
	 * @return Node $node
	 */
	abstract function getOtherNode(Node $node);
}

class EdgeNodesAreFullException extends Exception {
	function __construct($code = 100, $previous = NULL) {
		parent::__construct("This edge already has two edges", $code, $previous);
	}
}