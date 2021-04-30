<?php

declare(strict_types=1);

namespace App\InputFilter;

use Laminas\Filter;
use Laminas\Db\Adapter\AdapterInterface;
use Laminas\I18n\Validator\IsInt;
use Laminas\InputFilter\InputFilter;
use Laminas\Validator;

/** phpcs:disable */
class VoteFilter extends InputFilter
{
    /** @var AdapterInterface */
    private $dbAdapter;

    public function __construct(
        AdapterInterface $dbAdapter
    ) {
        $this->dbAdapter = $dbAdapter;
    }

    public function init()
    {
        $this->add([
            'name'        => 'rk_vote_CARE',
            'allow_empty' => false,
            'validators'  => [
                new Validator\NotEmpty([
                    'messages' => [
                        Validator\NotEmpty::IS_EMPTY => 'Kötelező a mező kitöltése',
                        Validator\NotEmpty::INVALID  => 'Hibás mező tipus',
                    ],
                ]),
                new IsInt([
                    'messages' => [
                        IsInt::INVALID        => 'Csak számérték adható meg',
                        IsInt::NOT_INT        => 'Csak egész számérték adható meg',
                        IsInt::NOT_INT_STRICT => 'Csak egész számérték adható meg',
                    ]
                ]),
                new Validator\Db\RecordExists([
                    'table'    => 'projects',
                    'field'    => 'id',
                    'adapter'  => $this->dbAdapter,
                    'messages' => [
                        Validator\Db\RecordExists::ERROR_NO_RECORD_FOUND => 'Nem található a kiválasztott projekt',
                        Validator\Db\RecordExists::ERROR_RECORD_FOUND    => '',
                    ]
                ])
            ],
            'filters'     => [
                new Filter\StringTrim(),
                new Filter\StripTags(),
            ],
        ]);

        $this->add([
            'name'        => 'rk_vote_GREEN',
            'allow_empty' => false,
            'validators'  => [
                new Validator\NotEmpty([
                    'messages' => [
                        Validator\NotEmpty::IS_EMPTY => 'Kötelező a mező kitöltése',
                        Validator\NotEmpty::INVALID  => 'Hibás mező tipus',
                    ],
                ]),
                new IsInt([
                    'messages' => [
                        IsInt::INVALID        => 'Csak számérték adható meg',
                        IsInt::NOT_INT        => 'Csak egész számérték adható meg',
                        IsInt::NOT_INT_STRICT => 'Csak egész számérték adható meg',
                    ]
                ]),
                new Validator\Db\RecordExists([
                    'table'    => 'projects',
                    'field'    => 'id',
                    'adapter'  => $this->dbAdapter,
                    'messages' => [
                        Validator\Db\RecordExists::ERROR_NO_RECORD_FOUND => 'Nem található a kiválasztott projekt',
                        Validator\Db\RecordExists::ERROR_RECORD_FOUND    => '',
                    ]
                ])
            ],
            'filters'     => [
                new Filter\StringTrim(),
                new Filter\StripTags(),
            ],
        ]);

        $this->add([
            'name'        => 'rk_vote_WHOLE',
            'allow_empty' => false,
            'validators'  => [
                new Validator\NotEmpty([
                    'messages' => [
                        Validator\NotEmpty::IS_EMPTY => 'Kötelező a mező kitöltése',
                        Validator\NotEmpty::INVALID  => 'Hibás mező tipus',
                    ],
                ]),
                new IsInt([
                    'messages' => [
                        IsInt::INVALID        => 'Csak számérték adható meg',
                        IsInt::NOT_INT        => 'Csak egész számérték adható meg',
                        IsInt::NOT_INT_STRICT => 'Csak egész számérték adható meg',
                    ]
                ]),
                new Validator\Db\RecordExists([
                    'table'    => 'projects',
                    'field'    => 'id',
                    'adapter'  => $this->dbAdapter,
                    'messages' => [
                        Validator\Db\RecordExists::ERROR_NO_RECORD_FOUND => 'Nem található a kiválasztott projekt',
                        Validator\Db\RecordExists::ERROR_RECORD_FOUND    => '',
                    ]
                ])
            ],
            'filters'     => [
                new Filter\StringTrim(),
                new Filter\StripTags(),
            ],
        ]);
    }
}
/** phpcs:enable */
