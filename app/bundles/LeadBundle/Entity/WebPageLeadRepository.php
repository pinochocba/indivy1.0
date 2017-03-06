<?php
/**
 * @copyright   2014 Mautic Contributors. All rights reserved
 * @author      Mautic
 *
 * @link        http://mautic.org
 *
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

namespace Mautic\LeadBundle\Entity;

use Mautic\CoreBundle\Entity\CommonRepository;
use Doctrine\ORM\Query;

/**
 * Class CompanyLeadRepository.
 */
class WebPageLeadRepository extends CommonRepository
{
    /**
     * Get webpages by leadId.
     *
     * @param $leadId
     * @param $webpageId
     *
     * @return array
     */
    public function getCompaniesByLeadId($leadId, $webpageId = null)
    {
        $q = $this->_em->getConnection()->createQueryBuilder();

        $q->select('wl.webpage_id, web.name, web.url')
            ->from(MAUTIC_TABLE_PREFIX.'webpages_leads', 'wl')
            ->join('cl', MAUTIC_TABLE_PREFIX.'webpages', 'web', 'web.id = wl.webpage_id')
            ->where('wl.lead_id = :leadId')
            ->setParameter('leadId', $leadId);

        $q->andWhere(
            $q->expr()->eq('wl.manually_removed', ':false')
        )->setParameter('false', false, 'boolean');

        if ($webpageId) {
            $q->where(
                $q->expr()->eq('wl.webpage_id', ':webpageId')
            )->setParameter('webpageId', $webpageId);
        }

        $result = $q->execute()->fetchAll();

        return $result;
    }

    /**
     * {@inheritdoc}
     *
     * @param int $leadId
     *
     * @return mixed|null
     */
    public function getWebPageEntity($leadId = 0)
    {
        try {
            /** @var Lead $entity */
            $q = $this->_em->getConnection()->createQueryBuilder();
            $q->select('wl.webpage_id')
                ->from(MAUTIC_TABLE_PREFIX.'webpages_leads', 'wl')
                ->where('wl.lead_id = :leadId')
                ->setParameters([
                    'leadId'    => $leadId,
                ]);
            $entity = $q->execute()->fetchAll();
        } catch (\Exception $e) {
            $entity = null;
        }

        return $entity[0]['webpage_id'];
    }

    /**
     * {@inheritdoc}
     *
     * @param int $webId
     * @param int $leadId
     *
     * @return mixed|null
     */
    public function getEntity($webId = 0, $leadId = 0)
    {
        try {
            /** @var Lead $entity */
            $q = $this->_em->getConnection()->createQueryBuilder();
            $q->select('wl.webpage_id')
                ->from(MAUTIC_TABLE_PREFIX.'webpages_leads', 'wl')
                ->where('wl.webpage_id = :webpageId')
                ->andWhere('wl.lead_id = :leadId')
                ->setParameters([
                    'webpageId' => $webId,
                    'leadId'    => $leadId,
                ]);
            $entity = $q->execute()->fetchAll();
        } catch (\Exception $e) {
            $entity = null;
        }

        return $entity;
    }
}