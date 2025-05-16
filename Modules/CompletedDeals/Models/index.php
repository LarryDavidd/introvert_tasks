<?php

namespace Modules\CompletedDeals\Models;

use System\Model;

class Index extends Model{
	protected static $instance;

	protected array $validationRules = [
		'title' => 'required|min:6|max:20',
		'content' => 'required|min:20'
	];
}