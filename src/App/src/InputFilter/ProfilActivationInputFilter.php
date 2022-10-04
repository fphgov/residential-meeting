<?php

declare(strict_types=1);

namespace App\InputFilter;

use Laminas\Filter;
use Laminas\InputFilter\InputFilter;
use Laminas\Validator;

class ProfilActivationInputFilter extends InputFilter
{
    public function init()
    {
        $this->add([
            'name'        => 'profile_save',
            'allow_empty' => false,
            'filters'     => [
                new Filter\StringTrim(),
                new Filter\StripTags(),
            ],
        ]);

        $this->add([
            'name'        => 'newsletter',
            'allow_empty' => false,
            'filters'     => [
                new Filter\StringTrim(),
                new Filter\StripTags(),
            ],
        ]);

        $this->add([
            'name'        => 'live_in_city',
            'allow_empty' => false,
            'validators'  => [
                new Validator\NotEmpty([
                    'messages' => [
                        Validator\NotEmpty::IS_EMPTY => 'Kötelező a mező kitöltése',
                        Validator\NotEmpty::INVALID  => 'Hibás mező tipus',
                    ],
                ]),
                new Validator\Callback([
                    'messages' => [
                        Validator\Callback::INVALID_VALUE    => 'Fiókodat csak akkor tudjuk aktíválni, amennyiben megerősíted a fentieket',
                        Validator\Callback::INVALID_CALLBACK => 'Ismeretlen hiba',
                    ],
                    'callback' => function ($value, $context = []) {
                        if ($context['profile_save'] === 'false') {
                            return true;
                        }

                        if ($value === null || empty($value) || $value === 'false') {
                            return false;
                        }

                        return true;
                    }
                ]),
            ],
            'filters'     => [
                new Filter\StringTrim(),
                new Filter\StripTags(),
            ],
        ]);

        $this->add([
            'name'        => 'privacy',
            'allow_empty' => false,
            'validators'  => [
                new Validator\NotEmpty([
                    'messages' => [
                        Validator\NotEmpty::IS_EMPTY => 'Kötelező a mező kitöltése',
                        Validator\NotEmpty::INVALID  => 'Hibás mező tipus',
                    ],
                ]),
                new Validator\Callback([
                    'messages' => [
                        Validator\Callback::INVALID_VALUE    => 'Fiókodat csak akkor tudjuk aktíválni, amennyiben megerősíted a fentieket',
                        Validator\Callback::INVALID_CALLBACK => 'Ismeretlen hiba',
                    ],
                    'callback' => function ($value) {
                        return $value === true || $value === "true" || $value === "on";
                    },
                ]),
                new Validator\Callback([
                    'messages' => [
                        Validator\Callback::INVALID_VALUE    => 'Köszönjük, hogy elfogadod az adatkezlési nyilatkozatot. Azonban el kell fogadnod a fiók megőrzését és/vagy fel kell iratkozni hírlevél. Ha egyiket sem szeretnéd, az e-mailben jelzett határidővel megszüntetjük a fiókodat.',
                        Validator\Callback::INVALID_CALLBACK => 'Ismeretlen hiba',
                    ],
                    'callback' => function ($value, $context = []) {
                        if (
                            $value === "true" && (
                                $context['profile_save'] === 'false' &&
                                $context['newsletter'] === 'false'
                            )
                        ) {
                            return false;
                        }

                        return true;
                    }
                ]),
            ],
            'filters'     => [
                new Filter\StringTrim(),
                new Filter\StripTags(),
            ],
        ]);
    }
}
