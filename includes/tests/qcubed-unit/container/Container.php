<?php

require_once(dirname(__FILE__) . '/../../../container/AbstractContainer.php');

class Container extends AbstractContainer
{
    /** @var array $services */
    protected $services = array(
        'logger' => null,
    );

    /**
     * @param string  $id
     * @param boolean $newInstance
     *
     * @return Logger
     */
    protected function getLoggerService($id, $newInstance)
    {
        if ($newInstance || !$this->services[$id]) {
            $this->services[$id] = new Logger();
        }

        return $this->services[$id];
    }
}
