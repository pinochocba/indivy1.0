<?php
/**
 * @copyright   2014 Mautic Contributors. All rights reserved
 * @author      Mautic
 *
 * @link        http://mautic.org
 *
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

namespace Mautic\UserBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use Mautic\ApiBundle\Serializer\Driver\ApiMetadataDriver;
use Mautic\CoreBundle\Doctrine\Mapping\ClassMetadataBuilder;
use Mautic\CoreBundle\Entity\FormEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Security\Core\User\AdvancedUserInterface;

/**
 * Class BusinessGroup.
 */
class BusinessGroup extends FormEntity
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $description;

    /**
     * @var bool
     */
    private $isAdmin = false;

    /**
     * @var ArrayCollection
     */
    private $permissions;

    /**
     * @var array
     */
    private $rawPermissions;

    /**
     * @var ArrayCollection
     */
    private $users;

    /**
     * @var string
     */
    private $mailerFromName;

    /**
     * @var string
     */
    private $mailerFrom;

    /**
     * @var string
     */
    private $mailerReturnPath;

    /**
     * @var string
     */
    private $mailerTransport;

    /**
     * @var string
     */
    private $mailerHost;

    /**
     * @var smallint
     */
    private $mailerPort;

    /**
     * @var string
     */
    private $mailerEncryption;

    /**
     * @var string
     */
    private $mailerAuthMode;

    /**
     * @var string
     */
    private $mailerUser;

    /**
     * @var string
     */
    private $mailerPassword;

    /**
     * @var string
     */
    private $mailerSpoolType;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->permissions = new ArrayCollection();
        $this->users       = new ArrayCollection();
    }

    /**
     * @param ORM\ClassMetadata $metadata
     */
    public static function loadMetadata(ORM\ClassMetadata $metadata)
    {
        $builder = new ClassMetadataBuilder($metadata);

        $builder->setTable('businessGroup')
            ->setCustomRepositoryClass('Mautic\UserBundle\Entity\BusinessGroupRepository');

        $builder->addIdColumns();

        $builder->createField('mailerFromName', 'string')
            ->columnName('mailer_from_name')
            ->build();

        $builder->createField('mailerFrom', 'string')
            ->columnName('mailer_from')
            ->build();

        $builder->createField('mailerReturnPath', 'string')
            ->columnName('mailer_return_path')
            ->nullable(true)
            ->build();

        $builder->createField('mailerTransport', 'string')
            ->columnName('mailer_transport')
            ->build();

        $builder->createField('mailerHost', 'string')
            ->columnName('mailer_host')
            ->build();

        $builder->createField('mailerPort', 'smallint')
            ->columnName('mailer_port')
            ->build();

        $builder->createField('mailerEncryption', 'string')
            ->columnName('mailer_encryption')
            ->nullable(true)
            ->build();

        $builder->createField('mailerAuthMode', 'string')
            ->columnName('mailer_auth_mode')
            ->nullable(true)
            ->build();

        $builder->createField('mailerUser', 'string')
            ->columnName('mailer_user')
            ->build();

        $builder->createField('mailerPassword', 'string')
            ->columnName('mailer_password')
            ->build();

        $builder->createField('mailerSpoolType', 'string')
            ->columnName('mailer_spool_type')
            ->build();

        $builder->createField('isAdmin', 'string')
            ->columnName('is_admin')
            ->build();

        $builder->createOneToMany('permissions', 'Permission')
            ->orphanRemoval()
            ->mappedBy('role')
            ->cascadePersist()
            ->cascadeRemove()
            ->fetchExtraLazy()
            ->build();

        $builder->createField('rawPermissions', 'array')
            ->columnName('readable_permissions')
            ->build();

        $builder->createOneToMany('users', 'User')
            ->mappedBy('role')
            ->fetchExtraLazy()
            ->build();
    }

    /**
     * @param ClassMetadata $metadata
     */
    public static function loadValidatorMetadata(ClassMetadata $metadata)
    {
        $metadata->addPropertyConstraint('name', new Assert\NotBlank(
            ['message' => 'mautic.user.businessgroup.name.notblank']
        ));
        $metadata->addPropertyConstraint('mailerFromName', new Assert\NotBlank(
            ['message' => 'mautic.core.value.required']
        ));
        $metadata->addPropertyConstraint('mailerFrom', new Assert\NotBlank(
            ['message' => 'mautic.core.email.required']
        ));
        $metadata->addPropertyConstraint('mailerFrom', new Assert\Email(
            ['message' => 'mautic.core.email.required']
        ));
    }

    /**
     * Prepares the metadata for API usage.
     *
     * @param $metadata
     */
    public static function loadApiMetadata(ApiMetadataDriver $metadata)
    {
        $metadata->setGroupPrefix('businessgroup')
            ->addListProperties(
                [
                    'id',
                    'name',
                    'description',
                    'isAdmin'
                ]
            )
            ->build();
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
     * @return BusinessGroup
     */
    public function setName($name)
    {
        $this->isChanged('name', $name);
        $this->name = $name;

        return $this;
    }

    /**
     * Get name.
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Add permissions.
     *
     * @param Permission $permissions
     *
     * @return BusinessGroup
     */
    public function addPermission(Permission $permissions)
    {
        $permissions->setRole($this);

        $this->permissions[] = $permissions;

        return $this;
    }

    /**
     * Remove permissions.
     *
     * @param Permission $permissions
     */
    public function removePermission(Permission $permissions)
    {
        $this->permissions->removeElement($permissions);
    }

    /**
     * Get permissions.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPermissions()
    {
        return $this->permissions;
    }

    /**
     * Set description.
     *
     * @param string $description
     *
     * @return BusinessGroup
     */
    public function setDescription($description)
    {
        $this->isChanged('description', $description);
        $this->description = $description;

        return $this;
    }

    /**
     * Get description.
     *
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }


    /**
     * Set isAdmin.
     *
     * @param bool $isAdmin
     *
     * @return BusinessGroup
     */
    public function setIsAdmin($isAdmin)
    {
        $this->isChanged('isAdmin', $isAdmin);
        $this->isAdmin = $isAdmin;

        return $this;
    }

    /**
     * Get isAdmin.
     *
     * @return bool
     */
    public function getIsAdmin()
    {
        return $this->isAdmin;
    }

    /**
     * Get isAdmin.
     *
     * @return bool
     */
    public function isAdmin()
    {
        return $this->getIsAdmin();
    }

    /**
     * Simply used to store a readable format of permissions for the changelog.
     *
     * @param array $permissions
     */
    public function setRawPermissions(array $permissions)
    {
        $this->isChanged('rawPermissions', $permissions);
        $this->rawPermissions = $permissions;
    }

    /**
     * Get rawPermissions.
     *
     * @return array
     */
    public function getRawPermissions()
    {
        return $this->rawPermissions;
    }

    /**
     * Add users.
     *
     * @param User $users
     *
     * @return BusinessGroup
     */
    public function addUser(User $users)
    {
        $this->users[] = $users;

        return $this;
    }

    /**
     * Remove users.
     *
     * @param User $users
     */
    public function removeUser(User $users)
    {
        $this->users->removeElement($users);
    }

    /**
     * Get users.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getUsers()
    {
        return $this->users;
    }

    /**
     * Get mailerFromName
     *
     * @return string
     */
    public function getMailerFromName()
    {
        return $this->mailerFromName;
    }

    /**
     * Set mailerFromName.
     *
     * @param string $mailerFromName
     *
     * @return BusinessGroup
     */
    public function setMailerFromName($mailerFromName)
    {
        $this->isChanged('mailerFromName', $mailerFromName);
        $this->mailerFromName = $mailerFromName;

        return $this;
    }

    /**
     * Get mailerFrom
     *
     * @return string
     */
    public function getMailerFrom()
    {
        return $this->mailerFrom;
    }

    /**
     * Set mailerFrom.
     *
     * @param string $mailerFromName
     *
     * @return BusinessGroup
     */
    public function setMailerFrom($mailerFrom)
    {
        $this->isChanged('mailerFrom', $mailerFrom);
        $this->mailerFrom = $mailerFrom;

        return $this;
    }

    /**
     * Get mailerReturnPath
     *
     * @return string
     */
    public function getMailerReturnPath()
    {
        return $this->mailerReturnPath;
    }

    /**
     * Set mailerReturnPath.
     *
     * @param string $mailerReturnPath
     *
     * @return BusinessGroup
     */
    public function setMailerReturnPath($mailerReturnPath)
    {
        $this->isChanged('mailerReturnPath', $mailerReturnPath);
        $this->mailerReturnPath = $mailerReturnPath;

        return $this;
    }

    /**
     * Get mailerTransport
     *
     * @return string
     */
    public function getMailerTransport()
    {
        return $this->mailerTransport;
    }

    /**
     * Set mailerTransport.
     *
     * @param string $mailerTransport
     *
     * @return BusinessGroup
     */
    public function setMailerTransport($mailerTransport)
    {
        $this->isChanged('mailerTransport', $mailerTransport);
        $this->mailerTransport = $mailerTransport;

        return $this;
    }

    /**
     * Get mailerHost
     *
     * @return string
     */
    public function getMailerHost()
    {
        return $this->mailerHost;
    }

    /**
     * Set mailerHost.
     *
     * @param string $mailerHost
     *
     * @return BusinessGroup
     */
    public function setMailerHost($mailerHost)
    {
        $this->isChanged('mailerHost', $mailerHost);
        $this->mailerHost = $mailerHost;

        return $this;
    }

    /**
     * Get mailerPort
     *
     * @return smallint
     */
    public function getMailerPort()
    {
        return $this->mailerPort;
    }

    /**
     * Set mailerPort.
     *
     * @param smallint $mailerPort
     *
     * @return BusinessGroup
     */
    public function setMailerPort($mailerPort)
    {
        $this->isChanged('mailerPort', $mailerPort);
        $this->mailerPort = $mailerPort;

        return $this;
    }

    /**
     * Get mailerEncryption
     *
     * @return string
     */
    public function getMailerEncryption()
    {
        return $this->mailerEncryption;
    }

    /**
     * Set mailerEncryption.
     *
     * @param string $mailerEncryption
     *
     * @return BusinessGroup
     */
    public function setMailerEncryption($mailerEncryption)
    {
        $this->isChanged('mailerEncryption', $mailerEncryption);
        $this->mailerEncryption = $mailerEncryption;

        return $this;
    }

    /**
     * Get mailerAuthMode
     *
     * @return string
     */
    public function getMailerAuthMode()
    {
        return $this->mailerAuthMode;
    }

    /**
     * Set mailerAuthMode.
     *
     * @param string $mailerAuthMode
     *
     * @return BusinessGroup
     */
    public function setMailerAuthMode($mailerAuthMode)
    {
        $this->isChanged('mailerAuthMode', $mailerAuthMode);
        $this->mailerAuthMode = $mailerAuthMode;

        return $this;
    }

    /**
     * Get mailerUser
     *
     * @return string
     */
    public function getMailerUser()
    {
        return $this->mailerUser;
    }

    /**
     * Set mailerUser.
     *
     * @param string $mailerUser
     *
     * @return BusinessGroup
     */
    public function setMailerUser($mailerUser)
    {
        $this->isChanged('mailerUser', $mailerUser);
        $this->mailerUser = $mailerUser;

        return $this;
    }

    /**
     * Get mailerPassword
     *
     * @return string
     */
    public function getMailerPassword()
    {
        return $this->mailerPassword;
    }

    /**
     * Set mailerPassword.
     *
     * @param string $mailerPassword
     *
     * @return BusinessGroup
     */
    public function setMailerPassword($mailerPassword)
    {
        $this->isChanged('mailerPassword', $mailerPassword);
        $this->mailerPassword = $mailerPassword;

        return $this;
    }

    /**
     * Get mailerSpoolType
     *
     * @return string
     */
    public function getMailerSpoolType()
    {
        return $this->mailerSpoolType;
    }

    /**
     * Set mailerSpoolType.
     *
     * @param string $mailerSpoolType
     *
     * @return BusinessGroup
     */
    public function setMailerSpoolType($mailerSpoolType)
    {
        $this->isChanged('mailerSpoolType', $mailerSpoolType);
        $this->mailerSpoolType = $mailerSpoolType;

        return $this;
    }

    /**
     * {@inheritdoc}
     */
    public function getSalt()
    {
        //bcrypt generates its own salt
        return null;
    }
}
