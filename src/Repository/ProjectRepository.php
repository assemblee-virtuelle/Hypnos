<?php

namespace App\Repository;

use App\Entity\Project;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Project|null find($id, $lockMode = null, $lockVersion = null)
 * @method Project|null findOneBy(array $criteria, array $orderBy = null)
 * @method Project[]    findAll()
 * @method Project[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ProjectRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Project::class);
    }

    public function pastProject()
   {
       $query = $this->createQueryBuilder('p')
       ->where('p.startedAt < :date')
       ->setParameter('date', (new \DateTime()))
       ->getQuery();
      return $query->getResult();
   }

   public function futurProject()
   {
       $query = $this->createQueryBuilder('p')
       ->where('p.startedAt > :d')
       ->setParameter('d', (new \DateTime()))
       ->getQuery();
   return $query->getResult();
   }

   public function localProject($ville)
   {
       $query = $this->createQueryBuilder('p')
       ->where('p.place LIKE :ville')
       ->setParameter('ville', $ville)
       ->getQuery();
   return $query->getResult();
   }

    // /**
    //  * @return Project[] Returns an array of Project objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Project
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
