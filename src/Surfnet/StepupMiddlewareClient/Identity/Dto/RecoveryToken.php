<?php

/**
 * Copyright 2022 SURFnet bv
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

namespace Surfnet\StepupMiddlewareClient\Identity\Dto;

final class RecoveryToken
{
    /**
     * @var string
     */
    public $recoveryTokenId;

    /**
     * @var string
     */
    public $type;

    /**
     * @var string
     */
    public $identifier;

    /**
     * @var string
     */
    public $status;

    /**
     * @var string
     */
    public $name;

    /**
     * @var string
     */
    public $institution;

    /**
     * @var string
     */
    public $email;

    /**
     * @var string
     */
    public $identityId;

    public static function from(array $apiResult)
    {
        $token = new self;
        $token->recoveryTokenId = $apiResult['id'];
        $token->type = $apiResult['type'];
        $token->status = $apiResult['status'];
        $token->identifier = $apiResult['recovery_method_identifier'];
        $token->identityId = $apiResult['identity_id'];
        $token->email = $apiResult['email'];
        $token->institution = $apiResult['institution'];
        $token->name = $apiResult['name'];
        return $token;
    }
}
