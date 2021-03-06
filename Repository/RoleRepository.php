<?php

namespace Esolving\Eschool\UserBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * RoleRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class RoleRepository extends EntityRepository {

    public function findAllRoleByLanguageExceptAdmin($xrole, $xlanguage) {
        $qb = $this->createQueryBuilder('role');
        $q = $qb->addSelect('role_roleType', 'languages')
                ->join("role.roleType", "role_roleType")
                ->join("role_roleType.languages", "languages")
                ->where($qb->expr()->eq("role_roleType.category", ":category"))
                ->andWhere($qb->expr()->neq("role_roleType.name", ":name"))
                ->andWhere($qb->expr()->eq("languages.language", ":language"))
                ->orderBy('role_roleType.name')
                ->setParameters(array(
                    'category' => 'role',
                    'name' => $xrole,
                    'language' => $xlanguage
                ))
                ->getQuery();
        return $q->getResult();
    }

    public function findByRoleByLanguage($xrole, $xlanguage) {
        $qb = $this->createQueryBuilder('role');
        $qb->addSelect('role_roleType', 'languages')
                ->join("role.roleType", "role_roleType")
                ->join("role_roleType.languages", "languages")
                ->where($qb->expr()->eq("role_roleType.category", ":category"))
                ->andWhere($qb->expr()->eq("role_roleType.name", ":name"))
                ->andWhere($qb->expr()->eq("languages.language", ":language"))
                ->orderBy('role_roleType.name')
                ->setParameters(array(
                    'category' => 'role',
                    'name' => $xrole,
                    'language' => $xlanguage
        ));
        $q = $qb->getQuery();
        return $q->getResult();
    }

    public function findAllByLanguageToSonataAdmin($xlanguage, $query) {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $query->addSelect('role_roleType', 'role_roleType_languages')
                ->join('o.roleType', 'role_roleType')
                ->join('role_roleType.languages', 'role_roleType_languages')
                ->where($qb->expr()->eq('role_roleType_languages.language', $qb->expr()->literal($xlanguage)))
                ->orderBy('role_roleType_languages.description')
        ;
        return $query;
    }

    public function findAllByLanguage($xlanguage) {
        $qb = $this->createQueryBuilder('role');
        $q = $qb->addSelect("role_roleType", "languages")
                ->join("role.roleType", "role_roleType")
                ->join("role_roleType.languages", "languages")
                ->where($qb->expr()->eq("languages.language", ":language"))
                ->andWhere($qb->expr()->eq("role.status", "1"))
                ->setParameter('language', $xlanguage)
                ->getQuery();
        return $q->getResult();
    }

    public function findOneByRoleIdByLanguage($roleId, $language) {
        $qb = $this->createQueryBuilder('role');
        $qb->addSelect("role_roleType", "languages")
                ->join("role.roleType", "role_roleType")
                ->join("role_roleType.languages", "languages")
                ->where($qb->expr()->eq("languages.language", ":language"))
                ->andWhere('role.id = :roleId')
                ->andWhere($qb->expr()->eq("role.status", "1"))
                ->setParameter('language', $language)
                ->setParameter('roleId', $roleId);
        $query = $qb->getQuery();
        return $query->getOneOrNullResult();
    }

}