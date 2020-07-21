<?php

declare(strict_types=1);

namespace App\InputFilter;

use Laminas\Filter;
use Laminas\I18n\Validator\IsInt;
use Laminas\Validator;
use Laminas\InputFilter\InputFilter;

class ProjectInputFilter extends InputFilter
{
    public function init()
    {
        $this->add([
            'name'              => 'title',
            'allow_empty'       => false,
            'validators'        => [
                new Validator\StringLength([
                    'min' => 3,
                    'max' => 255,
                ]),
            ],
            'filters'           => [
                new Filter\StringTrim(),
                new Filter\StripTags(),
            ],
        ]);
        
        $this->add([
            'name'              => 'description',
            'allow_empty'       => false,
            'validators'        => [
                new Validator\StringLength([
                    'min' => 4,
                    'max' => 10000,
                ]),
            ],
            'filters'           => [
                new Filter\StringTrim(),
                new Filter\StripTags(),
            ],
        ]);
        
        $this->add([
            'name'              => 'location',
            'allow_empty'       => false,
            'validators'        => [
                new Validator\StringLength([
                    'min' => 1,
                    'max' => 100,
                ]),
            ],
            'filters'           => [
                new Filter\StringTrim(),
                new Filter\StripTags(),
            ],
        ]);

        $this->add([
            'name'              => 'cost',
            'allow_empty'       => false,
            'validators'        => [
                new Validator\NotEmpty(),
                new IsInt(),
            ],
            'filters'           => [
                new Filter\ToInt(),
            ],
            'fallback_value'    => 0,
        ]);
    }
}
