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

namespace Surfnet\StepupMiddlewareClientBundle\Identity\Dto;

use Surfnet\StepupMiddlewareClientBundle\Dto\Dto;
use Symfony\Component\Validator\Constraints as Assert;

class RaCandidate implements Dto
{
    /**
     * @Assert\NotBlank(message="middleware_client.dto.ra_candidate.identity_id.must_not_be_blank")
     * @Assert\Type(type="string", message="middleware_client.dto.ra_candidate.identity_id.must_be_string")
     *
     * @var string
     */
    public string $identityId;

    /**
     * @Assert\NotBlank(message="middleware_client.dto.ra_candidate.institution.must_not_be_blank")
     * @Assert\Type(type="string", message="middleware_client.dto.ra_candidate.institution.must_be_string")
     *
     * @var string
     */
    public string $institution;

    /**
     * @Assert\NotBlank(message="middleware_client.dto.ra_candidate.name_id.must_not_be_blank")
     * @Assert\Type(type="string", message="middleware_client.dto.ra_candidate.name_id.must_be_string")
     *
     * @var string
     */
    public string $nameId;

    /**
     * @Assert\NotBlank(message="middleware_client.dto.ra_candidate.common_name.must_not_be_blank")
     * @Assert\Type(type="string", message="middleware_client.dto.ra_candidate.common_name.must_be_string")
     *
     * @var string
     */
    public string $commonName;

    /**
     * @Assert\NotBlank(message="middleware_client.dto.ra_candidate.email.must_not_be_blank")
     * @Assert\Type(type="string", message="middleware_client.dto.ra_candidate.email.must_be_string")
     *
     * @var string
     */
    public string $email;

    public static function fromData(array $data): self
    {
        $raCandidate              = new self();
        $raCandidate->identityId  = $data['identity_id'];
        $raCandidate->institution = $data['institution'];
        $raCandidate->nameId      = $data['name_id'];
        $raCandidate->commonName  = $data['common_name'];
        $raCandidate->email       = $data['email'];

        return $raCandidate;
    }
}
