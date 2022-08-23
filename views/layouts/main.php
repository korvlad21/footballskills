<?php
/* @var $this \yii\web\View */
/* @var $content string */


use app\widgets\Alert;
use app\assets\AppAsset;
use yii\helpers\Url;
use yii\helpers\Html;
use yii\widgets\Breadcrumbs;
$this->title = "Панель управления сайтом: " . Yii::$app->name;
AppAsset::register($this);
$user = Yii::$app->user->identity;

?>

<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
 <meta charset="<?= Yii::$app->charset ?>">
 <meta http-equiv="X-UA-Compatible" content="IE=edge">
 <meta name="viewport" content="width=device-width, initial-scale=1">
 <?php $this->registerCsrfMetaTags() ?>
 <title><?= Html::encode($this->title) ?></title>


  <link rel="apple-touch-icon" sizes="180x180" href="/img/favicons/apple-touch-icon.png">
  <link rel="manifest" href="/img/favicons/site.manifest">
 <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
 <?php $this->head() ?>
</head>


<body class="hold-transition skin-blue sidebar-mini">
  <?php $this->beginBody() ?>
  <div class="wrapper">
      <span data-toggle="control-sidebar"></span>
    <!-- шапка -->
    <header class="main-header">
      <nav class="navbar navbar-static-top">

        <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
          <span class="sr-only">Toggle navigation</span>
        </a>

        <div class="site-name">
          <a href="/" target="_blank" title="Перейти на сайт в новом окне">
            <img class="site-favicon" src=""><span class="text-white"><?= Yii::$app->name?></span>
          </a>
        </div>

        <div class="navbar-custom-menu">
          <ul class="nav navbar-nav">


            <li class="dropdown user user-menu">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                <i class="fa fa-user"></i>
                <span class="fa_caption"><?= Yii::$app->user->identity->username?></span>
              </a>
              <ul class="dropdown-menu">
                <li class="user-neme"><?= Yii::$app->user->identity->username?></li>

                <li><?= Html::beginForm(['/site/logout'], 'post') . Html::submitButton('<i class="fa fa-sign-out"></i>Выйти', ['class' => '']) . Html::endForm() ;?></li>
              </ul>
            </li>
          </ul>
        </div>

      </nav>
    </header>
    <!-- шапка -->

    <?= $this->render('_menu')?>
    <div class="control-sidebar" id="control-slider-id">
        <a href="#" onclick="myModal.panel.close()" class="button_closePr" ><i class="fa  fa-close" style="font-size: 19px;"></i></a>
        <div id="control-info-modal" style="margin-top: -30px"></div>
    </div>

    <div class="content-wrapper">
      <div class="content">
        <?=
        Breadcrumbs::widget([
          'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ])
        ?>
        
        <?php 
        if( Yii::$app->session->hasFlash('success') ) : 
          Alert::begin(['options' => ['class' => 'alert-success']]);
          Yii::$app->session->getFlash('success');
          Alert::end();
        endif;
        if ( Yii::$app->session->hasFlash('warning') ) :
          Alert::begin(['options' => ['class' => 'alert-warning']]);
          Yii::$app->session->getFlash('warning');
          Alert::end();
        endif;
        if ( Yii::$app->session->hasFlash('error') ) :
          Alert::begin(['options' => ['class' => 'alert-error']]);
          Yii::$app->session->getFlash('error');
          Alert::end();
        endif;
        ?>
        <?= $content ?>
      </div>
    </div>
	<a  data-toggle="control-sidebar" id="openModal"></a>

  </div>
  <?= $this->render('_modal')?> 
  <?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
