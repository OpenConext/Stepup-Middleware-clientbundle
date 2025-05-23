<?php

declare(strict_types = 1);

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

use PHPUnit\Framework\TestCase;
use Surfnet\StepupMiddlewareClient\Configuration\Service\InstitutionConfigurationOptionsService as LibraryInstitutionConfigurationOptionsService;
use Surfnet\StepupMiddlewareClientBundle\Configuration\Dto\InstitutionConfigurationOptions;
use Surfnet\StepupMiddlewareClientBundle\Configuration\Service\InstitutionConfigurationOptionsService;
use Surfnet\StepupMiddlewareClientBundle\Exception\InvalidResponseException;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class InstitutionConfigurationOptionsServiceTest extends TestCase
{
    protected function tearDown(): void
    {
        Mockery::close();
    }

    /**
     * @group institution-configuration
     */
    public function testQueriedInstitutionConfigurationOptionsAreConvertedToADtoCorrectly(): void
    {
        $institution = 'surfnet.nl';

        $expectedInstitutionConfigurationOptions = new InstitutionConfigurationOptions();
        $expectedInstitutionConfigurationOptions->useRaLocations = true;
        $expectedInstitutionConfigurationOptions->showRaaContactInformation = false;
        $expectedInstitutionConfigurationOptions->verifyEmail = true;
        $expectedInstitutionConfigurationOptions->selfVet = true;
        $expectedInstitutionConfigurationOptions->numberOfTokensPerIdentity = 2;
        $expectedInstitutionConfigurationOptions->allowedSecondFactors = ['sms', 'yubikey'];
        $expectedInstitutionConfigurationOptions->useRa = ['surfnet.nl'];
        $expectedInstitutionConfigurationOptions->useRaa = ['surfnet.nl'];
        $expectedInstitutionConfigurationOptions->selectRaa = ['surfnet.nl'];

        $validResponseData = [
            'institution'                   => $institution,
            'use_ra_locations'              => true,
            'show_raa_contact_information'  => false,
            'verify_email'                  => true,
            'self_vet'                      => true,
            'sso_on_2fa'                   => false,
            'sso_registration_bypass'      => false,
            'allow_self_asserted_tokens'    => false,
            'number_of_tokens_per_identity' => 2,
            'allowed_second_factors'        => ['sms', 'yubikey'],
            'use_ra'                        => [$institution],
            'use_raa'                       => [$institution],
            'select_raa'                    => [$institution],
        ];

        $libraryService = Mockery::mock(LibraryInstitutionConfigurationOptionsService::class);
        $libraryService->shouldReceive('getInstitutionConfigurationOptionsFor')
            ->with($institution)
            ->once()
            ->andReturn($validResponseData);

        $violations = Mockery::mock(ConstraintViolationListInterface::class);
        $violations->shouldReceive('count')
            ->once()
            ->andReturn(0);

        $validator = Mockery::mock(ValidatorInterface::class);
        $validator->shouldReceive('validate')
            ->once()
            ->andReturn($violations);

        $service = new InstitutionConfigurationOptionsService($libraryService, $validator);
        $actualInstitutionConfigurationOptions = $service->getInstitutionConfigurationOptionsFor($institution);

        $this->assertEquals($expectedInstitutionConfigurationOptions, $actualInstitutionConfigurationOptions);
    }

    /**
     * @group institution-configuration
     *
     * @dataProvider nonBooleanProvider
     * @param array|string|int|float|stdClass|null $nonBoolean
     */
    public function testInstitutionConfigurationOptionsWithANonBooleanUseRaLocationsOptionAreInvalid(null|array|string|int|float|stdClass $nonBoolean): void
    {
        $institution = 'surfnet.nl';

        $invalidResponseData = [
            'institution'                  => $institution,
            'use_ra_locations'             => true,
            'show_raa_contact_information' => $nonBoolean,
            'verify_email'                 => true,
            'self_vet'                     => false,
            'sso_on_2fa'                   => false,
            'sso_registration_bypass'      => false,
            'allow_self_asserted_tokens'   => false,
            'number_of_tokens_per_identity' => 1,
            'allowed_second_factors'       => ['sms', 'yubikey'],
            'use_ra'                        => [$institution],
            'use_raa'                       => [$institution],
            'select_raa'                    => [$institution],
        ];

        $libraryService = Mockery::mock(LibraryInstitutionConfigurationOptionsService::class);
        $libraryService->shouldReceive('getInstitutionConfigurationOptionsFor')
            ->with($institution)
            ->once()
            ->andReturn($invalidResponseData);

        $violations = Mockery::mock(ConstraintViolationListInterface::class);
        $violations->shouldReceive('count')
            ->once()
            ->andReturn(1);

        $violations->shouldReceive('getIterator')
            ->once()
            ->andReturn(new ArrayIterator);

        $validator = Mockery::mock(ValidatorInterface::class);
        $validator->shouldReceive('validate')
            ->once()
            ->andReturn($violations);

        $service = new InstitutionConfigurationOptionsService($libraryService, $validator);
        $this->expectException(InvalidResponseException::class);
        $service->getInstitutionConfigurationOptionsFor($institution);
    }

    /**
     * @group institution-configuration
     *
     * @dataProvider nonBooleanProvider
     * @param array|string|int|float|stdClass|null $nonBoolean
     */
    public function testInstitutionConfigurationOptionsWithANonBooleanShowRaaContactInformationOptionAreInvalid(null|array|string|int|float|stdClass $nonBoolean): void
    {
        $institution = 'surfnet.nl';

        $invalidResponseData = [
            'institution'                  => $institution,
            'use_ra_locations'             => $nonBoolean,
            'show_raa_contact_information' => true,
            'verify_email'                 => true,
            'self_vet'                     => false,
            'allow_self_asserted_tokens'    => false,
            'sso_on_2fa'                   => false,
            'sso_registration_bypass'      => false,
            'number_of_tokens_per_identity' => 0,
            'allowed_second_factors'       => ['sms', 'yubikey'],
            'use_ra'                        => [$institution],
            'use_raa'                       => [$institution],
            'select_raa'                    => [$institution],
        ];

        $libraryService = Mockery::mock(LibraryInstitutionConfigurationOptionsService::class);
        $libraryService->shouldReceive('getInstitutionConfigurationOptionsFor')
            ->with($institution)
            ->once()
            ->andReturn($invalidResponseData);

        $violations = Mockery::mock(ConstraintViolationListInterface::class);
        $violations->shouldReceive('count')
            ->once()
            ->andReturn(1);

        $violations->shouldReceive('getIterator')
            ->once()
            ->andReturn(new ArrayIterator);

        $validator = Mockery::mock(ValidatorInterface::class);
        $validator->shouldReceive('validate')
            ->once()
            ->andReturn($violations);

        $service = new InstitutionConfigurationOptionsService($libraryService, $validator);
        $this->expectException(InvalidResponseException::class);
        $service->getInstitutionConfigurationOptionsFor($institution);
    }

    /**
     * @group institution-configuration
     *
     * @dataProvider nonArrayProvider
     * @param bool|string|int|float|stdClass|null $nonArray
     */
    public function testInstitutionConfigurationOptionsWithANonArrayAllowedSecondFactorsAreInvalid(null|bool|string|int|float|stdClass $nonArray): void
    {
        $institution = 'surfnet.nl';

        $invalidResponseData = [
            'institution'                  => $institution,
            'use_ra_locations'             => $nonArray,
            'show_raa_contact_information' => true,
            'verify_email'                 => true,
            'self_vet'                     => false,
            'sso_on_2fa'                   => false,
            'sso_registration_bypass'      => false,
            'allow_self_asserted_tokens'    => false,
            'number_of_tokens_per_identity' => 5,
            'allowed_second_factors'       => ['sms', 'yubikey'],
            'use_ra'                        => [$institution],
            'use_raa'                       => [$institution],
            'select_raa'                    => [$institution],
        ];

        $libraryService = Mockery::mock(LibraryInstitutionConfigurationOptionsService::class);
        $libraryService->shouldReceive('getInstitutionConfigurationOptionsFor')
            ->with($institution)
            ->once()
            ->andReturn($invalidResponseData);

        $violations = Mockery::mock(ConstraintViolationListInterface::class);
        $violations->shouldReceive('count')
            ->once()
            ->andReturn(1);

        $violations->shouldReceive('getIterator')
            ->once()
            ->andReturn(new ArrayIterator);

        $validator = Mockery::mock(ValidatorInterface::class);
        $validator->shouldReceive('validate')
            ->once()
            ->andReturn($violations);

        $service = new InstitutionConfigurationOptionsService($libraryService, $validator);
        $this->expectException(InvalidResponseException::class);
        $service->getInstitutionConfigurationOptionsFor($institution);
    }

    /**
     * @group institution-configuration
     *
     * @dataProvider nonStringProvider
     * @param bool|array|int|float|stdClass|null $nonArray
     */
    public function testInstitutionConfigurationOptionsWithANonStringsAllowedSecondFactorsAreInvalid(null|bool|array|int|float|stdClass $nonArray): void
    {
        $institution = 'surfnet.nl';

        $invalidResponseData = [
            'institution'                  => $institution,
            'use_ra_locations'             => $nonArray,
            'show_raa_contact_information' => true,
            'verify_email'                 => true,
            'self_vet'                     => false,
            'sso_on_2fa'                   => false,
            'sso_registration_bypass'      => false,
            'allow_self_asserted_tokens'    => false,
            'number_of_tokens_per_identity' => 1,
            'allowed_second_factors'       => ['sms', 'yubikey'],
            'use_ra'                        => [$institution],
            'use_raa'                       => [$institution],
            'select_raa'                    => [$institution],
        ];

        $libraryService = Mockery::mock(LibraryInstitutionConfigurationOptionsService::class);
        $libraryService->shouldReceive('getInstitutionConfigurationOptionsFor')
            ->with($institution)
            ->once()
            ->andReturn($invalidResponseData);

        $violations = Mockery::mock(ConstraintViolationListInterface::class);
        $violations->shouldReceive('count')
            ->once()
            ->andReturn(1);

        $violations->shouldReceive('getIterator')
            ->once()
            ->andReturn(new ArrayIterator);

        $validator = Mockery::mock(ValidatorInterface::class);
        $validator->shouldReceive('validate')
            ->once()
            ->andReturn($violations);

        $service = new InstitutionConfigurationOptionsService($libraryService, $validator);
        $this->expectException(InvalidResponseException::class);
        $service->getInstitutionConfigurationOptionsFor($institution);
    }

    public function nonBooleanProvider(): array
    {
        return [
            'null'    => [null],
            'array'   => [[]],
            'string'  => [''],
            'integer' => [1],
            'float'   => [1.23],
            'object'  => [new stdClass],
        ];
    }

    public function nonArrayProvider(): array
    {
        return [
            'null'    => [null],
            'boolean' => [true],
            'string'  => [''],
            'integer' => [1],
            'float'   => [1.23],
            'object'  => [new stdClass],
        ];
    }

    public function nonStringProvider(): array
    {
        return [
            'null'    => [null],
            'boolean' => [true],
            'array'   => [[]],
            'integer' => [1],
            'float'   => [1.23],
            'object'  => [new stdClass],
        ];
    }
}
