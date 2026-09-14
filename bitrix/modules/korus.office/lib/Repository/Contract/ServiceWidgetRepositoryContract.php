<?php

declare(strict_types=1);

namespace Korus\Office\Repository\Contract;

interface ServiceWidgetRepositoryContract
{
    public function getActiveWidgets(): array;
}