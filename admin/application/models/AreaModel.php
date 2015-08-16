<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 权限模型
 * Enter description here ...
 * @author BenBen
 *
 */
class AreaModel extends CI_Model {
	private $tableName='area';
	function __construct() {
		parent::__construct();
	}
	/**
	 * 添加权限
	 * Enter description here ...
	 * @param $dataArray
	 */
	function add($dataArray){
		$this->db->insert($this->tableName, $dataArray);
		return $this->db->insert_id();
	}
	/**
	 * 修改权限信息
	 * Enter description here ...
	 * @param $dataArray
	 * @param $where
	 */
	function edit($dataArray,$where){
		return $this->db->update($this->tableName, $dataArray,$where);
	}
	/**
	 * 删除权限
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
	function get($where){
		$query=$this->db->where($where)->get($this->tableName);
		return $query->row_array();
	}
	/**
	 * 查询集合
	 * Enter description here ...
	 * @param $strWhere
	 */
	function getListByWhere($strWhere){
		$sql = "SELECT * FROM jooli_".$this->tableName." ";
		if ($strWhere != '')
			$sql .= "WHERE " . $strWhere;
		
		$query = $this->db->query($sql);
		return $query->result_array();
	}
	/**
	 * 分页函数
	 * Enter description here ...
	 * @param $pageSize
	 * @param $pageIndex
	 * @param $where
	 * @param $orderBy
	 */
	function getByPage($pageSize,$pageIndex,$strWhere,$orderBy){
		//$sql = "SELECT b.* FROM jooli_area a INNER JOIN jooli_area b on a.id = b.pid ORDER BY b.id ";
		$sql = "SELECT * FROM jooli_".$this->tableName." ";
		if ($strWhere != '')
			$sql .= "WHERE " . $strWhere;
		if ($orderBy != '')
			$sql .= " ORDER BY " . $orderBy;

		$sql .= " LIMIT " . ($pageSize * $pageIndex - $pageSize) . "," . $pageSize;  //print_r($sql);exit;
		$query = $this->db->query($sql);
		return $query->result_array();
	}
	/**
	 * 根据条件获取数量
	 * Enter description here ...
	 * @param $strWhere
	 */
	function getCountByWhere($strWhere){
		$sql = "SELECT * FROM jooli_".$this->tableName." " ;
		if ($strWhere != '')
			$sql .= "WHERE " . $strWhere;
			
		$query = $this->db->query($sql);
		return $query->num_rows(); 
	}
}