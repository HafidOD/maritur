<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{
	public function getEnumLabel($attr)
	{
		$labelsAttr = $attr.'Labels';
		$labels = $this->$labelsAttr;
		return $labels[$this->$attr];
	}
}
