<?php
/**
 * @copyright   2014 Mautic Contributors. All rights reserved
 * @author      Mautic
 *
 * @link        http://mautic.org
 *
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

namespace Mautic\LeadBundle\Form\Type;

use Mautic\CoreBundle\Factory\MauticFactory;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class WebPageListType.
 */
class WebPageListType extends AbstractType
{
    private $choices = [];

    /**
     * @param MauticFactory $factory
     */
    public function __construct(MauticFactory $factory)
    {
        $choices = $factory->getModel('lead.webpage')->getRepository()->getEntitiesForListType([
            'filter' => [
                'force' => [
                    [
                        'column' => 'web.businessgroup',
                        'expr'   => 'eq',
                        'value'  => $factory->getUser()->getBusinessGroup()->getId(),
                    ],
                ],
            ],
        ]);

        foreach ($choices as $choice) {
            $this->choices[$choice->getId()] = $choice->getName(true);
        }

        //sort by language
        ksort($this->choices);
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'choices'     => $this->choices,
            'empty_value' => false,
            'expanded'    => false,
            'multiple'    => true,
            'required'    => true,
            'empty_value' => 'mautic.core.form.chooseone',
        ]);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'webpage_list';
    }

    public function getParent()
    {
        return 'choice';
    }
}
