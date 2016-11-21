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
        $logger = $this->container->getService('logger');

        $this->assertInstanceOf('Logger', $logger);
    }
}
