<?php

declare(strict_types = 1);

/**
 * Copyright 2014 SURFnet bv
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

namespace Surfnet\StepupMiddlewareClient\Exception;

class InvalidArgumentException extends \InvalidArgumentException implements StepupMiddlewareClientException
{
    /**
     * @param string $expected description of expected type
     * @param $parameterName
     * @param mixed $parameter the parameter that is not of the expected type.
     * @return self
     */
    public static function invalidType(string $expected, $parameterName, mixed $parameter): self
    {
        $message = sprintf(
            'Invalid argument type: "%s" expected, "%s" given for "%s"',
            $expected,
            get_debug_type($parameter),
            $parameterName
        );

        return new self($message);
    }
}
