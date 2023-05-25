<?php

namespace App\Repository;

use App\Entity\TeamPlayerContract;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TeamPlayerContract>
 *
 * @method TeamPlayerContract|null find($id, $lockMode = null, $lockVersion = null)
 * @method TeamPlayerContract|null findOneBy(array $criteria, array $orderBy = null)
 * @method TeamPlayerContract[]    findAll()
 * @method TeamPlayerContract[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TeamPlayerContractRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TeamPlayerContract::class);
    }

    public function save(TeamPlayerContract $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(TeamPlayerContract $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function getContractedPlayerIds(): array
    {
        return array_map(
            fn (TeamPlayerContract $i) => $i->getPlayer()->getId(),
            $this
            ->createQueryBuilder('q')
            ->andWhere('q.endAt >= :now')
            ->setParameter('now', now())
            ->distinct()
            ->getQuery()
            ->getResult()
        );
    }

//    /**
//     * @return TeamPlayerContract[] Returns an array of TeamPlayerContract objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('t.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?TeamPlayerContract
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
