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

namespace Surfnet\StepupMiddlewareClientBundle\Identity\Command;

use Surfnet\StepupMiddlewareClientBundle\Command\AbstractCommand;

class UpdateIdentityCommand extends AbstractCommand
{
    /**
     * @var string
     */
    public $email;

    /**
     * @var string
     */
    public $commonName;

    /**
     * @param string $id
     * @param string $institution
     */
    public function __construct(public $id, public $institution)
    {
    }

    public function serialise(): array
    {
        return [
            'id'          => $this->id,
            'email'       => $this->email,
            'common_name' => $this->commonName,
            'institution' => $this->institution
        ];
    }
}
