<?php

namespace TallStackUi\View\Components;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Arr;
use Illuminate\View\ComponentSlot;
use TallStackUi\Foundation\Attributes\ColorsThroughOf;
use TallStackUi\Foundation\Attributes\SkipDebug;
use TallStackUi\Foundation\Attributes\SoftPersonalization;
use TallStackUi\Foundation\Personalization\Contracts\Personalization;
use TallStackUi\Foundation\Support\Colors\Components\CardColors;
use TallStackUi\TallStackUiComponent;

#[SoftPersonalization('card')]
#[ColorsThroughOf(CardColors::class)]
class Card extends TallStackUiComponent implements Personalization
{
    public function __construct(
        public ?string $color = null,
        public ?bool $light = null,
        public ?bool $bordered = null,
        public ?bool $minimize = null,
        public ?bool $initializeMinimized = false,
        public ?bool $close = null,
        public ?string $image = null,
        public ?string $position = 'top',
        #[SkipDebug]
        public string $style = 'solid',
        #[SkipDebug]
        public string $variation = 'background',
        #[SkipDebug]
        public ComponentSlot|string|null $header = null,
        #[SkipDebug]
        public ComponentSlot|string|null $footer = null
    ) {
        $this->style = $this->light ? 'light' : 'solid';
        $this->variation = $this->bordered ? 'border' : 'background';
    }

    public function blade(): View
    {
        return view('tallstack-ui::components.card');
    }

    public function personalization(): array
    {
        return Arr::dot([
            'wrapper' => [
                'first' => 'flex justify-center gap-4 min-w-full',
                'second' => 'dark:bg-dark-700 flex w-full flex-col rounded-lg bg-white shadow-md',
            ],
            'header' => [
                'wrapper' => [
                    'base' => 'dark:border-b-dark-600 flex items-center justify-between p-4',
                    'border' => 'border-b border-gray-100',
                ],
                'text' => [
                    'size' => 'text-md font-medium',
                    'color' => 'text-secondary-700 dark:text-dark-300',
                ],
            ],
            'body' => 'text-secondary-700 dark:text-dark-300 grow rounded-b-xl px-4 py-5',
            'footer' => [
                'wrapper' => 'text-secondary-700 dark:text-dark-300 dark:border-t-dark-600 rounded-lg rounded-t-none border-t p-4 px-6',
                'text' => 'flex items-center justify-end gap-2',
            ],
            'button' => [
                'minimize' => 'w-6 h-6',
                'maximize' => 'w-6 h-6',
                'close' => 'w-6 h-6',
            ],
            'image' => [
                'wrapper' => 'flex items-center gap-2',
                'rounded' => [
                    'top' => 'rounded-t-lg',
                    'bottom' => 'rounded-b-lg',
                ],
                'size' => 'w-full h-64',
            ],
            'transitions' => [
                'enter' => 'transition ease-out duration-100',
                'enter-start' => 'opacity-0 -translate-y-10',
                'enter-end' => 'opacity-100 translate-y-0',
                'leave' => 'transition ease-in duration-100',
                'leave-start' => 'opacity-100 translate-y-0',
                'leave-end' => 'opacity-0 -translate-y-10',
            ],
        ]);
    }
}
