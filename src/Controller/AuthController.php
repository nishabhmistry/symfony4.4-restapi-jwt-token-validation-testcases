<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Repository\UserRepository;
use App\Entity\User;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Controller\APIController;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\Validator\ConstraintViolationListInterface;

class AuthController extends APIController
{
    function __construct(UserRepository $userRepository){
        $this->userRepository = $userRepository;
    }

    public function create(ValidatorInterface $validator, Request $request, UserPasswordEncoderInterface $encoder)
    {

        $data = json_decode($request->getContent());
        $messages = $this->validate($validator, $data);
        if (count($messages) > 0) {
            return $this->throwValidation($messages);
        }

        $this->userRepository->create($data,$encoder);
        return $this->respond("User created successfully");
        //$user->setPassword($encoder->encodePassword($user, $password));
        
    }

    public function list(): JsonResponse
    {
        $data = $this->userRepository->list();
        return new JsonResponse($data);
    }

    public function validate($validator,$request){

        $messages = [];
        $user = new User();
        $user->username = $request->username;
        $user->password = $request->password;
        $user->firstname = $request->firstname;
        $user->lastname = $request->lastname;
        $user->email = $request->email;
        
        $errors = $validator->validate($user);
        if (count($errors) > 0) {
            foreach ($errors as $violation) {
                 $messages[$violation->getPropertyPath()][] = $violation->getMessage();
            }
        }
        return $messages;
    }
}
