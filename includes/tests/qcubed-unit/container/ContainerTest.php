<?php

require_once(dirname(__FILE__) . '/Container.php');
require_once(dirname(__FILE__) . '/Logger.php');
require_once(dirname(__FILE__) . '/../QUnitTestCaseBase.php');

class ContainerTest extends QUnitTestCaseBase
{
    /** @var Container $container */
    protected $container;

    public function setUp()
    {
        parent::setUp();

        $this->container = new Container();
    }

    public function testGetService()
    {
        $logger = $this->container->getService('logger', array());

        $this->assertInstanceOf('Logger', $logger);
        $this->assertSame($logger, $this->container->getService('logger', array()));
    }

    public function testGetNewService()
    {
        $logger = $this->container->getNewService('logger', array());

        $this->assertInstanceOf('Logger', $logger);
        $this->assertNotSame($logger, $this->container->getNewService('logger', array()));
    }

    public function testGet()
    {
        $logger = $this->container->get('logger');

        $this->assertInstanceOf('Logger', $logger);
        $this->assertSame($logger, $this->container->get('logger'));
    }

    public function testGetNew()
    {
        $logger = $this->container->getNew('logger');

        $this->assertInstanceOf('Logger', $logger);
        $this->assertNotSame($logger, $this->container->getNew('logger'));
    }

    public function testHas()
    {
        $this->assertFalse($this->container->has('non.existing.service'));
        $this->assertTrue($this->container->has('logger'));
    }
}
