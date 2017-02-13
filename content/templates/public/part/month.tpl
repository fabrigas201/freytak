<br><br>
{*
{if !empty($subnews)}
<div class="news__types">
	{foreach $subnews as $submenu}
	<a href="{get_url($request_uri)}/{$submenu -> alias}" class="news__type 
		{if "`$request_uri`/`$submenu -> alias`"|trim:"/" == $smarty.server.REQUEST_URI|regex_replace:"/\?.*/":""|trim:"/"}news__type_active {/if}linkTriangle">{$submenu -> name}
	</a>
	{/foreach}
</div>
{/if}

<div class="news__types news__types_month">
	<a href="?date=2016-01-01" class="news__type linkTriangle{if $date == '2016-01-01'} news__type_active {/if}">январь</a>
	<a href="?date=2016-02-01" class="news__type linkTriangle{if $date == '2016-02-01'} news__type_active {/if}">февраль</a>
	<a href="?date=2016-03-01" class="news__type linkTriangle{if $date == '2016-03-01'} news__type_active {/if}">март</a>
	<a href="?date=2016-04-01" class="news__type linkTriangle{if $date == '2016-04-01'} news__type_active {/if}">апрель</a>
	<a href="?date=2016-05-01" class="news__type linkTriangle{if $date == '2016-05-01'} news__type_active {/if}">май</a>
	<a href="?date=2016-06-01" class="news__type linkTriangle{if $date == '2016-06-01'} news__type_active {/if}">июнь</a>
	<a href="?date=2016-07-01" class="news__type linkTriangle{if $date == '2016-07-01'} news__type_active {/if}">июль</a>
	<a href="?date=2016-08-01" class="news__type linkTriangle{if $date == '2016-08-01'} news__type_active {/if}">август</a>
	<a href="?date=2016-09-01" class="news__type linkTriangle{if $date == '2016-09-01'} news__type_active {/if}">сентябрь</a>
	<a href="?date=2016-10-01" class="news__type linkTriangle{if $date == '2016-10-01'} news__type_active {/if}">октябрь</a>
</div>
*}