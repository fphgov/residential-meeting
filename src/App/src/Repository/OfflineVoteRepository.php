<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\User;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\EntityRepository;

use function array_values;

final class OfflineVoteRepository extends EntityRepository
{
    /** @return mixed|int */
    public function numberOfVotes(int $id)
    {
        $qb = $this->createQueryBuilder('v')
                   ->select('COUNT(1)')
                   ->where('v.projectCare = :id')
                   ->orWhere('v.projectGreen = :id')
                   ->orWhere('v.projectWhole = :id')
                   ->setParameter('id', $id);

        try {
            return (int)$qb->getQuery()->getSingleScalarResult();
        } catch (\Throwable $th) {

        }

        return 0;
    }

    public function getStatistics()
    {
        $qb = $this->createQueryBuilder('v')
                   ->select('u.id, CONCAT_WS(\' \', u.lastname, u.firstname) as title, DATE_FORMAT(v.createdAt, \'%Y-%m-%d\') as date, COUNT(1) as count')
                   ->innerJoin(User::class, 'u', Join::WITH, 'u.id = v.user')
                   ->groupBy('v.user, date')
                   ->orderBy('date', 'desc');

        $statResult = $qb->getQuery()->getResult();

        $stats = [];
        foreach ($statResult as $stat) {
            if (! isset($stats[$stat['id']])) {
                $stats[$stat['id']] = [
                    'title' => '',
                    'times' => [],
                ];
            }

            $stats[$stat['id']]['title'] = $stat['title'];
            $stats[$stat['id']]['times'][] = [
                'date'  => $stat['date'],
                'count' => $stat['count'],
            ];
        }

        return array_values($stats);
    }
}
