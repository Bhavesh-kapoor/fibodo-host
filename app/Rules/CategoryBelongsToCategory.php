<?php

namespace App\Rules;

use App\Models\Category;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;

class CategoryBelongsToCategory implements ValidationRule
{

    protected $category_id;

    /**
     * Create a new rule instance.
     *
     * @param  int  $category_id
     */
    public function __construct($category_id)
    {
        $this->category_id = (int)$category_id;
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $category = category::find($value);

        // Check if the category exists and its parent_id matches category_id
        if (!$category || $category->parent_id !== $this->category_id) {
            $fail("The selected $attribute does not belong to the specified category.");
        }
    }
}
