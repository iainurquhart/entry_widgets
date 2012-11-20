<?php 
	$i = 0;
	$item_count = count($items);
	foreach ($items as $key => $item): 
?>
<?php if($i == 0):?>
	<div class="fancy_list has_<?=$item_count?> <?=$type?>_list <?=$bullet_type?>_list <?php if($css_classes) echo ' '.$css_classes;?>">
		<?php if($label):?>
			<h3><?=$label?></h3>
		<?php endif ?>
	<ul>
<?php endif; $i++; ?>
		<li<?php if($i == 1):?> class="first_item"<?php elseif($i == $item_count):?> class="last_item"<?php endif ?>><?=$item['item'];?></li>
<?php if($i == $item_count):?>
	</ul>
</div>
<?php endif ?>
<?php endforeach ?>
