<?php
/**
 * Created by PhpStorm.
 * User: srhoney
 * Date: 2/25/2019
 * Time: 3:09 PM
 */

namespace Circle\DoctrineRestDriver\Events;


final class RestDriverEvents
{
    const PREPARE_STATEMENT = 'prepare_statement';

    const CREATE_TRANSFORMER = 'create_transformer';

    const CREATE_CLIENT = 'create_client';

    const CREATE_REQUEST_FACTORY = 'create_request_factory';

    const CREATE_SQL_PARSER = 'create_sql_parser';
}