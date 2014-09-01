<?php

namespace Comment\Model;

use Application\Helper\BaseModel;
use Zend\Db\Sql\Sql;
use Zend\Db\Sql\Where;
use Zend\Console\Prompt\Select;

class CommentTable extends BaseModel
{
	static protected $database = "db_default";
	protected $table = "configs";
 
	public function get($configName = null, $configId = null, $level = 2)
	{
		$configs = array(); 
		$where = new Where();
		($configName) AND $where->equalTo("name", $configName);
		($configId) AND $where->equalTo("id", $configId);
		$where->greaterThanOrEqualTo("level", $level);
		$results = $this->select($where);  
		return $results->toArray();
	}
	
	public function getChilds($configId = null)
	{
		$childs = array();
		$sql = new Sql($this->adapter);
		$select = $sql->select();
		$select->from("config_options");
		$where = new Where();
		$where->equalTo("config_id", $configId); 
		$select->where($where);
		$statement = $sql->prepareStatementForSqlObject($select);
		$results = $statement->execute();
		foreach ($results as $result) {
			$childs[] = $result;
		}
		
		return $childs;
	}
	
	public function updateConfig($configName = null, $configId = null, $value)
	{
		$sql = new Sql($this->adapter);
		$update = $sql->update("configs");
		$where = new Where();
		($configId) AND $where->equalTo("id", $configId);
		($configName) AND $where->equalTo("name", $configName);
		if (is_array($value)) {
			$update->set(array("value" => implode(";",$value)));
		} else {
			$update->set(array("value" => $value));
		}
		$update->where($where);
		$this->updateWith($update);
	}
}