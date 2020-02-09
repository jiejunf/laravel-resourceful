<?php


namespace Jiejunf\Resourceful\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Jiejunf\Resourceful\BaseAdapter;
use Jiejunf\Resourceful\Resourceful;
use function class_exists;

/**
 * Class ModelAdapter
 * @package Resourceful
 * @mixin Model
 */
class ModelAdapter extends BaseAdapter
{
    public function __construct(string $table = '', array $attributes = [])
    {
        $this->adapter = $this->resolveModel($table, $attributes);
    }

    /**
     * @param string $table
     * @param array $attributes
     * @return Model
     */
    private function resolveModel(string $table, array $attributes): Model
    {
        $rewriteClass = ModelAdapter::getNamespace()
            . Str::studly($table ? Str::singular($table) : Resourceful::currentResourceName());
        if (class_exists($rewriteClass)) {
            return new $rewriteClass($attributes);
        }
        return $this->newBaseModel($attributes)->setTable(Str::plural(Resourceful::currentResourceName()));
    }

    protected static function getNamespace(): string
    {
        return Str::finish(config('resourceful.model_namespace'), '\\');
    }

    /**
     * @param array $attributes
     * @return Model
     */
    private function newBaseModel(array $attributes): Model
    {
        $model = config('resourceful.base_model');
        return new $model($attributes);
    }
}
