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
use Circle\DoctrineRestDriver\Events\RestDriverEvents;
use Circle\DoctrineRestDriver\Events\RestRequestArgs;
use Circle\DoctrineRestDriver\Exceptions\Exceptions;
use Circle\DoctrineRestDriver\Factory\RestClientFactory;
use Circle\DoctrineRestDriver\Factory\ResponseExceptionFactory;
use Circle\DoctrineRestDriver\Types\Request;
use Doctrine\Common\EventManager;
use Symfony\Component\HttpFoundation\Response;
use Circle\RestClientBundle\Services\RestClient as CiRestClient;
use Circle\DoctrineRestDriver\Exceptions\RequestFailedException;

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
     * @var EventManager
     */
    private $eventManager;

    /**
     * RestClient constructor
     * @param EventManager|null $eventManager
     */
    public function __construct(EventManager $eventManager = null) {
        $this->restClient = (new RestClientFactory())->createOne([]);
        $this->eventManager = $eventManager;
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
        if ($this->eventManager && $this->eventManager->hasListeners(RestDriverEvents::SEND_REQUEST)) {
            $this->eventManager->dispatchEvent(RestDriverEvents::SEND_REQUEST, new RestRequestArgs($request));
        }

        $method   = strtolower($request->getMethod());
        $response = $method === HttpMethods::GET || $method === HttpMethods::DELETE ? $this->restClient->$method($request->getUrlAndQuery(), $request->getCurlOptions()) : $this->restClient->$method($request->getUrlAndQuery(), $request->getPayload(), $request->getCurlOptions());

        try {
            return $request->isExpectedStatusCode($response->getStatusCode()) ? $response : Exceptions::RequestFailedException($request, $response->getStatusCode(), $response->getContent());
        } catch (DBALException\DriverException $e) {
            $responseExceptionFactory = new ResponseExceptionFactory();
            throw $responseExceptionFactory->createDbalException($response, $e);
        }
    }
}
