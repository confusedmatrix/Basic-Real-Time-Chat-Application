<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html>
<head>
  <title><?php echo $page('title'); ?></title>
  <meta name="description" content="<?php echo $page('meta_description'); ?>" />
  <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

  <?php echo $this->getCss(); ?>
    
  <link rel="icon" type="image/png" href="_images/graphics/favicon.png" />
</head>

<body id="<?php echo $page('body_id', 'default'); ?>">

  <div class="container">
  
    <div id="header" class="page-header">
      <h1><?php echo $page('h1'); ?></h1>
    </div>
        
    <br />