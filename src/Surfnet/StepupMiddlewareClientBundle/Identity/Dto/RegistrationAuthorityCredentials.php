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

class RegistrationAuthorityCredentials implements Dto
{
    /**
     * @Assert\NotBlank(message="middleware_client.dto.ra_credentials.identity_id.must_not_be_blank")
     * @Assert\Type(type="string", message="middleware_client.dto.ra_credentials.identity_id.must_be_string")
     *
     * @var string
     */
    public $identityId;

    /**
     * @Assert\Expression(
     *     "this.assertNullOrString(value)",
     *     message="middleware_client.dto.ra_credentials.institution.must_be_null_or_string"
     * )
     *
     * @var string
     */
    public $institution;

    /**
     * @Assert\Expression(
     *     "this.assertNullOrString(value)",
     *     message="middleware_client.dto.ra_credentials.common_name.must_be_null_or_string"
     * )
     *
     * @var string
     */
    public $commonName;

    /**
     * @Assert\Expression(
     *     "this.assertNullOrString(value)",
     *     message="middleware_client.dto.ra_credentials.location.must_be_null_or_string"
     * )
     *
     * @var string
     */
    public $location;

    /**
     * @Assert\Expression(
     *     "this.assertNullOrString(value)",
     *     message="middleware_client.dto.ra_credentials.contact_information.must_be_null_or_string"
     * )
     *
     * @var string
     */
    public $contactInformation;

    /**
     * @Assert\Type(type="bool", message="middleware_client.dto.ra_credentials.is_raa.must_be_boolean")
     *
     * @var bool
     */
    public $isRaa;

    /**
     * @Assert\Type(type="bool", message="middleware_client.dto.ra_credentials.is_sraa.must_be_boolean")
     *
     * @var bool
     */
    public $isSraa;

    public static function fromData(array $data): self
    {
        $credentials = new self();
        $credentials->identityId = $data['id'];
        $credentials->institution = $data['attributes']['institution'];
        $credentials->commonName = $data['attributes']['common_name'];
        $credentials->location = $data['attributes']['location'];
        $credentials->contactInformation = $data['attributes']['contact_information'];
        $credentials->isRaa = $data['attributes']['is_raa'];
        $credentials->isSraa = $data['attributes']['is_sraa'];

        return $credentials;
    }

    /**
     * @return bool
     */
    public function assertNullOrString(mixed $value): bool
    {
        return is_null($value) || is_string($value);
    }
}
