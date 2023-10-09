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

class RaListing implements Dto
{
    /**
     * @Assert\NotBlank(message="middleware_client.dto.ra_listing.id.must_not_be_blank")
     * @Assert\Type(type="string", message="middleware_client.dto.ra_listing.id.must_be_string")
     *
     * @var string
     */
    public string $identityId;

    /**
     * @Assert\NotBlank(message="middleware_client.dto.ra_listing.institution.must_not_be_blank")
     * @Assert\Type(type="string", message="middleware_client.dto.ra_listing.institution.must_be_string")
     *
     * @var string
     */
    public string $institution;

    /**
     * @Assert\NotBlank(message="middleware_client.dto.ra_listing.institution.must_not_be_blank")
     * @Assert\Type(type="string", message="middleware_client.dto.ra_listing.institution.must_be_string")
     *
     * @var string
     */
    public string $raInstitution;

    /**
     * @Assert\NotBlank(message="middleware_client.dto.ra_listing.common_name.must_not_be_blank")
     * @Assert\Type(type="string", message="middleware_client.dto.ra_listing.common_name.must_be_string")
     *
     * @var string
     */
    public string $commonName;

    /**
     * @Assert\NotBlank(message="middleware_client.dto.ra_listing.email.must_not_be_blank")
     * @Assert\Type(type="string", message="middleware_client.dto.ra_listing.email.must_be_string")
     *
     * @var string
     */
    public string $email;

    /**
     * @Assert\NotBlank(message="middleware_client.dto.ra_listing.role.must_not_be_blank")
     * @Assert\Type(type="string", message="middleware_client.dto.ra_listing.role.must_be_string")
     *
     * @var string
     */
    public string $role;

    /**
     * @Assert\NotBlank(message="middleware_client.dto.ra_listing.location.must_not_be_blank")
     * @Assert\Type(type="string", message="middleware_client.dto.ra_listing.location.must_be_string")
     *
     * @var string
     */
    public string $location;

    /**
     * @Assert\NotBlank(message="middleware_client.dto.ra_listing.contact_information.must_not_be_blank")
     * @Assert\Type(type="string", message="middleware_client.dto.ra_listing.contact_information.must_be_string")
     *
     * @var string
     */
    public string $contactInformation;

    public static function fromData(array $data): self
    {
        $raListing                     = new self();
        $raListing->identityId         = $data['identity_id'];
        $raListing->institution        = $data['institution'];
        $raListing->commonName         = $data['common_name'];
        $raListing->email              = $data['email'];
        $raListing->role               = $data['role'];
        $raListing->location           = $data['location'];
        $raListing->contactInformation = $data['contact_information'];
        $raListing->raInstitution      = $data['ra_institution'];

        return $raListing;
    }
}
