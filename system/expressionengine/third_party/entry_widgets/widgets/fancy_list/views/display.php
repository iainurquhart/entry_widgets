<?php 
	$i = 0;
	$item_count = count($items);
	foreach ($items as $key => $item): 
?>
<?php if($i == 0):?>
	<div class="fancy_list has_<?=$item_count?>">
		<?php if($label):?>
			<h3><?=$label?></h3>
		<?php endif ?>
	<ul>
<?php endif; $i++; ?>
		<li class="item_<?=$i;?>"><?=$item['item'];?></li>
<?php if($i == $item_count):?>
	</ul>
</div>
<?php endif ?>
<?php endforeach ?>
