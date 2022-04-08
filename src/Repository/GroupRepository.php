<?php
namespace App\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NoResultException;

class GroupRepository extends EntityRepository
{
    public function findAllStudentsByLastname($groupId)
    {
        return $this->getEntityManager()
            ->createQuery('SELECT std'
             . ' FROM TecnotekExpedienteBundle:StudentYear stdY, TecnotekExpedienteBundle:Student std'
             . " WHERE stdY.group = $groupId AND stdY.student = std"
             . " ORDER BY std.lastname ASC, std.firstname ASC")
            ->getResult();
    }
}
?>
