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

namespace Circle\DoctrineRestDriver\Events;

/**
 * Class RestDriverEvents
 *
 * @author Shawn Rhoney <smrhoney@gmail.com>
 * @package Circle\DoctrineRestDriver\Events
 */
final class RestDriverEvents
{
    const PREPARE_STATEMENT = 'prepare_statement';

    const CREATE_TRANSFORMER = 'create_transformer';

    const CREATE_CLIENT = 'create_client';

    const CREATE_REQUEST_FACTORY = 'create_request_factory';

    const CREATE_SQL_PARSER = 'create_sql_parser';

    const SEND_REQUEST = 'sendRequest';
}