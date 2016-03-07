<?php
/**
 * Created by PhpStorm.
 * User: josej_000
 * Date: 22/08/2015
 * Time: 00:46
 */

namespace project\Transformers;
use project\Entities\ProjectNotes;
use League\Fractal\TransformerAbstract;
class ProjectNotesTransformer extends TransformerAbstract
{
    public function transform(ProjectNotes $projectNotes)
    {
        return[
            'id' => $projectNotes->id,
            'project_id' => $projectNotes->project_id,
            'title' => $projectNotes->title,
            'notes' => $projectNotes->notes,
        ];
    }
}