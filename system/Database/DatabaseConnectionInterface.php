<?php

namespace Pureeasyphp\Database;

interface DatabaseConnectionInterface
{
    public function connect(array $config);
}
