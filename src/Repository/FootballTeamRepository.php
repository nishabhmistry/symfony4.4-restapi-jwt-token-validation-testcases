<?php

namespace App\Repository;

use App\Entity\FootballTeam;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityRepository;

/**
 * @method FootballTeam|null find($id, $lockMode = null, $lockVersion = null)
 * @method FootballTeam|null findOneBy(array $criteria, array $orderBy = null)
 * @method FootballTeam[]    findAll()
 * @method FootballTeam[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FootballTeamRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, FootballTeam::class);
    }

    public function create($requestData, $id)
    {
        $entityManager = $this->getEntityManager();
        
        $team = new FootballTeam();
        $team->setLeagueid($id);
        $team->setName($requestData->name);
        $team->setStrip($requestData->strip);
        
        $entityManager->persist($team);
        return $entityManager->flush();
    }

    public function update($requestData, $leagueid, $id)
    {
        $entityManager = $this->getEntityManager();
        $team = $entityManager->getRepository(FootballTeam::class)->find($id);

        $team->setLeagueid($leagueid);
        $team->setName($requestData->name);
        $team->setStrip($requestData->strip);
        return $entityManager->flush();
    }


    public function delete($id)
    {
        $entityManager = $this->getEntityManager();
        $team = $entityManager->getRepository(FootballTeam::class)->find($id);

        $entityManager->remove($team);
        return $entityManager->flush();
    }


    public function list($leaguesid,$id)
    {
        $data = $this->createQueryBuilder('team');
        if(!empty($id)){
            $data = $data->andWhere('team.id = :searchTerm2');
            $data = $data->setParameter('searchTerm2', $id);
        }
        $data = $data->andWhere('team.leagueid = :searchTerm');
        $data = $data->setParameter('searchTerm', $leaguesid);
        $data = $data->getQuery();
        $data = $data->getArrayResult();
        return $data;
    }

    public function checkExistName($requestData)
    {
        $data = $this->createQueryBuilder('team');
        if(!is_null($requestData->id)){
            $data = $data->andWhere('team.id != :searchTerm2');
            $data = $data->setParameter('searchTerm2', $requestData->id);
        }
        $data = $data->andWhere('team.name = :searchTerm');
        $data = $data->setParameter('searchTerm', $requestData->name);
        $data = $data->getQuery();
        $data = $data->execute();
        return $data;
    }
}
