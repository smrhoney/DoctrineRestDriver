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

namespace Circle\DoctrineRestDriver\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Mock controller for functional testing
 *
 * @author    Tobias Hauck <tobias@circle.ai>
 * @copyright 2015 TeeAge-Beatz UG
 */
class MockController extends Controller {

    /**
     * Mock action for testing get
     *
     * @param  string   $id
     * @return Response
     */
    public function getAction($id) {
        if ($id != 1) return new Response('', 404);

        return new Response(json_encode([
            'id'                          => 1,
            'extremelyStrange_identifier' => 1,
            'name'                        => 'MyName',
            'value'                       => 'MyValue',
        ]));
    }

    /**
     * Mock action for testing getAll
     *
     * @return Response
     */
    public function getAllAction(Request $request) {
        $name = $request->query->get('name');

        $myName = [
            'id'    => 1,
            'name'  => 'MyName',
            'value' => 'MyValue',
        ];

        $nextName = [
            'id'    => 2,
            'name'  => 'NextName',
            'value' => 'NextValue',
        ];

        if ($name === 'MyName') {
            $data = json_encode($myName);
        }

        if ($name === 'NextName') {
            $data = json_encode($nextName);
        }

        if (!isset($data) && $name) {
            $data = json_encode([]);
        }

        if (! $name) {
            $data = json_encode([
                $myName,
                $nextName,
            ]);
        }

        return new Response($data);
    }

    /**
     * Mock action for testing post
     *
     * @param  Request  $request
     * @return Response
     */
    public function postAction(Request $request) {
        $payload = json_decode($request->getContent());

        return new Response(json_encode([
            'id'    => 1,
            'name'  => $payload->name,
            'value' => $payload->value,
        ]), 201);
    }

    /**
     * Mock action for testing put
     *
     * @param  string   $id
     * @return Response
     */
    public function putAction($id) {
        if ($id != 1) return new Response('', 404);

        return new Response(json_encode([
            'id'    => 1,
            'name'  => 'MyName',
            'value' => 'MyValue',
        ]));
    }

    /**
     * Mock action for testing delete
     *
     * @param  string   $id
     * @return Response
     */
    public function deleteAction($id) {
        if ($id != 1) return new Response('', 404);

        return new Response('', 204);
    }

    /**
     * Mock action for testing associated entities
     *
     * @param  Request  $request
     * @return Response
     */
    public function postCategoriesAction(Request $request) {
        $payload = json_decode($request->getContent());

        return new Response(json_encode([
            'id'         => 1,
            'name'       => $payload->name,
            'product_id' => $payload->product_id,
        ]), 201);
    }


    public function newProductsAction(Request $request) {
        $nextName = [
            'id'    => 2,
            'name'  => 'NextName',
            'value' => 'NextValue',
        ];


        return new Response (json_encode([
           $nextName,
        ]));
    }
}
