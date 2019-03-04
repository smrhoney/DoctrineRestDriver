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

namespace Circle\DoctrineRestDriver\Types;

use Circle\DoctrineRestDriver\Enums\SqlOperations;
use Circle\DoctrineRestDriver\MetaData;

/**
 * Payload type: Union type for InsertChangeSet and InsertChangeSet
 *
 * @author    Tobias Hauck <tobias@circle.ai>
 * @copyright 2015 TeeAge-Beatz UG
 */
class Payload {

    /**
     * Returns an InsertChangeSet::create or InsertChangeSet::create
     * result or null depending on the given tokens array
     *
     * @param  array $tokens
     * @param  array $options
     * @param MetaData $metaData
     * @return null|string
     *
     * @throws \Circle\DoctrineRestDriver\Validation\Exceptions\InvalidTypeException
     * @SuppressWarnings("PHPMD.StaticAccess")
     */
    public static function create(array $tokens, array $options, MetaData $metaData) {
        HashMap::assert($tokens, 'tokens');

        $options['Tokens'] = $tokens;
        $options['MetaData'] = $metaData;
        $format    = Format::create($options);
        $operation = SqlOperation::create($tokens);

        if ($operation === SqlOperations::INSERT) return $format->encode(InsertChangeSet::create($tokens));
        if ($operation === SqlOperations::UPDATE) return $format->encode(UpdateChangeSet::create($tokens));

        return null;
    }
}