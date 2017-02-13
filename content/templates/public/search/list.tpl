{if $l.foundProducts}
	<p><b>Товары</b></p>

	{foreach from=$l.foundProducts item=item}
		<div class="contentItem">
			<a href="/?mod=catalog&id={$item.id}">{$item.name}</a>
		</div>
	{/foreach}
{/if}

{if $l.foundProducers}
	<p><b>Производители</b></p>

	{foreach from=$l.foundProducers item=item}
		<div class="contentItem">
			<a href="/?mod=catalog&producer_id={$item.id}">{$item.name}</a>
		</div>
	{/foreach}
{/if}

