<?php


namespace Jiejunf\Resourceful\Controller;


use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller;
use Jiejunf\Resourceful\Request\RequestAdapter;
use Jiejunf\Resourceful\Resourceful;
use Jiejunf\Resourceful\Resources\ResourceAdapter;
use Jiejunf\Resourceful\Service\ServiceAdapter;

/**
 * Class BaseController
 * @package Resourceful\Controller
 */
class ResourceController extends Controller
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function index()
    {
        return ResourceAdapter::collection($this->resource()->index());
    }

    /**
     * @return ServiceAdapter
     */
    private function resource()
    {
        return resolve(ServiceAdapter::class);
    }

    /**
     * @return RequestAdapter
     */
    private function request()
    {
        return resolve(RequestAdapter::class);
    }

    public function show()
    {
        return ResourceAdapter::make($this->resource()->show($this->getResourceId()));
    }

    /**
     * @return string|float|null
     */
    protected function getResourceId()
    {
        return $this->request()->route()->parameter(Resourceful::currentResourceName());
    }

    public function store()
    {
        return ResourceAdapter::make($this->resource()->store($this->request()->validated()));
    }

    public function update()
    {
        return $this->resource()->update($this->getResourceId(), $this->request()->validated());
    }

    public function destroy()
    {
        return $this->resource()->destroy($this->getResourceId());
    }
}
