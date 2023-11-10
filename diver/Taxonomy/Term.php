<?php

namespace Diver\Taxonomy;

use Diver\Database\Eloquent\Model;
use Diver\Database\Eloquent\SoftDeleteModel;
use Illuminate\Database\Eloquent\Builder;

class Term extends Model
{
    /**
     * Status enum
     */
    const STATUS_ENABLED = 'enabled';
    const STATUS_DISABLED = 'disabled';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'taxonomy_terms';

    /**
     * Vocabulary
     *
     * @var \Diver\Taxonomy\Vocabulary
     */
    protected static $vocabulary;

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'data' => 'json',
    ];

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('taxonomicable', function (Builder $query) {
            $query->where('vocabulary_id', static::getVocabulary()->id);
        });

        static::saving(function (Term $term) {
            $term->vocabulary_id = static::getVocabulary()->id;
        });
    }

    /**
     * Get vocabulary
     */
    protected static function getVocabularyName()
    {
        throw new \RuntimeException(__METHOD__ . 'method is not implemented.');
    }

    /**
     * Get vocabulary
     *
     * @return \Diver\Taxonomy\Vocabulary
     * @throws \ReflectionException
     */
    protected static function getVocabulary()
    {
        if (!static::$vocabulary) {
            static::$vocabulary = Vocabulary::firstOrCreate(['name' => static::getVocabularyName()]);
        }

        return static::$vocabulary;
    }

    /**
     * Enabled scope
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeEnabled($query)
    {
        return $query->where('status', self::STATUS_ENABLED);
    }

    /**
     * Disabled scope
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeDisabled($query)
    {
        return $query->where('status', self::STATUS_DISABLED);
    }

    /**
     * Of vocabulary scope
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param mixed $lookup
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOfVocabulary($query, $lookup)
    {
        $vocabulary = Vocabulary::where('id', $lookup)->orWhere('name', $lookup)->firstOrFail();

        return $query->where('vocabulary_id', $vocabulary->id);
    }

    /**
     * @param \Illuminate\Database\Eloquent\Builder $query
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('weight')->orderBy('name');
    }

    /**
     * Get vocabulary attribute
     *
     * @return \Diver\Taxonomy\Vocabulary
     */
    public function getVocabularyAttribute()
    {
        return static::$vocabulary;
    }

    /**
     * Check if term is enabled
     *
     * @return bool
     */
    public function isEnabled()
    {
        return $this->status === static::STATUS_ENABLED;
    }

    /**
     * Cheif if term is disabled
     *
     * @return bool
     */
    public function isDisabled()
    {
        return $this->status === static::STATUS_DISABLED;
    }
}
