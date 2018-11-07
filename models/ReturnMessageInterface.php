<?php

namespace app\models;

interface ReturnMessageInterface
{
    public function setReturnMessage($code, $message);

    public function getReturnMessageCode();

    public function getReturnMessage();
}
