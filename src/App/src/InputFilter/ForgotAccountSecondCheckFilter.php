<?php

declare(strict_types=1);

namespace App\InputFilter;

use Laminas\Db\Adapter\AdapterInterface;
use Laminas\Diactoros\StreamFactory;
use Laminas\Diactoros\UploadedFileFactory;
use Laminas\Filter;
use Laminas\InputFilter\FileInput;
use Laminas\InputFilter\InputFilter;
use Laminas\Validator;

use function strval;

/** phpcs:disable */
class ForgotAccountSecondCheckFilter extends InputFilter
{
    public function __construct(
        private AdapterInterface $dbAdapter
    ) {
        $this->dbAdapter = $dbAdapter;
    }

    public function init()
    {
        $this->add([
            'name'        => 'token',
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
                    'table'    => 'forgot_account',
                    'field'    => 'token',
                    'adapter'  => $this->dbAdapter,
                    'messages' => [
                        Validator\Db\RecordExists::ERROR_NO_RECORD_FOUND => 'Ismeretlen token',
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
            'name'        => 'media',
            'type'        => FileInput::class,
            'allow_empty' => false,
            'validators'  => [
                new Validator\File\Extension([
                    'messages'  => [
                        Validator\File\Extension::FALSE_EXTENSION => 'A feltöltött fájl helytelen kiterjesztéssel rendelkezik',
                        Validator\File\Extension::NOT_FOUND       => 'A feltöltött fájl nem olvasható vagy nem létezik',
                    ],
                    'extension' => ['jpg', 'jpeg', 'png', 'heic', 'heif', 'avif'],
                    'case'      => false,
                ]),
                new Validator\File\MimeType([
                    'messages' => [
                        Validator\File\MimeType::FALSE_TYPE   => "A feltöltött fájl típusa helytelen: '%type%'",
                        Validator\File\MimeType::NOT_DETECTED => 'A feltöltött fájl típusa ellenőrízhetetlen, próbálja másképpen',
                        Validator\File\MimeType::NOT_READABLE => 'A feltöltött fájl nem olvasható vagy nem létezik',
                    ],
                    'mimeType' => [
                        'image/jpg',
                        'image/jpeg',
                        'image/png',
                        'image/heic',
                        'image/heif',
                        'image/avif',
                    ],
                ]),
                new Validator\File\Size([
                    'messages' => [
                        Validator\File\Size::TOO_BIG   => 'Az engedélyezett maximális fájlméret \'%max%\'. A feltöltött fájl mérete \'%size%\'',
                        Validator\File\Size::TOO_SMALL => 'Az engedélyezett minimális fájlméret \'%min%\'. A feltöltött fájl mérete \'%size%\'',
                        Validator\File\Size::NOT_FOUND => 'A feltöltött fájl nem olvasható vagy nem létezik',
                    ],
                    'max'      => 25 * 1024 * 1024,
                    'min'      => 1,
                ]),
            ],
            'filters'     => [
                new Filter\File\RenameUpload([
                    'target'               => getenv('APP_UPLOAD'),
                    'randomize'            => true,
                    'use_upload_extension' => true,
                    'overwrite'            => true,
                    'stream_factory'       => new StreamFactory(),
                    'upload_file_factory'  => new UploadedFileFactory(),
                ]),
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
                        Validator\Callback::INVALID_VALUE    => 'Csak az adatkezelési tájékoztató elfogadás utána tudjuk fogadni az űrlapot',
                        Validator\Callback::INVALID_CALLBACK => 'Ismeretlen hiba',
                    ],
                    'callback' => function ($value) {
                        return strval($value) === "true" || strval($value) === "on";
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
