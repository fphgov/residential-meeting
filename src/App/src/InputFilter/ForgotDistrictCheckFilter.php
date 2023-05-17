<?php

declare(strict_types=1);

namespace App\InputFilter;

use Laminas\Db\Adapter\AdapterInterface;
use Laminas\Filter;
use Laminas\InputFilter\InputFilter;
use Laminas\Validator;

/** phpcs:disable */
class ForgotDistrictCheckFilter extends InputFilter
{
    public function __construct(
        private AdapterInterface $dbAdapter
    ) {
        $this->dbAdapter = $dbAdapter;
    }

    public function init()
    {
        $this->add([
            'name'        => 'district',
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
                    'table'    => 'forgot_district',
                    'field'    => 'name',
                    'adapter'  => $this->dbAdapter,
                    'messages' => [
                        Validator\Db\RecordExists::ERROR_NO_RECORD_FOUND => 'Ismeretlen kerület',
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
