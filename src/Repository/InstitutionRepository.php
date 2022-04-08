<?php
namespace App\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NoResultException;

class InstitutionRepository extends EntityRepository
{
    public function findAllStudentsByLastname($institutionId, $periodId)
    {
        return $this->getEntityManager()
            ->createQuery('SELECT std'
             . ' FROM TecnotekExpedienteBundle:StudentYear stdY'
             . ', TecnotekExpedienteBundle:Student std'
             . ', TecnotekExpedienteBundle:Group g'
             . " WHERE g.period = $periodId AND g.institution = $institutionId AND stdY.group = g AND stdY.student = std"
             . " ORDER BY std.lastname ASC, std.firstname ASC")
            ->getResult();
    }
}
?>
