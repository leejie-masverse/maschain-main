<?php

namespace Diver\Taxonomy;

use Diver\Database\Eloquent\Model;

class Vocabulary extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'taxonomy_vocabularies';

    /**
     * Has many terms
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function terms()
    {
        return $this->hasMany(Term::class, 'vocabulary_id');
    }
}