<?php

declare(strict_types=1);

namespace App\InputFilter;

use Laminas\Db\Adapter\AdapterInterface;

class AdminIdeaInputFilter extends IdeaInputFilter
{
    /** @var AdapterInterface */
    protected $dbAdapter;

    public function __construct(
        AdapterInterface $dbAdapter
    ) {
        parent::__construct($dbAdapter);

        $this->dbAdapter = $dbAdapter;
    }

    public function init()
    {
        parent::init();

        $this->add([
            'name'        => 'workflowState',
            'allow_empty' => true,
        ]);

        $this->add([
            'name'        => 'workflowStateExtra',
            'allow_empty' => true,
        ]);

        $this->add([
            'name'        => 'solution',
            'allow_empty' => false,
            'validators'  => [
                new Validator\NotEmpty([
                    'messages' => [
                        Validator\NotEmpty::IS_EMPTY => 'A "Min szeretnél változtatni?" kitöltése kötelező',
                        Validator\NotEmpty::INVALID  => 'Min szeretnél változtatni?: Hibás mező tipus',
                    ],
                ]),
                new Validator\StringLength([
                    'messages' => [
                        Validator\StringLength::TOO_SHORT => 'Legalább %min% karaktert kell tartalmaznia a "Min szeretnél változtatni?" mezőnek',
                        Validator\StringLength::TOO_LONG  => 'Kevesebb karaktert kell tartalmaznia a "Min szeretnél változtatni?" mezőnek mint: %max%',
                        Validator\StringLength::INVALID   => 'Min szeretnél változtatni?: Hibás mező tipus. Csak szöveg fogadható el.',
                    ],
                    'min'      => 20,
                    'max'      => 500,
                ]),
            ],
            'filters'     => [
                new Filter\StringTrim(),
                new Filter\StripTags(),
            ],
        ]);
    }
}
