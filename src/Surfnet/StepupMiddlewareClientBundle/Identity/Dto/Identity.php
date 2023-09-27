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

use Serializable;
use Surfnet\StepupMiddlewareClientBundle\Dto\Dto;
use Symfony\Component\Validator\Constraints as Assert;

class Identity implements Dto, Serializable, \Stringable
{
    /**
     * @Assert\NotBlank(message="middleware_client.dto.identity.id.must_not_be_blank")
     * @Assert\Type(type="string", message="middleware_client.dto.identity.id.must_be_string")
     */
    public ?string $id = null;

    /**
     * @Assert\NotBlank(message="middleware_client.dto.identity.name_id.must_not_be_blank")
     * @Assert\Type(type="string", message="middleware_client.dto.identity.name_id.must_be_string")
     * @var string
     */
    public $nameId;

    /**
     * @Assert\NotBlank(message="middleware_client.dto.identity.institution.must_not_be_blank")
     * @Assert\Type(type="string", message="middleware_client.dto.identity.institution.must_be_string")
     * @var string
     */
    public $institution;

    /**
     * @Assert\NotBlank(message="middleware_client.dto.identity.email.must_not_be_blank")
     * @Assert\Type(type="string", message="middleware_client.dto.identity.email.must_be_string")
     * @var string
     */
    public $email;

    /**
     * @Assert\NotBlank(message="middleware_client.dto.identity.common_name.must_not_be_blank")
     * @Assert\Type(type="string", message="middleware_client.dto.identity.common_name.must_be_string")
     * @var string
     */
    public $commonName;

    /**
     * @Assert\NotBlank(message="middleware_client.dto.identity.preferred_locale.must_not_be_blank")
     * @Assert\Type(type="string", message="middleware_client.dto.identity.preferred_locale.must_be_string")
     * @var string
     */
    public $preferredLocale;

    public static function fromData(array $data): self
    {
        $identity = new self();
        $identity->id = $data['id'];
        $identity->nameId = $data['name_id'];
        $identity->institution = $data['institution'];
        $identity->email = $data['email'];
        $identity->commonName = $data['common_name'];
        $identity->preferredLocale = $data['preferred_locale'];

        return $identity;
    }

    /**
     * Used so that we can serialize the Identity within the SAMLToken, so we can store the token in a session.
     * This to support persistent login
     *
     * @return string
     */
    public function serialize()
    {
        return serialize(
            [
                $this->id,
                $this->nameId,
                $this->institution,
                $this->email,
                $this->commonName,
                $this->preferredLocale
            ]
        );
    }

    /**
     * Used so that we can unserialize the Identity within the SAMLToken, so that it can be loaded from the session
     * for persistent login.
     *
     * @param string $serialized
     */
    public function unserialize($serialized): void
    {
        [$this->id, $this->nameId, $this->institution, $this->email, $this->commonName, $this->preferredLocale] = unserialize($serialized);
    }

    /**
     * This is a requirement to be able to set the identity as user in the TokenInterface.
     * (so we can use it as user in SF)
     *
     * @return string
     */
    public function __toString(): string
    {
        return $this->id;
    }
}
