{if has_items}
	{if label}
		<h3>{label}</h3>
	{/if}
	{items}
		{if item_count == 1}<ul class="fancy_list {bullet_type} {css_classes} {type}">{/if}
		<li>{item}</li>
		{if item_count == total_items}</ul>{/if}
	{/items}
{/if}

