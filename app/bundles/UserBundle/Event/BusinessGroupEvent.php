<?php
/**
 * @copyright   2014 Mautic Contributors. All rights reserved
 * @author      Mautic
 *
 * @link        http://mautic.org
 *
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

namespace Mautic\UserBundle\Event;

use Mautic\CoreBundle\Event\CommonEvent;
use Mautic\UserBundle\Entity\Role;

/**
 * Class RoleEvent.
 */
class BusinessGroupEvent extends CommonEvent
{
    /**
     * @param BusinessGroup $role
     * @param bool $isNew
     */
    public function __construct(BusinessGroup &$role, $isNew = false)
    {
        $this->entity = &$role;
        $this->isNew  = $isNew;
    }

    /**
     * Returns the BusinessGroup entity.
     *
     * @return Role
     */
    public function getBusinessGroup()
    {
        return $this->entity;
    }

    /**
     * Sets the BusinessGroup entity.
     *
     * @param Role $role
     */
    public function setBusinessGroup(BusinessGroup $role)
    {
        $this->entity = $role;
    }
}
