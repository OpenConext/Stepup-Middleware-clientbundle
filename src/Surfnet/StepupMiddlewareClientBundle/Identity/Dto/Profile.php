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

class Profile implements Dto
{
    /**
     * @Assert\NotBlank(message="middleware_client.dto.identity.id.must_not_be_blank")
     * @Assert\Type(type="string", message="middleware_client.dto.identity.id.must_be_string")
     * @var string
     */
    public string $id;

    /**
     * @Assert\NotBlank(message="middleware_client.dto.identity.name_id.must_not_be_blank")
     * @Assert\Type(type="string", message="middleware_client.dto.identity.name_id.must_be_string")
     * @var string
     */
    public string $nameId;

    /**
     * @Assert\NotBlank(message="middleware_client.dto.identity.institution.must_not_be_blank")
     * @Assert\Type(type="string", message="middleware_client.dto.identity.institution.must_be_string")
     * @var string
     */
    public string $institution;

    /**
     * @Assert\NotBlank(message="middleware_client.dto.identity.email.must_not_be_blank")
     * @Assert\Type(type="string", message="middleware_client.dto.identity.email.must_be_string")
     * @var string
     */
    public string $email;

    /**
     * @Assert\NotBlank(message="middleware_client.dto.identity.common_name.must_not_be_blank")
     * @Assert\Type(type="string", message="middleware_client.dto.identity.common_name.must_be_string")
     * @var string
     */
    public string $commonName;

    /**
     * @Assert\NotBlank(message="middleware_client.dto.identity.preferred_locale.must_not_be_blank")
     * @Assert\Type(type="string", message="middleware_client.dto.identity.preferred_locale.must_be_string")
     * @var string
     */
    public string $preferredLocale;

    /**
     * @var bool
     */
    public bool $isSraa;

    /**
     * @var array
     */
    public array $authorizations;


    public static function fromData(array $data): self
    {
        $identity = new self();
        $identity->id = $data['id'];
        $identity->nameId = $data['name_id'];
        $identity->institution = $data['institution'];
        $identity->email = $data['email'];
        $identity->commonName = $data['common_name'];
        $identity->preferredLocale = $data['preferred_locale'];
        $identity->authorizations = $data['authorizations'];
        $identity->isSraa = $data['is_sraa'];

        return $identity;
    }


    /**
     * @return array List with institutions
     */
    public function getRaaInstitutions(): array
    {
        $choices = [];
        foreach ($this->authorizations as $institution => $role) {
            if ($role[0] == 'raa') {
                $choices[$institution] = $institution;
            }
        }

        return $choices;
    }
}
