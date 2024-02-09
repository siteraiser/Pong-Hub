<!DOCTYPE html>
<html>
<head>

<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name=viewport content="width=device-width, initial-scale=1">
<?php /*<link rel="shortcut icon" href="images/favicon.ico" type="image/x-icon" /> */ ?>

<?php echo (isset($meta) ? $meta : '');?>

<?php echo(isset($title) ? "<title>$title</title>" : '')?>

<?php echo(isset($description) ? "<meta name=\"description\" content=\"$description\"> ": '');?>					

<?php echo(isset($keywords) ? "<meta name=\"keywords\" content=\"$keywords\"> ": '');?>						



<style>
nav,main,footer{max-width:1400px;margin:auto;padding:10px;}
nav ul {list-style-type: none;margin:10px;padding:10px;}
nav li {display:inline-block;}
<?php if($this->path!='home'){ 
?>

<?php
}
?>

<?php if($this->path=='home'){ 
?>
.iaddress{border: 1px solid #888;padding:10px;}
<?php
}
?>


</style>

<body>


<nav>
	<ul>
		<li <?php $length=strlen($url=$this->base_url.'home'); echo(substr($this->base_url.$this->path,0,$length) == $url ? 'class="active"' : '');?>><a href="<?php echo $url;?>">Home</a></li>				
		<li <?php $length=strlen($url=$this->base_url.'info'); echo(substr($this->base_url.$this->path,0,$length) == $url ? 'class="active"' : '');?>><a href="<?php echo $url;?>">Info</a></li>
	</ul>	
</nav>
<main>