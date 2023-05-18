<?php

declare(strict_types=1);

namespace App\InputFilter;

use Laminas\Filter;
use Laminas\InputFilter\InputFilter;
use Laminas\Validator;

/** phpcs:disable */
class ForgotAccountFirstCheckFilter extends InputFilter
{
    public function init()
    {
        $this->add([
            'name'        => 'email',
            'allow_empty' => false,
            'validators'  => [
                new Validator\EmailAddress([
                    'messages' => [
                        Validator\EmailAddress::INVALID            => "Érvénytelen típus megadva. Szöveg adható meg.",
                        Validator\EmailAddress::INVALID_FORMAT     => "A bevitel nem érvényes e-mail cím. Használja az alapformátumot pl. email@kiszolgalo.hu",
                        Validator\EmailAddress::INVALID_HOSTNAME   => "'%hostname%' érvénytelen gazdagépnév",
                        Validator\EmailAddress::INVALID_MX_RECORD  => "'%hostname%' úgy tűnik, hogy az e-mail címhez nincs érvényes MX vagy A rekordja",
                        Validator\EmailAddress::INVALID_SEGMENT    => "'%hostname%' is not in a routable network segment. The email address should not be resolved from public network",
                        Validator\EmailAddress::DOT_ATOM           => "'%localPart%' can not be matched against dot-atom format",
                        Validator\EmailAddress::QUOTED_STRING      => "'%localPart%' nem illeszthető idézőjel a szövegbe",
                        Validator\EmailAddress::INVALID_LOCAL_PART => "'%localPart%' nem érvényes az e-mail cím helyi része",
                        Validator\EmailAddress::LENGTH_EXCEEDED    => "A szöveg meghaladja az engedélyezett hosszúságot",
                    ],
                ]),
                new Validator\StringLength([
                    'messages' => [
                        Validator\StringLength::TOO_SHORT => 'Legalább %min% karaktert kell tartalmaznia a mezőnek',
                        Validator\StringLength::TOO_LONG  => 'Kevesebb karaktert kell tartalmaznia a mezőnek mint: %max%',
                        Validator\StringLength::INVALID   => 'Hibás mező tipus. Csak szöveg fogadható el',
                    ],
                    'min'      => 5,
                    'max'      => 255,
                ]),
            ],
            'filters'     => [
                new Filter\StringTrim(),
                new Filter\StripTags(),
                new Filter\StringToLower(),
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
                        Validator\Callback::INVALID_VALUE    => 'Csak elfogadás utána tudjuk fogadni a patkány bejelentést',
                        Validator\Callback::INVALID_CALLBACK => 'Ismeretlen hiba',
                    ],
                    'callback' => function ($value) {
                        return $value === true || $value === "true";
                    },
                ]),
            ],
            'filters'     => [
                new Filter\Boolean([
                    'casting' => false,
                ]),
            ],
        ]);
    }
}
/** phpcs:enable */
