<?php

namespace Sun\Db;

class WordsDb
{
	private $connection;

	public function __construct()
	{
		$this->connection = @mysqli_connect('127.0.0.1', 'root', '12345678', 'test');
	}

	public function query($sql)
	{
		$res = mysqli_query($this->connection, $sql);
     	if (!$res) {
      		echo "sql语句执行失败".PHP_EOL;
      		echo "错误码: ".mysqli_errno($this->connection).PHP_EOL;
      		echo "错误信息: ".mysqli_error($this->connection).PHP_EOL;
      		exit;
     	}
     	return $res;
	}

	public function insert($data)
	{
		$keyStr = $valStr = '';
	    foreach ($data as $key => $v) {
	        $keyStr.="`$key`,";
	        $valStr.="'$v',";
	    }
	    $keyStr = trim($keyStr, ',');
	    $valStr = trim($valStr, ',');
	    $sql = "insert into `words` ($keyStr) values ($valStr)";
	    $this->query($sql);
	    return mysqli_insert_id($this->connection);
	}

	public function select()
	{
		$date = date('Y-m-d');
		$sql = 'select `date`,`from`,`content` from `words` where `date`='."'$date'";
		$result = $this->query($sql);
		if (empty($result)) {
			return null;
		}
		$ret = [];
	    while ($row = mysqli_fetch_assoc($result)) {
	        $ret[] = [
	        	'date' => $row['date'],
	        	'from' => $row['from'],
	        	'content' => $row['content']
	       	];
	    }
	    // free result
	    mysqli_free_result($result);
	    return $ret;
	}

	public function __destruct()
	{
		mysqli_close($this->connection);
	}
}