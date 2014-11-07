<?php

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

namespace Surfnet\StepupMiddlewareClientBundle\Exception;

use Symfony\Component\Validator\ConstraintViolationListInterface;

class InvalidResponseException extends RuntimeException
{
    /**
     * @param string $message
     * @param ConstraintViolationListInterface $violations
     * @return self
     */
    public static function withViolations($message, ConstraintViolationListInterface $violations)
    {
        $message = sprintf('%s (%s)', $message, self::convertViolationsToString($violations));

        return new self($message);
    }

    /**
     * @param ConstraintViolationListInterface $violations
     * @return string
     */
    private static function convertViolationsToString(ConstraintViolationListInterface $violations)
    {
        $violationStrings = [];

        foreach ($violations as $violation) {
            /** @var ConstraintViolationInterface $violation */
            $violationStrings[] = sprintf('%s: %s', $violation->getPropertyPath(), $violation->getMessage());
        }

        return join('; ', $violationStrings);
    }
}
