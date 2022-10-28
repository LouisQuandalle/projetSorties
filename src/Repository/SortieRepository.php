<?php

namespace App\Repository;

use App\Entity\Sortie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Sortie>
 *
 * @method Sortie|null find($id, $lockMode = null, $lockVersion = null)
 * @method Sortie|null findOneBy(array $criteria, array $orderBy = null)
 * @method Sortie[]    findAll()
 * @method Sortie[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SortieRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Sortie::class);
    }

    public function add(Sortie $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Sortie $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

//    /**
//     * @return Sortie[] Returns an array of Sortie objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('s.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Sortie
//    {
//        return $this->createQueryBuilder('s')
//            ->andWhere('s.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

    public function search(string $campus, string $nom = null, string $dateDebut = null, string $dateFin = null, int $idUser, string $sortieOrga = null, string $sortieInscrit = null, string $sortiePasInscrit = null, string $sortiePasse = null)
    {
        $query = $this->createQueryBuilder('s');

        dump($sortiePasInscrit);

        if ($campus != null)
        {
            $query->leftJoin('s.siteOrganisateur', 'c');
            $query->andWhere('c.id = :id')->setParameter('id', $campus);
        }

        if ($nom != null){
            $query->andWhere('s.nom like :nom')->setParameter('nom', $nom);
        }


        if ($dateDebut != null)
        {
            $query->andWhere('s.dateHeureDebut > :dateHeureDebut')->setParameter('dateHeureDebut',  $dateDebut);
            $query->orderBy('s.dateHeureDebut', 'ASC');
        }

        if ($dateFin != null)
        {
            $query->andWhere('s.dateLimiteInscription < :dateLimiteInscription')
                ->setParameter('dateLimiteInscription', $dateFin);
            $query->orderBy('s.dateHeureDebut', 'ASC');
        }

        if ($sortieOrga != null)
        {
            $query->andWhere('s.organisateur = :id')->setParameter('id', $idUser);
        }

        if ($sortieInscrit != null)
        {
            $query->innerJoin('s.inscrit', 'p');
            $query->andWhere('p.id = :id') ->setParameter('id', $idUser);
        }

        if ($sortiePasInscrit != null)
        {
            dump('fre');
            $query->innerJoin('s.inscrit', 'p');
            $query->andWhere('p.id != :id')->setParameter('id', $idUser);
        }

        if ($sortiePasse != null)
        {
            $query->andWhere('s.etat = 2 ');
        }

        dump($query->getQuery()->getResult());
        return $query->getQuery()->getResult();
    }




}

