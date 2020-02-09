<?php


namespace Jiejunf\Resourceful\Service;


use Illuminate\Support\Str;
use Jiejunf\Resourceful\BaseAdapter;
use Jiejunf\Resourceful\Model\ModelAdapter;
use Jiejunf\Resourceful\Request\RequestAdapter;
use Jiejunf\Resourceful\Resourceful;

/**
 * Class ServiceAdapter
 * @package Resourceful\Service
 * @mixin BaseService
 */
class ServiceAdapter extends BaseAdapter
{
    /**
     * @var RequestAdapter
     */
    private $request;

    public function __construct(RequestAdapter $request)
    {
        $this->request = $request;
        $this->adapter = new BaseService($this->getModelAdapter());
    }

    private function getModelAdapter(): ModelAdapter
    {
        $parameters = $this->request->route()->parameters();
        $modelAdapter = null;
        foreach ($parameters as $model => $id) {
            $modelAdapter = $modelAdapter ? $modelAdapter->{Str::plural($model)}() : new ModelAdapter($model);
            $modelAdapter = $modelAdapter->newQuery()->findOrFail($id);
        }
        if (isset($model) && $model !== Resourceful::currentResourceName()) {
            $modelAdapter = $modelAdapter->{Str::camel(Str::plural(Resourceful::currentResourceName()))}();
        }
        return ModelAdapter::convert($modelAdapter);
    }
}
