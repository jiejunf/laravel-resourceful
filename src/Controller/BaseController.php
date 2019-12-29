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

    public function index()
    {
        return ResourceAdapter::collection($this->getService()->index());
    }

    public function show()
    {
        return ResourceAdapter::make($this->getService()->show($this->getResourceId()));
    }

    /**
     * @return string|float|null
     */
    protected function getResourceId()
    {
        return $this->getRequest()->route()->parameter(Resourceful::currentResourceName());
    }

    public function store()
    {
        return ResourceAdapter::make($this->getService()->store($this->getRequest()->validated()));
    }

    public function update()
    {
        return $this->getService()->update($this->getResourceId(), $this->getRequest()->validated());
    }

    public function destroy()
    {
        return $this->getService()->destroy($this->getResourceId());
    }

    private function getService()
    {
        return $this->service ?? ($this->service = new ServiceAdapter($this->getRequest()));
    }

    private function getRequest()
    {
        return $this->request ?? ($this->request = new RequestAdapter());
    }
}
