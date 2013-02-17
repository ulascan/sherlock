<?php
/**
 * User: Zachary Tong
 * Date: 2/16/13
 * Time: 10:23 PM
 */

namespace sherlock\components\mappings;

use sherlock\components;
use sherlock\common\exceptions;


/**
 * @method \sherlock\components\mappings\Object field() field(\string $value)
 * @method \sherlock\components\mappings\Object enabled() enabled(\bool $value)
 * @method \sherlock\components\mappings\Object path() path(\string $value)
 * @method \sherlock\components\mappings\Object dynamic() dynamic(\bool $value)
 * @method \sherlock\components\mappings\Object include_in_all() include_in_all(\bool $value)
 * @method \sherlock\components\mappings\Object object() object(\sherlock\components\MappingInterface $value)
 */
class Object extends \sherlock\components\BaseComponent implements \sherlock\components\MappingInterface
{
	protected $type;

	public function __construct($type = null, $hashMap = null)
	{
		//if $type is set, we need to wrap the mapping property in a type
		//this is used for multi-mappings on index creation
		if (isset($type))
		{
			$this->type = $type;
		}

		$this->params['type'] = 'object';
		parent::__construct($hashMap);
	}

	public function toArray()
	{
		$ret = array();
		if (!isset($this->params['field']))
		{
			\Analog\Analog::log("Field name must be set for Object mapping", \Analog\Analog::ERROR);
			throw new \sherlock\common\exceptions\RuntimeException("Field name must be set for Object mapping");
		}

		if (!isset($this->params['object']))
		{
			\Analog\Analog::log("Object parameter name must be set for Object mapping", \Analog\Analog::ERROR);
			throw new \sherlock\common\exceptions\RuntimeException("Object parameter must be set for Object mapping");
		}

		$object = $this->params['object']->toArray();


		$extra = array();
		foreach($this->params as $key => $value)
		{
			if($key == 'field' || $key == 'object')
				continue;
			$extra[$key] = $value;
		}

		$ret = array($this->params['field'] => array_merge(array("properties" => $object), $extra));

		if (isset($this->type))
			$ret = array($this->type => array("properties" => $ret));


		return $ret;

	}


}

?>