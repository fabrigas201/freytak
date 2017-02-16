<header class="header header_inner">
	<div class="header__images header__images_widen">

	{if $smarty.server.REQUEST_URI != "{config('lang.weblang')}/index_buro"}
		<img src="{get_url('assets/images/header/wide_1.jpg')}" alt="" class="header__imageImg">
	{else}
		<img src="{get_url('assets/images/header/1.jpg')}" alt="" class="header__imageImg">
	{/if}

	</div>
	<div class="container container_header">
		<div class="container__row container__row_header">
			<div class="container__col header__logo">
				{include file=$logo}
			</div>
			<div class="container__col header__lists{if $smarty.server.REQUEST_URI != "{config('lang.weblang')}/index_buro/"} header__lists_closed{/if}">
				<div class="container__row">
					<div class="container__col header__list list">
						<a href="{get_url(config('lang.weblang'), 'page/yuridicheskie-uslugi')}" class="list__title linkTriangle linkTriangle_line arno">
							{__('practice')}
							<span class="clear"></span>
							<span class="linkTriangle__text">{__('more')}</span>
							<span class="linkTriangle__line"></span>
						</a>

						{if count($practics)}
						<ul class="list__items">
						{foreach $practics as $pr}
							<li class="list__item">
								<a href="{get_url(config('lang.weblang'), 'page', $pr -> alias)}" class="list__link">{$pr -> title}</a>
							</li>
						{/foreach}
						</ul>
						{/if}

					</div>
					<div class="container__col header__list list">
						<a href="{get_url(config('lang.weblang'), 'page/specialnye-predlozheniya')}" class="list__title linkTriangle linkTriangle_line arno">
							{__('topical_solution_for_business')}
							<span class="clear"></span>
							<span class="linkTriangle__text">{__('more')}</span>
							<span class="linkTriangle__line"></span>
						</a>

						{if the_tags('inIndex')|count}
						<ul class="list__items">

						{foreach the_tags('inIndex') as $page}
							<li class="list__item">
							{*<a href="{get_url(config('lang.weblang'), 'article', $page -> alias)}" class="list__link">{$page -> name}</a>*}
							<a href="{get_url(config('lang.weblang'), 'article/specialnye-predlozheniya')}" class="list__link">{$page -> title}</a>
							</li>

						{/foreach}

						</ul>
						{/if}

					</div>
					<div class="clear"></div>
				</div>
			</div>
			<div class="clear"></div>
		</div>
	</div>
	<div class="header__panel">
		<div class="container">
			<div class="container__row">
				<div class="container__col glory">
					<div class="glory__row">
					
					{if count($historyAvatars)}
						{foreach $historyAvatars as $item}
						<div class="glory__item">
							<a href="{get_url(config('lang.weblang'), 'history', $item -> alias)}">
								<span class="glory__image">
									<img src="{asset_cache($item -> cover -> name, ['width' => '61', 'height' => '79', 'resize' => 'crop', 'pref'=> 'top'])}" alt="" class="glory__img">
								</span>
								<span class="glory__name">{fio($item -> title)}</span>
							</a>
						</div>
						{/foreach}
					{/if}
					 </div>
				</div>

				<div class="container__col">
					{include file=$search}
					<div class="header__nav">
						<ul class="nav">
							<li class="nav__item">
								<a href="{get_url(config('lang.weblang'))}" class="nav__link">
									<span class="arno nav__linkInfo">
										<span class="nav__linkHelp">{__('home')}</span>{__('page')}
									</span>
								</a>
							</li>

							<li class="nav__item">
								<a href="javascript:void(0)" class="nav__link">
									<span class="arno nav__linkInfo">
										<span class="nav__linkHelp">{__('our_bureau_top')}</span>
										{__('our_bureau_bottom')}
									</span>
								</a>

							{if isset($subBuro)}
								<ul class="nav__second">
								{foreach $subBuro as $item}
									{if $item -> alias == 'podpiska-na-pravovye-novosti'}{continue}{/if}
									<li class="nav__item nav__item_second">
										{if preg_match("/^(http:|https:)\/\//", $item -> alias)}
											<a href="{$item->alias}" class="nav__link nav__link_second">{$item->title}</a>
										{else}
											<a href="{get_url(config('lang.weblang'),$item->typeMenu,$item->alias )}" class="nav__link nav__link_second">{$item->title}</a>
										{/if}
										
									</li>
								{/foreach}
								</ul>
							{/if}
							</li>

							<li class="nav__item">
								<a href="{get_url(config('lang.weblang'), 'article/opyt-i-proekty')}" class="nav__link">
									<span class="arno nav__linkInfo">
										<span class="nav__linkHelp">{__('experience_and_projects_top')}</span>
											{__('experience_and_projects_bottom')}
									</span>
								</a>
							</li>

							<li class="nav__item">
								<a href="javascript:void(0)" class="nav__link">
									<span class="arno nav__linkInfo">
										<span class="nav__linkHelp">{__('Media_and_Information_top')} </span>{__('Media_and_Information_bottom')}
									</span>
								</a>

							{if isset($subInfo)}
								<ul class="nav__second">
								{foreach $subInfo as $item}
									<li class="nav__item nav__item_second">
										{if preg_match("/^(http:|https:)\/\//", $item -> alias)}
											<a href="{$item->alias}" class="nav__link nav__link_second">{$item->title}</a>
										{else}
											<a href="{get_url(config('lang.weblang'),$item->typeMenu,$item->alias )}" class="nav__link nav__link_second">{$item->title}</a>
										{/if}
									</li>
								{/foreach}
								</ul>
							{/if}
							</li>
							<li class="nav__item">
								<a href="{get_url(config('lang.weblang'), 'contact/kontakty')}" class="nav__link">
									<span class="arno nav__linkInfo">
										<span class="nav__linkHelp">{__('Contact_us_top')}</span>{__('Contact_us_bottom')}
									</span>
								</a>
							</li>
						</ul>
						<div class="clear"></div>
					</div>
				</div>
				<div class="clear"></div>
			</div>
		</div>
	</div>

	<div class="panel">
		<a href="{get_url(config('lang.weblang'), 'history')}" class="panel__text arno">{__('GALLERY_OF_RUSSIAN_LAW_OF_FAME')}</a>
	</div>
	<div class="header__folders">
		<div class="container">
			<a href="" class="header__folder linkTriangle linkTriangle_line arno">{__('industry_solutions')}</a>
			<a href="" class="header__folder linkTriangle linkTriangle_line arno">{__('practics')}</a>
		</div>
	</div>
</header>
<div class="panel panel_inner">
	<div class="container container_panel">
		  <a href="{get_url(config('lang.weblang'),'history')}" class="panel__text arno">{__('GALLERY_OF_RUSSIAN_LAW_OF_FAME')}</a>
	</div>
</div>