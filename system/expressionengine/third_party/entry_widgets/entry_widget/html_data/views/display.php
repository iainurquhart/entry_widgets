<h3><?php echo $title; ?></h3>
<?php echo $output; ?>


<div style="color:pink">
{exp:channel:entries limit="1"}
{title} - {entry_date format="%y %D"}
{/exp:channel:entries}
</div>

