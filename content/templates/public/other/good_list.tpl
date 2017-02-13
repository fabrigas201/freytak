

<div class='sub'>
	<div class='other_item'>
		<div class='title'>{$sub.name}</div>
		<div class='clear'></div>
		{if $sub.descr}
			<div class='good_list_info'>
				{$sub.descr}
			</div>
		{/if}
		
		{if $ch_cats}
			Подразделы<br>
			<ul class='cat_list'>
				{foreach from=$ch_cats item=item}
					<li><a href='/catalog{$item.hidden_url}'>{$item.name}</a></li>
				{/foreach}
				{$sub.alt_links}
			</ul>
		{/if}
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
</div>