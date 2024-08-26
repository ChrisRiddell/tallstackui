<?php

namespace TallStackUi\Foundation\Support\Runtime;

use TallStackUi\Foundation\Attributes\PassThroughRuntime;
use TallStackUi\Foundation\Support\Miscellaneous\ReflectComponent;

// The main purpose of this class, the classes inside the Components/ folder and
// the PassThroughRuntime attribute is to allow us to define variables at runtime
// so that the components contain only one @php tag at the top of the file with
// $personalize = $classes() instead of having to do several steps to define
// variables. To make this happen, it was necessary to rename the names of
// certain variables used in Blade only, since when we merge, we do not replace
// the original variables, but rather create new ones.
class CompileRuntime
{
    public static function of(string $component, array $data, array $shared): array
    {
        $reflect = app(ReflectComponent::class, ['component' => $component]);
        $class = $reflect->attribute(PassThroughRuntime::class)?->newInstance()->runtime;

        if (! $class) {
            return [];
        }

        return app($class, [
            'data' => $data,
            'livewire' => $shared['__livewire'] ?? null,
            'errors' => $shared['errors'] ?? null,
        ])->runtime();
    }
}
