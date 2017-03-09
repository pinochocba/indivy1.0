<?php
/**
 * @copyright   2014 Mautic Contributors. All rights reserved
 * @author      Mautic
 *
 * @link        http://mautic.org
 *
 * @license     GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 */

namespace Mautic\LeadBundle\Controller;

use Mautic\CoreBundle\Controller\FormController;
use Mautic\CoreBundle\Helper\InputHelper;
use Mautic\LeadBundle\Entity\WebPage;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class WebPageController.
 */
class WebPageController extends FormController
{
    /**
     * @param int $page
     *
     * @return JsonResponse|Response
     */
    public function indexAction($page = 1)
    {
        //set some permissions
        $permissions = $this->get('mautic.security')->isGranted(
            [
                'lead:leads:viewother',
                'lead:leads:create',
                'lead:leads:editother',
                'lead:leads:deleteother',
            ],
            'RETURN_ARRAY'
        );

        if (!$permissions['lead:leads:viewother']) {
            return $this->accessDenied();
        }

        if ($this->request->getMethod() == 'POST') {
            $this->setListFilters();
        }

        //set limits
        $limit = $this->get('session')->get(
            'mautic.webpage.limit',
            $this->factory->getParameter('default_pagelimit')
        );
        $start = ($page === 1) ? 0 : (($page - 1) * $limit);
        if ($start < 0) {
            $start = 0;
        }

        $search = $this->request->get('search', $this->get('session')->get('mautic.webpage.filter', ''));
        $this->get('session')->set('mautic.webpage.filter', $search);

        $filter     = ['string' => $search, 'force' => []];
        $orderBy    = $this->get('session')->get('mautic.webpage.orderby', 'web.name');
        $orderByDir = $this->get('session')->get('mautic.webpage.orderbydir', 'ASC');


        $webpages = $this->getModel('lead.webpage')->getEntities(
            [
                'start'          => $start,
                'limit'          => $limit,
                'filter'         => $filter,
                'orderBy'        => $orderBy,
                'orderByDir'     => $orderByDir,
                'withTotalCount' => true,
            ]
        );


        //Check to see if the number of pages match the number of webpages
        $count = count($webpages);

        if ($count && $count < ($start + 1)) {
            $lastPage = ($count === 1) ? 1 : (ceil($count / $limit)) ?: 1;
            $this->get('session')->set('mautic.webpage.page', $lastPage);
            $returnUrl = $this->generateUrl('mautic_webpage_index', ['page' => $lastPage]);

            return $this->postActionRedirect(
                [
                    'returnUrl'       => $returnUrl,
                    'viewParameters'  => ['page' => $lastPage],
                    'contentTemplate' => 'MauticLeadBundle:WebPage:index',
                    'passthroughVars' => [
                        'activeLink'    => '#mautic_webpage_index',
                        'mauticContent' => 'webpage',
                    ],
                ]
            );
        }

        //set what page currently on so that we can return here after form submission/cancellation
        $this->get('session')->set('mautic.webpage.page', $page);

        $tmpl       = $this->request->isXmlHttpRequest() ? $this->request->get('tmpl', 'index') : 'index';
        $model      = $this->getModel('lead.webpage');

        // Get identifiers for webpages
        foreach ($webpages as $web) {
            $webpageIds[] = $web->getId();
        }

        $leadCounts = (!empty($webpageIds)) ? $model->getRepository()->getLeadCount($webpageIds) : [];

        return $this->delegateView(
            [
                'viewParameters' => [
                    'searchValue' => $search,
                    'leadCounts'  => $leadCounts,
                    'items'       => $webpages,
                    'page'        => $page,
                    'limit'       => $limit,
                    'permissions' => $permissions,
                    'tmpl'        => $tmpl,
                    'totalItems'  => $count,
                ],
                'contentTemplate' => 'MauticLeadBundle:WebPage:list.html.php',
                'passthroughVars' => [
                    'activeLink'    => '#mautic_webpage_index',
                    'mauticContent' => 'webpage',
                    'route'         => $this->generateUrl('mautic_webpage_index', ['page' => $page]),
                ],
            ]
        );
    }

    /**
     * Generates new form and processes post data.
     *
     * @param \Mautic\LeadBundle\Entity\WebPage $entity
     *
     * @return JsonResponse|\Symfony\Component\HttpFoundation\RedirectResponse|Response
     */
    public function newAction($entity = null)
    {

        if (!$this->get('mautic.security')->isGranted('lead:leads:create')) {
            return $this->accessDenied();
        }

        /** @var \Mautic\UserBundle\Model\WebPage $model */
        $model = $this->getModel('lead.webpage');

        //retrieve the user entity
        $web = $model->getEntity();

        //set the return URL for post actions
        $returnUrl = $this->generateUrl('mautic_webpage_index');

        //set the page we came from
        $page = $this->get('session')->get('mautic.webpage.page', 1);

        //get the user form factory
        $action = $this->generateUrl('mautic_webpage_action', ['objectAction' => 'new']);
        $form   = $model->createForm($web, $this->get('form.factory'), $action);

        //Check for a submitted form and process it
        if ($this->request->getMethod() == 'POST') {
            $valid = false;
            if (!$cancelled = $this->isFormCancelled($form)) {

                if ($valid = $this->isFormValid($form)) {
                    //form is valid so process the data

                    $web->setBusinessGroup($this->user->getBusinessGroup()->getId());
                    $model->saveEntity($web);

                    $this->addFlash('mautic.core.notice.created',  [
                        '%name%'      => $web->getName(),
                        '%menu_link%' => 'mautic_webpage_index',
                        '%url%'       => $this->generateUrl('mautic_webpage_action', [
                            'objectAction' => 'edit',
                            'objectId'     => $web->getId(),
                        ]),
                    ]);
                }
            }

            if ($cancelled || ($valid && $form->get('buttons')->get('save')->isClicked())) {
                return $this->postActionRedirect([
                    'returnUrl'       => $returnUrl,
                    'viewParameters'  => ['page' => $page],
                    'contentTemplate' => 'MauticLeadBundle:WebPage:index',
                    'passthroughVars' => [
                        'activeLink'    => '#mautic_webpage_index',
                        'mauticContent' => 'webpage',
                    ],
                ]);
            } elseif ($valid && !$cancelled) {
                return $this->editAction($web->getId(), true);
            }
        }

        return $this->delegateView([
            'viewParameters'  => ['form' => $form->createView()],
            'contentTemplate' => 'MauticLeadBundle:WebPage:form.html.php',
            'passthroughVars' => [
                'activeLink'    => '#mautic_webpage_new',
                'route'         => $action,
                'mauticContent' => 'webpage',
            ],
        ]);
    }

    /**
     * Generates edit form and processes post data.
     *
     * @param int  $objectId
     * @param bool $ignorePost
     *
     * @return \Symfony\Component\HttpFoundation\JsonResponse|\Symfony\Component\HttpFoundation\Response
     */
    public function editAction($objectId, $ignorePost = false)
    {
        if (!$this->get('mautic.security')->isGranted('lead:leads:edit')) {
            return $this->accessDenied();
        }
        $model = $this->getModel('lead.webpage');
        $web  = $model->getEntity($objectId);

        //set the page we came from
        $page = $this->get('session')->get('mautic.webpage.page', 1);

        //set the return URL
        $returnUrl = $this->generateUrl('mautic_webpage_index', ['page' => $page]);

        $postActionVars = [
            'returnUrl'       => $returnUrl,
            'viewParameters'  => ['page' => $page],
            'contentTemplate' => 'MauticLeadBundle:WebPage:index',
            'passthroughVars' => [
                'activeLink'    => '#mautic_webpage_index',
                'mauticContent' => 'webpage',
            ],
        ];

        //user not found
        if ($web === null) {
            return $this->postActionRedirect(
                array_merge($postActionVars, [
                    'flashes' => [
                        [
                            'type'    => 'error',
                            'msg'     => 'mautic.webpage.error.notfound',
                            'msgVars' => ['%id%' => $objectId],
                        ],
                    ],
                ])
            );
        } elseif ($model->isLocked($web)) {
            //deny access if the entity is locked
            return $this->isLocked($postActionVars, $web, 'lead.webpage');
        }

        $action = $this->generateUrl('mautic_webpage_action', ['objectAction' => 'edit', 'objectId' => $objectId]);
        $form   = $model->createForm($web, $this->get('form.factory'), $action);

        ///Check for a submitted form and process it
        if (!$ignorePost && $this->request->getMethod() == 'POST') {

            $valid = false;
            if (!$cancelled = $this->isFormCancelled($form)) {

                if ($valid = $this->isFormValid($form)) {
                    //form is valid so process the data

                    $model->saveEntity($web, $form->get('buttons')->get('save')->isClicked());

                    $this->addFlash('mautic.core.notice.updated',  [
                        '%name%'      => $web->getName(),
                        '%menu_link%' => 'mautic_webpage_index',
                        '%url%'       => $this->generateUrl('mautic_webpage_action', [
                            'objectAction' => 'edit',
                            'objectId'     => $web->getId(),
                        ]),
                    ]);
                }
            } else {
                //unlock the entity
                $model->unlockEntity($web);
            }

            if ($cancelled || ($valid && $form->get('buttons')->get('save')->isClicked())) {
                return $this->postActionRedirect($postActionVars);
            }
        } else {
            //lock the entity
            $model->lockEntity($web);
        }

        return $this->delegateView([
            'viewParameters'  => ['form' => $form->createView()],
            'contentTemplate' => 'MauticLeadBundle:WebPage:form.html.php',
            'passthroughVars' => [
                'activeLink'    => '#mautic_webpage_index',
                'route'         => $action,
                'mauticContent' => 'webpage',
            ],
        ]);
    }
}