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
				<h2 class="title title_big">
					<span class="title__text arno">{stripcslashes($title)}</span>
				</h2>
				<div class="formated">
				{if isset($month)}{include file=$monthDate}{/if}
				{if !empty($result)}
					{if isset($cover)}
					<div class="image-gallery">
					<a href="{get_url($result -> cover -> path, $result -> cover -> name)}" class="image highslide" onclick="return hs.expand(this)">
						<img src="{$cover}" alt="{$descr}" class="image__object" >
					</a>
					</div>
					{/if}
					{stripcslashes($result -> descrfull)}
					
					{if isset($result -> images)}
					<div class="gallery image-gallery">
					{foreach $result -> images as $images }
					{if $images -> isCover == 1}{continue}{/if}
					<a href="{get_url(config('lang.weblang'), $images -> path, $images -> name)}" class="image highslide" onclick="return hs.expand(this)">
						<img src="{asset_cache($images -> name, ['width' => 232, 'height' => 171])}" alt="" class="image__object">
					</a>
					{/foreach}
					</div>
					{/if}
					
				{/if}
				</div>
				{if $smarty.server.REQUEST_URI =="/{config('lang.weblang')}/page/podpiska-na-pravovye-novosti"}
				<div class="container__col hiddenMobile">
					<div class="form__wrap">
						<form action="{get_url('contact/subscribe')}" method="POST" class="form form_page">
							<input name="url" type="hidden" value="{get_url($smarty.server.REQUEST_URI)}">
							<div class="form__line" style="display:none;">
								<div class="form__field">
									<input name="remaller_check" type="text" value="" placeholder="">
								</div>
							</div>
							<div class="form__border">
								<div class="form__line">
									<div class="form__field">
										<input name="name" type="text" placeholder="Как к Вам обращаться">
									</div>
								</div>
								<div class="form__line">
									<div class="form__field">
										<input name="phone" type="text" placeholder="Ваш телефон">
									</div>
								</div>
								<div class="form__line">
									<div class="form__field">
										<input name="email" type="text" placeholder="Ваш E-mail">
									</div>
								</div>
							</div>
							<label class="form__submit linkTriangle">
								<input type="submit" value="Отправить " class="form__submitInput">
							</label>
						</form>
					</div>
				</div>
				{/if}
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