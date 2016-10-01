<?php

use Psr\Container\ContainerInterface;
use Psr\Container\Exception\ContainerException;
use Psr\Container\Exception\NotFoundException;

abstract class AbstractContainer implements ContainerInterface
{
    /**
     * @param string $id
     * @param array  $arguments
     *
     * @return Object
     */
    public function getService($id, array $arguments)
    {
        return $this->getServiceBase($id, $arguments, false);
    }

    /**
     * @param string $id
     * @param array  $arguments
     *
     * @return Object
     */
    public function getNewService($id, array $arguments)
    {
        return $this->getServiceBase($id, $arguments, true);
    }

    /**
     * {@inheritdoc}
     */
    public function get($id)
    {
        return $this->getService($id, array());
    }

    /**
     * @param string $id
     *
     * @return Object
     */
    public function getNew($id)
    {
        return $this->getNewService($id, array());
    }

    /**
     * {@inheritdoc}
     */
    public function has($id)
    {
        return array_key_exists($id, $this->services);
    }

    /**
     * @param string  $id
     * @param array   $arguments
     * @param boolean $newInstance
     *
     * @return Object
     */
    protected function getServiceBase($id, array $arguments, $newInstance)
    {
        if (!$this->has($id)) {
            throw new NotFoundException(sprintf('Invalid id: %s', $id));
        }

        $methodName = $this->getServiceMethodName($id);
        return call_user_func_array(
            'static::' .  $methodName,
            array_merge(array($id), array($newInstance), $arguments)
        );
    }

    /**
     * Construct the service method name from the service id
     *
     * @param string $id
     *
     * @return string
     */
    protected function getServiceMethodName($id)
    {
        $methodName = join(
            '_',
            array_map(
                function ($idSegment) {
                    return join(
                        '',
                        array_map('ucfirst', explode('_', $idSegment))
                    );
                },
                explode('.', $id)
            )
        );
        return 'get' . $methodName . 'Service';
    }
}
