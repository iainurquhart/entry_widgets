<aside class="content-quote">
	<blockquote>
		<?=$quote_text?>
		<?php if($who_said): ?>
			<cite>
				<span class="citee-name">
					<strong><?=$who_said?></strong><?php if($position): ?>, <?=$position?><?php endif ?>
				</span>
				<?php if($company && $url): ?>
					<a href="<?=$url?>" class="citee-url"><?=$company?></a>
				<?php elseif($company): ?>
					<span class="citee-company"><?=$company?></span>
				<?php endif ?>
			</cite>
		<?php endif ?>
	</blockquote>
</aside>