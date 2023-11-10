<?php

namespace Diver\Database\Eloquent\Traits;

use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Query\JoinClause;

trait LinkRelationship
{
    /**
     * Relation links
     *
     * @var array
     */
    protected $links = [];

    /**
     * Link relation
     *
     * @param $relations
     *
     * @return mixed
     */
    public function link($relations)
    {
        $this->select("{$this->getModel()->getTable()}.*");

        if (is_string($relations)) {
            $relations = collect(explode('.', $relations));
        }

        $linked = $relations->reduce(function ($parent, $relation) {
            if (isset($this->links[$relation])) {
                return $this->links[$relation];
            }

            $related['name']       = $relation;
            $related['relation']   = $parent['model']->{$related['name']}();
            $related['model']      = $related['relation']->getRelated();
            $related['modelAlias'] = $parent['modelAlias'].'_'.snake_case($relation);

            if ( ! $this->isRelationLinkable($related['relation'])) {
                $relatedRelationClass = get_class($related['relation']);
                throw new \ErrorException("Linking {$relatedRelationClass}({$related['name']}) is not allowed.");
            }

            $this->leftJoin($this->getQualifiedLinkedTableAlias($related), function (JoinClause $join) use ($related, $parent) {
                // call `Builder::link{RelationType}()
                $linkRelationTypeMethod = 'link' . $this->resolveRelationType($related['relation']);
                $this->$linkRelationTypeMethod($join, $related, $parent);

                // call `Builder::link{Relation}()` if exist
                $linkRelationTypeMethod = 'link' . ucfirst($related['name']);
                if (method_exists($this, $linkRelationTypeMethod)) {
                    $this->$linkRelationTypeMethod($join, $related, $parent);
                }
            });

            $this->links[$related['name']] = $related;

            return $related;
        }, [
            'model'      => $this->getModel(),
            'modelAlias' => $this->getModel()->getTable(),
        ]);

        return $linked['modelAlias'];
    }

    /**
     *
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $target
     *
     * @return string
     */
    public function linkedColumn($target)
    {
        $relations = collect(explode('.', $target));
        $column    = $relations->pop();

        return "{$this->link($relations)}.{$column}";
    }

    /**
     * Link belongs to relation
     *
     * @param \Illuminate\Database\Query\JoinClause $join
     * @param array $related
     * @param array $parent
     */
    protected function linkBelongsTo(JoinClause $join, array $related, array $parent)
    {
        $join->on("{$related['modelAlias']}.{$related['relation']->getOwnerKey()}", "{$parent['modelAlias']}.{$related['relation']->getForeignKey()}");
    }

    /**
     * Link morph to relation
     *
     * @param \Illuminate\Database\Query\JoinClause $join
     * @param array $related
     * @param array $parent
     */
    protected function linkMorphTo(JoinClause $join, array $related, array $parent)
    {
        //@todo
        throw new \RuntimeException('todo: ' . __METHOD__);
    }

    /**
     * Link has one relation
     *
     * @param \Illuminate\Database\Query\JoinClause $join
     * @param array $related
     * @param array $parent
     */
    protected function linkHasOne(JoinClause $join, array $related, array $parent)
    {
        //@todo
        $join->on("{$related['modelAlias']}.{$related['relation']->getForeignKeyName()}", "{$parent['modelAlias']}.{$parent['model']->getKeyName()}");
    }

    /**
     * Link morph one relation
     *
     * @param \Illuminate\Database\Query\JoinClause $join
     * @param array $related
     * @param array $parent
     */
    protected function linkMorphOne(JoinClause $join, array $related, array $parent)
    {
        $join->on("{$related['modelAlias']}.{$related['relation']->getForeignKeyName()}", "{$parent['modelAlias']}.{$parent['model']->getKeyName()}")
             ->where("{$related['modelAlias']}.{$related['relation']->getMorphType()}", $related['relation']->getMorphClass());
    }

    /**
     * Get qualified linked table alias
     *
     * @param $related
     *
     * @return string
     */
    protected function getQualifiedLinkedTableAlias(array $related)
    {
        return "{$related['model']->getTable()} AS {$related['modelAlias']}";
    }

    /**
     * Check if relation can be linked
     *
     * @param \Illuminate\Database\Eloquent\Relations\Relation $relation
     *
     * @return bool
     */
    protected function isRelationLinkable(Relation $relation)
    {
        $relationClass = get_class($relation);

        return in_array($relationClass, [
            BelongsTo::class,
            MorphTo::class,
            HasOne::class,
            MorphOne::class,
        ]);
    }

    /**
     * Resolve relation type
     *
     * @param \Illuminate\Database\Eloquent\Relations\Relation $relation
     *
     * @return string
     * @throws \ReflectionException
     */
    protected function resolveRelationType(Relation $relation)
    {
        return (new \ReflectionClass($relation))->getShortName();
    }
}
