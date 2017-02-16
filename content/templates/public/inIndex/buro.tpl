{extends "../main.tpl"}
{block name="content"}

<div class="content">
	<div class="container container_inner">
		<div class="container__row">
			<div class="container__col text">
				<div class="treatment">
					<h2 class="title">
						<span class="title__text arno">{__('message_from_the_managing_partner')}</span>
					</h2>
					<div class="container__row">
						<div class="treatment__image container__col">
							<img src="{get_url('assets/images/treatment.png')}" class="treatment__img" alt="">
						</div>
						<div class="treatment__text container__col quote quote_finish">
							{if !empty($treatment)}
								{$treatment->descrfull}
							{/if}
							<div class="signature"></div>
						</div>
						<div class="clear"></div>
					</div>
				</div>

				<div class="info">
					<h2 class="title">
						<span class="title__text arno">{__('information_centre')}</span>
						<a href="{get_url(config('lang.weblang'),'news/infocentr')}" class="title__link title__link_insert linkTriangle">{__('watch_the_news')}</a>
					</h2>

					<div class="container__row">
						<div class="container__col info__item">
							<a href="{get_url(config('lang.weblang'),'news/novosti-byuro')}" class="info__title arno">
								<img src="{get_url('assets/images/info/1.png')}" alt="" class="info__image">
								<span class="info__titleWrap">{__('news_bureau')}</span>
							</a>

						{if !empty($news_1) }
							{foreach $news_1 as $item}
							
							<div class="info__text">
								<p>{$item->descr}</p>
							</div>

							<div class="linkWrap">
								<a href="{get_url(config('lang.weblang'), 'item', $item->alias)}" class="linkTriangle linkTriangle_line">
									<span class="linkTriangle__text">{__('more')}</span>
									<span class="linkTriangle__line"></span>
								</a>
							</div>
							<div class="info__about">

							{if isset($item->dateAdd) && $item->dateAdd != '0000-00-00 00:00:00'}
								{date_to($item->dateAdd)} {__('years')}
							{/if}
							{if isset($item->for_smi)}
								| <i>для "{$item->for_smi}"</i>
							{/if}

							</div>
							{/foreach}
						{else}
							<b>{__('the_list_is_empty')}</b>
						{/if}

						</div>
						<div class="container__col info__item">
							<a href="/{config('lang.weblang')}/news/pravovye-novosti" class="info__title arno">
								<img src="/assets/images/info/2.png" alt="" class="info__image">
								<span class="info__titleWrap">{__('legal_news')}</span>
							</a>

						{if !empty($news_2) }
							{foreach $news_2 as $item}
							
							<div class="info__text">
								<p>{$item->descr}</p>
							</div>

							<div class="linkWrap">
								<a href="{get_url(config('lang.weblang'), 'item', $item->alias)}" class="linkTriangle linkTriangle_line">
									<span class="linkTriangle__text">{__('more')}</span>
									<span class="linkTriangle__line"></span>
								</a>
							</div>
							<div class="info__about">

							{if isset($item->dateAdd) && $item->dateAdd != '0000-00-00 00:00:00'}
								{date_to($item->dateAdd)} {__('years')}
							{/if}
							{if isset($item->for_smi)}
								| <i>для "{$item->for_smi}"</i>
							{/if}

							</div>
							{/foreach}
						{else}
							<b>{__('the_list_is_empty')}</b>
						{/if}

						</div>
						<div class="container__col info__item">
							<a href="{get_url(config('lang.weblang'),'articles/analitika')}" class="info__title arno">
								<img src="/assets/images/info/3.png" alt="" class="info__image">
								<span class="info__titleWrap">{__('analitika')}</span>
							</a>

						{if !empty($news_3) }
							{foreach $news_3 as $item}
							
							<div class="info__text">
								<p>{$item->descr}</p>
							</div>

							<div class="linkWrap">
								<a href="{get_url(config('lang.weblang'), 'article', $item->alias)}" class="linkTriangle linkTriangle_line">
									<span class="linkTriangle__text">{__('more')}</span>
									<span class="linkTriangle__line"></span>
								</a>
							</div>
							<div class="info__about">

							{if isset($item->dateAdd) && $item->dateAdd != '0000-00-00 00:00:00'}
								{date_to($item->dateAdd)} {__('years')}
							{/if}
							{if isset($item->for_smi)}
								| <i>для "{$item->for_smi}"</i>
							{/if}

							</div>
							{/foreach}
						{else}
							<b>{__('the_list_is_empty')}</b>
						{/if}
						</div>
						<div class="clear"></div>
					</div>
				</div>
			</div>
			<div class="container__col menu">
				{include file=$menu_calendar}
				{include file=$projects}
			</div>
			<div class="clear"></div>
		</div>
	</div>
	{include file=$assoc}
</div>
{/block}