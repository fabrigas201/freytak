<form action="{get_url(config('lang.weblang'), 'search')}" class="search">
	<input type="text" placeholder="{__('enter_a_search_term')}" name="q" class="search__field">
	<input type="submit" value="" class="search__submit">
	<div class="clear"></div>
	<div class="search__help">{__('search_rules')}</div>
</form>