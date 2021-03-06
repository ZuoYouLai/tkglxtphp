<?php
defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * 圈子模型
 * Enter description here ...
 * @author BenBen
 *
 */
class CircleModel extends CI_Model {
	private $tableName='circle';
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
	 * 根据条件查询集合
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
	/**
	 * 分页函数
	 * Enter description here ...
	 * @param $pageSize
	 * @param $pageIndex
	 * @param $where
	 * @param $orderBy
	 */
	function getCircleByPage($pageSize,$pageIndex,$strWhere,$orderBy){
		$sql = "SELECT a.*,b.name AS parentName FROM jooli_".$this->tableName." a INNER JOIN jooli_".$this->tableName." b ON a.pid=b.id ";
		if ($strWhere != '')
		$sql .= " WHERE " . $strWhere;
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
	function getCircleCountByWhere($strWhere){
		$sql = "SELECT a.*,b.name AS parentName FROM jooli_".$this->tableName." a INNER JOIN jooli_".$this->tableName." b ON a.pid=b.id ";
		if ($strWhere != '')
		$sql .= " WHERE " . $strWhere;
			
		$query = $this->db->query($sql);
		return $query->num_rows();
	}
	/**
	 * 分页函数
	 * Enter description here ...
	 * @param $pageSize
	 * @param $pageIndex
	 * @param $where
	 * @param $orderBy
	 */
	function getListByPage($pageSize,$pageIndex,$strWhere,$orderBy){
		$sql = "SELECT * FROM jooli_".$this->tableName;
		if ($strWhere != '')
		$sql .= " WHERE " . $strWhere;
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
	function getListCountByWhere($strWhere){
		$sql = "SELECT * FROM jooli_".$this->tableName;
		if ($strWhere != '')
		$sql .= " WHERE " . $strWhere;
			
		$query = $this->db->query($sql);
		return $query->num_rows();
	}
}