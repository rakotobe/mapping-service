<?php

declare(strict_types=1);

namespace Core\Domain\Addon;

use Core\Domain\InvalidArgumentException;
use Illuminate\Support\Collection;

class AddonsCollection extends Collection
{
    /**
     * @param mixed $offset
     * @param mixed $value
     * @throws InvalidArgumentException
     */
    public function offsetSet($offset, $value)
    {
        if (!is_a($value, Addon::class)) {
            throw new InvalidArgumentException('Invalid type for collection ' . __CLASS__);
        }
        parent::offsetSet($offset, $value);
    }
}
