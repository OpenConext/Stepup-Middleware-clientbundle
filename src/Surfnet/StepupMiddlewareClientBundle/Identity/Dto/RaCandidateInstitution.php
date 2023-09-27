<?php

declare(strict_types = 1);

/**
 * Copyright 2019 SURFnet B.V.
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

namespace Surfnet\StepupMiddlewareClientBundle\Identity\Dto;

use Surfnet\StepupMiddlewareClientBundle\Dto\Dto;
use Symfony\Component\Validator\Constraints as Assert;

class RaCandidateInstitution implements Dto
{
    /**
     * @Assert\NotBlank(message="middleware_client.dto.ra_candidate_institution.institution.must_not_be_blank")
     * @Assert\Type(type="string", message="middleware_client.dto.ra_candidate_institution.institution.must_be_string")
     *
     * @var string
     */
    public $institution;

    /**
     * @param array $data
     * @return static
     */
    public static function fromData(array $data): self
    {
        $institutionListing              = new self();
        $institutionListing->institution = $data['ra_institution'];

        return $institutionListing;
    }
}
