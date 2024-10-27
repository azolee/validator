<?php

namespace Tests;

use Azolee\Validator\Exceptions\InvalidValidationRule;
use Azolee\Validator\Helpers\ClassHelper;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use ReflectionException;
use ReflectionFunction;
use ReflectionMethod;

class ClassHelperTest extends TestCase
{
    public function testValidCallable()
    {
        $callable = function ($data) {
            return true;
        };

        $this->expectNotToPerformAssertions();
        ClassHelper::validateCallable($callable, 'Invalid callable');
    }

    public function testInvalidCallableTooFewParameters()
    {
        $callable = function () {
            return true;
        };

        $this->expectException(InvalidValidationRule::class);
        $this->expectExceptionMessage('Invalid callable');
        ClassHelper::validateCallable($callable, 'Invalid callable');
    }

    public function testInvalidCallableTooManyParameters()
    {
        $callable = function ($a, $b, $c, $d) {
            return true;
        };

        $this->expectException(InvalidValidationRule::class);
        $this->expectExceptionMessage('Invalid callable');
        ClassHelper::validateCallable($callable, 'Invalid callable');
    }

    public function testInvalidCallableNotCallable()
    {
        $callable = 'not_a_callable';

        $this->expectException(InvalidArgumentException::class);
        ClassHelper::validateCallable($callable, 'Invalid callable');
    }

    public function testIsCallableWithValidClassMethod()
    {
        $callable = [self::class, 'exampleMethod'];
        $this->assertTrue(ClassHelper::isCallable($callable));
    }

    public function testIsCallableWithInvalidClassMethod()
    {
        $callable = [self::class, 'nonExistentMethod'];
        $this->assertFalse(ClassHelper::isCallable($callable));
    }

    public function testIsCallableWithValidObjectMethod()
    {
        $object = new self();
        $callable = [$object, 'exampleMethod'];
        $this->assertTrue(ClassHelper::isCallable($callable));
    }

    public function testIsCallableWithInvalidObjectMethod()
    {
        $object = new self();
        $callable = [$object, 'nonExistentMethod'];
        $this->assertFalse(ClassHelper::isCallable($callable));
    }

    public function testIsCallableWithNonExistentClass()
    {
        $callable = ['NonExistentClass', 'exampleMethod'];
        $this->assertFalse(ClassHelper::isCallable($callable));
    }

    public function testIsCallableWithNonCallableArray()
    {
        $callable = ['NotAClassOrObject', 'notAMethod'];
        $this->assertFalse(ClassHelper::isCallable($callable));
    }

    public function testGetReflectionForCallableWithFunction()
    {
        $callable = 'strlen';
        $reflection = ClassHelper::getReflectionForCallable($callable);
        $this->assertInstanceOf(ReflectionFunction::class, $reflection);
    }

    public function testGetReflectionForCallableWithStaticMethod()
    {
        $callable = [self::class, 'exampleStaticMethod'];
        $reflection = ClassHelper::getReflectionForCallable($callable);
        $this->assertInstanceOf(ReflectionMethod::class, $reflection);
    }

    public function testGetReflectionForCallableWithObjectMethod()
    {
        $object = new self();
        $callable = [$object, 'exampleMethod'];
        $reflection = ClassHelper::getReflectionForCallable($callable);
        $this->assertInstanceOf(ReflectionMethod::class, $reflection);
    }

    public function testGetReflectionForCallableWithInvokeMethod()
    {
        $callable = new class {
            public function __invoke()
            {
            }
        };
        $reflection = ClassHelper::getReflectionForCallable($callable);
        $this->assertInstanceOf(ReflectionMethod::class, $reflection);
    }

    public function testGetReflectionForCallableWithInvalidCallable()
    {
        $callable = 'nonExistentFunction';
        $this->expectException(InvalidArgumentException::class);
        ClassHelper::getReflectionForCallable($callable);
    }

    public static function exampleStaticMethod()
    {
        return true;
    }

    public function exampleMethod()
    {
        return true;
    }
}