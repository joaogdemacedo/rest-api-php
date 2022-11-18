<?php

namespace App\Plugins\Http\Response;

use App\Plugins\Http\JsonStatus;

class Forbidden extends JsonStatus
{
    /** @var int */
    const STATUS_CODE = 403;
    /** @var string */
    const STATUS_MESSAGE = 'Forbidden';

    /**
     * Constructor of this class
     * @param mixed $body
     */
    public function __construct($body = '')
    {
        parent::__construct(self::STATUS_CODE, self::STATUS_MESSAGE, $body);
    }
}