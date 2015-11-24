<?php
class JSONX
{
	protected $file;
	protected $data=array();


/*
	this constructor set main json file path 
	otherwise create it and read file contents 
	and decode as an array and store it in $this->data
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

		$this->file=$path;

		$data=file_get_contents($this->file);
		$this->data=json_decode($data, true);
	}


/*
saveData()

This function helps to you to save or update data or value in specific node

@param 		:	string $node // ':' colon separeted string
@param 		: 	string/int $value
@param 		: 	boolean $array

@return 	: 	json otherwise false
*/


	public function saveData($node, $value, $array=false)
	{
		$json='';
		$node=explode(':', $node);

		$data = &$this->data;
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


		$json=json_encode($this->data);

	    if(file_put_contents($this->file, $json)){
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

	public function getNodeValue($node)
	{
		$data = $this->data;
	    $path=$node=explode(':', $node);
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

	public function nodeDelete($node)
	{
		$json='';
		$node=explode(':', $node);

		$data = &$this->data;
	    $finalKey = array_pop($node);
	    foreach ($node as $key) {
	        $data = &$data[$key];
	    }

	    if(isset($data[$finalKey])){
	    	unset($data[$finalKey]);
	    }else{
	    	return false;
	    }


		$json=json_encode($this->data);

	    if(file_put_contents($this->file, $json)){
	    	return $json;
	    }

	    return false;

	}
}

