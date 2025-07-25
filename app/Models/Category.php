<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{

    use HasFactory;

    protected $fillable = ['name', 'parent_id'];

    public function parent(): BelongsTo
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(self::class, 'parent_id');
    }

    // Recursive fn
    public function nestedChildren()
    {
        return $this->children()->with('nestedChildren');
    }

    /**
     * Get categories with nested children up to the given depth.
     */
    public static function getNestedCategoriesByDepth(int $depth, int $parent_id = null)
    {

        $query = $parent_id ? self::where(['parent_id' => $parent_id]) : self::whereNull('parent_id');

        if ($depth === 0) return $query; // // Return only the top-level categories (no children)


        // Build the relationship string dynamically up to the given depth
        $relations = collect(range(0, ($depth - 1)))
            ->map(fn() => 'children')
            ->filter()
            ->implode('.');

        // Eager load the relations
        return $query->with($relations, fn($q) => $q->paginate());
    }
}
