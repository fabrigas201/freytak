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
									
										<img src='{$item.path}{$item.name}' alt='{$item.alt}'  title='{$item.alt}' />
										
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
{*	<a href='http://ctt-expo.ru/visitors/ticket-online/' target='_blank' style='display:block;margin: 30px auto 0px;'>
		<img src='/images/adadasdads.jpg'  style='display:block;margin: 0px auto;' />
	</a>
*}	<div class='big_buttons'>
		<a  href='/?mod=feedback&in=5' class='first ajax'>Задать вопрос</a>
		<a  href='/?mod=feedback&in=4' class='second ajax1'>Заказать звонок</a>
		<a  href='/bloknot.html' class='third'>Оформить заявку</a>
	</div>
	<div class='clear' style='text-align:center;padding:0px 0px 30px 0px;'>
	<!--a href='http://www.testing-control.ru/ru-RU/visitors/e-ticket.aspx#personal' target='_blank'><img src='/images/tc16_468x60_uch.gif' style='border:1px #136e35 solid;' /></a-->
	</div>

	<div class='index_cat_menu'>
		<div class='title'>КАТЕГОРИИ ТОВАРОВ</div>
		<script src='/js/jquery.treeview.js'></script>
					<script>
					$(document).ready(function(){
						$(".treeview_in").treeview({
						
							collapsed: true,
							unique: true,
							persist: "location"
						});
						$(".treeview_in li").hover(
							function() {
								$(this).find("ul:first").fadeIn(200);
								$(this).addClass("active");
							}, function() {
								$(this).find("ul:first").fadeOut(0);
								$(this).removeClass("active");
						});
					})
					</script>
					{$cats_tree}
	</div>
	<div class='tree_item'>
	<div class='title1'>ТОВАРЫ ИЗ НАШЕГО КАТАЛОГА</div>
	<div class='clear'></div>
		 <ul>
			
			{foreach from=$good_list item=item}
					<script>
						$.imgLoader("{$item.image.path}tm_{$item.image.name}", 
							function(){  }, 
							
							function(src, width, height){ 
							
								var vh=show_img_n(width,height,$('#sim{$item.id}').width(),$('#sim{$item.id}').height())
								
								//$('#sim{$item.id}').html("<img width='"+vh.w+"' height='"+vh.h+"' src='"+src+"'/>")
								
								$('#sim{$item.id} img').css({
									width:new_w+"px",
									height:new_h+"px",
									left:"50%",
									top:"50%",
									'margin-left':'-'+new_w/2+"px",
									'margin-top':'-'+new_h/2+"px",
								})
								
							}
						)
					
					</script>
				<li>

					<a class='img' id='sim{$item.id}' href='{$item.hidden_url}'>
						
						{if $item.image}
							<img src='{$item.image.path}tm_{$item.image.name}' alt='{$item.image.alt}' title='{$item.image.alt}' />
						{/if}
						
					</a>
					
					<span class='name'>{$item.name}</span>
					
					<a href='#' onclick="addToBasket({$item.id},'shop',0,0);return false;"  class='to_basket'>
						В заявку
					</a>
					
				</li>
				
				
			{/foreach}
			
			
				
			  
		</ul>
	</div>
	<div class='clear'></div>
	
	
	
	
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
							<img src='{$item.image.src}' alt='{$item.image.alt}' title='{$item.image.alt}' />
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
<script>
$('.tree_item ul li').height($('.treeview_in').height())
</script>