<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * ģ������
 * Enter description here ...
 * @author BenBen
 *
 */
class LoginModel extends CI_Model {
	function __construct() {
		parent::__construct();
	}
	/**
	 * ���
	 * Enter description here ...
	 * @param $dataArray
	 */
	function add($dataArray){
		$this->db->insert($this->tableName, $dataArray);
		return $this->db->insert_id();
	}
	
}