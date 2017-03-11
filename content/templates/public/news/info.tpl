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

{if !empty($page)}
<div class="content">
	<div class="container container_inner">
		<div class="container__row">
			<div class="container__col text">
				<h2 class="title title_big title_post">
					<span class="title__text arno">{$page -> title}</span>
					<a href="javascript:history.go(-1)" class="title__link title__link_insert linkTriangle">{__('back_to_list')}</a>
				</h2>
				
				<div class="formated formated_news">
					
					{if isset($month)}{include file=$monthDate}{/if}
					
					<p><b>{$page -> title}</b></p>
					
					{if isset($cover)}
					<div class="image-gallery">
						<a id="thumb1" href="{get_url('i/news/')}/{$cover_big}" class="image highslide" onclick="return hs.expand(this)">
							<img src="{$cover}" alt="{$descr}" class="image__object"  />
						</a>
					</div>
					{/if}
					
					{if isset($page -> text)}
						{$page -> text}
					{/if}
					
					{if isset($page -> descrfull)}
						{$page -> descrfull}
					{/if}
					
					{if isset($page -> images)}
					<div class="gallery image-gallery">
					{foreach $page -> images as $images }
					{if $images -> isCover == 1}{continue}{/if}
					<a href="{get_url($images -> path)}/{$images -> name}" class="image highslide" onclick="return hs.expand(this)">
						<img src="{asset_cache($images -> name, ['width' => 232, 'height' => 171])}" alt="" class="image__object"/>
					</a>
					{/foreach}
					</div>
					{/if}
					<div class="clear clear_image"></div>
					
				</div>
				<div class="info__about">
					{if isset($page -> dateAdd) && $page -> dateAdd != '0000-00-00 00:00:00'}{$page -> dateAdd} года | {/if} {if isset($page -> for_smi)} <i>для "{$page -> for_smi}"</i>{/if}
				</div>
				
				<div class="news news_preview">
					<div class="container__row">
						{if !empty($prev_item)}
						<div class="post post_news container__col">
							<a href="{get_url(config('lang.weblang'), 'item', $prev_item -> alias)}" class="post__title arno">{$prev_item -> title}</a>
							<div class="post__text">
								<p>{$prev_item -> descr}</p>
							</div>
							<div class="linkWrap">
								<a href="{get_url(config('lang.weblang'), 'item', $prev_item -> alias)}" class="linkTriangle linkTriangle_line">
									<span class="linkTriangle__text">{__('previous_news')}</span>
									<span class="linkTriangle__line"></span>
								</a>
							</div>
							<span class="post__author info__about">
								{if isset($prev_item -> dateAdd) && $prev_item -> dateAdd != '0000-00-00 00:00:00'}{date_to($prev_item -> dateAdd)} года{/if} {if isset($prev_item -> for_smi)}| <i>для "{$prev_item -> for_smi}"</i>{/if}
							</span>
						</div>
						{/if}
						{if !empty($next_item)}
						<div class="post post_news container__col">
							<a href="{get_url(config('lang.weblang'), 'item', $next_item -> alias)}" class="post__title arno">{$next_item -> title}</a>
							<div class="post__text">
								<p>{$next_item -> descr}</p>
							</div>
							<div class="linkWrap">
								<a href="{get_url(config('lang.weblang'), 'item', $next_item -> alias)}" class="linkTriangle linkTriangle_line">
									<span class="linkTriangle__text">{__('next_news')}</span>
									<span class="linkTriangle__line"></span>
								</a>
							</div>
							<span class="post__author info__about">
								{if isset($next_item -> dateAdd) && $next_item -> dateAdd != '0000-00-00 00:00:00'}{date_to($next_item -> dateAdd)} года{/if} {if isset($next_item -> for_smi)}| <i>для "{$next_item -> for_smi}"</i>{/if}
							</span>
						</div>
						{/if}
					</div>
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
{/if}
{/block}