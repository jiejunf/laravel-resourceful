<?php


namespace Jiejunf\Resourceful;


use Illuminate\Support\Str;
use Jiejunf\Resourceful\Request\ResourceRequest;

class ResourceHelper
{
    /**
     * @var array
     */
    private $_current;

    /**
     * @var ResourceRequest
     */
    private $request;

    public function __construct(ResourceRequest $request)
    {
        $this->request = $request;
    }

    /**
     * @param string $key
     * @param mixed $default
     * @param string $resource
     * @return mixed
     */
    public function serviceConfig($key, $default = null, $resource = null)
    {
        return config(sprintf('resourceful.services.%s.%s', $resource ?? $this->currentResourceName(), $key), $default);
    }

    public function currentResourceName(): string
    {
        if (isset($this->_current['name'])) {
            return $this->_current['name'];
        }
        return $this->_current['name'] = Str::singular(Str::before($this->request->route()->getName(), '.'));
    }

    public function getActionMethod(): string
    {
        if (isset($this->_current['action'])) {
            return $this->_current['action'];
        }
        return $this->_current['action'] = Str::after($this->request->route()->getName(), '.');
    }
}
