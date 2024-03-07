<?php

declare(strict_types = 1);

/**
 * Copyright 2016 SURFnet B.V.
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

namespace Surfnet\StepupMiddlewareClientBundle\Configuration\Service;

use Surfnet\StepupMiddlewareClient\Configuration\Service\InstitutionConfigurationOptionsService
    as LibraryInstitutionConfigurationOptionsService;
use Surfnet\StepupMiddlewareClientBundle\Configuration\Dto\InstitutionConfigurationOptions;
use Surfnet\StepupMiddlewareClientBundle\Exception\InvalidResponseException;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class InstitutionConfigurationOptionsService
{
    public function __construct(
        private readonly LibraryInstitutionConfigurationOptionsService $service,
        private readonly ValidatorInterface $validator
    ) {
    }

    /**
     * @return null|InstitutionConfigurationOptions
     */
    public function getInstitutionConfigurationOptionsFor(string $institution): ?InstitutionConfigurationOptions
    {
        $data = $this->service->getInstitutionConfigurationOptionsFor($institution);

        if ($data === null) {
            return null;
        }

        $institutionConfigurationOptions = InstitutionConfigurationOptions::fromData($data);

        $violations = $this->validator->validate($institutionConfigurationOptions);
        if (count($violations) > 0) {
            throw InvalidResponseException::withViolations(
                sprintf('Could not get institution configuration options for "%s": invalid response', $institution),
                $violations
            );
        }

        return $institutionConfigurationOptions;
    }
}
