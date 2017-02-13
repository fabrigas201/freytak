{extends "../main.tpl"}
{block name="content"}

<div class="panel">
	<div class="container container_panel">
	{include file=$search}
	</div>
</div>

<div class="content">
	<div class="promo">
		<div class="container container_content">
			<div class="container__row container__row_promo">

				{if !empty(the_tags('pageInIndex'))}
				{foreach the_tags('pageInIndex') as $index name=it}
				<div class="container__col promo__item mobileBlock">
					{if $smarty.foreach.it.iteration == 2}
					<a href="{get_url(config('lang.weblang'),'index_buro')}" class="promo__title arno">
						<span class="promo__titleText">фрейтак и сыновья</span>
						<span class="promo__titleHelp">{$index -> title}</span>
					</a>
					{else}
					<a href="{get_url(config('lang.weblang'),'article', $index -> alias)}" class="promo__title arno">
						<span class="promo__titleText">фрейтак и сыновья</span>
						<span class="promo__titleHelp">{$index -> title}</span>
					</a>
					{/if}
					<div class="promo__text quote quote_start">
					{$index -> descr}
					<p>&nbsp;</p>
					<p>&nbsp;</p>
					</div>
					{if $smarty.foreach.it.iteration == 2}
					<a href="{get_url(config('lang.weblang'),'index_buro')}" class="promo__link">
						<span class="promo__linkText linkTriangle">перейти в раздел</span>
						{*<span class="promo__linkText linkTriangle">&nbsp;</span>*}
					</a>
					{else}
					<a href="{get_url(config('lang.weblang'),'article', $index -> alias)}" class="promo__link">
						<span class="promo__linkText linkTriangle">перейти в раздел</span>
						{*<span class="promo__linkText linkTriangle">&nbsp;</span>*}
					</a>
					{/if}
				</div>
				{/foreach}
				{/if}
				<div class="clear"></div>
			</div>
		</div>
	</div>

	<div class="about hiddenMobile">
		<div class="container container_content">
			<h2 class="title">
				<span class="title__text arno">О  корпорации</span>
			</h2>

			<p>Корпорация «Фрейтак и Сыновья» оказывает услуги в сфере юридического и финансового консультирования. Объединяя в своей команде лучших специалистов в области финансового, налогового и юридического анализа и консультирования, мы осуществляем комплексную поддержку российских и иностранных клиентов в решении их деловых задач.
Качество оказываемых нами услуг обеспечивается высокими стандартами профессионализма и конфиденциальности, глубоким и внимательным подходом к каждому клиенту, а также многолетним практическим опытом.</p>

			<div class="signature">
{*				<a href="" class="signature__link linkTriangle">подробнее</a> *}
			</div>
		</div>
	</div>
	{include file=$assoc}
</div>
{/block}