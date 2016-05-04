<?php
/**
 * Created by PhpStorm.
 * User: josej_000
 * Date: 22/08/2015
 * Time: 00:46
 */

namespace project\Transformers;
use project\Entities\ProjectFile;
use League\Fractal\TransformerAbstract;
class ProjectFileTransformer extends TransformerAbstract
{
    public function transform(ProjectFile $projectFile)
    {
        return[
            'id' => $projectFile->id,
            'name' => $projectFile->name,
            'extension' => $projectFile->extension,
            'description' => $projectFile->description,
        ];
    }
}