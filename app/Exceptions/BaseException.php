<?php

namespace App\Exceptions;

use Exception;

abstract class BaseException extends Exception
{
    /**
     * @var string
     */
    protected $status;

    /**
     * @var string 存放错误位置（string限制）
     */
    protected $message;

    /**
     * @var
     */
    protected $inform;

    /**
     * @param @string $message
     * @return void
     */
    function __construct($message)
    {
        parent::__construct($message);
    }

    /**
     * Get the status
     *
     * @return int
     */
    public function getStatusCode()
    {
        return (int)$this->status;
    }

    /**
     * Description : 返回错误信息（支持数组等）
     * Auth : Shelter
     *
     * @return array
     */
    public function getError()
    {
        return $this->inform;
    }

    /**
     * Description : 返回错误位置
     * Auth : Shelter
     *
     * @return string
     */
    public function getPosition()
    {
        return $this->message;
    }
}