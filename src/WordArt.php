<?php

namespace Sun;

use Sun\Constract\WordArtInterface;
use Sun\Utils\Curl;
use Sun\Db\WordsDb;

class WordArt implements WordArtInterface
{
	const URL = 'http://guozhivip.com/yy/api/api.php';

	/**
	 * 获取一句
	 *
	 * @return array
	 */
	public function getOne()
	{
		$dbData = $this->getFromDatabase();
		if (!empty($dbData)) {
			return $this->formatWords($dbData);
		}
		$spiderData = $this->searchFromYiYan();
		if (empty($spiderData)) {
			return [];
		}
		return $this->formatWords($spiderData);
	}

	/**
	 * 从每日一言抓取数据入库
	 *
	 * @return array
	 */
	public function searchFromYiYan()
	{
		$data = Curl::run(self::URL);
		if (empty($data)) {
			return null;
		}
		// $data = 'document.write("世事如书，我偏爱你这一句。愿做个逗号，待在你脚边。——张嘉佳")';
		// 数据处理
		$str = str_replace(['document.write("', '");'], '', $data);
		$array = explode('——', $str);
		
		$returnData = [
			'date' => date('Y-m-d'),
			'from' => isset($array[1]) ? $array[1] : '未知',
			'content' => $array[0]
		];

		$this->setToDb($returnData);
		return [$returnData];
	}

	/**
	 * 从数据库获取今日一句
	 *
	 * @return array
	 */
	public function getFromDatabase()
	{
		$wordsDb = new WordsDb();
		$selectRes = $wordsDb->select();
		return empty($selectRes) ? [] : $selectRes;
	}

	/**
	 * 数据入库
	 *
	 * @param  array $data
	 * @return int 
	 */
	public function setToDb($data)
	{
		$wordsDb = new WordsDb();
		$insertId = $wordsDb->insert($data);
		return $insertId;
	}

	/**
	 * 格式化数据，用于commandTable数据渲染
	 *
	 * @param  array $data
	 * @return array
	 */
	private function formatWords($data)
	{
		foreach ($data as &$value) {
			$value['date'] = sprintf("<fg=cyan>%s</>", $value['date']);
			$value['from'] = sprintf("<fg=red;options=bold>%s</>", $value['from']);
			$value['content'] = sprintf("<fg=yellow;options=bold>%s</>", $value['content']);
		}
		unset($value);
		return $data;
	}
}
