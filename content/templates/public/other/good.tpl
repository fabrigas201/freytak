<div class='item_data'>
	<div class='item'>
		<div class='images' {if $good.co_img<=1}style='height:auto;'{/if}>
			<script>
					$.imgLoader("{$good.image[0].path}{$good.image[0].name}", 
						
						function(){  }, 
						
						function(src, width, height){
						
							var vh=show_img_n(width,height,$('.images').width(),$('.images').height())
							
							$('.images .img a').html("<img width='"+vh.w+"' height='"+vh.h+"' src='"+src+"' alt='{$good.image[0].alt}' title='{$good.image[0].alt}' />")
							
							$('.images .img a img').css({
								width:vh.w+"px",
								height:vh.h+"px",
								left:"50%",
								top:"50%",
								'margin-left':'-'+vh.w/2+"px",
								'margin-top':'-'+vh.h/2+"px",
							})
							
						}
					)
				
				</script>
			<div class='img'>
				<a href=''></a>
			</div>
			
			{if $good.co_img>1}
			<div class='img_list'>
				<ul>
					{foreach from=$good.image item=item}
						<script>
							$.imgLoader("{$item.path}tm_{$item.name}", 
								function(){  }, 
								
								function(src, width, height){ 
								
									var vh=show_img_n(width,height,$('.img_list li').width()-20,$('.img_list li').height()-20)
									$('#sim{$item.id}').html("<img width='"+vh.w+"' height='"+vh.h+"' src='"+src+"' alt='{$item.alt}' title='{$item.alt}'  />")
									$('#sim{$item.id} img').css({
										width:vh.w+"px",
										height:vh.h+"px",
										left:"50%",
										top:"50%",
										'margin-left':'-'+vh.w/2+"px",
										'margin-top':'-'+vh.h/2+"px",
									})
									
								}
							)
						</script>
						<li>
							<a href='{$item.path}{$item.name}'  id='sim{$item.id}'>
								<img src='{$item.path}tm_{$item.name}' alt='{$item.alt}' title='{$item.alt}' />
							</a>
						</li>
						
					{/foreach}
					
				</ul>
				
				
			</div>
			{/if}
			{if $good.co_img>4}
				<script>
					$(function(){
						$('.img_list').jCarouselLite({
							btnNext:'.next',
							btnPrev:'.prev',
							mouseWheel:true,
							circular: true,
							visible:4,
							scroll:4,
							wrapClass:'wrapCarousel_item',
						});
						
					})
						
				</script>
			{/if}

		</div>
		<div class='info'>
			<div class='info_indent'>
				<h1>{$good.name}</h1>
				<div class='descr'>
				{$good.descr}
				</div>
				<div class='to_basket'>
					<form>
						<input name='count' class='count' value='1' />
						<a class='ajax2' href='/?mod=feedback&in=7'>Задать вопрос</a>
						<div class='clear'></div> 
						<button onclick="addToBasket({$good.id},'shop',0,0);return false;">Добавить в заявку</button>
					</form>
				</div>
				<div class='clear'></div>
				<div class='categoty_info'>
					
					<span>Категория:</span> <a href=''> <span>{$sub.name}</span></a>
				
				</div>
				<div class='pluso_cont'>
				
				</div>
				
			</div>
		</div>
		<div class='clear'></div>
		<div class='full_info'>
			<div class='descr_file'>
				<span class='title'>Описание</span>
				{if $pdf_file}
					<div class='file_info'>
						<A href='{$pdf_file}' target='_blank'>
							<img src='/images/ico_pdf.gif' align=absmiddle /><span><span>Техническая<br/>спецификация</span>
							(pdf)
							</span>
						</A>
					</div>
				{/if}
			</div>
			<div class='descrfull'>
				<span class='title'>Описание товара</span>
				{$good.descrfull}
			</div>
			<div class='clear'></div>
		</div> 
		
	</div>
	<div class='other_item'>
	{if $similar}
	
	<div class='title'>Похожие товары</div>
	  <ul>
		
		{foreach $similar item=item}
		
		<script>
			$.imgLoader("{$item.src}", 
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

			<a class='img' href='{$item.hidden_url}' id='sim{$item.id}'>
			
			  <img src='{$item.src}' alt='{$item.alt}' title='{$item.alt}'/>
				
			</a>
			
			<span class='name'>{$item.name}</span>
			
			<a href='' class='to_basket' onclick="addToBasket({$item.id},'shop',0,0);return false;">
				В заявку
			</a>
			
		</li>
		{/foreach}
	</ul>
	</div>
	{/if}
</div>