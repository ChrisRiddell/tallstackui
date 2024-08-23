<?php

namespace TallStackUi\Foundation\Support\Concerns;

use Illuminate\View\ComponentAttributeBag;

trait SanitizePropertyValue
{
    // When the component is being used out of the Livewire context,
    // we need to prepare the value to the format expected by the component.
    public function sanitize(ComponentAttributeBag $attributes, ?string $property = null, ?bool $livewire = false): null|int|string|array
    {
        $value = $attributes->get('value');
        $value = $value === 'null' ? null : ($value === '[]' ? [] : $value);

        // We just transform the value when is not a Livewire
        // component or when the value is not empty and is a string.
        if ($livewire || (! $property || ! $value || ! is_string($value))) {
            return $value;
        }

        $string = str(htmlspecialchars_decode($value))->remove('"');

        // This function aims to sanitize the value, removing the
        // brackets and converting the value to the correct type.
        // We avoid use the `Stringable` here to increase the performance.
        $sanitize = function (string $value): int|string {
            $value = trim(str_replace(['[', ']'], '', $value));

            return ctype_digit($value) ? (int) $value : $value;
        };

        // If the value is not an array, we just sanitize the value.
        if (! $string->contains(',')) {
            $result = $sanitize($string->remove(['[', ']'])->trim()->value());

            return $string->contains(['[', ']']) ? [$result] : $result;
        }

        // If the value is an array, we need to explode
        // the string and map the values to sanitize them.
        return $string->explode(',')
            ->collect()
            ->map(fn (string|int $value) => $sanitize($value))
            ->toArray();
    }
}
