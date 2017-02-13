<div class='index_indent'>
<script>
	 
    $(function() {
	
		
	$('.slides-overflow').slides({
		
		generatePagination: true,
		effect: 'fade',
		play: 3000,
		fadeSpeed: 1000,
		pause:15000,
		pagination: {
		  
		  active: true,
			// [boolean] Create pagination items.
			// You cannot use your own pagination. Sorry.
		  effect: "slide"
			// [string] Can be either "slide" or "fade".
			
		}
	});
      /*$('#slides').slidesjs({
        width: 940,
        height: 528,
        navigation: false
      });
	  */
    });
</script>
	<div class='b_baner'>
		<div class='slider'>
		
			<div class='slides-container'>
				
				<div class='slides-overflow'>
					<div class='slides_container'>
						{if $b_baner}
							{foreach from=$b_baner item=item}
								<div class='slide-item'>
									{if $item.descr}<a href='{$item.descr}'>{/if}
									
										<img src='{$item.path}{$item.name}' alt='{$item.alt}' title='{$item.alt}'  />
										
									{if $item.descr}</a>{/if}
								</div>
							{/foreach}
						
						{else}
							<div class='slide-item'>
								<a href='#'><img src='/images/cont/b_baner.jpg' /></a>
							</div>
						{/if}
						

					</div>
				</div>
			</div>
	
		</div>
		
		<table class='i_menu'>
			<tr>
				{foreach from=$catalog item=item}
				
					<td {if $item['last']}style='border-right:0px;width:194px;'{/if}><a href='{if $item.alt_link}{$item.alt_link}{else}/catalog/{$item.alt_link}/{/if}'>{$item.name}</a></td>
					
				{/foreach}
			</tr>
		</table>

	</div>
	
	
	<div class='big_buttons'>
		<a  href='/?mod=feedback&in=5' class='first ajax'>Задать вопрос</a>
		<a  href='/?mod=feedback&in=4' class='second ajax1'>Заказать звонок</a>
		<a  href='/bloknot.html' class='third'>Оформить заявку</a>
	</div>
	<div class='clear'></div>

	
	{foreach from=$catalog item=item key=key}
		
		<div style='z-index:{$item.zindex}' class='items_blok{if $item['second']} second{/if} zindex{$item.zindex}'>
		
			<div class='title_line'>
			
				<div class='title'>
					<span>{$item.name}</span>
				</div>
				
			</div>
			<div class='l_menu'>
				
				{$cats_tree[$item.id]}
				{if $cats_tree[$item.id]}
				<script>
				/*
				$(document).ready(function(){
					$("#tree{$item.id}").treeview({
					
						collapsed: true,
						unique: true,
						persist: "location"
					});
				})
				*/
				</script>
				{/if}
				{*
				<ul class='menu'>
				
					{foreach from=$item.sub sub}
						<li><a href='/catalog/{$item.alias}/{$sub.alias}/'>{$sub.name}</a></li>
					{/foreach}
			
				</ul>
			
				<!-- div class='info'>
					<div class='info_indent'>
					 Домашний кот проявил невероятную отвагу и героизм, заступившись за своего маленького хозяина
					</div>
				</div>
			
				<ul class='nav'>
					<li><a href=''></a></li>
					<li><a href=''></a></li>
					<li><a href=''></a></li>
				</ul-->
				*}
				
			</div>
		</div>
		
	{/foreach}
	
	
	
	<div class='clear'></div>

	<iframe width="1165" height="171" frameborder="0" style="margin:43px 0 40px;cursor:pointer;" scrolling="no" src="/222/index.html"></iframe>
	{*
	<a href='http://hisco.ru/catalog/gidroprivod_promyshlennogo_oborudovanija/konditsionirovanie_zhidkosti/teploobmenniki/prochie_teploobmenniki/teploobmenniki_avtonomnye.html' class='b_b'>
	
	<img src='/images/b.jpg' /></a>
	*}

	<div class='news_block'>
		     
		
		<div class='title_line'>
			<div class='title'><a href='/news/'>новости</a></div>
			<div class='clear'></div>
		</div>
	
		{foreach from=$news item=item}
			
			<div class='column'{if $item['last']} style='float:right;'{/if}>
				<div class='column_indent'>
					<div class='img'>
						{if $item.image}
							<img src='{$item.image}' alt='{$item.alt}' title='{$item.alt}' />
						{/if}
					</div>
					
					<div class='info'>
						<a class='title' href='/news/{$item.alias}.html'>{$item.name}</a>
						<div class='announce'>{$item.descr}</div>
						<div class='date'><!--17 Января 2014 | 0 комментариев --></div></div>
						<a class='more' href='/news/{$item.alias}.html'>подробнее</a>
				</div>	
			</div>
			{if $item['last']}<div class='clear'></div><br><br>{/if}
			
			
		{/foreach}
		
		<div class='clear'></div>
	</div>

	<br>
</div>