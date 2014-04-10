<?php

class Record extends \Phalcon\Mvc\Model
{

	public function setSource()
	{
		return 'record';
	}
	
	public function initialize()
	{
		
	}
	
	public function columnMap()
    {
        return array(
            'id' => 'id',
            'time' => 'theTime',
            'name' => 'name'
        );
    }
}
