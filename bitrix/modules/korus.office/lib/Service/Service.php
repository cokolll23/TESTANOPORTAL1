<?php

namespace Korus\Office\Service;

use KTeam\Main\EO_User;

abstract class Service implements Widgetable
{
    protected EO_User $user;

    public function __construct(EO_User $user)
    {
        $this->user = $user;
    }
}
