<?php

namespace TallStackUi\View\Components\Form\Select\Traits;

use Illuminate\Support\Collection;
use InvalidArgumentException;
use TallStackUi\View\Components\Form\Select\Native;

trait Setup
{
    protected function setup(): void
    {
        $this->options = $this->options instanceof Collection
            ? $this->options->values()->toArray()
            : array_values($this->options);

        $this->select ??= 'label:label|value:value';

        if (! $this->select || ($this->options !== [] && ! is_array($this->options[0]))) {
            return;
        }

        $select = explode('|', $this->select);

        [$label, $value] = array_map(fn ($item) => explode(':', $item)[1], $select);

        $component = $this instanceof Native ? 'select.native' : 'select.styled';

        $this->options = collect($this->options)->map(function (array $item) use ($label, $value, $component): array {
            if (! isset($item[$label])) {
                throw new InvalidArgumentException("The $component key [$label] is missing in the options array.");
            }

            if (! isset($item[$value])) {
                throw new InvalidArgumentException("The $component [$value] is missing in the options array.");
            }

            $this->grouped = is_array($item[$value]);

            return [
                $label => $item[$label],
                $value => $item[$value],
                'image' => current(array_intersect_key($item, array_flip(['image', 'img', 'img_src']))) ?: null,
                'disabled' => $item['disabled'] ?? false,
                'description' => current(array_intersect_key($item, array_flip(['description', 'note']))) ?: null,
            ];
        })->toArray();

        $this->selectable = ['label' => $label, 'value' => $value];
    }
}
