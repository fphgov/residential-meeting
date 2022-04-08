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

use function getenv;

class PostInputFilter extends InputFilter
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
            'name'        => 'title',
            'allow_empty' => false,
            'validators'  => [
                new Validator\NotEmpty([
                    'messages' => [
                        Validator\NotEmpty::IS_EMPTY => 'Az "Cikk megnevezése" mező kitöltése kötelező',
                        Validator\NotEmpty::INVALID  => 'Cikk megnevezése: Hibás mező tipus',
                    ],
                ]),
                new Validator\StringLength([
                    'messages' => [
                        Validator\StringLength::TOO_SHORT => 'Legalább %min% karaktert kell tartalmaznia az "Cikk megnevezése" mezőnek',
                        Validator\StringLength::TOO_LONG  => 'Kevesebb karaktert kell tartalmaznia az "Cikk megnevezése" mezőnek mint: %max%',
                        Validator\StringLength::INVALID   => 'Cikk megnevezése: Hibás mező tipus. Csak szöveg fogadható el.',
                    ],
                    'min'      => 2,
                    'max'      => 255,
                ]),
            ],
            'filters'     => [
                new Filter\StringTrim(),
                new Filter\StripTags(),
            ],
        ]);

        $this->add([
            'name'        => 'slug',
            'allow_empty' => false,
            'validators'  => [
                new Validator\NotEmpty([
                    'messages' => [
                        Validator\NotEmpty::IS_EMPTY => 'Az "URL hivatkozás" mező kitöltése kötelező',
                        Validator\NotEmpty::INVALID  => 'URL hivatkozás: Hibás mező tipus',
                    ],
                ]),
                new Validator\StringLength([
                    'messages' => [
                        Validator\StringLength::TOO_SHORT => 'Legalább %min% karaktert kell tartalmaznia az "URL hivatkozás" mezőnek',
                        Validator\StringLength::TOO_LONG  => 'Kevesebb karaktert kell tartalmaznia az "URL hivatkozás" mezőnek mint: %max%',
                        Validator\StringLength::INVALID   => 'URL hivatkozás: Hibás mező tipus. Csak szöveg fogadható el.',
                    ],
                    'min'      => 1,
                    'max'      => 255,
                ]),
                new Validator\Db\NoRecordExists([
                    'table'    => 'posts',
                    'field'    => 'slug',
                    'adapter'  => $this->dbAdapter,
                    'messages' => [
                        Validator\Db\RecordExists::ERROR_NO_RECORD_FOUND => '',
                        Validator\Db\RecordExists::ERROR_RECORD_FOUND    => 'Foglalt az URL hivatkozás. Válassz másikat',
                    ],
                ]),
            ],
            'filters'     => [
                new Filter\StringTrim(),
                new Filter\StripTags(),
            ],
        ]);

        $this->add([
            'name'        => 'created',
            'allow_empty' => false,
            'validators'  => [
                new Validator\NotEmpty([
                    'messages' => [
                        Validator\NotEmpty::IS_EMPTY => 'A "Dátum" kitöltése kötelező',
                        Validator\NotEmpty::INVALID  => 'Dátum: Hibás mező tipus',
                    ],
                ]),
            ],
            'filters'     => [
                new Filter\StringTrim(),
                new Filter\StripTags(),
                new Filter\DateTimeFormatter(),
            ],
        ]);

        $this->add([
            'name'        => 'description',
            'allow_empty' => false,
            'validators'  => [
                new Validator\NotEmpty([
                    'messages' => [
                        Validator\NotEmpty::IS_EMPTY => 'A "Bevezető" kitöltése kötelező',
                        Validator\NotEmpty::INVALID  => 'Bevezető: Hibás mező tipus',
                    ],
                ]),
                new Validator\StringLength([
                    'messages' => [
                        Validator\StringLength::TOO_SHORT => 'Legalább %min% karaktert kell tartalmaznia a "Bevezető" mezőnek',
                        Validator\StringLength::TOO_LONG  => 'Kevesebb karaktert kell tartalmaznia a "Bevezető" mezőnek mint: %max%',
                        Validator\StringLength::INVALID   => 'Bevezető: Hibás mező tipus. Csak szöveg fogadható el.',
                    ],
                    'min'      => 1,
                ]),
            ],
            'filters'     => [
                new Filter\StringTrim(),
                new Filter\StripTags(),
            ],
        ]);

        $this->add([
            'name'        => 'content',
            'allow_empty' => false,
            'validators'  => [
                new Validator\NotEmpty([
                    'messages' => [
                        Validator\NotEmpty::IS_EMPTY => 'A "Tartalom" kitöltése kötelező',
                        Validator\NotEmpty::INVALID  => 'Tartalom: Hibás mező tipus',
                    ],
                ]),
                new Validator\StringLength([
                    'messages' => [
                        Validator\StringLength::TOO_SHORT => 'Legalább %min% karaktert kell tartalmaznia a "Tartalom" mezőnek',
                        Validator\StringLength::TOO_LONG  => 'Kevesebb karaktert kell tartalmaznia a "Tartalom" mezőnek mint: %max%',
                        Validator\StringLength::INVALID   => 'Tartalom: Hibás mező tipus. Csak szöveg fogadható el.',
                    ],
                    'min'      => 20,
                ]),
            ],
        ]);

        $this->add([
            'name'        => 'category',
            'allow_empty' => false,
            'validators'  => [
                new Validator\NotEmpty([
                    'messages' => [
                        Validator\NotEmpty::IS_EMPTY => 'Kötelező a "Kategória" mező kitöltése',
                        Validator\NotEmpty::INVALID  => 'Hibás "Kategória" mező tipusa',
                    ],
                ]),
                new Validator\Db\RecordExists([
                    'table'    => 'post_categories',
                    'field'    => 'code',
                    'adapter'  => $this->dbAdapter,
                    'messages' => [
                        Validator\Db\RecordExists::ERROR_NO_RECORD_FOUND => 'Nem választható kategória',
                        Validator\Db\RecordExists::ERROR_RECORD_FOUND    => '',
                    ],
                ]),
            ],
        ]);

        $this->add([
            'name'        => 'status',
            'allow_empty' => false,
            'validators'  => [
                new Validator\NotEmpty([
                    'messages' => [
                        Validator\NotEmpty::IS_EMPTY => 'Kötelező a "Állapot" mező kitöltése',
                        Validator\NotEmpty::INVALID  => 'Hibás "Állapot" mező tipusa',
                    ],
                ]),
                new Validator\Db\RecordExists([
                    'table'    => 'post_statuses',
                    'field'    => 'code',
                    'adapter'  => $this->dbAdapter,
                    'messages' => [
                        Validator\Db\RecordExists::ERROR_NO_RECORD_FOUND => 'Nem választható állapot',
                        Validator\Db\RecordExists::ERROR_RECORD_FOUND    => '',
                    ],
                ]),
            ],
        ]);

        $this->add([
            'name'        => 'file',
            'type'        => FileInput::class,
            'allow_empty' => true,
            'validators'  => [
                new Validator\File\Extension([
                    'messages'  => [
                        Validator\File\Extension::FALSE_EXTENSION => 'A feltöltött fájl helytelen kiterjesztéssel rendelkezik',
                        Validator\File\Extension::NOT_FOUND       => 'A feltöltött fájl nem olvasható vagy nem létezik',
                    ],
                    'extension' => ['jpg', 'jpeg', 'png', 'heic', 'heif', 'pdf', 'doc', 'docx'],
                    'case'      => true,
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
                        'application/pdf',
                        'application/msword',
                        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
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
                new Validator\File\Count([
                    'messages' => [
                        Validator\File\Count::TOO_MANY => "Túl sok a csatolt fájl, maximum '%max%' lehet, de '%count%' érkezett",
                        Validator\File\Count::TOO_FEW  => "Túl kevés a csatolt fájl, minimum '%min%' kell, de '%count%' érkezett",
                    ],
                    'min'      => 0,
                    'max'      => 1,
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
    }
}
