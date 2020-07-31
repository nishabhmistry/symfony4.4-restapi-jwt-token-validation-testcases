<?php

namespace App\Controller;

use App\Entity\FootballLeague;
use App\Repository\FootballLeagueRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;
use App\Controller\APIController;

class FootballLeagueController extends APIController
{
    
    function __construct(FootballLeagueRepository $footballLeagueRepository){
    	$this->footballLeagueRepository = $footballLeagueRepository;
    }

    public function create(ValidatorInterface $validator, Request $request)
    {
        $data = json_decode($request->getContent());
        $messages = $this->validate($validator, $data);
        if (count($messages) > 0) {
            return $this->throwValidation($messages);
        }

    	$this->footballLeagueRepository->create($data);
        return $this->respond("League created successfully");
    }

    public function update(ValidatorInterface $validator, Request $request, $id)
    {
        $data = json_decode($request->getContent());
        $messages = $this->validate($validator, $data, $id);
        if (count($messages) > 0) {
            return $this->throwValidation($messages);
        }
        
        $this->footballLeagueRepository->update($data, $id);
        return $this->respond("League updated successfully");
    }

    public function delete($id)
    {
        $result = $this->footballLeagueRepository->delete($id);

        if($result == "assigned"){
            return $this->throwValidation("League cannot be delete, Team already assigned in the League.");
        }
        return $this->respond("League deleted successfully");
    }

    public function list($id = ""): JsonResponse
    {
        $data = $this->footballLeagueRepository->list($id);
        return new JsonResponse($data);
    }

    public function validate($validator,$request,$id = ""){

        $messages = [];
        $league = new FootballLeague();
        if(!empty($id)){
            $league->id = $id;
        }
        $league->name = $request->name;
        
        $errors = $validator->validate($league);
        if (count($errors) > 0) {
            foreach ($errors as $violation) {
                 $messages[$violation->getPropertyPath()][] = $violation->getMessage();
            }
        }
        return $messages;
    }
}
