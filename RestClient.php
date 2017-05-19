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

namespace Circle\DoctrineRestDriver;

use Circle\DoctrineRestDriver\Enums\HttpMethods;
use Circle\DoctrineRestDriver\Exceptions\Exceptions;
use Circle\DoctrineRestDriver\Factory\RestClientFactory;
use Circle\DoctrineRestDriver\Types\Request;
use Symfony\Component\HttpFoundation\Response;
use Circle\RestClientBundle\Services\RestClient as CiRestClient;
use Circle\DoctrineRestDriver\Exceptions\RequestFailedException;
use Doctrine\DBAL\Exception as DBALException;
use Doctrine\DBAL\Driver\DriverException as DriverExceptionInterface;
use Doctrine\DBAL\Exception\DriverException;

/**
 * Rest client to send requests and map responses
 *
 * @author    Tobias Hauck <tobias@circle.ai>
 * @copyright 2015 TeeAge-Beatz UG
 */
class RestClient {

    /**
     * @var CiRestClient
     */
    private $restClient;
    
    /**
     * @var int[]
     */
    private $sucessfulStatusCodes = [
        Response::HTTP_OK,
        Response::HTTP_CREATED,
        Response::HTTP_ACCEPTED,
        Response::HTTP_NON_AUTHORITATIVE_INFORMATION,
        Response::HTTP_NO_CONTENT,
        Response::HTTP_RESET_CONTENT,
        Response::HTTP_PARTIAL_CONTENT,
        Response::HTTP_MULTI_STATUS,
        Response::HTTP_ALREADY_REPORTED,
        Response::HTTP_IM_USED,
        Response::HTTP_NOT_FOUND,
        Response::HTTP_GONE,
        
    ];

    /**
     * RestClient constructor
     */
    public function __construct() {
        $this->restClient = (new RestClientFactory())->createOne([]);
    }

    /**
     * sends the request
     *
     * @param  Request $request
     * @return Response
     * @throws RequestFailedException
     *
     * @SuppressWarnings("PHPMD.StaticAccess")
     */
    public function send(Request $request) {
        $method     = strtolower($request->getMethod());
        $response   = $method === HttpMethods::GET || $method === HttpMethods::DELETE ? $this->restClient->$method($request->getUrlAndQuery(), $request->getCurlOptions()) : $this->restClient->$method($request->getUrlAndQuery(), $request->getPayload(), $request->getCurlOptions());

        try {
            return $this->isSuccessfulRequest($response->getStatusCode()) ? $response : Exceptions::RequestFailedException($request, $response->getStatusCode(), $response->getContent());
        } catch (DBALException\DriverException $e) {
            throw $this->handleFailedResponse($response, $e);
        }
    }
    
    /**
     * Handle a failed response by creating a specific exception for the given
     * response.
     * 
     * @param Response $response
     * @param DriverExceptionInterface $exception
     * @return DriverException
     */
    private function handleFailedResponse(Response $response, DriverExceptionInterface $exception) {
        switch ($response->getStatusCode()) {
            case Response::HTTP_CONFLICT:
                return new DBALException\UniqueConstraintViolationException($response->getContent(), $exception);

            case Response::HTTP_BAD_REQUEST:
                return new DBALException\SyntaxErrorException($response->getContent(), $exception);

            case Response::HTTP_METHOD_NOT_ALLOWED:
            case Response::HTTP_NOT_ACCEPTABLE:
            case Response::HTTP_REQUEST_TIMEOUT:
            case Response::HTTP_LENGTH_REQUIRED:
            case Response::HTTP_INTERNAL_SERVER_ERROR:
                return new DBALException\ServerException($response->getContent(), $exception);
            
            case Response::HTTP_CONFLICT:
                return new DBALException\ConstraintViolationException($response->getContent(), $exception);

            case Response::HTTP_UNAUTHORIZED:
            case Response::HTTP_FORBIDDEN:
            case Response::HTTP_PROXY_AUTHENTICATION_REQUIRED:
            case Response::HTTP_BAD_GATEWAY:
            case Response::HTTP_SERVICE_UNAVAILABLE:
            case Response::HTTP_GATEWAY_TIMEOUT:
                return new DBALException\ConnectionException($response->getContent(), $exception);

            default:
                return new DBALException\DriverException($response->getContent(), $exception);

        }
    }
    
    private function isSuccessfulRequest($statusCode)
    {
        return in_array($statusCode, $this->sucessfulStatusCodes);
    }
}