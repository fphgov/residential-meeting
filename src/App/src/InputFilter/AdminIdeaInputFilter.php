<?php

declare(strict_types=1);

namespace App\InputFilter;

use Laminas\Db\Adapter\AdapterInterface;
use Laminas\Filter;
use Laminas\Validator;

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
                    'max'      => 1000,
                ]),
            ],
            'filters'     => [
                new Filter\StringTrim(),
                new Filter\StripTags(),
            ],
        ]);

        $this->add([
            'name'        => 'description',
            'allow_empty' => false,
            'validators'  => [
                new Validator\NotEmpty([
                    'messages' => [
                        Validator\NotEmpty::IS_EMPTY => 'A "Leírás" kitöltése kötelező',
                        Validator\NotEmpty::INVALID  => 'Leírás: Hibás mező tipus',
                    ],
                ]),
                new Validator\StringLength([
                    'messages' => [
                        Validator\StringLength::TOO_SHORT => 'Legalább %min% karaktert kell tartalmaznia a "Leírás" mezőnek',
                        Validator\StringLength::TOO_LONG  => 'Kevesebb karaktert kell tartalmaznia a "Leírás" mezőnek mint: %max%',
                        Validator\StringLength::INVALID   => 'Leírás: Hibás mező tipus. Csak szöveg fogadható el.',
                    ],
                    'min'      => 200,
                    'max'      => 4000,
                ]),
            ],
            'filters'     => [
                new Filter\StringTrim(),
                new Filter\StripTags(),
            ],
        ]);
    }
}
