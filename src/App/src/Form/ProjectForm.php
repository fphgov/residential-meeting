<?php

declare(strict_types=1);

namespace App\Form;

use Laminas\Form\Element;
use Laminas\Form\Form as LaminasForm;
use Laminas\Hydrator\ClassMethodsHydrator;
use Laminas\InputFilter\InputFilterProviderInterface;
use Laminas\Validator;

class ProjectForm extends LaminasForm implements InputFilterProviderInterface
{
    public function __construct(?string $name = null)
    {
        $name = $name ?? 'project-form';
        parent::__construct($name);
    }

    public function init()
    {
        $this->setAttribute('method', 'post')->setHydrator(new ClassMethodsHydrator());

        $this->add([
            'type'    => Element\Text::class,
            'name'    => 'title',
            'options' => [
                'label' => 'Title',
            ],
        ]);

        $this->add([
            'type'    => Element\Text::class,
            'name'    => 'description',
            'options' => [
                'label' => 'Description',
            ],
        ]);

        $this->add([
            'type'    => Element\Text::class,
            'name'    => 'cost',
            'options' => [
                'label' => 'Cost',
            ],
        ]);

        $this->add([
            'type'    => Element\Text::class,
            'name'    => 'status',
            'options' => [
                'label' => 'Status',
            ],
        ]);

        $this->add([
            'type'    => Element\Text::class,
            'name'    => 'location',
            'options' => [
                'label' => 'Location',
            ],
        ]);
    }

    public function getInputFilterSpecification(): array
    {
        return [
            'title'       => [
                'required'   => true,
                'validators' => [
                    new Validator\NotEmpty(),
                    // new Validator\Db\NoRecordExists([
                    //     'table'   => 'projects',
                    //     'field'   => 'protocolNumber',
                    //     'adapter' => GlobalAdapterFeature::getStaticAdapter(),
                    //     'exclude' => [
                    //         'field' => 'id',
                    //         'value' => is_int($projectId) ? $projectId : '',
                    //     ],
                    // ]),
                ],
            ],
            'description' => [
                'required'   => true,
                'validators' => [
                    new Validator\NotEmpty(),
                ],
            ],
            'cost'        => [
                'required'   => true,
                'validators' => [
                    new Validator\NotEmpty(),
                ],
            ],
            'status'      => [
                'required'   => true,
                'validators' => [
                    new Validator\NotEmpty(),
                ],
            ],
            'location'    => [
                'required'   => true,
                'validators' => [
                    new Validator\NotEmpty(),
                ],
            ],
        ];
    }
}
