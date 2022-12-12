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

namespace Surfnet\StepupMiddlewareClientBundle\Identity\Dto;

use Surfnet\StepupMiddlewareClientBundle\Dto\Dto;
use Symfony\Component\Validator\Constraints as Assert;

class VettedSecondFactor implements Dto
{
    /**
     * @Assert\NotBlank(message="middleware_client.dto.vetted_second_factor.id.must_not_be_blank")
     * @Assert\Type(type="string", message="middleware_client.dto.vetted_second_factor.id.must_be_string")
     * @var string
     */
    public $id;

    /**
     * @Assert\NotBlank(message="middleware_client.dto.vetted_second_factor.type.must_not_be_blank")
     * @Assert\Type(type="string", message="middleware_client.dto.vetted_second_factor.type.must_be_string")
     * @var string
     */
    public $type;

    /**
     * @Assert\NotBlank(message="middleware_client.dto.vetted_second_factor.second_factor_identifier.must_not_be_blank")
     * @Assert\Type(
     *     type="string",
     *     message="middleware_client.dto.vetted_second_factor.second_factor_identifier.must_be_string"
     * )
     * @var string
     */
    public $secondFactorIdentifier;

    /**
     * @Assert\NotBlank(message="middleware_client.dto.vetted_second_factor.vetting_typ.must_not_be_blank")
     * @Assert\Type(
     *     type="string",
     *     message="middleware_client.dto.vetted_second_factor.veting_type.must_be_string"
     * )
     * @var string
     */
    public $vettingType;

    /**
     * The calculated loa level based on vetting type and seconnd factor type
     * @var float
     */
    public $loaLevel;

    public static function fromData(array $data)
    {
        $secondFactor = new self();
        $secondFactor->id = $data['id'];
        $secondFactor->type = $data['type'];
        $secondFactor->secondFactorIdentifier = $data['second_factor_identifier'];
        $secondFactor->vettingType = $data['vetting_type'];
        $secondFactor->loaLevel = $data['loa_level'];

        return $secondFactor;
    }
}
