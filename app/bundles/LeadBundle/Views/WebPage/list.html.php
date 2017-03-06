<?php
/**
 * @copyright   2014 Mautic Contributors. All rights reserved
 * @author      Mautic
 *
 * @link        http://mautic.org
 *
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */
if ($tmpl == 'index') {
    $view->extend('MauticLeadBundle:WebPage:index.html.php');
}
?>

<?php if (count($items)): ?>
    <div class="table-responsive page-list">
        <table class="table table-hover table-striped table-bordered webpage-list" id="webpageTable">
            <thead>
            <tr>
                <?php
                echo $view->render(
                    'MauticCoreBundle:Helper:tableheader.html.php',
                    [
                        'checkall' => 'true',
                        'target'   => '#webpageTable',
                    ]
                );

                echo $view->render(
                    'MauticCoreBundle:Helper:tableheader.html.php',
                    [
                        'sessionVar' => 'webpage',
                        'text'       => 'mautic.webpage.name',
                        'class'      => 'col-company-name',
                        'orderBy'    => 'web.name',
                    ]
                );
                echo $view->render(
                    'MauticCoreBundle:Helper:tableheader.html.php',
                    [
                        'sessionVar' => 'webpage',
                        'text'       => 'mautic.webpage.url',
                        'class'      => 'visible-md visible-lg col-company-website',
                        'orderBy'    => 'web.url',
                    ]
                );
                echo $view->render('MauticCoreBundle:Helper:tableheader.html.php', [
                    'sessionVar' => 'webpage',
                    'text'       => 'mautic.lead.list.thead.leadcount',
                    'class'      => 'visible-md visible-lg col-leadlist-leadcount',
                ]);
                echo $view->render(
                    'MauticCoreBundle:Helper:tableheader.html.php',
                    [
                        'sessionVar' => 'webpage',
                        'orderBy'    => 'web.id',
                        'text'       => 'mautic.core.id',
                        'class'      => 'visible-md visible-lg col-company-id',
                    ]
                );
                ?>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($items as $item): ?>
                <tr>
                    <td>
                        <?php
                        echo $view->render(
                            'MauticCoreBundle:Helper:list_actions.html.php',
                            [
                                'item'            => $item,
                                'templateButtons' => [
                                    'edit'   => $permissions['lead:leads:editother'],
                                    'clone'  => $permissions['lead:leads:create'],
                                    'delete' => $permissions['lead:leads:deleteother'],
                                ],
                                'routeBase' => 'webpage',
                            ]
                        );
                        ?>
                    </td>
                    <td>
                        <div>

                            <a href="<?php echo $view['router']->generate(
                                'mautic_webpage_action',
                                ['objectAction' => 'edit', 'objectId' => $item->getId()]
                            ); ?>" data-toggle="ajax">
                                <?php echo $item->getName(); ?>
                            </a>
                        </div>
                    </td>
                    <td class="visible-md visible-lg">
                        <?php echo $item->getUrl(); ?>
                    </td>
                    <td class="visible-md visible-lg">
                        <a class="label label-primary" href="<?php echo $view['router']->path('mautic_contact_index', ['search' => $view['translator']->trans('mautic.lead.lead.searchcommand.webpage').':'. chr(34) . $item->getName() . chr(34)]); ?>" data-toggle="ajax"<?php echo ($leadCounts[$item->getId()] == 0) ? 'disabled=disabled' : ''; ?>>
                            <?php echo $view['translator']->transChoice('mautic.lead.company.viewleads_count', $leadCounts[$item->getId()], ['%count%' => $leadCounts[$item->getId()]]); ?>
                        </a>
                    </td>
                    <td class="visible-md visible-lg"><?php echo $item->getId(); ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <div class="panel-footer">
        <?php echo $view->render(
            'MauticCoreBundle:Helper:pagination.html.php',
            [
                'totalItems' => $totalItems,
                'page'       => $page,
                'limit'      => $limit,
                'menuLinkId' => 'mautic_webpage_index',
                'baseUrl'    => $view['router']->generate('mautic_webpage_index'),
                'sessionVar' => 'webpage',
            ]
        ); ?>
    </div>
<?php else: ?>
    <?php echo $view->render(
        'MauticCoreBundle:Helper:noresults.html.php',
        ['tip' => 'mautic.company.action.noresults.tip']
    ); ?>
<?php endif; ?>
