<?php

declare(strict_types = 1);

/**
 * Copyright 2016 SURFnet bv
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

class RemoveRaLocationCommand extends AbstractCommand
{
    /**
     * @var string
     */
    public string $institution;

    /**
     * @var string
     */
    public string $raLocationId;

    /**
     * @return array
     */
    public function serialise(): array
    {
        return [
            'institution'      => $this->institution,
            'ra_location_id'   => $this->raLocationId
        ];
    }
}
