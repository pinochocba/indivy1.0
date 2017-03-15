<?php
/**
 * @copyright   2014 Mautic Contributors. All rights reserved
 * @author      Mautic
 *
 * @link        http://mautic.org
 *
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

namespace Mautic\UserBundle\Form\Type;

use Mautic\CoreBundle\Form\EventListener\CleanFormSubscriber;
use Mautic\CoreBundle\Form\EventListener\FormExitSubscriber;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Email;

/**
 * Class BusinessGroupType.
 */
class BusinessGroupType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addEventSubscriber(new CleanFormSubscriber());
        $builder->addEventSubscriber(new FormExitSubscriber('user.businessgroup', $options));

        $builder->add('name', 'text', [
            'label'      => 'mautic.core.name',
            'label_attr' => ['class' => 'control-label'],
            'attr'       => ['class' => 'form-control'],
        ]);

        $builder->add('description', 'textarea', [
            'label'      => 'mautic.core.description',
            'label_attr' => ['class' => 'control-label'],
            'attr'       => ['class' => 'form-control editor'],
            'required'   => false,
        ]);

        $builder->add('isAdmin', 'yesno_button_group', [
            'label' => 'mautic.user.businessgroup.form.isadmin',
            'attr'  => [
                'onchange' => 'Mautic.togglePermissionVisibility();',
                'tooltip'  => 'mautic.user.businessgroup.form.isadmin.tooltip',
            ],
        ]);

        $builder->add(
            'mailer_from_name',
            'text',
            [
                'label'      => 'mautic.email.config.mailer.from.name',
                'label_attr' => ['class' => 'control-label'],
                'attr'       => [
                    'class'   => 'form-control',
                    'tooltip' => 'mautic.email.config.mailer.from.name.tooltip',
                ],
                'constraints' => [
                    new NotBlank(
                        [
                            'message' => 'mautic.core.value.required',
                        ]
                    ),
                ],
            ]
        );

        $builder->add(
            'mailer_from',
            'text',
            [
                'label'      => 'mautic.email.config.mailer.from.email',
                'label_attr' => ['class' => 'control-label'],
                'attr'       => [
                    'class'   => 'form-control',
                    'tooltip' => 'mautic.email.config.mailer.from.email.tooltip',
                ],
                'constraints' => [
                    new NotBlank(
                        [
                            'message' => 'mautic.core.email.required',
                        ]
                    ),
                    new Email(
                        [
                            'message' => 'mautic.core.email.required',
                        ]
                    ),
                ],
            ]
        );

        $builder->add(
            'mailer_return_path',
            'text',
            [
                'label'      => 'mautic.email.config.mailer.return.path',
                'label_attr' => ['class' => 'control-label'],
                'attr'       => [
                    'class'   => 'form-control',
                    'tooltip' => 'mautic.email.config.mailer.return.path.tooltip',
                ],
                'required' => false,
            ]
        );

        $builder->add(
            'mailer_spool_type',
            'choice',
            [
                'choices' => [
                    'memory' => 'mautic.email.config.mailer_spool_type.memory',
                    'file'   => 'mautic.email.config.mailer_spool_type.file',
                ],
                'label'      => 'mautic.email.config.mailer.spool.type',
                'label_attr' => ['class' => 'control-label'],
                'required'   => false,
                'attr'       => [
                    'class'   => 'form-control',
                    'tooltip' => 'mautic.email.config.mailer.spool.type.tooltip',
                ],
                'empty_value' => false,
            ]
        );

        $builder->add(
            'mailer_transport',
            'choice',
            [
                'choices' => [
                    'mautic.transport.amazon'    => 'mautic.email.config.mailer_transport.amazon',
                    'gmail'                      => 'mautic.email.config.mailer_transport.gmail',
                    'mautic.transport.mandrill'  => 'mautic.email.config.mailer_transport.mandrill',
                    'smtp'                       => 'mautic.email.config.mailer_transport.smtp',
                    'mail'                       => 'mautic.email.config.mailer_transport.mail',
                    'mautic.transport.postmark'  => 'mautic.email.config.mailer_transport.postmark',
                    'mautic.transport.sendgrid'  => 'mautic.email.config.mailer_transport.sendgrid',
                    'sendmail'                   => 'mautic.email.config.mailer_transport.sendmail',
                ],
                'label'    => 'mautic.email.config.mailer.transport',
                'required' => false,
                'attr'     => [
                    'class'   => 'form-control',
                    'tooltip' => 'mautic.email.config.mailer.transport.tooltip',
                ],
                'empty_value' => false,
            ]
        );

        $smtpServiceShowConditions  = '{"businessgroup_mailer_transport":["smtp"]}';
        $amazonRegionShowConditions = '{"businessgroup_mailer_transport":["mautic.transport.amazon"]}';

        $builder->add(
            'mailer_host',
            'text',
            [
                'label'      => 'mautic.email.config.mailer.host',
                'label_attr' => ['class' => 'control-label'],
                'attr'       => [
                    'class'        => 'form-control',
                    'data-show-on' => $smtpServiceShowConditions,
                    'tooltip'      => 'mautic.email.config.mailer.host.tooltip',
                ],
                'required' => false,
            ]
        );

        $builder->add(
            'mailer_port',
            'text',
            [
                'label'      => 'mautic.email.config.mailer.port',
                'label_attr' => ['class' => 'control-label'],
                'attr'       => [
                    'class'        => 'form-control',
                    'data-show-on' => $smtpServiceShowConditions,
                    'tooltip'      => 'mautic.email.config.mailer.port.tooltip',
                ],
                'required' => false,
            ]
        );

        $builder->add(
            'mailer_auth_mode',
            'choice',
            [
                'choices' => [
                    'plain'    => 'mautic.email.config.mailer_auth_mode.plain',
                    'login'    => 'mautic.email.config.mailer_auth_mode.login',
                    'cram-md5' => 'mautic.email.config.mailer_auth_mode.cram-md5',
                ],
                'label'      => 'mautic.email.config.mailer.auth.mode',
                'label_attr' => ['class' => 'control-label'],
                'required'   => false,
                'attr'       => [
                    'class'        => 'form-control',
                    'data-show-on' => $smtpServiceShowConditions,
                    'tooltip'      => 'mautic.email.config.mailer.auth.mode.tooltip',
                ],
                'empty_value' => 'mautic.email.config.mailer_auth_mode.none',
            ]
        );

        $mailerLoginUserShowConditions = '{
            "businessgroup_mailer_auth_mode":[
                "plain",
                "login",
                "cram-md5"
            ], "businessgroup_mailer_transport":[
                "mautic.transport.mandrill",
                "mautic.transport.sendgrid",
                "mautic.transport.amazon",
                "mautic.transport.postmark",
                "gmail"
            ]
        }';

        $mailerLoginPasswordShowConditions = '{
            "businessgroup_mailer_auth_mode":[
                "plain",
                "login",
                "cram-md5"
            ], "businessgroup_mailer_transport":[
                "mautic.transport.sendgrid",
                "mautic.transport.amazon",
                "mautic.transport.postmark",
                "gmail"
            ]
        }';

        $mailerLoginUserHideConditions = '{
         "businessgroup_mailer_transport":[
                "mail",
                "sendmail",
                "mautic.transport.sparkpost"
            ]
        }';

        $mailerLoginPasswordHideConditions = '{
         "businessgroup_mailer_transport":[
                "mail",
                "sendmail",
                "mautic.transport.sparkpost",
                "mautic.transport.mandrill"
            ]
        }';

        $builder->add(
            'mailer_user',
            'text',
            [
                'label'      => 'mautic.email.config.mailer.user',
                'label_attr' => ['class' => 'control-label'],
                'attr'       => [
                    'class'        => 'form-control',
                    'data-show-on' => $mailerLoginUserShowConditions,
                    'data-hide-on' => $mailerLoginUserHideConditions,
                    'tooltip'      => 'mautic.email.config.mailer.user.tooltip',
                    'autocomplete' => 'off',
                ],
                'required' => false,
            ]
        );

        $builder->add(
            'mailer_password',
            'password',
            [
                'label'      => 'mautic.email.config.mailer.password',
                'label_attr' => ['class' => 'control-label'],
                'attr'       => [
                    'class'        => 'form-control',
                    'placeholder'  => 'mautic.user.user.form.passwordplaceholder',
                    'preaddon'     => 'fa fa-lock',
                    'data-show-on' => $mailerLoginPasswordShowConditions,
                    'data-hide-on' => $mailerLoginPasswordHideConditions,
                    'tooltip'      => 'mautic.email.config.mailer.password.tooltip',
                    'autocomplete' => 'off',
                ],
                'required' => false,
            ]
        );

        $builder->add(
            'mailer_encryption',
            'choice',
            [
                'choices' => [
                    'ssl' => 'mautic.email.config.mailer_encryption.ssl',
                    'tls' => 'mautic.email.config.mailer_encryption.tls',
                ],
                'label'    => 'mautic.email.config.mailer.encryption',
                'required' => false,
                'attr'     => [
                    'class'        => 'form-control',
                    'data-show-on' => $smtpServiceShowConditions,
                    'tooltip'      => 'mautic.email.config.mailer.encryption.tooltip',
                ],
                'empty_value' => 'mautic.email.config.mailer_encryption.none',
            ]
        );

        $builder->add(
            'mailer_test_connection_button',
            'standalone_button',
            [
                'label'    => 'mautic.email.config.mailer.transport.test_connection',
                'required' => false,
                'attr'     => [
                    'class'   => 'btn btn-success',
                    'onclick' => 'Mautic.testEmailServerConnection()',
                ],
            ]
        );

        $builder->add(
            'mailer_test_send_button',
            'standalone_button',
            [
                'label'    => 'mautic.email.config.mailer.transport.test_send',
                'required' => false,
                'attr'     => [
                    'class'   => 'btn btn-info',
                    'onclick' => 'Mautic.testEmailServerConnection(true)',
                ],
            ]
        );
        
//        $spoolConditions = '{"config_emailconfig_mailer_spool_type":["memory"]}';

        // add a normal text field, but add your transformer to it
        $hidden = ($options['data']->isAdmin()) ? ' hide' : '';

        $builder->add(
            'permissions', 'permissions', [
                'label'    => 'mautic.user.businessgroup.permissions',
                'mapped'   => false, //we'll have to manually build the permissions for persisting
                'required' => false,
                'attr'     => [
                    'class' => $hidden,
                ],
                'permissionsConfig' => $options['permissionsConfig'],
            ]
        );

        $builder->add('buttons', 'form_buttons');

        if (!empty($options['action'])) {
            $builder->setAction($options['action']);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class'         => 'Mautic\UserBundle\Entity\BusinessGroup',
            'cascade_validation' => true,
            'permissionsConfig'  => [],
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'businessgroup';
    }
}
