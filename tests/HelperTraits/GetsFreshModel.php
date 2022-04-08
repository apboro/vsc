<?php

namespace Tests\HelperTraits;

trait GetsFreshModel
{
    protected function freshModel(string $modelClass, $factory = null, ?array $arguments = null)
    {
        if ($factory !== null) {
            return $arguments === null ? $factory() : $factory($arguments);
        }

        return $arguments === null ? new $modelClass : new $modelClass($arguments);
    }
}
