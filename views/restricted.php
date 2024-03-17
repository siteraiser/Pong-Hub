<h1>Restricted Area</h1>

<div class="content">
<h2>Welcome to the RESTRICTED AREA!</h2>

<?php if($access_granted){ ?>
<p>
Access level <?php echo $access_level; ?> granted.
</p>
<?php if($access_level == 1){ ?>
<p>
You are cordially invited to join the Secret Discord Channel where there is a PHP Pong Server thread! <a href="https://discord.gg/vrGGCqpV">https://discord.gg/vrGGCqpV</a>
</p>

<?php }else if($access_level == 2) { ?>
<p>
You are cordially invited to join the <a href="https://discord.gg/mnySnYAb">Secret Discord Channel</a> where there is a PHP Pong Server thread and to join the new discord channel for <a href="https://discord.gg/mnySnYAb">Dero Pong Hub</a>! 
</p>
<?php }else{ ?>
<p>
You don't have access to this content. You can purchase an access link <a href="https://www.siteraiser.com/dero-pong-store/order/23">here</a>.
</p>
<?php } ?>


<?php } ?>
</div>
