<?php
namespace App\Repository;

use Doctrine\ORM\EntityRepository;

class CustomRepository extends EntityRepository {
    public function findOneOrCreate(array $criteria) {
        $entity = $this->findOneBy($criteria);
        if (null === $entity) {
            $entity = new $this->getClassName();
            $entity->setTheDataSomehow($criteria);
            $this->_em->persist($entity);
            $this->_em->flush();
        }
        return $entity;
    }
}
?>
