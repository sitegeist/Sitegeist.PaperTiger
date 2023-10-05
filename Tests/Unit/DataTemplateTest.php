<?php
declare(strict_types=1);

namespace Sitegeist\PaperTiger\Tests\Unit;

use PHPUnit\Framework\TestCase;
use Sitegeist\PaperTiger\FusionObjects\DataTemplateImplementation;

class DataTemplateTest extends TestCase
{

    protected DataTemplateImplementation $dataTemplate;

    public function setUp(): void
    {
        $this->dataTemplate = $this->createPartialMock(DataTemplateImplementation::class, ['getTemplate', 'getData', 'getDateTimeFormat']);
        $this->dataTemplate->expects($this->any())->method('getDateTimeFormat')->willReturn(\DateTimeImmutable::W3C);
    }

    public function stringifyConvertsDataProvider(): \Generator
    {
        yield 'string' => ['foo', 'foo'];
        yield 'int' => [123, '123'];
        yield 'float' => [1.23, '1.23'];
        yield 'true' => [true, ''];
        yield 'false' => [false, ''];
        yield 'array' => [['foo', 'bar'], 'foo, bar'];
        yield 'dateTime' => [\DateTimeImmutable::createFromFormat(\DateTimeImmutable::W3C, '2005-08-15T15:52:01+00:00'), '2005-08-15T15:52:01+00:00'];
        yield 'object' => [new \stdClass(), ''];
    }

    /**
     * @test
     * @dataProvider stringifyConvertsDataProvider
     */
    public function stringifyConvertsData(mixed $data, string $expectedString): void
    {

        $this->assertSame($expectedString, $this->dataTemplate->stringify($data));
    }

    public function evaluateEscapesDataProvider(): \Generator
    {
        yield 'tags' => [['item' => '<h1>foo</h1>'], '-{item}-', '-foo-'];
        yield 'specialchars' => [['item' => '&foo'], '-{item}-', '-&amp;foo-'];

    }

    /**
     * @test
     * @dataProvider evaluateEscapesDataProvider
     */
    public function evaluateEscapesData(mixed $data, string $template, string $expectedString): void
    {
        $this->dataTemplate->expects($this->once())->method('getTemplate')->willReturn($template);
        $this->dataTemplate->expects($this->once())->method('getData')->willReturn($data);
        $this->assertSame($expectedString, $this->dataTemplate->evaluate());
    }

    public function evaluateAccessesNestedDataProvider(): \Generator
    {
        yield 'simple' => [['item' => 'value'], '-{item}-', '-value-'];
        yield 'mixedCase' => [['fooBar' => 'value'], '-{fooBar}-', '-value-'];
        yield 'minus' => [['foo-bar' => 'value'], '-{foo-bar}-', '-value-'];
        yield 'multiple' => [['foo' => 'value1', 'bar' => 'value2'], '-{foo}-{bar}-', '-value1-value2-'];
        yield 'nested' => [['foo' => ['bar' => ['baz' => 'value']]], '-{foo.bar.baz}-', '-value-'];
    }

    /**
     * @test
     * @dataProvider evaluateAccessesNestedDataProvider
     */
    public function evaluateAccessesNestedData(mixed $data, string $template, string $expectedString): void
    {
        $this->dataTemplate->expects($this->once())->method('getTemplate')->willReturn($template);
        $this->dataTemplate->expects($this->once())->method('getData')->willReturn($data);
        $this->assertSame($expectedString, $this->dataTemplate->evaluate());
    }
}
