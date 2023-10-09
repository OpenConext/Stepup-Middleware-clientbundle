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

namespace Surfnet\StepupMiddlewareClientBundle\Configuration\Command;

use Surfnet\StepupMiddlewareClientBundle\Command\AbstractCommand;
use Symfony\Component\Validator\Constraints as Assert;

class ChangeRaLocationCommand extends AbstractCommand
{
    /**
     * @Assert\NotBlank()
     * @Assert\Type(type="string")
     *
     * @var string
     */
    public string $id;

    /**
     * @Assert\NotBlank()
     * @Assert\Type(type="string")
     *
     * @var string
     */
    public string $institution;

    /**
     * @Assert\NotBlank()
     * @Assert\Type(type="string")
     *
     * @var string
     */
    public string $name;

    /**
     * @Assert\NotBlank()
     * @Assert\Type(type="string")
     *
     * @var string
     */
    public string $location;

    /**
     * @Assert\NotBlank()
     * @Assert\Type(type="string")
     *
     * @var string
     */
    public string $contactInformation;

    public function serialise(): array
    {
        return [
            'ra_location_id' => $this->id,
            'institution' => $this->institution,
            'ra_location_name' => $this->name,
            'location' => $this->location,
            'contact_information' => $this->contactInformation,
        ];
    }
}
