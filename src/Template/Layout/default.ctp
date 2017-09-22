<?php
/**
 * Default layout.
 *
 * @var \OurSociety\View\AppView $this The view class.
 * @var \OurSociety\Model\Entity\User $currentUser The current user.
 */
$this->extend('base');

/**
 * Page content block.
 */
$this->start('page');
?>
    <?= $this->Breadcrumbs->render() ?>
    <?= $this->Flash->render(); ?>
    <?= $this->fetch('content'); ?>
<?php
$this->end();

/**
 * Navbar block.
 */
$this->start('navbar');
?>
    <nav class="navbar navbar-inverse navbar-static-top" role="navigation">
        <div class="container">
            <div class="navbar-header">
                <button type="button" class="navbar-toggle visible-xs collapsed" data-toggle="collapse" data-target="#navbar-top" aria-expanded="false">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <?= $this->Html->link(
                    $this->Html->image('banner.png', ['title' => 'OurSociety', 'alt' => 'Brand']),
                    ['_name' => 'home'],
                    ['class' => 'navbar-brand', 'escape' => false]
                ); ?>
            </div>
            <div class="collapse navbar-collapse" id="navbar-top">
                <div class="navbar-right text-muted">
                    <?php if ($this->get('currentUser')): ?>
                        <?= $this->cell('Navbar/User', [['style' => 'float: left; margin-top: 8px;']]) ?>
                        <?=''// $this->element('topbar'); ?>
                        <ul class="nav navbar-nav">
                            <li><?= $this->Html->link(__('Home'), ['_name' => 'home']) ?></li>
                            <li><?= $this->Html->link(__('Politicians'), ['_name' => 'politicians']) ?></li>
                            <li><?= $this->Html->dashboardLink($currentUser->role, __('Dashboard')) ?></li>
                            <li role="separator" class="divider"></li>
                            <li><?= $this->Html->link(__('Sign Out'), ['_name' => 'users:logout']) ?></li>
                        </ul>
                    <?php else: ?>
                        <ul class="nav navbar-nav">
                            <li><?= $this->Html->link(__('Home'), ['_name' => 'home']) ?></li>
                            <li><?= $this->Html->link(__('Politicians'), ['_name' => 'politicians']) ?></li>
                            <li><?= $this->Html->link(__('Sign In'), ['_name' => 'users:login']) ?></li>
                        </ul>
                    <?php endif ?>
                </div>
            </div>
        </div>
    </nav>
<?php
$this->end();

/**
 * Render layout.
 */
?>
<?= $this->fetch('navbar'); ?>
<div class="container">
    <div id="app" class="view">
        <?= $this->fetch('page') ?>
    </div>
    <?= $this->fetch('action_link_forms') // For CrudView delete links to work ?>
    <?= $this->element('footer') ?>
</div>
