<?php

namespace App\Repository;

use App\Entity\Book;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\ORM\QueryBuilder;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Book|null find($id, $lockMode = null, $lockVersion = null)
 * @method Book|null findOneBy(array $criteria, array $orderBy = null)
 * @method Book[]    findAll()
 * @method Book[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BookRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry, PaginatorInterface $paginator)
    {
        parent::__construct($registry, Book::class);
    }

    /**
     * @param int $limit
     * @param string $date
     * @return Book[]
     */
    public function findByLimitAndDate(int $limit, string $date) :array
    {
        return $this->createQueryBuilder('b')
                    ->orderBy('b.'. $date, 'DESC' )
                    ->setMaxResults($limit)
                    ->getQuery()
                    ->getResult();
    }

    /**
     * @return Query
     */
    public function findAllBooksQuery(): Query
    {
        return $this->createQueryBuilder('b')
                    ->orderBy('b.title', 'ASC')
                    ->getQuery();
    }

    /**
     * @param $name
     * @return array
     */
    public function findByAuthor($name): array
    {
        $author = explode(' ', $name);

        return $this->createQueryBuilder('b')
            ->andWhere('b.authorLastname IN (:author)')
            ->andWhere('b.authorFirstname IN (:author)')
            ->setParameter('author', $author)
            ->orderBy('b.yearBook', 'DESC')
            ->getQuery()
            ->getResult();
    }


    /*public function findAllOrderBy()
    {
        return $this->createQueryBuilder('b')
            ->orderBy('b.category', 'DESC')
            ->addOrderBy('b.yearBook', 'DESC')
            ->getQuery()
            ->getResult();
    }


    public function findByCategoryTitle($param)
    {
        return $query = $this->withCategories()
            ->andWhere('c.title = :param')
            ->setParameter('param', $param)
            ->orderBy('b.yearBook', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function findBySearch($search, $author = null, $category = null)
    {
        $multi = explode(" ", $search);

        $sql = 'b.title LIKE :search 
                OR c.title LIKE :search 
                OR b.authorFirstname LIKE :search 
                OR b.authorLastname LIKE :search 
                OR b.yearBook LIKE :search  
                OR b.content LIKE :search ';

        $query = $this->withCategories()
                    ->where($sql)
                    ->setParameter('search', '%'.$multi[0].'%');

        if (count($multi) > 1) {
            for ($i = 0; $i < count($multi); $i++) {
                $query->andWhere($sql)
                    ->setParameter('search', '%' .$multi[$i]. '%');
            }
        }

        if (!is_null($author)) {
            $name = explode(" ", $author);
            $query->andWhere('b.authorLastname IN (:name)')
                ->andWhere('b.authorFirstname IN (:name)')
                ->setParameter('name', $name);
        }
        if (!is_null($category)) {
            $query->andWhere('c.title = :category')
                ->setParameter('category', $category);
        }

        return $query->getQuery()->getResult();
    }

    public function getLastBooks(int $limit)
    {
        return $this->createQueryBuilder('b')
                    ->orderBy('b.updatedAt', 'DESC')
                    ->setMaxResults($limit)
                    ->getQuery()
                    ->getResult();
    }

    private function withCategories()
    {
        $query = $this->createQueryBuilder('b')
            ->leftJoin('b.category','c');

        return $query;
    }*/
}
