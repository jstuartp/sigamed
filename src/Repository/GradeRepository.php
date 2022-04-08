<?php
namespace App\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NoResultException;

class GradeRepository extends EntityRepository
{
    public function findAllStudentsByLastname($gradeId, $periodId)
    {
        return $this->getEntityManager()
            ->createQuery('SELECT std'
             . ' FROM TecnotekExpedienteBundle:StudentYear stdY'
             . ', TecnotekExpedienteBundle:Student std'
             . ', TecnotekExpedienteBundle:Group g'
             . " WHERE g.period = $periodId AND g.grade = $gradeId AND stdY.group = g AND stdY.student = std"
             . " ORDER BY std.lastname ASC, std.firstname ASC")
            ->getResult();
    }
}
?>
