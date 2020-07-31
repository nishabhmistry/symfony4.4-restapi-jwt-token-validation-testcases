<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Doctrine\ORM\EntityRepository;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function create($requestData, $encoder)
    {
        $entityManager = $this->getEntityManager();
        
        $user = new User();
        
        $user->setFirstname($requestData->firstname);
        $user->setLastname($requestData->lastname);
        $user->setEmail($requestData->email);
        $user->setUsername($requestData->username);
        $user->setPassword($encoder->encodePassword($user, $requestData->password));
        $user->setActive(1);
        
        $entityManager->persist($user);
        return $entityManager->flush();
    }

    public function list()
    {
        $data = $this->createQueryBuilder('user');
        $data = $data->getQuery();
        $data = $data->execute();
        return $data;
    }
}
