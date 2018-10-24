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

namespace Surfnet\StepupMiddlewareClient\Tests\Service;

use GuzzleHttp\Client;
use GuzzleHttp\Handler\MockHandler;
use GuzzleHttp\Psr7\Response;
use Mockery as m;
use Surfnet\StepupMiddlewareClient\Service\ApiService;

class ApiModelServiceTest extends \PHPUnit_Framework_TestCase
{
    use m\Adapter\Phpunit\MockeryPHPUnitIntegration;

    public function testItResources()
    {
        $data     = ['data' => 'My first resource'];
        $response = new Response(200, [], json_encode($data));

        $handler = new MockHandler([$response]);
        $client  = new Client(['handler' => $handler]);
        $service = new ApiService($client);

        $responseData = $service->read('/resource');

        $this->assertSame($data, $responseData);
    }

    public function testItFormatsResourceParameters()
    {
        $data        = ['data' => 'My first resource'];
        $expectedUri = '/resource/John%2FDoe';

        $response = m::mock('GuzzleHttp\Message\ResponseInterface');
        $response->shouldReceive('getBody')->andReturn(json_encode($data));
        $response->shouldReceive('getStatusCode')->andReturn('200');
        $guzzle      = m::mock('GuzzleHttp\Client')
            ->shouldReceive('get')->with($expectedUri, m::any())->once()->andReturn($response)
            ->getMock();

        $service = new ApiService($guzzle);
        $service->read('/resource/%s', ['John/Doe']);
    }

    public function testItThrowsWhenMalformedJsonIsReturned()
    {
        $malformedJson = 'This is some malformed JSON';
        $response      = new Response(200, [], $malformedJson);

        $handler = new MockHandler([$response]);
        $client  = new Client(['handler' => $handler]);
        $service = new ApiService($client);

        $this->expectException('\Surfnet\StepupMiddlewareClient\Exception\MalformedResponseException');
        $this->expectExceptionMessage('malformed JSON');

        $service->read('/resource');
    }

    public function testItReturnsNullWhenTheResourceDoesntExist()
    {
        $data     = ['errors' => ["Requested identity doesn't exist"]];
        $response = new Response(404, [], json_encode($data));

        $handler = new MockHandler([$response]);
        $client  = new Client(['handler' => $handler]);
        $service = new ApiService($client);

        $responseData = $service->read('/identity/abc');

        $this->assertNull($responseData, "Resource doesn't exist, yet a non-null value was returned");
    }

    public function testItThrowsWhenTheConsumerIsntAuthorisedToAccessTheResource()
    {
        $this->expectException('Surfnet\StepupMiddlewareClient\Exception\AccessDeniedToResourceException');

        $data     = ['errors' => ['You are not authorised to access this identity']];
        $response = new Response(403, [], json_encode($data));

        $handler = new MockHandler([$response]);
        $client  = new Client(['handler' => $handler]);
        $service = new ApiService($client);

        $service->read('/identity/abc');
    }
}
