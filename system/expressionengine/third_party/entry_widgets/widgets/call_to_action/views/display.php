<aside class="call-to-action<?php if($cta_title =='') : ?> no_title<?php endif ?><?php if($cta_text =='') : ?> no_text<?php endif ?>">
<?php if($cta_title) : ?>
	<?php if($cta_url) : ?>
		<h3><a href="<?=$cta_url?>"><?=$cta_title?></a></h3>
	<?php else : ?>
		<h3><?=$cta_title?></h3>
	<?php endif ?>
<?php endif ?>
	<?php if($cta_text) : ?>
	<div class="call-to-action-text">
		<?=$cta_text?>
	</div>
	<?php endif ?>
	<?php if($cta_url) : ?>
		<p class="call-to-action-more"><a href="<?=$cta_url?>" class="button"><?=$cta_link_text?></a></p>
	<?php endif ?>
</aside>