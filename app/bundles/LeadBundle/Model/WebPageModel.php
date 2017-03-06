<?php
/**
 * @copyright   2014 Mautic Contributors. All rights reserved
 * @author      Mautic
 *
 * @link        http://mautic.org
 *
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

namespace Mautic\LeadBundle\Model;

use Doctrine\DBAL\Query\Expression\ExpressionBuilder;
use Mautic\CoreBundle\Model\AjaxLookupModelInterface;
use Mautic\CoreBundle\Model\FormModel as CommonFormModel;
use Mautic\LeadBundle\Entity\WebPage;
use Mautic\LeadBundle\Entity\WebPageLead;
use Mautic\LeadBundle\Entity\Lead;
use Mautic\LeadBundle\Entity\LeadField;
use Mautic\LeadBundle\Event\CompanyEvent;
use Mautic\LeadBundle\Event\LeadChangeCompanyEvent;
use Mautic\LeadBundle\LeadEvents;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

/**
 * Class WebPageModel.
 */
class WebPageModel extends CommonFormModel
{
    /**
     * {@inheritdoc}
     *
     * @return \Mautic\LeadBundle\Entity\WebPageRepository
     */
    public function getRepository()
    {
        return $this->em->getRepository('MauticLeadBundle:WebPage');
    }

    /**
     * {@inheritdoc}
     *
     * @return \Mautic\LeadBundle\Entity\WebPageLeadRepository
     */
    public function getWebPageLeadRepository()
    {
        return $this->em->getRepository('MauticLeadBundle:WebPageLead');
    }

    /**
     * {@inheritdoc}
     */
    public function getPermissionBase()
    {
        return 'webpage:webpages';
    }

    /**
     * {@inheritdoc}
     *
     * @return string
     */
    public function getNameGetter()
    {
        return 'getPrimaryIdentifier';
    }

    /**
     * {@inheritdoc}
     *
     * @return WebPage|null
     */
    public function getEntity($id = null)
    {
        if ($id === null) {
            return new WebPage();
        }

        return parent::getEntity($id);
    }

    /**
     * {@inheritdoc}
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function createForm($entity, $formFactory, $action = null, $options = [])
    {
        if (!$entity instanceof WebPage) {
            throw new MethodNotAllowedHttpException(['WebPage'], 'Entity must be of class WebPage()');
        }
        if (!empty($action)) {
            $options['action'] = $action;
        }

        return $formFactory->create('webpage', $entity, $options);
    }

    /**
     * {@inheritdoc}
     *
     * @throws \Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException
     */
    public function saveEntity($entity, $unlock = true)
    {
        if (!$entity instanceof WebPage) {
            throw new MethodNotAllowedHttpException(['WebPage'], 'Entity must be of class WebPage()');
        }

        parent::saveEntity($entity, $unlock);
    }
}