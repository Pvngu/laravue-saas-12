<?php

namespace App\Casts;

use Sqids\Sqids;
use Illuminate\Contracts\Database\Eloquent\CastsInboundAttributes;

class Hash implements CastsInboundAttributes
{
	/**
	 * The hashing algorithm.
	 *
	 * @var string
	 */
	protected $algorithm;

	/**
	 * Create a new cast class instance.
	 *
	 * @param  string|null  $algorithm
	 * @return void
	 */
	public function __construct($algorithm = null)
	{
		$this->algorithm = $algorithm;
	}

	/**
	 * Prepare the given value for storage.
	 *
	 * @param  \Illuminate\Database\Eloquent\Model  $model
	 * @param  string  $key
	 * @param  array  $value
	 * @param  array  $attributes
	 * @return string
	 */
	public function set($model, $key, $value, $attributes)
	{
		$sqids = new Sqids();
		
		if ($value && !is_numeric($value)) {
			$newValue = $sqids->decode((string) $value);
			$value = $newValue && $newValue[0] ? $newValue[0] : $value;
		}

		return $value;
	}
}
