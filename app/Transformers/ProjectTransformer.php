<?php
/**
 * Created by PhpStorm.
 * User: josej_000
 * Date: 22/08/2015
 * Time: 00:46
 */

namespace project\Transformers;
use project\Entities\Projects;
use League\Fractal\TransformerAbstract;
class ProjectTransformer extends TransformerAbstract
{
    protected $defaultIncludes =['members'];
    public function transform(Projects $projects)
    {
        return[
            'id' => $projects->id,
            'name' => $projects->name,
            'description' => $projects->description,
            'progress' => (int) $projects->progress,
            'status' => $projects->status,
            'due_date' => $projects->due_date,
            'client' => $projects->client,
            'client_id' =>$projects->client_id,
        ];
    }

    public function includeMembers(Projects $projects)
    {
        return $this->collection($projects->members, new ProjectMemberTransformer());
    }

}