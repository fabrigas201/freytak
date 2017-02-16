<div class="header__langs">
{if isset($langs)}
	{foreach $langs as $ml name=langsCicle}
	<a href="{$ml.href}" class="header__lang {if $ml.lang == $segment}header__lang_active{/if}">{if $ml.lang == 'ru'}РУС{elseif $ml.lang == 'en'}ENG{/if}</a>
	{if !$smarty.foreach.langsCicle.last}/{/if}
	{/foreach}
{/if}
</div>
<a href="{get_url(config('lang.weblang'),'index_buro')}" class="logo arno">
	{__('freytak_and_sons')}
	<span class="logo__about logo__about_index">{__('corporation')}</span>
	<span class="logo__about logo__about_inner">{__('bureau_attorneys')}</span>
</a>
<div class="search__open">{__('search')}</div>