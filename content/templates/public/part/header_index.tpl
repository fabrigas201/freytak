<header class="header">
	<div class="header__images header__images_slides">
		{foreach $slides as $item}
		<div class="header__slide">
			<img src="{get_url($item -> cover -> path, $item -> cover -> name)}" alt="" class="header__slideImg">
		</div>
		{foreachelse}
		<div class="header__slide">
			<img src="{get_url('i/other/1.jpg')}" alt="" class="header__slideImg">
		</div>
		{/foreach}
	</div>
	<div class="container container_header">
		<div class="header__special arno">{__('topical_solution_for_business')}</div>
		<div class="container__row container__row_header">
			<div class="container__col header__logo">
			{include file=$logo}
			</div>
			{if count(the_tags('inIndex')) > 0}
			<div class="container__col header__services">
				<ul>
					{foreach the_tags('inIndex') as $page}
					<li class="header__service arno">
						<a href="{get_url(config('lang.weblang'), 'article', $page -> alias)}" class="header__serviceLink">{$page -> title}</a>
					</li>
					{/foreach}
				</ul>
			</div>
			{/if}
			<div class="clear"></div>
			<div class="signature"></div>
		</div>
	</div>

	<div class="header__nav">
		<div class="container container_header">
		{$topMenu}
		</div>
	</div>

	<div class="panel">
		<a href="{get_url(config('lang.weblang'), 'history')}" class="panel__text arno">{__('GALLERY_OF_RUSSIAN_LAW_OF_FAME')}</a>
	</div>

	<div class="header__folders">
		<div class="container">
			<a href="{get_url(config('lang.weblang'), 'page/specialnye-predlozheniya')}" class="header__folder linkTriangle linkTriangle_line arno">{__('industry_solutions')}</a>
			<a href="{get_url(config('lang.weblang'), 'articles/yuridicheskie-uslugi')}" class="header__folder linkTriangle linkTriangle_line arno">{__('practice')}</a>
		</div>
	</div>
</header>