<?php

declare(strict_types=1);

namespace Cortex\Bookings\DataTables\Tenantarea;

use Cortex\Bookings\Contracts\ResourceContract;
use Cortex\Foundation\DataTables\AbstractDataTable;
use Cortex\Bookings\Transformers\Tenantarea\ResourceTransformer;

class ResourcesDataTable extends AbstractDataTable
{
    /**
     * {@inheritdoc}
     */
    protected $model = ResourceContract::class;

    /**
     * {@inheritdoc}
     */
    protected $transformer = ResourceTransformer::class;

    /**
     * Get the query object to be processed by dataTables.
     *
     * @return \Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder|\Illuminate\Support\Collection
     */
    public function query()
    {
        $query = app($this->model)->query()->orderBy('sort_order', 'ASC');

        return $this->applyScopes($query);
    }

    /**
     * Display ajax response.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function ajax()
    {
        $transformer = app($this->transformer);

        return datatables()->eloquent($this->query())
                           ->setTransformer($transformer)
                           ->orderColumn('name', 'name->"$.'.app()->getLocale().'" $1')
                           ->make(true);
    }

    /**
     * Get columns.
     *
     * @return array
     */
    protected function getColumns()
    {
        $link = config('cortex.foundation.route.locale_prefix')
            ? '"<a href=\""+routes.route(\'tenantarea.resources.edit\', {resource: full.slug, locale: \''.$this->request->segment(1).'\'})+"\">"+data+"</a>"'
            : '"<a href=\""+routes.route(\'tenantarea.resources.edit\', {resource: full.slug})+"\">"+data+"</a>"';

        return [
            'name' => ['title' => trans('cortex/bookings::common.name'), 'render' => $link.'+(full.is_active ? " <i class=\"text-success fa fa-check\"></i>" : " <i class=\"text-danger fa fa-close\"></i>")', 'responsivePriority' => 0],
            'price' => ['title' => trans('cortex/bookings::common.price')],
            'unit' => ['title' => trans('cortex/bookings::common.unit')],
            'currency' => ['title' => trans('cortex/bookings::common.currency')],
            'created_at' => ['title' => trans('cortex/bookings::common.created_at'), 'render' => "moment(data).format('MMM Do, YYYY')"],
            'updated_at' => ['title' => trans('cortex/bookings::common.updated_at'), 'render' => "moment(data).format('MMM Do, YYYY')"],
        ];
    }
}
