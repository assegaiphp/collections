<?php

/**
 * Returns a message for a type error.
 *
 * @param string $expectedType The expected type.
 * @param string $actualType The actual type.
 * @return string The type error message.
 */
function get_method_arg_type_error_message(string $methodName, int $argPosition, string $expectedType, string $actualType): string
{
  return sprintf(
    'Argument %d passed to %s() must be of type %s, %s given',
    $argPosition,
    $methodName,
    $expectedType,
    $actualType
  );
}