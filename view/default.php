<!DOCTYPE html>
<html lang="en">
<head>
    <title><?= ( !empty( $title ) ? $title  : $site_title ) ?></title>

    <meta http-equiv="X-UA-Compatible" content="IE=Edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?= $site_name ?> - This is only a test">


    <?= $this->asset_retrieve( REQ_ASSET_CSS ); ?>
    <?= $this->asset_retrieve( REQ_ASSET_JS_GLOBAL ); ?>
    <?= $this->asset_retrieve( REQ_ASSET_JS ); ?>

</head>
<body>

    <?php include( 'default_head.php' ); ?>

    <?php include( $template ); ?>

    <?php include( 'default_foot.php' ); ?>

</body>
</html>