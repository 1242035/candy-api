<?php

namespace GetCandy\Api\Core\Search\Providers\Elastic\Query;

use Elastica\Query\DisMax;
use Elastica\Query\MultiMatch;

class Term
{
    /**
     * The query value.
     *
     * @var string
     */
    protected $text;

    /**
     * The query fields.
     *
     * @var array
     */
    protected $fields;

    public function __construct($text, $fields)
    {
        $this->text = $text;
        $this->fields = $fields;
    }

    /**
     * Get the text value.
     *
     * @return string
     */
    public function getText()
    {
        return $this->text;
    }

    public function getQuery()
    {
        $disMaxQuery = new DisMax;
        $disMaxQuery->setBoost(1.5);
        $disMaxQuery->setTieBreaker(1);

        $multiMatchQuery = new MultiMatch;
        $multiMatchQuery->setType('phrase');
        $multiMatchQuery->setQuery($this->text);
        $multiMatchQuery->setFields($this->fields);

        $disMaxQuery->addQuery($multiMatchQuery);

        $multiMatchQuery = new MultiMatch;
        $multiMatchQuery->setType('best_fields');
        $multiMatchQuery->setQuery($this->text);

        $multiMatchQuery->setFields($this->fields);

        $disMaxQuery->addQuery($multiMatchQuery);

        return $disMaxQuery;
    }
}
