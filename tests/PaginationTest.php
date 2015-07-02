<?php

namespace Navius\Tests;

use Navius\Adapter\DataAdapterInterface;
use Navius\Pagination;

class PaginationTest extends \PHPUnit_Framework_TestCase
{
    /** @var DataAdapterInterface */
    private $adapter;
    /** @var Pagination */
    private $pagination;

    protected function setUp()
    {
        $this->adapter = $this->getMockBuilder('Navius\\Adapter\\AdapterInterface')
            ->disableOriginalConstructor()
            ->getMock();
        $this->pagination = new Pagination($this->adapter);
    }

    public function testPaginationPropertiesDefaultValues()
    {
        $this->assertSame($this->adapter, $this->pagination->getAdapter());
        $this->assertEquals(1, $this->pagination->getCurrentPage());
        $this->assertEquals(10, $this->pagination->getPageSize());
        $this->assertFalse($this->pagination->getNormalizeOutOfRangePages());
    }
}