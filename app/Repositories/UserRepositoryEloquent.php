<?php
/**
 * Created by PhpStorm.
 * User: josej_000
 * Date: 06/03/2016
 * Time: 11:05
 */

namespace Projeto\Repositories;


use Prettus\Repository\Eloquent\BaseRepository;
use project\Entities\User;

class UserRepositoryEloquent extends BaseRepository implements UserRepository
{
    public function model(){
        return User::class;
    }
}