<?php

namespace GSManager\Http\Resources\Json;

use Countable;
use GSManager\Http\Request;
use GSManager\Http\Resources\CollectsResources;
use GSManager\Pagination\AbstractCursorPaginator;
use GSManager\Pagination\AbstractPaginator;
use IteratorAggregate;

class ResourceCollection extends JsonResource implements Countable, IteratorAggregate
{
    use CollectsResources;

    /**
     * The resource that this resource collects.
     *
     * @var string
     */
    public $collects;

    /**
     * The mapped collection instance.
     *
     * @var \GSManager\Support\Collection
     */
    public $collection;

    /**
     * Indicates if all existing request query parameters should be added to pagination links.
     *
     * @var bool
     */
    protected $preserveAllQueryParameters = false;

    /**
     * The query parameters that should be added to the pagination links.
     *
     * @var array|null
     */
    protected $queryParameters;

    /**
     * Create a new resource instance.
     *
     * @param  mixed  $resource
     */
    public function __construct($resource)
    {
        parent::__construct($resource);

        $this->resource = $this->collectResource($resource);
    }

    /**
     * Indicate that all current query parameters should be appended to pagination links.
     *
     * @return $this
     */
    public function preserveQuery()
    {
        $this->preserveAllQueryParameters = true;

        return $this;
    }

    /**
     * Specify the query string parameters that should be present on pagination links.
     *
     * @param  array  $query
     * @return $this
     */
    public function withQuery(array $query)
    {
        $this->preserveAllQueryParameters = false;

        $this->queryParameters = $query;

        return $this;
    }

    /**
     * Return the count of items in the resource collection.
     *
     * @return int
     */
    public function count(): int
    {
        return $this->collection->count();
    }

    /**
     * Transform the resource into a JSON array.
     *
     * @param  \GSManager\Http\Request  $request
     * @return array|\GSManager\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray(Request $request)
    {
        return $this->collection->map->toArray($request)->all();
    }

    /**
     * Create an HTTP response that represents the object.
     *
     * @param  \GSManager\Http\Request  $request
     * @return \GSManager\Http\JsonResponse
     */
    public function toResponse($request)
    {
        if ($this->resource instanceof AbstractPaginator || $this->resource instanceof AbstractCursorPaginator) {
            return $this->preparePaginatedResponse($request);
        }

        return parent::toResponse($request);
    }

    /**
     * Create a paginate-aware HTTP response.
     *
     * @param  \GSManager\Http\Request  $request
     * @return \GSManager\Http\JsonResponse
     */
    protected function preparePaginatedResponse($request)
    {
        if ($this->preserveAllQueryParameters) {
            $this->resource->appends($request->query());
        } elseif (! is_null($this->queryParameters)) {
            $this->resource->appends($this->queryParameters);
        }

        return (new PaginatedResourceResponse($this))->toResponse($request);
    }
}
