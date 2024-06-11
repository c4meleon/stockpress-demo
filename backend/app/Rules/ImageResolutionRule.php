<?php

declare(strict_types=1);

namespace App\Rules;

use Illuminate\Contracts\Validation\ValidationRule;
use Intervention\Image\Laravel\Facades\Image;

class ImageResolutionRule implements ValidationRule
{
    protected int $minWidth;
    protected int $minHeight;

    /**
     * Create a new rule instance.
     *
     * @param int $minWidth
     * @param int $minHeight
     */
    public function __construct(int $minWidth = 500, int $minHeight = 500)
    {
        $this->minWidth = $minWidth;
        $this->minHeight = $minHeight;
    }

    /**
     * Run the validation rule.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @param  \Closure  $fail
     * @return void
     */
    public function validate(string $attribute, mixed $value, \Closure $fail): void
    {
        try {
            $image = Image::read($value->getRealPath());

            if ($image->width() < $this->minWidth || $image->height() < $this->minHeight) {
                $fail("The {$attribute} must have a minimum resolution of {$this->minWidth}x{$this->minHeight} pixels.");
            }
        } catch (\Exception $e) {
            $fail("The {$attribute} is not a valid image.");
        }
    }
}
