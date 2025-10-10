<?php
declare(strict_types=1);

namespace Sitegeist\PaperTiger\Tests\Unit;

use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Sitegeist\PaperTiger\Helper\PaperTigerHelper;

class HelperTest extends TestCase
{

    protected PaperTigerHelper $helper;

    public function setUp(): void
    {
        $this->helper = new PaperTigerHelper();
    }

    public function flattenArrayWorksDataProvider(): \Generator
    {
        yield 'plain array stays plain' => [
            ['foo', 'bar', 'baz'],
            ['foo', 'bar', 'baz'],
        ];

        yield 'subkeys are expanded' => [
            ['foo', ['bar', 'baz']],
            ['foo', 'bar', 'baz'],
        ];

        yield 'sub sub keys aswell' => [
            ['foo', ['bar', ['baz']]],
            ['foo', 'bar', 'baz'],
        ];
    }

    /**
     * @test
     * @dataProvider flattenArrayWorksDataProvider
     */
    public function flattenArrayWorks(array $source, array $expected): void
    {

        $this->assertSame($expected, $this->helper->flattenArray($source));
    }
}
