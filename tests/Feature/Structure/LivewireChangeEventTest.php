<?php

use TallStackUi\Foundation\Traits\WireChangeEvent;
use TallStackUi\View\Components\Form\Date;
use TallStackUi\View\Components\Form\Pin;
use TallStackUi\View\Components\Form\Time;
use TallStackUi\View\Components\Select\Styled;

describe('LivewireChangeEvent', function () {
    test('trait has method', function () {
        expect(WireChangeEvent::class)->toHaveMethod('change');
    });

    test('trait should only be used in', function () {
        expect(WireChangeEvent::class)
            ->toOnlyBeUsedIn([
                Pin::class,
                Styled::class,
                Date::class,
                Time::class,
            ]);
    });
});
