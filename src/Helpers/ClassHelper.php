<?php

namespace Azolee\Validator\Helpers;

use InvalidArgumentException;
use ReflectionException;
use ReflectionFunction;
use ReflectionMethod;

class ClassHelper
{
    /**
     * @throws ReflectionException
     * @throws InvalidArgumentException
     */
    public static function getReflectionForCallable(mixed $callable): \Reflector
    {
        if ($callable instanceof \Closure) {
            return new ReflectionFunction($callable);
        }

        if (is_array($callable)) {
            return new ReflectionMethod($callable[0], $callable[1]);
        }

        if (is_string($callable)) {
            if (function_exists($callable)) {
                return new ReflectionFunction($callable);
            }

            return new ReflectionMethod($callable);
        }

        if (is_object($callable) && method_exists($callable, '__invoke')) {
            return new ReflectionMethod($callable, '__invoke');
        }

        throw new InvalidArgumentException("Unknown callable type");
    }

    public static function is_callable($callable): bool
    {
        if (is_callable($callable)) {
            return true;
        }

        if (is_array($callable) && count($callable) === 2) {
            [$classOrObject, $method] = $callable;

            return (
                (is_string($classOrObject) && class_exists($classOrObject) && method_exists($classOrObject, $method)) ||
                (is_object($classOrObject) && method_exists($classOrObject, $method))
            );
        }

        return false;
    }
}