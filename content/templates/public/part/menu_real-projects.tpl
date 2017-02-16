{if $smarty.server.REQUEST_URI != "{config('lang.weblang')}/article/o-byuro_1"}
{if $projectsData|count > 0}
<div class="menu__block realProjects">
	<div class="menu__title arno">{__('our_Projects')}</div>
	{foreach $projectsData as $project}
	<a href="{get_url(config('lang.weblang'), 'article', $project -> alias)}" class="post post_menu">
		<span class="post__title">{$project -> title}</span>
	</a>
	{/foreach}
	<a href="/{config('lang.weblang')}/article/nashi-proekty" class="linkTriangle linkTriangle_menu">
		<span class="linkTriangle__text">{__('more')}</span>
	</a>
</div>
{/if}
{/if}