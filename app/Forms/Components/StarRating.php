<?php

declare(strict_types=1);

namespace App\Forms\Components;

use Filament\Forms\Components\Field;

class StarRating extends Field
{
    public $hoverRating = 0;

    protected string $view = 'forms.components.star-rating';

    public function hoverStar($rating)
    {
        $this->hoverRating = $rating;
    }

    public function resetStars()
    {
        $this->hoverRating = 0;
    }

    public function rating()
    {
        return $this->hoverRating;
    }
}
