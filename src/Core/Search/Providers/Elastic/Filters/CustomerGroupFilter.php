<?php

namespace GetCandy\Api\Core\Search\Providers\Elastic\Filters;

use Elastica\Query\Term;
use Elastica\Query\Range;
use Elastica\Query\BoolQuery;
use Elastica\Query\Nested;

class CustomerGroupFilter extends AbstractFilter
{
    /**
     * The user
     *
     * @var mixed
     */
    protected $user;

    public function process($payload, $type = null)
    {
        $this->user = $payload;
    }

    public function getQuery()
    {
        $filter = new BoolQuery;

        foreach ($this->getCustomerGroups() as $model) {
            $cat = new Nested;
            $cat->setPath('customer_groups');
            $term = new Term;
            $term->setTerm('customer_groups.id', $model->encodedId());

            $cat->setQuery($term);

            $filter->addShould($cat);
        }

        return $filter;
    }


    private function getCustomerGroups()
    {
        if ($this->user) {
            // Set to empty array as we don't want to filter any out.
            if ($user->hasRole('admin')) {
                $groups = [];
            } else {
                $groups = $user->groups;
            }
        } else {
            $groups = [app('api')->customerGroups()->getGuest()];
        }

        return $groups;
    }
}