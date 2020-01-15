<?php

/**
 * Copyright 2017 SURFnet B.V.
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

namespace Surfnet\StepupMiddlewareClient\Helper;

use Surfnet\StepupMiddlewareClient\Exception\InvalidArgumentException;
use Surfnet\StepupMiddlewareClient\Exception\JsonException;

final class JsonHelper
{
    static $jsonErrors = [
        JSON_ERROR_DEPTH          => 'JSON_ERROR_DEPTH - Maximum stack depth exceeded',
        JSON_ERROR_STATE_MISMATCH => 'JSON_ERROR_STATE_MISMATCH - Underflow or the modes mismatch',
        JSON_ERROR_CTRL_CHAR      => 'JSON_ERROR_CTRL_CHAR - Unexpected control character found',
        JSON_ERROR_SYNTAX         => 'JSON_ERROR_SYNTAX - Syntax error, malformed JSON',
        JSON_ERROR_UTF8           => 'JSON_ERROR_UTF8 - Malformed UTF-8 characters, possibly incorrectly encoded',
    ];

    public static function decode($json)
    {
        if (!is_string($json)) {
            throw InvalidArgumentException::invalidType('string', 'json', $json);
        }

        $data = json_decode($json, true);

        if (JSON_ERROR_NONE !== json_last_error()) {
            $last         = json_last_error();

            $errorMessage = 'Unknown error';
            if (array_key_exists($last, static::$jsonErrors)) {
                $errorMessage = static::$jsonErrors[$last];
            }

            throw JsonException::withMessage($errorMessage);
        }

        return $data;
    }
}
