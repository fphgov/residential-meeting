<?php

declare(strict_types=1);

namespace App\InputFilter;

use Laminas\Db\Adapter\AdapterInterface;
use Laminas\Filter;
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
            'name'        => 'projects',
            'allow_empty' => false,
            'validators'  => [
                new Validator\NotEmpty([
                    'messages' => [
                        Validator\NotEmpty::IS_EMPTY => 'Kötelező a projektek kiválasztása',
                        Validator\NotEmpty::INVALID  => 'Hibás mező tipus',
                    ],
                ]),
            ],
            'filters'     => [
            ],
        ]);
    }
}
/** phpcs:enable */
