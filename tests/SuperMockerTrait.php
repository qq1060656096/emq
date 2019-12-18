<?php
/**
 * Created by PhpStorm.
 * User: zhaoweijie
 * Date: 2019-09-26
 * Time: 23:02
 */

namespace Zwei\Emq\Tests;


use PHPUnit\Framework\TestCase;

trait SuperMockerTrait
{
    /**
     * @param SuperMockerEntity $entity
     * @param array $constructorArg
     * @return \PHPUnit\Framework\MockObject\MockObject
     */
    public function createSuperMocker(SuperMockerEntity $entity, $constructorArg = [])
    {
        /* @var TestCase $this */
        $stub = $this->getMockBuilder($entity->getClassName())->setConstructorArgs($constructorArg);
	    return $this->setSuperMockerMethods($stub, $entity);
    }
	
	/**
	 * @param SuperMockerEntity $entity
	 * @return \PHPUnit\Framework\MockObject\MockObject
	 */
	public function createModelSuperMocker(SuperMockerEntity $entity)
	{
		/* @var TestCase $this */
		$stub = $this->getMockBuilder($entity->getClassName())->disableOriginalConstructor();
		return $this->setSuperMockerMethods($stub, $entity);
	}
	
	/**
	 * @param \PHPUnit\Framework\MockObject\MockBuilder $stub
	 * @param SuperMockerEntity $entity
	 * @return \PHPUnit\Framework\MockObject\MockObject
	 */
	public function setSuperMockerMethods($stub, SuperMockerEntity $entity)
	{
		$methods = $entity->getMethods();
		$mock = $stub->setMethods(array_keys($methods))->getMock();
		foreach ($methods as $method => $return) {
		    if (is_callable($return)) {
                $mock->expects($this->any())->method($method)->willReturnCallback($return);
            } else if ($return !== null) {
				$mock->expects($this->any())->method($method)->willReturn($return);
			}
		}
		return $mock;
	}
}
