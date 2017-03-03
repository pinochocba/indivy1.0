<?php
/**
 * @copyright   2014 Mautic Contributors. All rights reserved
 * @author      Mautic
 *
 * @link        http://mautic.org
 *
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */
$view->extend('MauticCoreBundle:Default:content.html.php');
$view['slots']->set('mauticContent', 'businessgroup');
$view['slots']->set('headerTitle', $view['translator']->trans('mautic.user.businessgroup'));

$view['slots']->set('actions', $view->render('MauticCoreBundle:Helper:page_actions.html.php', [
    'templateButtons' => [
        'new' => $permissions['create'],
    ],
    'routeBase' => 'businessgroup',
    'langVar'   => 'user.businessgroup',
]));
?>

<?php echo $view->render('MauticCoreBundle:Helper:list_toolbar.html.php', [
    'searchValue'     => $searchValue,
    'searchHelp'      => 'mautic.user.role.help.searchcommands',
    'action'          => $currentRoute,
    'langVar'         => 'user.businessgroup',
    'routeBase'       => 'businessgroup',
    'templateButtons' => [
        'delete' => $permissions['delete'],
    ],
]); ?>

<div class="page-list">
    <?php $view['slots']->output('_content'); ?>
</div>
