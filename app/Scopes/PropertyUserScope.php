<?php
namespace App\Scopes;

use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\DB;

class PropertyUserScope implements Scope {
    private $relatedTable;
    private $relatedCode; 

    public function __construct($relatedTable, $relatedCode) {
        $this->relatedTable = $relatedTable;
        $this->relatedCode  = $relatedCode;
    }

    public function apply(Builder $builder, Model $model) {
        $scope = $this;
        $builder->join('users_has_properties', function($join) use ($scope) {
            $join
                ->on("{$scope->relatedTable}.id", 'users_has_properties.id', '=')
                ->where('users_has_properties.type', '=', $scope->relatedCode);
        });
    }
}