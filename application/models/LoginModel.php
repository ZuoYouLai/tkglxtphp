<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 模型名称
 * Enter description here ...
 * @author BenBen
 *
 */
class LoginModel extends CI_Model {
	function __construct() {
		parent::__construct();
	}
	/**
	 * 添加
	 * Enter description here ...
	 * @param $dataArray
	 */
	function add($dataArray){
		$this->db->insert($this->tableName, $dataArray);
		return $this->db->insert_id();
	}
	
}