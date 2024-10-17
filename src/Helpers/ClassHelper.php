<?php

namespace Azolee\Validator\Helpers;

use Azolee\Validator\Exceptions\InvalidValidationRule;
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

    public static function isCallable($callable): bool
    {
        if (is_callable($callable) && !static::isMethodNameBuiltIn($callable)) {
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

    public static function isMethodNameBuiltIn($methodName)
    {
        $definedFunctions = get_defined_functions();
        $builtInFunctions = $definedFunctions['internal'];
        return in_array($methodName, $builtInFunctions);
    }

    /**
     * @throws InvalidValidationRule
     * @throws ReflectionException
     */
    public static function validateCallable($rule, string $message): void
    {
        $reflectionOfTheRule = self::getReflectionForCallable($rule);
        if ($reflectionOfTheRule->getNumberOfParameters() < 1 || $reflectionOfTheRule->getNumberOfParameters() > 3) {
            throw new InvalidValidationRule($message);
        }
    }
}