<?php
declare (strict_types = 1);

namespace app\validate;

class TranslateValidate extends BaseValidate
{
    protected $rule = [
        'content' => 'require',
    ];

    protected $message = [];
}
