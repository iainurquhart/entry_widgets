{exp:entry_widgets:prep_vars var_prefix="nested_" parse="inward"}
	{exp:channel:entries 
		channel="<?=$channel_name?>" 
		limit="<?=$limit?>" 
		dynamic="no"
		show_future_entries="<?=$show_future_entries?>"
	}
		<h3><a href="{nested_comment_url_title_auto_path}">{nested_title} {nested_count}</a></h3>
	{/exp:channel:entries}
{/exp:entry_widgets:prep_vars}