<blockquote class="content-quote">
	{quote_text}
	{if who_said}
		<cite>
			<span class="citee-name">
				<strong>{who_said}</strong>{if position}, {position}{/if}
			</span>
			{if company && url}
				<a href="{url}" class="citee-url">{company}</a>
			{if:elseif company}
				<span class="citee-company">{company}</span>
			{/if}
		</cite>
	{/if}
</blockquote>