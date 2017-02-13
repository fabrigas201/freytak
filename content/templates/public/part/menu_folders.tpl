{if $f_name != '' }
<div class="menu__block siteFolders">
	<div class="menu__title arno">разделы {$f_name}</div>
	<ul>
	{foreach $f_links as $item}
		<li class="siteFolders__item {if $smarty.server.REQUEST_URI == $item.uri}siteFolders__item_active {/if}post">
			<a href="{$item.href}" class="siteFolders__link">{$item.title} </a>
		</li>
	{/foreach}

	</ul>
{if $smarty.server.REQUEST_URI == "{config('lang.weblang')}/news/pravovye-novosti" }
	<div class="siteFolders__subscribe">
		<a href="{get_url(config('lang.weblang'), 'page/podpiska-na-pravovye-novosti')}" class="linkTriangle linkTriangle_menu">
			Подписаться на правовые новости
		</a>
	</div>
{/if}

</div>
{/if}