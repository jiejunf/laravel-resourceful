<?php


namespace Jiejunf\Resourceful\Controller;


use App\Http\Controllers\Controller;
use Jiejunf\Resourceful\Request\RequestAdapter;
use Jiejunf\Resourceful\Resourceful;
use Jiejunf\Resourceful\Resources\ResourceAdapter;
use Jiejunf\Resourceful\Service\ServiceAdapter;

/**
 * Class BaseController
 * @package Resourceful\Controller
 */
class BaseController extends Controller
{
    /**
     * @var RequestAdapter
     */
    protected $request;

    /**
     * @var ServiceAdapter
     */
    protected $service;

    public function __construct(RequestAdapter $request, ServiceAdapter $service)
    {
        $this->request = $request;
        $this->service = $service;
    }

    public function index()
    {
        return ResourceAdapter::collection($this->service->index());
    }

    public function show()
    {
        return ResourceAdapter::make($this->service->show($this->getResourceId()));
    }

    /**
     * @return string|float|null
     */
    protected function getResourceId()
    {
        return $this->request->route()->parameter(Resourceful::currentResourceName());
    }

    public function store()
    {
        return ResourceAdapter::make($this->service->store($this->request->validated()));
    }

    public function update()
    {
        return $this->service->update($this->getResourceId(), $this->request->validated());
    }

    public function destroy()
    {
        return $this->service->destroy($this->getResourceId());
    }
}
