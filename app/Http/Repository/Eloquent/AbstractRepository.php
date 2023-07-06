<?php 

namespace App\Http\Repository\Eloquent;

use Illuminate\Database\Eloquent\Model;

abstract class AbstractRepository
{

    protected $model;

    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    public function GetAll()
    {
        return $this->model->latest()->paginate(PAGINATION_COUNT);
    }

}
