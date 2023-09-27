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

use Doctrine\ORM\Mapping as ORM;
use JsonSerializable;
use Surfnet\StepupMiddlewareClientBundle\Dto\Dto;

/**
 * A second factor as displayed in the registration authority application. One exists for every second factor,
 * regardless of state. As such, it sports a status property, indicating whether its vetted, revoked etc.
 */
final class RaSecondFactor implements Dto
{
    public const STATUS_UNVERIFIED = 'unverified';
    public const STATUS_VERIFIED = 'verified';
    public const STATUS_VETTED = 'vetted';
    public const STATUS_REVOKED = 'revoked';

    /**
     * @var string The second factor's ID (UUID).
     */
    public $id;

    /**
     * @var string
     */
    public $type;

    /**
     * @var string The ID of the specific instance of second factor type (ie. phone number, Yubikey public ID).
     */
    public $secondFactorId;

    /**
     * @var string One of the RaSecondFactor::STATUS_* constants.
     */
    public $status;

    /**
     * @var string
     */
    public $identityId;

    /**
     * @var string
     */
    public $institution;

    /**
     * The name of the registrant.
     *
     * @var string
     */
    public $name;

    /**
     * Number of the document that was used in vetting.
     *
     * @var string|null
     */
    public $documentNumber;

    /**
     * The e-mail of the registrant.
     *
     * @var string
     */
    public $email;

    /**
     * @param array $data
     * @return static
     */
    public static function fromData(array $data): self
    {
        $secondFactor = new self();
        $secondFactor->id = $data['id'];
        $secondFactor->type = $data['type'];
        $secondFactor->secondFactorId = $data['second_factor_id'];
        $secondFactor->status = $data['status'];
        $secondFactor->identityId = $data['identity_id'];
        $secondFactor->institution = $data['institution'];
        $secondFactor->name = $data['name'];
        if (isset($data['document_number'])) {
            $secondFactor->documentNumber = $data['document_number'];
        }
        $secondFactor->email = $data['email'];

        return $secondFactor;
    }

    private function __construct()
    {
    }
}
