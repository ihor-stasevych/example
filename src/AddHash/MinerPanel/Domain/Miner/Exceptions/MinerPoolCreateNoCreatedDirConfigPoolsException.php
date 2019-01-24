<?php
/**
 * Created by PhpStorm.
 * User: viacheslav
 * Date: 2019-01-24
 * Time: 15:10
 */

namespace App\AddHash\MinerPanel\Domain\Miner\Exceptions;


class MinerPoolCreateNoCreatedDirConfigPoolsException extends \Exception
{
    protected $code = 400;
}