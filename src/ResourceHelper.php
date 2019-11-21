<?php


namespace Jiejunf\Resourceful;


use Illuminate\Support\Str;
use Jiejunf\Resourceful\Request\BaseRequest;

class ResourceHelper
{
    /**
     * @var array
     */
    private $_current;

    /**
     * @var BaseRequest
     */
    private $request;

    public function __construct(BaseRequest $request)
    {
        $this->request = $request;
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
