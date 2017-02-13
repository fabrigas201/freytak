{if $smarty.server.REQUEST_URI != "{config('lang.weblang')}/article/o-byuro_1"}

<div class="menu__block realProjects">
	<div class="menu__title arno">{__('our_Projects')}</div>
	
	<a href="/{config('lang.weblang')}/article/nashi-proekty" class="post post_menu">
		<span class="post__title">{__('our_Projects_1')}</span>
	</a>
	<a href="/{config('lang.weblang')}/article/nashi-proekty#proj2" class="post post_menu">
		<span class="post__title">{__('our_Projects_2')}</span>
	</a>
	<a href="/{config('lang.weblang')}/article/nashi-proekty#proj3" class="post post_menu post_last">
		<span class="post__title">{__('our_Projects_3')}</span>
	</a>

	<a href="/{config('lang.weblang')}/article/nashi-proekty" class="linkTriangle linkTriangle_menu">
		<span class="linkTriangle__text">{__('more')}</span>
	</a>
</div>
{/if}