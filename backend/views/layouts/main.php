<?php

use backend\assets\AppAsset;
use yii\helpers\Html;
use yii\widgets\Breadcrumbs;
use yii\helpers\Url;

/* @var $this \yii\web\View */
/* @var $content string */

$bundle = AppAsset::register($this);

?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<!--[if IE 8]> <html lang="<?= Yii::$app->language ?>" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="<?= Yii::$app->language ?>" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="<?= Yii::$app->language ?>" dir="rtl">
<!--<![endif]-->
    <head>
        <meta charset="<?= Yii::$app->charset ?>">
        <meta content="width=device-width, initial-scale=1" name="viewport" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="robots" content="noindex,nofollow"/>
        <link rel="icon" href="<?= Yii::$app->params['staticUrl'] . '/static-images/logo2.png' ?>" type="image/x-icon"/>
        <?= Html::csrfMetaTags() ?>
        <title><?= Html::encode($this->title) ?></title>
        <?= Html::cssFile(Yii::$app->params['staticUrl'].'/backend/styles/all.css?v=' . filemtime(Yii::getAlias('@static/backend/styles/all.css'))) ?>
        <?php $this->head() ?>
    </head>
    <body class="page-container-bg-solid page-header-menu-fixed page-boxed page-md">
        <?php $this->beginBody() ?>
        <div class="page-header">
            <div class="page-header-menu">
                <div class="container-fluid">
                    <div class="hor-menu ">
                        <ul class="nav navbar-nav">
                            <li>
                                <a href="<?= Url::to(Yii::$app->homeUrl); ?>">
                                    <img src="<?= Yii::$app->params['staticUrl']; ?>/static-images/logo.png" alt="Navar" width="15"/>
                                </a>
                            </li>
                            <?php if (Yii::$app->user->isGuest): ?>
                            <li>
                                <a href="<?= Url::to(['/auth/login']) ?>">
                                    <?= Yii::t('app', 'Login'); ?>
                                </a>
                            </li>
                            <?php endif; ?>
                            <li class="menu-dropdown mega-menu-dropdown mega-menu-full ">
                                <a data-hover="megamenu-dropdown" data-close-others="true" data-toggle="dropdown" href="javascript:;" class="dropdown-toggle">
                                    <?= Yii::t('app', 'Administrator') ?> <i class="fa fa-angle-down"></i>
                                </a>
                                <ul class="dropdown-menu">
                                    <li>
                                        <div class="mega-menu-content">
                                            <div class="row">
                                                <?php if (Yii::$app->getUser()->can('AdminUser')): ?>
                                                <div class="col-md-3">
                                                    <ul class="mega-menu-submenu">
                                                        <li>
                                                            <h3><?= Yii::t('app', 'Users') ?></h3>
                                                        </li>
                                                        <?php if (Yii::$app->getUser()->can('AdminUserList')): ?>
                                                        <li>
                                                            <a href="<?= Url::to(['/admin/index']); ?>">
                                                                <i class="fa fa-users"></i> <?= Yii::t('app', 'Admin List') ?>
                                                            </a>
                                                        </li>
                                                        <?php endif; ?>
                                                        <?php if (Yii::$app->getUser()->can('UserList')): ?>
                                                            <li>
                                                                <a href="<?= Url::to(['/user/index']); ?>">
                                                                    <i class="fa fa-users"></i> <?= Yii::t('app', 'User List') ?>
                                                                </a>
                                                            </li>
                                                        <?php endif; ?>
                                                    </ul>
                                                </div>
                                                <?php endif; ?>
                                                <?php if (Yii::$app->getUser()->can('Admin')): ?>
                                                <div class="col-md-3">
                                                    <ul class="mega-menu-submenu">
                                                        <li>
                                                            <h3><?= Yii::t('app', 'Configuration') ?></h3>
                                                        </li>
                                                        <?php if (Yii::$app->getUser()->can('Logs')): ?>
                                                        <li>
                                                            <a href="<?= Url::to(['/log/index']) ?>">
                                                                <i class="fa fa-history"></i> <?= Yii::t('app', 'Logs'); ?>
                                                            </a>
                                                        </li>
                                                        <?php endif; ?>
                                                        <?php if (Yii::$app->getUser()->can('Activity')): ?>
                                                        <li>
                                                            <a href="<?= Url::to(['/activity/index']) ?>">
                                                                <i class="fa fa-history"></i> <?= Yii::t('app', 'User Activity'); ?>
                                                            </a>
                                                        </li>
                                                        <?php endif; ?>
                                                    </ul>
                                                </div>
                                                <?php endif; ?>
                                                <?php if (Yii::$app->getUser()->can('Roles')): ?>
                                                <div class="col-md-3">
                                                    <ul class="mega-menu-submenu">
                                                        <li>
                                                            <h3><?= Yii::t('app', 'Administrator Roles and Permission') ?></h3>
                                                        </li>
                                                        <?php foreach (Yii::$app->getModule('roles')->getMenus() as $role): ?>
                                                            <li><a href="<?= Url::to($role['url']); ?>"><?= Html::encode($role['label']) ?></a></li>
                                                        <?php endforeach; ?>
                                                    </ul>
                                                </div>
                                                <?php endif; ?>
                                            </div>
                                            <div class="row">
                                                <?php if (Yii::$app->getUser()->can('Navar')): ?>
                                                <div class="col-md-3">
                                                    <ul class="mega-menu-submenu">
                                                        <li>
                                                            <h3><?= Yii::t('app', 'Navar') ?></h3>
                                                        </li>
                                                        <?php if (Yii::$app->getUser()->can('UploadNavar')): ?>
                                                        <li>
                                                            <a href="<?= Url::to(['/navar/upload']); ?>">
                                                                <i class="fa fa-gamepad"></i> <?= Yii::t('app', 'Upload new version') ?>
                                                            </a>
                                                        </li>
                                                        <?php endif; ?>
                                                    </ul>
                                                </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </li>
                            <?php if (Yii::$app->getUser()->can('MusicAdmin')): ?>
                                <li class="menu-dropdown classic-menu-dropdown ">
                                    <a data-hover="megamenu-dropdown" data-close-others="true" data-toggle="dropdown" href="javascript:;">
                                        <i class="fa fa-music"></i> <?= Yii::t('app', 'Musics'); ?> <i class="fa fa-angle-down"></i>
                                    </a>
                                    <ul class="dropdown-menu pull-left">
                                        <?php if (Yii::$app->getUser()->can('MusicAdd')): ?>
                                            <li>
                                                <a href="<?= Url::to(['/music/add']) ?>">
                                                    <i class="fa fa-plus"></i> <?= Yii::t('app', 'Add Music') ?></a>
                                            </li>
                                        <?php endif; ?>
                                        <?php if (Yii::$app->getUser()->can('MusicList')): ?>
                                            <li>
                                                <a href="<?= Url::to(['/music/index']) ?>">
                                                    <i class="fa fa-list"></i> <?= Yii::t('app', 'Music list') ?></a>
                                            </li>
                                        <?php endif; ?>
                                        <?php if (Yii::$app->getUser()->can('MusicUpdate')): ?>
                                            <li>
                                                <a href="<?= Url::to(['/music/zip']) ?>">
                                                    <i class="fa fa-list"></i> <?= Yii::t('app', 'Music zip') ?></a>
                                            </li>
                                        <?php endif; ?>
                                    </ul>
                                </li>
                            <?php endif; ?>
                            <?php if (Yii::$app->getUser()->can('ArtistAdmin')): ?>
                                <li class="menu-dropdown classic-menu-dropdown ">
                                    <a data-hover="megamenu-dropdown" data-close-others="true" data-toggle="dropdown" href="javascript:;">
                                        <i class="fa fa-user"></i> <?= Yii::t('app', 'Artists'); ?> <i class="fa fa-angle-down"></i>
                                    </a>
                                    <ul class="dropdown-menu pull-left">
                                        <?php if (Yii::$app->getUser()->can('ArtistAdd')): ?>
                                            <li>
                                                <a href="<?= Url::to(['/artist/add']) ?>">
                                                    <i class="fa fa-plus"></i> <?= Yii::t('app', 'Add Artist') ?></a>
                                            </li>
                                        <?php endif; ?>
                                        <?php if (Yii::$app->getUser()->can('ArtistList')): ?>
                                            <li>
                                                <a href="<?= Url::to(['/artist/index']) ?>">
                                                    <i class="fa fa-list"></i> <?= Yii::t('app', 'Artist list') ?></a>
                                            </li>
                                        <?php endif; ?>
                                    </ul>
                                </li>
                            <?php endif; ?>
                            <?php if (Yii::$app->getUser()->can('CommentAdmin')): ?>
                                <li class="menu-dropdown classic-menu-dropdown ">
                                    <a data-hover="megamenu-dropdown" data-close-others="true" data-toggle="dropdown" href="javascript:;">
                                        <i class="fa fa-comment"></i> <?= Yii::t('app', 'Comments'); ?> <i class="fa fa-angle-down"></i>
                                    </a>
                                    <ul class="dropdown-menu pull-left">
                                        <?php if (Yii::$app->getUser()->can('CommentList')): ?>
                                            <li>
                                                <a href="<?= Url::to(['/comment/index']) ?>">
                                                    <i class="fa fa-list"></i> <?= Yii::t('app', 'Comment list') ?></a>
                                            </li>
                                        <?php endif; ?>
                                    </ul>
                                </li>
                            <?php endif; ?>
                            <?php if (Yii::$app->getUser()->can('SpecialAdmin')): ?>
                                <li class="menu-dropdown classic-menu-dropdown ">
                                    <a data-hover="megamenu-dropdown" data-close-others="true" data-toggle="dropdown" href="javascript:;">
                                        <i class="fa fa-star"></i> <?= Yii::t('app', 'Specials'); ?> <i class="fa fa-angle-down"></i>
                                    </a>
                                    <ul class="dropdown-menu pull-left">
                                        <?php if (Yii::$app->getUser()->can('SpecialAdd')): ?>
                                            <li>
                                                <a href="<?= Url::to(['/special/add']) ?>">
                                                    <i class="fa fa-plus"></i> <?= Yii::t('app', 'Add Special') ?></a>
                                            </li>
                                        <?php endif; ?>
                                        <?php if (Yii::$app->getUser()->can('SpecialList')): ?>
                                            <li>
                                                <a href="<?= Url::to(['/special/index']) ?>">
                                                    <i class="fa fa-list"></i> <?= Yii::t('app', 'Special list') ?></a>
                                            </li>
                                        <?php endif; ?>
                                    </ul>
                                </li>
                            <?php endif; ?>
                            <?php if (Yii::$app->getUser()->can('PlaylistAdmin')): ?>
                                <li class="menu-dropdown classic-menu-dropdown ">
                                    <a data-hover="megamenu-dropdown" data-close-others="true" data-toggle="dropdown" href="javascript:;">
                                        <i class="fa fa-list-ol"></i> <?= Yii::t('app', 'Playlists'); ?> <i class="fa fa-angle-down"></i>
                                    </a>
                                    <ul class="dropdown-menu pull-left">
                                        <?php if (Yii::$app->getUser()->can('MoodAdd')): ?>
                                            <li>
                                                <a href="<?= Url::to(['/mood/add']) ?>">
                                                    <i class="fa fa-plus"></i> <?= Yii::t('app', 'Add Mood') ?></a>
                                            </li>
                                        <?php endif; ?>
                                        <?php if (Yii::$app->getUser()->can('MoodList')): ?>
                                            <li>
                                                <a href="<?= Url::to(['/mood/index']) ?>">
                                                    <i class="fa fa-list"></i> <?= Yii::t('app', 'Mood list') ?></a>
                                            </li>
                                        <?php endif; ?>
                                        <br>
                                        <?php if (Yii::$app->getUser()->can('PlaylistAdd')): ?>
                                            <li>
                                                <a href="<?= Url::to(['/playlist/add']) ?>">
                                                    <i class="fa fa-plus"></i> <?= Yii::t('app', 'Add playlist') ?></a>
                                            </li>
                                        <?php endif; ?>
                                        <?php if (Yii::$app->getUser()->can('PlaylistList')): ?>
                                            <li>
                                                <a href="<?= Url::to(['/playlist/index']) ?>">
                                                    <i class="fa fa-list"></i> <?= Yii::t('app', 'Playlist list') ?></a>
                                            </li>
                                        <?php endif; ?>
                                        <br>
                                        <?php if (Yii::$app->getUser()->can('PlaylistMusicAdd')): ?>
                                            <li>
                                                <a href="<?= Url::to(['/playlist-music/add']) ?>">
                                                    <i class="fa fa-plus"></i> <?= Yii::t('app', 'Add Music to playlist') ?></a>
                                            </li>
                                        <?php endif; ?>
                                        <?php if (Yii::$app->getUser()->can('PlaylistMusicList')): ?>
                                            <li>
                                                <a href="<?= Url::to(['/playlist-music/index']) ?>">
                                                    <i class="fa fa-list"></i> <?= Yii::t('app', 'Playlist music list') ?></a>
                                            </li>
                                        <?php endif; ?>
                                    </ul>
                                </li>
                            <?php endif; ?>
                            <?php if (Yii::$app->getUser()->can('LikeAdmin')): ?>
                                <li class="menu-dropdown classic-menu-dropdown ">
                                    <a data-hover="megamenu-dropdown" data-close-others="true" data-toggle="dropdown" href="javascript:;">
                                        <i class=""></i> <?= Yii::t('app', 'Other'); ?> <i class="fa fa-angle-down"></i>
                                    </a>
                                    <ul class="dropdown-menu pull-left">
                                        <?php if (Yii::$app->getUser()->can('CommentList')): ?>
                                            <li>
                                                <a href="<?= Url::to(['/contact/index']) ?>">
                                                    <i class="fa fa-list"></i> <?= Yii::t('app', 'Contact list') ?></a>
                                            </li>
                                        <?php endif; ?>
                                        <?php if (Yii::$app->getUser()->can('SpecialList')): ?>
                                            <li>
                                                <a href="<?= Url::to(['/like/index']) ?>">
                                                    <i class="fa fa-list"></i> <?= Yii::t('app', 'Like list') ?></a>
                                            </li>
                                        <?php endif; ?>
                                        <?php if (Yii::$app->getUser()->can('TagAdd')): ?>
                                            <li>
                                                <a href="<?= Url::to(['/tag/add']) ?>">
                                                    <i class="fa fa-plus"></i> <?= Yii::t('app', 'Add Tag') ?></a>
                                            </li>
                                        <?php endif; ?>
                                        <?php if (Yii::$app->getUser()->can('TagList')): ?>
                                            <li>
                                                <a href="<?= Url::to(['/tag/index']) ?>">
                                                    <i class="fa fa-list"></i> <?= Yii::t('app', 'Tag list') ?></a>
                                            </li>
                                        <?php endif; ?>
                                    </ul>
                                </li>
                            <?php endif; ?>
                            <?php if (!Yii::$app->user->isGuest): ?>
                            <li>
                                <a href="<?= Url::to(['/admin/change-password']) ?>">
                                    <i class="fa fa-lock"></i> <?= Yii::t('app', 'Change password') ?></a>
                            </li>
                            <li>
                                <a href="<?= Url::to(['/auth/logout']) ?>" data-method="post">
                                    <i class="fa fa-key"></i> <?= Yii::t('app', 'Logout') ?></a>
                            </li>
                            <?php endif; ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        <div class="page-container">
            <div class="page-head">
                <div class="container-fluid">
                    <!-- <div class="page-title">
                        <h1><?php //Html::encode($this->title) ?>Dashboard <small>statistics & reports</small></h1>
                    </div> -->
                </div>
            </div>
            <div class="page-content">
                <div class="container-fluid">
                    <?= Breadcrumbs::widget([
                        'options' => ['class' => 'page-breadcrumb breadcrumb'],
                        'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
                        'itemTemplate' => '<li>{link} <i class="fa fa-angle-left"></i></li>',
                        'homeLink' => [
                            'label' => Yii::t('app', 'Home'),
                            'url' => ['/admin/index'],
                        ]
                    ]) ?>
                    <div id="pjax-loader-container" class="margin-top-10">
                        <?= $content ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="page-footer">
            <div class="container-fluid">
                <?= Yii::$app->getFormatter()->asDate(time(), 'php:Y'); ?> &copy; <?= Yii::t('app', 'Navar Version {version}', ['version' => Yii::$app->version]); ?>
            </div>
        </div>
        <div class="scroll-to-top">
            <i class="icon-arrow-up"></i>
        </div>
        <?= Html::jsFile(Yii::$app->params['staticUrl'] . '/backend/scripts/all.js?v=' . filemtime(Yii::getAlias('@static/backend/scripts/all.js'))) ?>
        <?php $this->endBody() ?>
    </body>
</html>
<?php $this->endPage() ?>