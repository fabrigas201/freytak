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
				<h2 class="title title_big title_news">
					<span class="title__text arno">{$title}</span>
				</h2>

				<div class="news">

					{foreach $result as $v}
					
					{if in_array($v -> mod, ['news'])}
						{assign var="mod" value="item"}
					{elseif in_array($v -> mod, ['pages'])}
						{assign var="mod" value="article"}
					{elseif in_array($v -> mod, ['history'])}
						{assign var="mod" value="history"}
					{else}
						{assign var="mod" value="news"}
					{/if}
					
					
					<div class="post post_news">
						<a href="{get_url(config('lang.weblang'), $mod, $v -> alias)}" class="post__title arno">{$v -> title}</a>
						<div class="post__text">
							<p>{$v -> descr}</p>
						</div>
						<div class="linkWrap">
							<a href="{get_url(config('lang.weblang'), $mod, $v -> alias)}" class="linkTriangle linkTriangle_line">
								<span class="linkTriangle__text">{__('more')}</span>
								<span class="linkTriangle__line"></span>
							</a>
						</div>
						<span class="post__author info__about">
							{if isset($v -> dateAdd) && $v -> dateAdd != '0000-00-00 00:00:00'}{$v -> dateAdd} {__('years')} | {/if} {if isset($v -> for_smi)} <i>для "{$v -> for_smi}"</i>{/if}
						</span>
					</div>
					{foreachelse}
						<b>{__('the_list_is_empty')}</b>
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