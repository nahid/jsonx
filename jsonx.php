<?php
class JSONX
{
	protected $_file;
	protected $_node='';
	protected $_data=array();
	
	/**
	 * Stores where conditions
	 * @var array
	 */
	protected $_andConditions = [];

	/**
	 * Stores orWhere conditions
	 * @var array
	 */
	protected $_orConditions = [];

	protected $_calculatedData = null;

	protected $_conditions = [
		'>'=>'greater',
		'<'=>'less',
		'='=>'equal',
		'!='=>'notequal',
		'>='=>'greaterequal',
		'<='=>'lessequal',
		];

	/*
		this constructor set main json file path
		otherwise create it and read file contents
		and decode as an array and store it in $this->_data
	*/
	function __construct($path)
	{
		if(!file_exists($path)){
			$pathInfo=pathinfo($path);

			mkdir($pathInfo['dirname'], 0755, true);
			$file=fopen($path, 'w+') or die("Unable to open file!");
			fwrite($file, '{}');
			fclose($path);
		}

		$this->_file=$path;

		$data=file_get_contents($this->_file);
		$this->_data=json_decode($data, true);
	}

	public function node($node=null)
	{
		if(is_null($node) || $node=='') return false;

		$this->_node=explode(':', $node);
		return $this;
	}

	private function getData()
	{
		$terminate=false;
		$data = $this->_data;
	    $path=$this->_node;

	    foreach($path as $val){

	    	if(!isset($data[$val])){
				$terminate=true;
				break;
	    	}

	    	$data=$data[$val];
	    }


		if($terminate) return false;
		return $data;
	}
	
	private function runFilter($data, $key, $condition, $value)
	{
	    $func ='where'. ucfirst($this->_conditions[$condition]);
	    return $this->$func($data, $key, $value);
	}

	private function makeWhere($rule, $key=null, $condition=null, $value=null)
	{
		$data = $this->getData();
		$calculatedData = $this->runFilter($data, $key, $condition, $value);
		if(!is_null($this->_calculatedData)) {
			if($rule=='and')
				$calculatedData = array_intersect(array_keys($this->_calculatedData), array_keys($calculatedData));		
			if($rule=='or')
				$calculatedData = array_merge(array_keys($this->_calculatedData), array_keys($calculatedData));
			$this->_calculatedData='';

			foreach ($calculatedData as $value) {
				$this->_calculatedData[$value]= $data[$value];
			}
			return true;
		}
		$this->_calculatedData = $calculatedData;
		return true;
	}
	public function where($key=null, $condition=null, $value=null)
	{
		$this->makeWhere('and', $key, $condition, $value);
		return $this;
	}


	public function orWhere($key=null, $condition=null, $value=null)
	{
		$this->makeWhere('or', $key, $condition, $value);
		return $this;
	}

	public function fetch()
	{
		if(is_null($this->_calculatedData)) {
			$terminate=false;
			$data = $this->_data;
		    $path=$this->_node;

		    foreach($path as $val){

		    	if(!isset($data[$val])){
					$terminate=true;
					break;
		    	}

		    	$data=$data[$val];
		    }
		    return $data;
		}

		return $this->_calculatedData;
	}
	/*
	saveData()

	This function helps to you to save or update data or value in specific node

	@param 		:	string $node // ':' colon separeted string
	@param 		: 	string/int $value
	@param 		: 	boolean $array

	@return 	: 	json otherwise false
	*/
	public function save($value, $array=false)
	{
		$json='';
		$node=$this->_node;

		$data = &$this->_data;
	    $finalKey = array_pop($node);
	    foreach ($node as $key) {
	        $data = &$data[$key];
	    }

	    if(is_array($data[$finalKey])){
	    	array_push($data[$finalKey], $value);
	    }else{
	    	if($array==true){
		    	$data[$finalKey] = [$value];

	    	}else{
		    	$data[$finalKey] = $value;
	    	}
		}


		$json=json_encode($this->_data, JSONS_PRETTY_PRINT);

	    if(file_put_contents($this->_file, $json)){
	    	return $json;
	    }

	    return false;

	}


/*
getNodeValue()

This method helps to you to find or get specific node value.

@param 		: 	string $node // ':' colon separeted string

@return 	: 	string/false
*/

	public function delete()
	{
		$json='';
		$node=$this->_node;

		$data = &$this->_data;
	    $finalKey = array_pop($node);
	    foreach ($node as $key) {
	        $data = &$data[$key];
	    }

	    if(isset($data[$finalKey])){
	    	unset($data[$finalKey]);
	    }else{
	    	return false;
	    }


		$json=json_encode($this->_data);

	    if(file_put_contents($this->_file, $json)){
	    	return $json;
	    }

	    return false;

	}



	protected function whereGreater($data, $key, $value)
	{
		return array_filter($data, function($var) use($key, $value){
			if(isset($var[$key]))
			if($var[$key]>$value){
				return $var;
			}
		});
	}

	protected function whereLess($data, $key, $value)
	{
		return array_filter($data, function($var) use($key, $value){
			if(isset($var[$key]))
			if($var[$key]<$value){
				return $var;
			}
		});
	}

	protected function whereEqual($data, $key, $value)
	{
		return array_filter($data, function($var) use($key, $value){
			if(isset($var[$key]))
			if($var[$key]==$value){
				return $var;
			}
		});
	}

	protected function whereGreaterequal($data, $key, $value)
	{
		return array_filter($data, function($var) use($key, $value){
			if(isset($var[$key]))
			if($var[$key]>=$value){
				return $var;
			}
		});
	}
	protected function whereLessequal($data, $key, $value)
	{
		return array_filter($data, function($var) use($key, $value){
			if(isset($var[$key]))
			if($var[$key]<=$value){
				return $var;
			}
		});
	}
}
