<?php
/**
 * This file is part of DoctrineRestDriver.
 *
 * DoctrineRestDriver is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * DoctrineRestDriver is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with DoctrineRestDriver.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace Circle\DoctrineRestDriver\Tests\Types;

use Circle\DoctrineRestDriver\Types\Request;
use Circle\DoctrineRestDriver\Types\RestClientOptions;

/**
 * Tests the request type
 *
 * @author    Tobias Hauck <tobias@circle.ai>
 * @copyright 2015 TeeAge-Beatz UG
 *
 * @coversDefaultClass Circle\DoctrineRestDriver\Types\Request
 */
class RequestTest extends \PHPUnit_Framework_TestCase {

    /**
     * @test
     * @group  unit
     * @covers ::__construct
     * @covers ::getMethod
     * @covers ::getUrl
     * @covers ::getUrlAndQuery
     * @covers ::getPayload
     * @covers ::getQuery
     * @covers ::getCurlOptions
     * @covers ::isExpectedStatusCode
     * @covers ::__toString
     */
    public function constructAndGetAll() {
        $options = [];

        $request = new Request([
            'method'      => 'get',
            'url'         => 'http://circle.ai',
            'curlOptions' => $options,
            'query'       => 'genious=1'
        ]);

        $this->assertSame('get', $request->getMethod());
        $this->assertSame('http://circle.ai', $request->getUrl());
        $this->assertSame('http://circle.ai?genious=1', $request->getUrlAndQuery());
        $this->assertSame(null, $request->getPayload());
        $this->assertSame('genious=1', $request->getQuery());
        $this->assertSame('GET http://circle.ai?genious=1 HTTP/1.1', $request->__toString());
        $this->assertEquals([], $request->getCurlOptions());
        $this->assertTrue($request->isExpectedStatusCode(200));
    }

    /**
     * @test
     * @group  unit
     * @covers ::__construct
     * @covers ::setCurlOptions
     */
    public function setCurlOptions() {
        $options = [
            'CURLOPT_HEADER' => true
        ];

        $expected = new Request([
            'method'      => 'get',
            'url'         => 'http://circle.ai',
            'curlOptions' => $options,
            'query'       => 'genious=1'
        ]);

        $request = new Request([
            'method'      => 'get',
            'url'         => 'http://circle.ai',
            'curlOptions' => [],
            'query'       => 'genious=1'
        ]);

        $this->assertEquals($expected, $request->setCurlOptions($options));
    }
}