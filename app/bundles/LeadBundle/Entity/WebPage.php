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

use Doctrine\ORM\Mapping as ORM;
use Mautic\ApiBundle\Serializer\Driver\ApiMetadataDriver;
use Mautic\CoreBundle\Doctrine\Mapping\ClassMetadataBuilder;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Mautic\CoreBundle\Entity\FormEntity;
use Mautic\UserBundle\Entity\User;
use Mautic\UserBundle\Entity\BusinessGroup;

/**
 * Class WebPage.
 */
class WebPage extends FormEntity
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var \Mautic\UserBundle\Entity\User
     */
    private $owner;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $url;

    /**
     * @var int
     */
    private $businessgroup;

    /**
     * @param ORM\ClassMetadata $metadata
     */
    public static function loadMetadata(ORM\ClassMetadata $metadata)
    {
        $builder = new ClassMetadataBuilder($metadata);
        $builder->setTable('webpages')
            ->setCustomRepositoryClass('Mautic\LeadBundle\Entity\WebPageRepository');

        $builder->createField('id', 'integer')
            ->isPrimaryKey()
            ->generatedValue()
            ->build();

        $builder->createField('name', 'string')
            ->length(255)
            ->build();

        $builder->createField('url', 'string')
            ->length(255)
            ->build();

        $builder->createField('businessgroup', 'integer')
            ->columnName('businessgroup')
            ->build();
    }

    /**
     * @param ClassMetadata $metadata
     */
    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        $metadata->addPropertyConstraint('name', new Assert\NotBlank(
            ['message' => 'mautic.lead.webpage.name.notblank']
        ));

        $metadata->addPropertyConstraint('url', new Assert\NotBlank(
            ['message' => 'mautic.lead.webpage.url.notblank']
        ));
    }

    /**
     * @param string $prop
     * @param mixed  $val
     */
    protected function isChanged($prop, $val)
    {
        $getter  = 'get'.ucfirst($prop);
        $current = $this->$getter();
        if ($prop == 'owner') {
            if ($current && !$val) {
                $this->changes['owner'] = [$current->getName().' ('.$current->getId().')', $val];
            } elseif (!$current && $val) {
                $this->changes['owner'] = [$current, $val->getName().' ('.$val->getId().')'];
            } elseif ($current && $val && $current->getId() != $val->getId()) {
                $this->changes['owner'] = [
                    $current->getName().'('.$current->getId().')',
                    $val->getName().'('.$val->getId().')',
                ];
            }
        } else {
            $this->changes[$prop] = [$current, $val];
        }
    }

    /**
     * Get id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name.
     *
     * @param string $name
     *
     * @return WebPage
     */
    public function setName($name)
    {
        $this->isChanged('name', $name);
        $this->name = $name;

        return $this;
    }

    /**
     * Get $name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set owner.
     *
     * @param User $owner
     *
     * @return WebPage
     */
    public function setOwner(User $owner = null)
    {
        $this->isChanged('owner', $owner);
        $this->owner = $owner;

        return $this;
    }

    /**
     * Get owner.
     *
     * @return User
     */
    public function getOwner()
    {
        return $this->owner;
    }

    /**
     * Set url.
     *
     * @param string $url
     *
     * @return WebPage
     */
    public function setUrl($url)
    {
        $this->isChanged('url', $url);
        $this->url = $url;

        return $this;
    }

    /**
     * Get $name.
     *
     * @return string
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set businessgroup.
     *
     * @param BusinessGroup $businessgroup
     *
     * @return WebPage
     */
    public function setBusinessGroup($businessgroup)
    {
        $this->isChanged('businessgroup', $businessgroup);
        $this->businessgroup = $businessgroup;

        return $this;
    }

    /**
     * Get businessgroup.
     *
     * @return WebPage
     */
    public function getBusinessGroup()
    {
        return $this->businessgroup;
    }
}