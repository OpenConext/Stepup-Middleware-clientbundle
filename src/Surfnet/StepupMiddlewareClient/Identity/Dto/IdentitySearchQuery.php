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

namespace Surfnet\StepupMiddlewareClient\Identity\Dto;

use Assert;
use Surfnet\StepupMiddlewareClient\Dto\HttpQuery;

class IdentitySearchQuery implements HttpQuery
{
    private string $nameId;

    private string $institution;

    private string $email;

    private string $commonName;

    public function setInstitution(string $institution): self
    {
        $this->assertNonEmptyString($institution, 'institution');

        $this->institution = $institution;

        return $this;
    }

    public function setCommonName(string $commonName): self
    {
        $this->assertNonEmptyString($commonName, 'commonName');

        $this->commonName = $commonName;

        return $this;
    }

    public function setEmail(string $email): self
    {
        $this->assertNonEmptyString($email, 'email');

        $this->email = $email;

        return $this;
    }

    public function setNameId(string $nameId): self
    {
        $this->assertNonEmptyString($nameId, 'nameId');

        $this->nameId = $nameId;

        return $this;
    }

    private function assertNonEmptyString(string $value, string $name): void
    {
        $message = sprintf(
            '"%s" must be a non-empty string, "%s" given',
            $name,
            (get_debug_type($value))
        );

        Assert\that($value)->string($message)->notEmpty($message);
    }

    public function toHttpQuery(): string
    {
        $fields = [];
        if ($this->institution !== '' && $this->institution !== '0') {
            $fields['institution'] = $this->institution;
        }

        if ($this->commonName !== '' && $this->commonName !== '0') {
            $fields['commonName'] = $this->commonName;
        }

        if ($this->email !== '' && $this->email !== '0') {
            $fields['email'] = $this->email;
        }

        if ($this->nameId !== '' && $this->nameId !== '0') {
            $fields['NameID'] = $this->nameId;
        }

        return '?' . http_build_query($fields);
    }
}
