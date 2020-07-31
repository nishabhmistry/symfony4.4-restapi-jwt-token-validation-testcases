<?php

namespace App\Repository;

use App\Entity\FootballLeague;
use App\Entity\FootballTeam;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityRepository;

/**
 * @method FootballLeague|null find($id, $lockMode = null, $lockVersion = null)
 * @method FootballLeague|null findOneBy(array $criteria, array $orderBy = null)
 * @method FootballLeague[]    findAll()
 * @method FootballLeague[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FootballLeagueRepository extends ServiceEntityRepository
{
    
    public function __construct(
        ManagerRegistry $registry
    )
    {
        parent::__construct($registry, FootballLeague::class);
    }

    public function create($requestData)
    {
        $entityManager = $this->getEntityManager();
        
        $time = new \DateTime();
        $date = $time->format('Y-m-d H:i:s');

        $sql = "INSERT INTO football_league (name, created_at) VALUES ('".$requestData->name."', '".$date."')";
        $stmt = $entityManager->getConnection()->prepare($sql);
        return $result = $stmt->execute();
    }

    public function update($requestData, $id)
    {
        $entityManager = $this->getEntityManager();
        $league = $entityManager->getRepository(FootballLeague::class)->find($id);

        $league->setName($requestData->name);
        return $entityManager->flush();
    }


    public function delete($id)
    {
    	$entityManager = $this->getEntityManager();

    	/** Check team assigned to any league or not **/
    	$result = $entityManager->getRepository(FootballTeam::class)->findBy(["leagueid"=>$id]);
    	if(count($result) > 0){
    		return "assigned";	
    	}            	
        
        $league = $entityManager->getRepository(FootballLeague::class)->find($id);

        $entityManager->remove($league);
        return $entityManager->flush();
    }


    public function list($id)
    {
        $data = $this->createQueryBuilder('league');
        if(!empty($id)){
            $data = $data->andWhere('league.id = :searchTerm');
            $data = $data->setParameter('searchTerm', $id);
        }
        $data = $data->getQuery();
        $data = $data->execute();
        return $data;
    }

    public function checkExistName($requestData)
    {
        $data = $this->createQueryBuilder('league');
        if(!is_null($requestData->id)){
            $data = $data->andWhere('league.id != :searchTerm2');
            $data = $data->setParameter('searchTerm2', $requestData->id);
        }
        $data = $data->andWhere('league.name = :searchTerm');
        $data = $data->setParameter('searchTerm', $requestData->name);
        $data = $data->getQuery();
        $data = $data->execute();
        return $data;
    }
}
