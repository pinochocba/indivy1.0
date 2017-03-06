<?php
/**
 * @copyright   2014 Mautic Contributorcomp. All rights reserved
 * @author      Mautic
 *
 * @link        http://mautic.org
 *
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

namespace Mautic\LeadBundle\Entity;

use Mautic\CoreBundle\Entity\CommonRepository;

/**
 * Class WebPageRepository.
 */
class WebPageRepository extends CommonRepository
{

    /**
     * {@inheritdoc}
     *
     * @param int $id
     *
     * @return mixed|null
     */
    public function getEntity($id = 0)
    {
        try {
            /** @var Lead $entity */
            $entity = $this
                ->createQueryBuilder('web')
                ->select('web')
                ->where('web.id = :webpageId')
                ->setParameter('webpageId', $id)
                ->getQuery()
                ->getSingleResult();
        } catch (\Exception $e) {
            $entity = null;
        }

        return $entity;
    }

    /**
     * {@inheritdoc}
     *
     * @param int $id
     *
     * @return mixed|null
     */
    public function getEntityByUrl($url = "")
    {
        try {
            /** @var Lead $entity */
            $entity = $this
                ->createQueryBuilder('web')
                ->select('web')
                ->where('web.url = :webpageUrl')
                ->setParameter('webpageUrl', $url)
                ->getQuery()
                ->getSingleResult();
        } catch (\Exception $e) {
            $entity = null;
        }

        return $entity;
    }

    /**
     * {@inheritdoc}
     *
     * @param int $id
     *
     * @return mixed|null
     */
    public function getEntityByBusinessgroup($businessgroup = "")
    {
        try {
            /** @var Lead $entity */
            $qb = $this->createQueryBuilder('web');
            $entity = $qb
                ->select('web')
                ->where('web.businessgroup = :bid')
                ->andWhere($qb->expr()->like('web.url', $qb->expr()->literal('%importados')))
                ->setParameter('bid', $businessgroup)
                ->getQuery()
                ->getSingleResult();
        } catch (\Exception $e) {
            $entity = null;
        }

        return $entity;
    }

    /**
     * Get a list of webpages.
     *
     * @param array $args
     *
     * @return Paginator
     */
    public function getEntities($args = [])
    {

        $q = $this->createQueryBuilder('web');
        $q->andWhere($q->expr()->eq('web.businessgroup', ':bid'))
            ->setParameter('bid', $this->currentUser->getBusinessGroup());

        $args['qb'] = $q;

        return parent::getEntities($args);
    }

    /**
     * Get a list of webpages.
     *
     * @param array $args
     *
     * @return Paginator
     */
    public function getEntitiesForListType($args = [])
    {

        $q = $this->createQueryBuilder('web');
        $args['qb'] = $q;

        return parent::getEntities($args);
    }

    /**
     * @return \Doctrine\DBAL\Query\QueryBuilder
     */
    public function getEntitiesDbalQueryBuilder()
    {
        $dq = $this->getEntityManager()->getConnection()->createQueryBuilder()
            ->from(MAUTIC_TABLE_PREFIX . 'webpages', $this->getTableAlias());

        return $dq;
    }

    /**
     * @param $order
     *
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function getEntitiesOrmQueryBuilder($order)
    {
        $q = $this->getEntityManager()->createQueryBuilder();
        $q->select($this->getTableAlias() . ',' . $order)
            ->from('MauticLeadBundle:WebPage', $this->getTableAlias(), $this->getTableAlias() . '.id');

        return $q;
    }

    /**
     * Get the groups available for fields.
     *
     * @return array
     */
    public function getFieldGroups()
    {
        return ['core', 'professional', 'other'];
    }

    /**
     * {@inheritdoc}
     */
    public function getTableAlias()
    {
        return 'web';
    }

    /**
     * {@inheritdoc}
     */
    protected function addCatchAllWhereClause(&$q, $filter)
    {
        return $this->addStandardCatchAllWhereClause(
            $q,
            $filter,
            [
                'web.name',
            ]
        );
    }

    /**
     * @param bool   $user
     * @param string $id
     *
     * @return array|mixed
     */
    public function getWebPages($user = false, $id = '')
    {
        $q                = $this->_em->getConnection()->createQueryBuilder();
        static $companies = [];

        if ($user) {
            $user = $this->currentUser;
        }

        $key = (int) $id;
        if (isset($companies[$key])) {
            return $companies[$key];
        }

        $q->select('web.*')
            ->from(MAUTIC_TABLE_PREFIX.'webpages', 'web');

        if (!empty($id)) {
            $q->where(
                $q->expr()->eq('web.id', $id)
            );
        }

        if ($user) {
            $q->andWhere('web.created_by = :user');
            $q->setParameter('user', $user->getId());
        }
        else {
            // Filter by businessgroup
            $q->andWhere('web.businessgroup = :businessgroup');
            $q->setParameter('businessgroup', $this->currentUser->getBusinessGroup()->getId());
        }

        $q->orderBy('web.name', 'ASC');

        $results = $q->execute()->fetchAll();

        $companies[$key] = $results;

        return $results;
    }
	
	/**
 * @param bool   $user
 * @param string $id
 *
 * @return array|mixed
 */
    public function getWebPagesList($businessgroup)
    {
        $q = $this->_em->getConnection()->createQueryBuilder();

        $q->select('web.url')
            ->from(MAUTIC_TABLE_PREFIX.'webpages', 'web');

        $q->where('web.businessgroup = :bid');
        $q->setParameter('bid', $businessgroup);

        $results = $q->execute()->fetchAll();

        $return = [];
        foreach ($results as $r) {
            $return[] = $r['url'];
        }

        return $return;
    }

    /**
     * @param bool   $user
     * @param string $id
     *
     * @return array|mixed
     */
    public function getWebPagesIdList($businessgroup)
    {
        $q = $this->_em->getConnection()->createQueryBuilder();

        $q->select('web.id')
            ->from(MAUTIC_TABLE_PREFIX.'webpages', 'web');

        $q->where('web.businessgroup = :bid');
        $q->setParameter('bid', $businessgroup);

        $results = $q->execute()->fetchAll();

        $return = [];
        foreach ($results as $r) {
            $return[] = $r['id'];
        }

        return $return;
    }

    /**
     * Get a count of leads that belong to the webpageIds.
     *
     * @param $webpageIds
     *
     * @return array
     */
    public function getLeadCount($webpageIds)
    {
        $q = $this->_em->getConnection()->createQueryBuilder();

        $q->select('count(wl.lead_id) as thecount, wl.webpage_id')
            ->from(MAUTIC_TABLE_PREFIX.'webpages_leads', 'wl');

        $returnArray = (is_array($webpageIds));

        if (!$returnArray) {
            $webpageIds = [$webpageIds];
        }

        $q->where(
            $q->expr()->in('wl.webpage_id', $webpageIds),
            $q->expr()->eq('wl.manually_removed', ':false')
        )
            ->setParameter('false', false, 'boolean')
            ->groupBy('wl.webpage_id');

        $result = $q->execute()->fetchAll();

        $return = [];
        foreach ($result as $r) {
            $return[$r['webpage_id']] = $r['thecount'];
        }

        // Ensure lists without leads have a value
        foreach ($webpageIds as $l) {
            if (!isset($return[$l])) {
                $return[$l] = 0;
            }
        }

        return ($returnArray) ? $return : $return[$webpageIds[0]];
    }

    /**
     * Get a list of leads that belong to the webpageIds.
     *
     * @param $webpageIds
     *
     * @return array
     */
    public function getLeadList($webpageIds)
    {
        $q = $this->_em->getConnection()->createQueryBuilder();

        $q->select('wl.lead_id')
            ->from(MAUTIC_TABLE_PREFIX.'webpages_leads', 'wl');

        $q->where(
            $q->expr()->in('wl.webpage_id', $webpageIds),
            $q->expr()->eq('wl.manually_removed', ':false')
        )
            ->setParameter('false', false, 'boolean');

        $result = $q->execute()->fetchAll();

        $return = [];
        foreach ($result as $r) {
            $return[] = $r['lead_id'];
        }

        return $return;
    }

    /**
     * Get a list of leads that belong to the Businessgroup.
     *
     * @param $businessgroup
     *
     * @return array
     */
    public function getLeads($businessgroup)
    {
        $query = $this->_em->getConnection()->createQueryBuilder();
        $query->select('web.id')
            ->from(MAUTIC_TABLE_PREFIX.'webpages', 'web')
            ->where('web.is_published = true')
            ->andWhere('web.businessgroup = :businessgroup')
            ->setParameter('businessgroup', $businessgroup);

        $results = $query->execute()->fetchAll();
        foreach ($results as $r) {
            $webpageIds[] = $r['id'];
        }

        // Check exist a webpageids
        if(count($webpageIds)) {

            $q = $this->_em->getConnection()->createQueryBuilder();

            $q->select('wl.lead_id')
                ->from(MAUTIC_TABLE_PREFIX . 'webpages_leads', 'wl');

            $q->where(
                $q->expr()->in('wl.webpage_id', $webpageIds),
                $q->expr()->eq('wl.manually_removed', ':false')
            )
                ->setParameter('false', false, 'boolean');

            $result = $q->execute()->fetchAll();

            $return = [];
            foreach ($result as $r) {
                $return[] = $r['lead_id'];
            }

            return $return;
        }

        return [];
    }
}