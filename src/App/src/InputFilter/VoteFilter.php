<?php

declare(strict_types=1);

namespace App\InputFilter;

use Laminas\Db\Adapter\AdapterInterface;
use Laminas\Filter;
use Laminas\InputFilter\InputFilter;
use Laminas\Validator;

/** phpcs:disable */
class VoteFilter extends InputFilter
{
    public function __construct(
        private AdapterInterface $dbAdapter
    ) {
        $this->dbAdapter = $dbAdapter;
    }

    public function init()
    {
        $this->add([
            'name'        => 'auth_code',
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
                    'table'    => 'accounts',
                    'field'    => 'auth_code',
                    'adapter'  => $this->dbAdapter,
                    'messages' => [
                        Validator\Db\RecordExists::ERROR_NO_RECORD_FOUND => 'Helytelen egyedi azonosító',
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

        $this->add([
            'name'        => 'email',
            'allow_empty' => true,
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
                        Validator\Callback::INVALID_VALUE    => 'Ha megadtad az e-mail címed, akkor hozzá kell járulnod ahhoz, hogy ezt az adatot kezeljük. Ezután lesz elérhető a szavazás.',
                        Validator\Callback::INVALID_CALLBACK => 'Ismeretlen hiba',
                    ],
                    'callback' => function ($value) {
                        return $value === true || $value === "true" || $value === "on";
                    },
                ]),
            ],
        ]);

        $this->add([
            'name'        => 'newsletter',
            'allow_empty' => true,
            'validators' => [
                new Validator\Callback([
                    'messages' => [
                        Validator\Callback::INVALID_VALUE => 'Adjon meg e-mail címet feliratkozáshoz',
                    ],
                    'callback' => function ($value, $context = []) {
                        if (strval($value) === "true" && ($context['email'] === null ||
                            empty($context['email'])
                        )) {
                            return false;
                        }

                        return true;
                    }
                ]),
            ],
        ]);

        $this->add([
            'name'        => 'questions',
            'allow_empty' => false,
            'validators'  => [
                new Validator\NotEmpty([
                    'messages' => [
                        Validator\NotEmpty::IS_EMPTY => 'Kötelező a mező kitöltése',
                        Validator\NotEmpty::INVALID  => 'Hibás mező tipus',
                    ],
                ]),
                new Validator\IsCountable([
                    'messages' => [
                        Validator\IsCountable::NOT_COUNTABLE => 'Hibás szavazat beküldés',
                    ],
                ]),
            ],
        ]);
    }
}
/** phpcs:enable */
