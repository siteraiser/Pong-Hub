<h1>Restricted Area</h1>

<div class="content">
<h2>Welcome to the RESTRICTED AREA!</h2>

<?php if($access_granted){ ?>
<p>
Access level <?php echo $access_level; ?> granted.
</p>
<p>
You are cordially invited to join the Discord Channel for Dero Pong Hub! <a href="https://discord.gg/mnySnYAb">https://discord.gg/mnySnYAb</a>
</p>
<?php }else{?>
<p>
You don't have access to this content. Check the store to purchase an access link.
</p>
<?php }?>
</div>