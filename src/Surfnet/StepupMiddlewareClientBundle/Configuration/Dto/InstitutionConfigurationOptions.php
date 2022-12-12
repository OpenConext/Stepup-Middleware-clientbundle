<?php

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

namespace Surfnet\StepupMiddlewareClientBundle\Configuration\Dto;

use Surfnet\StepupMiddlewareClientBundle\Dto\Dto;
use Symfony\Component\Validator\Constraints as Assert;

class InstitutionConfigurationOptions implements Dto
{
    /**
     * @Assert\Type(type="boolean", message="middleware_client.dto.configuration.use_ra_locations.must_be_boolean")
     */
    public $useRaLocations;

    /**
     * @Assert\Type(type="boolean", message="middleware_client.dto.configuration.show_raa_contact_information.must_be_boolean")
     */
    public $showRaaContactInformation;

    /**
     * @Assert\Type(type="boolean", message="middleware_client.dto.configuration.verify_email.must_be_boolean")
     */
    public $verifyEmail;

    /**
     * @Assert\Type(type="boolean", message="middleware_client.dto.configuration.self_vet.must_be_boolean")
     */
    public $selfVet;

    /**
     * @Assert\Type(type="boolean", message="middleware_client.dto.configuration.allow_self_asserted_tokens.must_be_boolean")
     */
    public $allowSelfAssertedTokens;

    /**
     * @Assert\Type(type="integer", message="middleware_client.dto.configuration.number_of_tokens_per_identity.must_be_integer")
     */
    public $numberOfTokensPerIdentity;

    /**
     * @Assert\Type(type="array", message="middleware_client.dto.configuration.allowed_second_factors.must_be_array")
     */
    public $allowedSecondFactors;

    public $useRa;

    public $useRaa;

    public $selectRaa;

    /**
     * @param array $data
     * @return InstitutionConfigurationOptions
     */
    public static function fromData(array $data)
    {
        $institutionConfigurationOptions                            = new self();
        $institutionConfigurationOptions->useRaLocations            = $data['use_ra_locations'];
        $institutionConfigurationOptions->showRaaContactInformation = $data['show_raa_contact_information'];
        $institutionConfigurationOptions->verifyEmail               = $data['verify_email'];
        $institutionConfigurationOptions->selfVet                   = $data['self_vet'];
        $institutionConfigurationOptions->allowSelfAssertedTokens = $data['allow_self_asserted_tokens'];
        $institutionConfigurationOptions->allowedSecondFactors      = $data['allowed_second_factors'];
        $institutionConfigurationOptions->numberOfTokensPerIdentity = $data['number_of_tokens_per_identity'];
        $institutionConfigurationOptions->useRa                     = $data['use_ra'];
        $institutionConfigurationOptions->useRaa                    = $data['use_raa'];
        $institutionConfigurationOptions->selectRaa                 = $data['select_raa'];

        return $institutionConfigurationOptions;
    }
}
