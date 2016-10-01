<?php

abstract class AbstractContainer
{
    /**
     * {@inheritdoc}
     */
    public function getService($id, array $arguments = array(), $newInstance = false)
    {
        if (!array_key_exists($id, $this->services)) {
            throw new Exception(sprintf('Invalid id: %s', $id));
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
