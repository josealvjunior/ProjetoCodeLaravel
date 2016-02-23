<?php
/**
 * Created by PhpStorm.
 * User: josej_000
 * Date: 22/08/2015
 * Time: 00:46
 */

namespace project\Transformers;
use project\Entities\Client;
use League\Fractal\TransformerAbstract;
class ClientTransformer extends TransformerAbstract
{
    //protected $defaultIncludes =['members'];
    public function transform(Client $client)
    {
        return[
            'id' => (int)$client->id,
            'name' => $client->name,
            'responsible' => $client->responsabile,
            'email' => $client->email,
            'phone' => $client->phone,
            'address' => $client->address,
            'obs' => $client->obs,
        ];
    }
}