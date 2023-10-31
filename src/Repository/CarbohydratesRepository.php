<?php

namespace App\Repository;

use App\Entity\Carbohydrates;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Carbohydrates>
 *
 * @method Carbohydrates|null find($id, $lockMode = null, $lockVersion = null)
 * @method Carbohydrates|null findOneBy(array $criteria, array $orderBy = null)
 * @method Carbohydrates[]    findAll()
 * @method Carbohydrates[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CarbohydratesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Carbohydrates::class);
    }

    public function save(Carbohydrates $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Carbohydrates $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return Carbohydrates[] Returns an array of Carbohydrates objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('c.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Carbohydrates
//    {
//        return $this->createQueryBuilder('c')
//            ->andWhere('c.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
