<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 表情模型
 * Enter description here ...
 * @author BenBen
 *
 */
class IconsModel extends CI_Model {
	private $tableName='icons';
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
	/**
	 * 修改
	 * Enter description here ...
	 * @param $dataArray
	 * @param $where
	 */
	function edit($dataArray,$where){
		return $this->db->update($this->tableName, $dataArray,$where);
	}
	/**
	 * 删除
	 * Enter description here ...
	 * @param $strWhere
	 */
	function del($where){
		return $this->db->delete($this->tableName, $where);
	}
	/**
	 * 获取对象
	 * Enter description here ...
	 * @param unknown_type $where
	 */
	function getModel($where){
		$query=$this->db->where($where)->get($this->tableName);
		return $query->row_array();
	}
	/**
	 * 
	 * Enter description here ...
	 * @param $strWhere
	 */
	function getList($strWhere){
		$sql = "SELECT * FROM jooli_".$this->tableName;
		if ($strWhere != '')
		$sql .= " WHERE " . $strWhere;
		
		$query = $this->db->query($sql);
		return $query->result_array();
	}
}