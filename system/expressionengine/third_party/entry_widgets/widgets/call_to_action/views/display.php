<aside class="call-to-action">
	{if cta_title}
		<h3>
			{if cta_url}<a href="{cta_url}">{/if}
				{cta_title}
			{if cta_url}</a>{/if}
		</h3>
	{/if}
	{if cta_text}
	<div class="call-to-action-text">
		{cta_text}
	</div>
	{/if}
	{if cta_url && cta_link_text}
		<p class="call-to-action-more"><a href="{cta_url}">{cta_link_text}</a></p>
	{/if}
</aside>