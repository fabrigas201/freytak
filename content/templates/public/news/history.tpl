{extends "../main.tpl"}
{block name="content"}
<div class="breadcrumbs hiddenMobile">
	<div class="container">
		<ul class="breadcrumbs__wrap">
			{foreach from=$breadcrumbs item=item key=key}
				<li class="breadcrumbs__item">{$item}</li>
			{/foreach}
		</ul>
	</div>
</div>

<div class="content">
	<div class="container container_inner">
		<div class="container__row">
			<div class="container__col text">
				<div class="history">
				{foreach $results as $v}
					<div class="history__item">
						<div class="title title_big">
							<span class="title__text arno">{$v -> title}</span>
							<a href="{get_url(config('lang.weblang'),'history', $v -> alias)}" class="title__link title__link_insert linkTriangle">подробнее</a>
						</div>

						{if isset($v -> cover -> name)}
						<div class="history__image">
							<div class="history__frame">
								<a href="{get_url(config('lang.weblang'),'history', $v -> alias)}" class="history__frameLink"></a>
								<img src="{asset_cache($v -> cover -> name, ['width' => 139, 'height' => 190, 'resize' => 'crop', 'pref' => 'history'])}" alt="{$v -> cover -> descr}" class="history__img">
							</div>
						</div>
						{/if}
						
						<div class="history__text quote">
						<p> {$v -> descr} </p>
						</div>
						<div class="clear"></div>
					</div>
				{/foreach}

				{if $pagesList}
				<div class="pagination">
					{$pagesList}
				</div>
				{/if}
				</div>
			</div>
			<div class="container__col menu menu_notSpace">
				{include file=$folders}
				{include file=$projects}
				{include file=$menu_calendar}
				{include file=$menu_calendar_future}
			</div>
			<div class="clear"></div>
		</div>
	</div>
</div>
{/block}