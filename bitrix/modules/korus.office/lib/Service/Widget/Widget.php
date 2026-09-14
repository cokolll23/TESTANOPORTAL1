<?php

declare(strict_types=1);

namespace Korus\Office\Service\Widget;

use Bitrix\Main\Type\Contract\Arrayable;
use Korus\Office\Service\Service;

abstract class Widget implements Arrayable
{
    protected Service $service;
    protected string $title;
    protected string $color;
    protected string $image;

    public function __construct(Service $service)
    {
        $this->service = $service;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    protected function getTitleSuffix(): string
    {
        return '';
    }

    public function getImage(): string
    {
        return $this->image;
    }

    public function getColor(): string
    {
        return $this->color;
    }

    abstract public function getButtons(): array;

    public function toArray(): array
    {
        return [
            'TITLE' => $this->getTitle(),
            'TITLE_SUFFIX' => $this->getTitleSuffix(),
            'IMAGE' => $this->getImage(),
            'COLOR' => $this->getColor(),
            'DETAILS' => $this->service->getWidgetDetails(),
            'BUTTONS' => $this->getButtons()
        ];
    }

    public function setTitle(string $name): static
    {
        $this->title = $name;

        return $this;
    }

    public function setColor(string $color): static
    {
        $this->color = $color;

        return $this;
    }

    public function setImage(string $image): static
    {
        $this->image = $image;

        return $this;
    }
}
