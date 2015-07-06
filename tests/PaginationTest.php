<?php

namespace Navius\Tests;

use Navius\DataProvider\DataProviderInterface;
use Navius\Pagination;

class PaginationTest extends \PHPUnit_Framework_TestCase
{
    /** @var DataProviderInterface */
    private $dataProvider;
    /** @var Pagination */
    private $pagination;

    protected function setUp()
    {
        $this->dataProvider = $this->getMockBuilder('Navius\\DataProvider\\DataProviderInterface')
            ->disableOriginalConstructor()
            ->getMock();
        $this->pagination = new Pagination($this->dataProvider);
    }

    public function testPaginationPropertiesDefaultValues()
    {
        $this->assertSame($this->dataProvider, $this->pagination->getDataProvider());
        $this->assertEquals(1, $this->pagination->getCurrentPage());
        $this->assertEquals(10, $this->pagination->getPageSize());
        $this->assertFalse($this->pagination->getNormalizeOutOfRangePages());
    }

    public function testGetDataProvider()
    {
        $this->assertSame($this->dataProvider, $this->pagination->getDataProvider());
    }

    public function testNormalizeOutOfRangePages()
    {
        $this->pagination->setNormalizeOutOfRangePages(true);
        $this->assertTrue($this->pagination->getNormalizeOutOfRangePages());
    }

    public function testSetCurrentPage()
    {
        try {
            $this->pagination->setCurrentPage(0);
            $this->fail('Expected exception not thrown');
        } catch(\InvalidArgumentException $e){
            $this->assertInstanceOf('\\InvalidArgumentException', $e);
        }
        try {
            $this->pagination->setCurrentPage(-1);
            $this->fail('Expected exception not thrown');
        } catch(\InvalidArgumentException $e){
            $this->assertInstanceOf('\\InvalidArgumentException', $e);
        }
    }

    public function testGetCurrentPage()
    {
        $this->assertEquals(1, $this->pagination->getCurrentPage());
    }
}