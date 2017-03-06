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
$view['slots']->set('mauticContent', 'webpage');
$userId = $form->vars['data']->getId();
if (!empty($userId)) {
    $user   = $form->vars['data']->getName();
    $header = $view['translator']->trans('mautic.webpages.header.edit', ['%name%' => $user]);
} else {
    $header = $view['translator']->trans('mautic.webpages.header.new');
}
$view['slots']->set('headerTitle', $header);
?>
<!-- start: box layout -->
<div class="box-layout">
    <!-- container -->
    <?php echo $view['form']->start($form); ?>
    <div class="col-md-12 bg-auto height-auto bdr-r">
        <div class="pa-md">
            <div class="form-group mb-0">
                <div class="row">
                    <div class="col-md-6<?php echo (count($form['name']->vars['errors'])) ? ' has-error' : ''; ?>">
                        <label class="control-label mb-xs"><?php echo $view['form']->label($form['name']); ?></label>
                        <?php echo $view['form']->widget($form['name'], ['attr' => ['placeholder' => $form['name']->vars['label']]]); ?>
                        <?php echo $view['form']->errors($form['name']); ?>
                    </div>
                </div>
            </div>

            <hr class="mnr-md mnl-md">

            <div class="form-group mb-0">
                    <div class="row">
                        <div class="col-md-6<?php echo (count($form['url']->vars['errors'])) ? ' has-error' : ''; ?>">
                                <label class="control-label mb-xs"><?php echo $view['form']->label($form['url']); ?></label>
                                <?php echo $view['form']->widget($form['url'], ['attr' => ['placeholder' => $form['url']->vars['label']]]); ?>
                                <?php echo $view['form']->errors($form['url']); ?>
                            </div>
                    </div>
            </div>

            <hr class="mnr-md mnl-md">
        </div>
    </div>
    <?php echo $view['form']->end($form); ?>
</div>