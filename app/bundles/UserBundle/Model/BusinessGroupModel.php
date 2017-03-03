<?php
/**
 * @copyright   2014 Mautic Contributors. All rights reserved
 * @author      Mautic
 *
 * @link        http://mautic.org
 *
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

namespace Mautic\UserBundle\Model;

use Mautic\CoreBundle\Model\FormModel;
use Mautic\UserBundle\Entity\BusinessGroup;
use Mautic\UserBundle\Event\BusinessGroupEvent;
use Mautic\UserBundle\UserEvents;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\PreconditionRequiredHttpException;

/**
 * Class BusinessGroupModel.
 */
class BusinessGroupModel extends FormModel
{
    /**
     * {@inheritdoc}
     */
    public function getRepository()
    {
        return $this->em->getRepository('MauticUserBundle:BusinessGroup');
    }

    /**
     * {@inheritdoc}
     */
    public function getPermissionBase()
    {
        return 'user:businessgroup';
    }

    /**
     * {@inheritdoc}
     *
     * @throws \Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException
     */
    public function saveEntity($entity, $unlock = true)
    {
        if (!$entity instanceof BusinessGroup) {
            throw new MethodNotAllowedHttpException(['BusinessGroup'], 'Entity must be of class BusinessGroup()');
        }

        $isNew = ($entity->getId()) ? 0 : 1;

        if (!$isNew) {
            //delete all existing
            $this->em->getRepository('MauticUserBundle:Permission')->purgeRolePermissions($entity);
        }

        parent::saveEntity($entity, $unlock);
    }

    /**
     * Generate the role's permissions.
     *
     * @param Role  $entity
     * @param array $rawPermissions (i.e. from request)
     */
    public function setRolePermissions(BusinessGroup &$entity, $rawPermissions)
    {
        if (!is_array($rawPermissions)) {
            return;
        }

        //set permissions if applicable and if the user is not an admin
        $permissions = (!$entity->isAdmin() && !empty($rawPermissions)) ?
            $this->security->generatePermissions($rawPermissions) :
            [];

        foreach ($permissions as $permissionEntity) {
            $entity->addPermission($permissionEntity);
        }

        $entity->setRawPermissions($rawPermissions);
    }

    /**
     * {@inheritdoc}
     *
     * @throws PreconditionRequiredHttpException
     */
    public function deleteEntity($entity)
    {
        if (!$entity instanceof BusinessGroup) {
            throw new MethodNotAllowedHttpException(['BusinessGroup'], 'Entity must be of class BusinessGroup()');
        }

        $users = $this->em->getRepository('MauticUserBundle:User')->findByRole($entity);
        if (count($users)) {
            throw new PreconditionRequiredHttpException(
                $this->translator->trans('mautic.user.role.error.deletenotallowed', ['%name%' => $entity->getName()], 'flashes')
            );
        }

        parent::deleteEntity($entity);
    }

    /**
     * {@inheritdoc}
     *
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function createForm($entity, $formFactory, $action = null, $options = [])
    {
        if (!$entity instanceof BusinessGroup) {
            throw new MethodNotAllowedHttpException(['BusinessGroup']);
        }

        if (!empty($action)) {
            $options['action'] = $action;
        }

        return $formFactory->create('businessgroup', $entity, $options);
    }

    /**
     * {@inheritdoc}
     */
    public function getEntity($id = null)
    {
        if ($id === null) {
            return new BusinessGroup();
        }

        return parent::getEntity($id);
    }

    /**
     * {@inheritdoc}
     *
     * @throws \Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException
     */
    protected function dispatchEvent($action, &$entity, $isNew = false, Event $event = null)
    {
        /*
        if (!$entity instanceof BusinessGroup) {
            throw new MethodNotAllowedHttpException(['BusinessGroup'], 'Entity must be of class BusinessGroup()');
        }

        switch ($action) {
            case 'pre_save':
                $name = UserEvents::ROLE_PRE_SAVE;
                break;
            case 'post_save':
                $name = UserEvents::ROLE_POST_SAVE;
                break;
            case 'pre_delete':
                $name = UserEvents::ROLE_PRE_DELETE;
                break;
            case 'post_delete':
                $name = UserEvents::ROLE_POST_DELETE;
                break;
            default:
                return null;
        }
        
        $name = null;

        if ($this->dispatcher->hasListeners($name)) {
            if (empty($event)) {
                $event = new BusinessGroupEvent($entity, $isNew);
                $event->setEntityManager($this->em);
            }
            $this->dispatcher->dispatch($name, $event);

            return $event;
        }
        */
        return null;
    }
}
