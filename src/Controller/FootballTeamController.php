<?php

namespace App\Controller;

use App\Entity\FootballTeam;
use App\Repository\FootballTeamRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class FootballTeamController extends APIController
{
    
    function __construct(FootballTeamRepository $footballTeamRepository){
    	$this->footballTeamRepository = $footballTeamRepository;
    }

    public function create(ValidatorInterface $validator, Request $request, $leaguesid)
    {
        $data = json_decode($request->getContent());
        $messages = $this->validate($validator, $data);
        if (count($messages) > 0) {
            return $this->throwValidation($messages);
        }

    	$create = $this->footballTeamRepository->create($data, $leaguesid);
        return $this->respond("Team created successfully");
    }

    public function update(ValidatorInterface $validator, Request $request, $leaguesid, $id)
    {
        
        $data = json_decode($request->getContent());
        $messages = $this->validate($validator,$data,$leaguesid,$id);
        if (count($messages) > 0) {
            return $this->throwValidation($messages);
        }
        
        $this->footballTeamRepository->update($data, $leaguesid, $id);
        return $this->respond("Team updated successfully");
    }

    public function delete($id)
    {
        $this->footballTeamRepository->delete($id);
        return $this->respond("Team deleted successfully");
    }

    public function list($leaguesid,$id=""): JsonResponse
    {
        $data = $this->footballTeamRepository->list($leaguesid,$id);
        return new JsonResponse($data);
    }

    public function validate($validator, $request, $leaguesid = "",$id = ""){

        $messages = [];
        $league = new FootballTeam();

        if(!empty($id)){
            $league->id = $id;
        }
        $league->leagueid = $leaguesid;
        $league->name = $request->name;
        $league->strip = $request->strip;
        
        $errors = $validator->validate($league);
        if (count($errors) > 0) {
            foreach ($errors as $violation) {
                 $messages[$violation->getPropertyPath()][] = $violation->getMessage();
            }
        }
        return $messages;
    }
}
