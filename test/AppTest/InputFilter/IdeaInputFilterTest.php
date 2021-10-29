<?php

declare(strict_types=1);

namespace AppTest\InputFilter;

use App\InputFilter\IdeaInputFilter;
use AppTest\AbstractServiceTest;
use Psr\Http\Message\StreamInterface;
use Psr\Http\Message\UploadedFileInterface;

use function basename;
use function dirname;
use function mime_content_type;

final class IdeaInputFilterTest extends AbstractServiceTest
{
    private string $testFile = __DIR__ . '/_files/logo.png';

    private array $formValidData = [];

    private IdeaInputFilter $ideaInputFilter;

    protected function setUp(): void
    {
        $stream = $this->createMock(StreamInterface::class);
        $stream->method('getMetadata', 'uri')->willReturn($this->testFile);

        $file = $this->createMock(UploadedFileInterface::class);
        $file->method('getClientFilename')->willReturn(basename($this->testFile));
        $file->method('getClientMediaType')->willReturn(mime_content_type($this->testFile));
        $file->method('getStream')->willReturn($stream);

        $this->formValidData = [
            'title'               => 'Idea title',
            'solution'            => 'Everywhere',
            'description'         => 'Everywhere',
            'participate'         => true,
            'participate_comment' => 'Implementation',
            'cost'                => '1234',
            'theme'               => 1,
            'file'                => [
                $file,
            ],
        ];

        $this->ideaInputFilter = self::$container->get(IdeaInputFilter::class);
    }

    private function ideaInputFilterInit(array $formData): void
    {
        $this->ideaInputFilter->init();

        $fileFilters = $this->ideaInputFilter->getInputs()['file']->getFilterChain()->getFilters();

        $fileExtract = $fileFilters->extract();
        $fileExtract->setTarget(dirname($this->testFile));

        $this->ideaInputFilter->setData($formData);
    }

    public function testSubmitValidData(): void
    {
        $this->ideaInputFilterInit($this->formValidData);

        $this->assertTrue($this->ideaInputFilter->isValid());

        $values = $this->ideaInputFilter->getValues();

        $this->assertEquals($values['title'], 'Idea title');
        $this->assertEquals($values['solution'], 'Everywhere');
        $this->assertEquals($values['description'], 'Everywhere');
        $this->assertEquals($values['participate'], true);
        $this->assertEquals($values['participate_comment'], 'Implementation');
        $this->assertEquals($values['cost'], 1234);
        $this->assertEquals($values['theme'], 1);

        $this->assertInstanceOf(UploadedFileInterface::class, $values['file'][0]);
    }

    public function testSubmitInvalidData(): void
    {
        $this->ideaInputFilterInit([]);

        $this->assertFalse($this->ideaInputFilter->isValid());

        $message = $this->ideaInputFilter->getMessages();

        $this->assertArrayHasKey('isEmpty', $message['title']);
        $this->assertArrayHasKey('isEmpty', $message['solution']);
        $this->assertArrayHasKey('isEmpty', $message['description']);
        $this->assertArrayHasKey('isEmpty', $message['participate']);

        $this->assertArrayNotHasKey('participate_comment', $message);
        $this->assertArrayNotHasKey('cost', $message);
    }

    /**
     * @dataProvider provideBooleanFilterData
     * @param mixed $unfilteredData
     */
    public function testBooleanFilter(bool $result, $unfilteredData): void
    {
        $formData                = $this->formValidData;
        $formData['participate'] = $unfilteredData;

        $this->ideaInputFilterInit($formData);

        $values = $this->ideaInputFilter->getValues();

        $this->assertIsBool($values['participate']);
        $this->assertEquals($values['participate'], $result);
    }

    public function provideBooleanFilterData(): array
    {
        return [
            [true, true],
            [true, "true"],
            [true, "on"],
            [true, "1"],
            [true, 1],
            [false, false],
            [false, "false"],
            [false, "0"],
            [false, 0],
        ];
    }

    /**
     * @dataProvider provideIntegerData
     * @param mixed $unfilteredData
     */
    public function testInteger(int $result, $unfilteredData, bool $isValid): void
    {
        $formData         = $this->formValidData;
        $formData['cost'] = $unfilteredData;

        $this->ideaInputFilterInit($formData);

        $values = $this->ideaInputFilter->getValues();

        $this->assertIsInt($values['cost']);
        $this->assertEquals($values['cost'], $result);
        $this->assertEquals($this->ideaInputFilter->isValid(), $isValid);
    }

    public function provideIntegerData(): array
    {
        return [
            [1, 1, true],
            [1, "1", true],
            [0, "0", true],
            [-1, "-1", false],
        ];
    }

    public function testSubmitInvalidCategoryData(): void
    {
        $formData          = $this->formValidData;
        $formData['theme'] = 0;

        $this->ideaInputFilterInit($formData);

        $this->assertFalse($this->ideaInputFilter->isValid());

        $message = $this->ideaInputFilter->getMessages();

        $this->assertArrayHasKey('noRecordFound', $message['theme']);
    }
}
