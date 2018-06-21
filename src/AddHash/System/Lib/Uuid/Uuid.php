<?php

namespace AddHash\System\Lib\Uuid;

class Uuid
{
	/**
	 * @return string
	 */
	public static function generate() : string
	{
		return \Ramsey\Uuid\Uuid::uuid4()->toString();
	}
}