<h3><?php echo $title; ?></h3>
<?php echo $output; ?>

<?php $limit = 2 ?>

<div style="color:pink">
{exp:channel:entries limit="<?=$limit?>"}
{entry_id} {title} - {entry_date format="%y %D"}
{/exp:channel:entries}
</div>

