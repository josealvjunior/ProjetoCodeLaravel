<?php

namespace project\Http\Controllers;

use Faker\Provider\File;
use Illuminate\Contracts\Filesystem\Filesystem;
use Illuminate\Contracts\Validation\ValidationException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use LucaDegasperi\OAuth2Server\Facades\Authorizer;
use Mockery\CountValidator\Exception;
use Prettus\Validator\Contracts\ValidatorInterface;
use Prettus\Validator\Exceptions\ValidatorException;
use project\Entities\ProjectFile;
use project\Http\Requests;
use project\Http\Controllers\Controller;
use project\Repositories\ProjectFileRepository;
use project\Services\ProjectFileService;
use project\Validators\ProjectFileValidator;
use Symfony\Component\HttpFoundation\File\UploadedFile;


class ProjectFileController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    /**
     * @var ProjectFileRepository
     */
    private $repository;

    /**
     * @var ProjectFileService
     */
    private $service;

    public function __Construct(ProjectFileRepository $repository, ProjectFileService $service, ProjectFileValidator $projectFileValidator)
    {
        $this->repository = $repository;
        $this->service = $service;
        $this->validator = $projectFileValidator;
    }

    public function index($id)
    {
        return $this->repository->findWhere(['project_id' => $id]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
        $file= $request->file('file');
        $extension = $file->getClientOriginalExtension();

        $data['file'] = $file;
        $data['name'] = $request->name;
        $data['description'] = $request->description;
        $data['extension'] = $extension;
        $data['projects_id'] = $request->projects_id;
        Try {
            $this->validator->with($data)->passesOrFail(ValidatorInterface::RULE_CREATE);
            $this->service->create($data);
        } catch (ValidatorException $e){
            return response()->json ([
               'error' =>true,
                'message' => $e->getMessageBag()
            ]);
        };
    }

    public function showFile($id){
        if($this->service->checkProjectPermissions($id)== false){
            return ['error' => 'Acces Forbidden'];
        }
        return response()->download($this->service->getFilePath($id));
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        if($this->checkProjectPermissions($id)==false){
            return ['error'=> 'Acesso Negado'];
        }
        return $this->repository->find($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        if($this->checkProjectOwner($id)==false){
            return ['error'=> 'Acesso Negado'];
        }
        return $this->service->update($request->all(),$id);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($projectFileId, $id)
    {
        if($this->checkProjectOwner($id)==false){
            return ['error'=> 'Acesso Negado'];
        }
        if ($file = ProjectFile::find($id)== null){
            return "arquivo não existe";
        }

        $this->service->removeProjecFile($id);

        //return $file->id.".".$file->extension;

        //$filename = Input::get('id');
        //$extension = Input::get('extension');

        //if(!Storage::delete($file->id+.+file->extension)){
         //   Session::flash('fash_message', 'Error');
        //}else {
          //  $file->delete();
        //}



    }

    private function checkProjectOwner($projectId)
    {
        $userId = \Authorizer::getResourceOwnerId();
        return $this->repository->isOwner($projectId, $userId);
    }

    private function checkProjectMember($projectId)
    {
        $userId = \Authorizer::getResourceOwnerId();
        return $this->repository->hasMember($projectId, $userId);
    }

    private function checkProjectPermissions($projectId)
    {
        if($this->checkProjectOwner($projectId) or $this->checkProjectMember($projectId)){
            return true;
        }
        return false;
    }


}
