<div class='producers_list'>
		<ul class='bread_crumbs'>
			{foreach from=$breadCrumbs item=item key=key}
				<li>{$item}</li>{ $key}
				{if $key!='end'}
					<li class='divider'>&nbsp;>&nbsp;</li>
				{/if}
			{/foreach}
		</ul>
		<div class='clear'></div>
		
		{foreach from=$prod_list item=prod}
		
			<h2>{$prod.name}</h2>
			
			<div class='producers_divider'></div>
			
			
			<div class='logo_producer' align=center>
			{if $prod.image}
							<img src='{$prod.image.path}{$prod.image.name}' />
			{/if}</div>
			
			{foreach from=$prod.items item=item1 }
				<table class='producer_list'>
					{foreach from=$item1 item=item2 }
						<tr>
							{foreach from=$item2 item=item3 }
								<td class='producer_small'>
									{if $images[$item3.id]}
									<script>
										$.imgLoader("{$images[$item3.id]['path']}tm_{$images[$item3.id]['name']}", 
											function(){  }, 
											
											function(src, width, height){ 
											
												var vh=show_img_n(width,height,273,298)
												
												$('#sim{$item3.id}').html("<img width='"+vh.w+"' height='"+vh.h+"' src='{$images[$item3.id]['path']}tm_{$images[$item3.id]['name']}'/>")
												
												$('#sim{$item3.id} img').css({
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
									{/if}
									<div class='img' id='sim{$item3.id}' align=center>
										{if $images[$item3.id]}
											<img src='{$images[$item3.id]['path']}tm_{$images[$item3.id]['name']}' />
										{/if}
									</div>
									
									<table class='properties'>
										{if $item3.custom_9}
						<tr>
							<td>Количество цилиндров</td>
							<td>{$item3.custom_9}</td>
						</tr>
					{/if}
					{if $item3.custom_26}
					<tr>
						<td>Рабочий объем, см3</td>
						<td>{$item3.custom_26}</td>
					</tr>
					{/if}
					{if $item3.custom_22}
					<tr>
						<td>Максимальная мощность по SAE J1995, кВт/л.с. (об/мин)</td>
						<td>{$item3.custom_22}</td>
					</tr>
					{/if}
					
					{if $item3.custom_27}
					<tr>
						<td>Габаритные размеры, мм</td>
						<td>{$item3.custom_27}</td>
					</tr>
					{/if}
					{if $item3.custom_8}
					<tr>
						<td>Масса (сухая), кг</td>
						<td>{$item3.custom_8}</td>
					</tr>
					{/if}
					
					{if $item3.custom_23}
					<tr>
						<td>Система подачи воздуха</td>
						<td>{$item3.custom_23}</td>
					</tr>
					{/if}
					
					{if $item3.custom_24}
					<tr>
						<td>Мощность при повторно-кратковременном режиме, Квт</td>
						<td>{$item3.custom_24}</td>
					</tr>
					{/if}
					
					{if $item3.custom_25}
					<tr>
						<td>Мощность при повторно-кратковременном режиме, л.с.</td>
						<td>{$item3.custom_25}</td>
					</tr>
					{/if}
					
					{if $item3.custom_10}
					<tr>
						<td>Номинальная частота вращения, об/мин</td>
						<td>{$item3.custom_10}</td>
					</tr>
					{/if}
										
									</table>
									
									<div class='clear'></div>
									
									<div class='name_button'>
										<h3>{$item3.name}</h3>
										<a href='/catalog/{$item3.alias}.html'>Подробное описание</a>
									</div>
									<div class='clear'></div>
									
								</td>
								
								{if $item3.divider=='yes'}
									
										<td class='producer_small_divider_ver'></td>
								{/if}
							{/foreach}
						</tr>
						<tr>
							<td colspan=3 class='producer_small_divider_hor'></td>
						</tr>
					{/foreach}
				</table>
			{/foreach}
		
		{/foreach}
		
		
	</div>