<div class="menu__block">
	<div class="menu__title arno">{__('upcoming_events')}</div>
	{if $events|count}
		{foreach $events as $event}
		{if (int)strtotime($event -> eventDate|date_format:"%Y-%m-%d") >= (int)strtotime($smarty.now|date_format:"%Y-%m-%d") }
		<a href="{get_url('page')}/{$event -> alias}" class="post post_menu post_date">
			<span class="post__date">{$event -> eventDate|date_format:"%d/%m/%Y"}</span>
			<span class="post__title">{$event -> title}</span>
			<span class="post__content">{$event -> descr}</span>
		</a>
		{/if}
		{/foreach}
	{/if}
	<a href="javascript:void(0);" class="linkTriangle linkTriangle_menu calendar__open">
		<span class="linkTriangle__text">открыть календарь</span>
	</a>
</div>