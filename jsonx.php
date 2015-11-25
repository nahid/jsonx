<?php
class JSONX
{
	protected $_file;
	protected $_node='';
	protected $_data=array();




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
		if(is_null($node)) return false;

		$this->_node=$node;
		return $this;
	}

	public function fetch()
	{
		$data = $this->_data;
	    $path=explode(':', $this->_node);
	    $node=$path;
	    end($node);
	    $finalKey = key($path);

	    foreach($path as $val){

	    	if(!isset($data[$val])){
	    		break;
	    		return false;

	    	}
	    		$data=$data[$val];


	    }


	    return $data;
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
		$node=explode(':', $this->_node);

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


		$json=json_encode($this->_data);

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
		$node=explode(':', $this->_node);

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
}
