<?php
/**
 * Created by PhpStorm.
 * User: josej_000
 * Date: 27/07/2015
 * Time: 21:47
 */

namespace project\Services;


use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Facades\Storage;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use project\Entities\ProjectFile;
use project\Repositories\ProjectFileRepository;
use Illuminate\Http\Exception;
use project\Repositories\ProjectsRepository;
use project\Validators\ProjectFileValidator;

class ProjectFileService
{
    /**
     * @var ProjectFileRepository
     */
    protected $repository;
    /**
     * @var ProjectFileValidator
     */
    protected $validator;

    public function __construct(ProjectFileRepository $repository,
                                ProjectsRepository $projectsRepository,
                                ProjectFileValidator $validator,
                                Filesystem $filesystem,
                                Storage $storage)
    {
        $this->repository = $repository;
        $this->projectsRepository = $projectsRepository;
        $this->validator = $validator;
        $this->filesytem = $filesystem;
        $this->storage = $storage;
    }

    public function all()
    {
        return response()->json($this->repository->with(['owner', 'client'])->all());
    }

    public function read($id)
    {
        try{
            return response()->json($this->repository->with(['owner', 'client'])->find($id));
        }catch (ModelNotFoundException $e){
            return $this->notFound($id);
        }
    }

    public function create(array $data)
    {
        try {
            $this->validator->with($data)->passesOrFail(ValidatorInterface::RULE_CREATE);
            return $this->repository->create($data);
        } catch(ValidatorException $e) {
            return [
            'error' => true,
            'message' => $e->getMessageBag()
            ];
        };

    }

    public function update(array $data, $id)
    {
        try{
            $this->validator->update($data, $id);
        }catch(ValidatorException $e){
            return [
                'erro' => true,
                'message' => $e->getMessageBag()
            ];
        }
    }

    public function delete($id)
    {
        $projectFile = $this->repository->skipPresenter()->find($id);
        if ($this->storage->exists($projectFile->id.'.'.$projectFile->extension)){
            $this->storage->delete($projectFile->id.'.'.$projectFile->extension);
            $projectFile->delete();
        }
    }

    public function getFilePath($id){
        $projectFile = $this->repository->skipPresenter()->find($id);
        return $this->getBaseURL($projectFile);
    }

    private function getBaseURL($projectFile){
        switch ($this->storage->getDefaultDriver()){
            case 'local';
                return $this->storage->getDriver()->getAdapter()->getPathPrefix()
                    .'/'. $projectFile->id. '.' . $projectFile->extension;
        }
    }


}