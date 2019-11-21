<?php


namespace Jiejunf\Resourceful\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Jiejunf\Resourceful\BaseAdapter;
use Jiejunf\Resourceful\Resourceful;

/**
 * Class ModelAdapter
 * @package Resourceful
 * @mixin Model
 */
class ModelAdapter extends BaseAdapter
{
    public function __construct(string $table = '', array $attributes = [])
    {
        $modelClass = self::getModelClass($table);
        $this->adapter = $this->resolveModel($modelClass, $attributes);
    }

    /**
     * if ParamModel class exists, return ParamModel::class
     *      else return null-string
     *
     * @param string $table
     * @return string
     */
    protected static function getModelClass(string $table = ''): string
    {
        // todo : config
        $modelClass = '\\App\\' . Str::studly($table ?: Resourceful::currentResourceName());
        if (class_exists($modelClass)) {
            return $modelClass;
        }
        return '';
    }

    private function resolveModel(string $modelClass, array $attributes): Model
    {
        if ($modelClass) {
            return new $modelClass($attributes);
        }
        // todo : config
        return (new BaseResourceModel($attributes))->setTable(Str::plural(Resourceful::currentResourceName()));
    }
}
