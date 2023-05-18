<?php

declare(strict_types=1);

namespace App\InputFilter;

use Laminas\Db\Adapter\AdapterInterface;
use Laminas\Filter;
use Laminas\InputFilter\InputFilter;
use Laminas\Validator;

/** phpcs:disable */
class ForgotTokenCheckFilter extends InputFilter
{
    public function __construct(
        private AdapterInterface $dbAdapter
    ) {
        $this->dbAdapter = $dbAdapter;
    }

    public function init()
    {
        $this->add([
            'name'        => 'token',
            'allow_empty' => false,
            'required'    => true,
            'validators'  => [
                new Validator\NotEmpty([
                    'messages' => [
                        Validator\NotEmpty::IS_EMPTY => 'Kötelező a mező kitöltése',
                        Validator\NotEmpty::INVALID  => 'Hibás mező tipus',
                    ],
                ]),
                new Validator\Db\RecordExists([
                    'table'    => 'forgot_account',
                    'field'    => 'token',
                    'adapter'  => $this->dbAdapter,
                    'messages' => [
                        Validator\Db\RecordExists::ERROR_NO_RECORD_FOUND => 'Ismeretlen token',
                        Validator\Db\RecordExists::ERROR_RECORD_FOUND    => '',
                    ]
                ]),
            ],
            'filters'     => [
                new Filter\StringTrim(),
                new Filter\StripTags(),
                new Filter\StringToUpper(),
            ],
        ]);
    }
}
/** phpcs:enable */
