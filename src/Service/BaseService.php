<?php


namespace Jiejunf\Resourceful\Service;


use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Jiejunf\Resourceful\Model\ModelAdapter;

class BaseService implements ResourceServiceInterface
{

    /**
     * @var Model
     */
    protected $modelClass;

    public function __construct(ModelAdapter $modelClass)
    {
        $this->modelClass = $modelClass;
    }

    public function index()
    {
        return $this->modelClass->newQuery()
            ->when(...$this->ifGroup())
            ->when(...$this->ifSearch())
            ->when(...$this->ifSort())
            ->when(...$this->ifPaginate());
    }

    public function model($id)
    {
        return $this->modelClass->newQuery()->findOrFail($id);
    }

    public function show($id)
    {
        return $this->model($id);
    }

    public function store($inputs)
    {
        return $this->modelClass->newQuery()->create($inputs);
    }

    public function update($id, $inputs)
    {
        return $this->modelClass->newQuery()->where('id', $id)->update($inputs);
    }

    public function destroy($id)
    {
        return $this->modelClass->newQuery()->where('id', $id)->delete();
    }

    protected function ifPaginate(): array
    {
        return [
            # if:
            !is_null(request('page')),
            # :
            function (Builder $builder) {
                return $builder->paginate(intval(request('per_page')));
            },
            # else:
            function (Builder $builder) {
                return $builder->get();
            }
        ];
    }

    protected function ifGroup(): array
    {
        // todo : BaseService::ifGroup
        return [
            # if:
            false,
            # :
            null
            # else:
        ];
    }

    protected function ifSearch(): array
    {
        // todo : BaseService::ifSearch()
        return [
            # if:
            false,
            # :
            null
            # else:
        ];
    }

    protected function ifSort(): array
    {
        // todo : BaseService::ifSort
        return [
            # if:
            false,
            # :
            null
            # else:
        ];
    }
}
