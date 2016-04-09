<?php
/**
 * Created by PhpStorm.
 * User: josej_000
 * Date: 26/07/2015
 * Time: 19:40
 */

namespace project\Repositories;


use Prettus\Repository\Eloquent\BaseRepository;
use project\Entities\Client;
use project\Repositories\ClientRepository;
use project\Presenters\ClientPresenter;

class ClientRepositoryEloquent extends BaseRepository implements ClientRepository
{
    protected $fieldSearchable= [
      'name',
        'email'
    ];

    public function model()
    {
     return Client::class;
    }

    public function presenter()
    {
        return ClientPresenter::class;
    }

    public function boot()
    {
        $this->pushCriteria(app('Prettus\Repository\Criteria\RequestCriteria'));
    }
}