<?php

namespace TallStackUi\Foundation\Personalization;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS)]
class SoftPersonalization
{
    public function __construct(protected string $key)
    {
        //
    }

    public function key(): string
    {
        return 'tallstack-ui::personalizations.'.$this->key;
    }
}