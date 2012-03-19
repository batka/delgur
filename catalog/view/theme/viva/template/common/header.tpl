<!DOCTYPE html>
<html>
<head>
	<title><?php echo $title; ?></title>
	<meta charset='UTF-8' />
	<base href="<?php echo $base; ?>" />
	<link rel="shortcut icon" href="favicon.ico" />
	<?php if ($description) { ?><meta name="description" content="<?php echo $description; ?>" /><?php } ?>
	<?php if ($keywords) { ?><meta name="keywords" content="<?php echo $keywords; ?>" /><?php } ?>
	<?php if ($icon) { ?><link href="<?php echo $icon; ?>" rel="icon" /><?php } ?>
	<?php foreach ($links as $link) { ?>
	<link href="<?php echo $link['href']; ?>" rel="<?php echo $link['rel']; ?>" />
	<?php } ?>
	<link rel="stylesheet" type="text/css" href="catalog/view/theme/viva/stylesheet/stylesheet.css" />
	<?php foreach ($styles as $style) { ?>
		<link rel="<?php echo $style['rel']; ?>" type="text/css" href="<?php echo $style['href']; ?>" media="<?php echo $style['media']; ?>" />
	<?php } ?>
	<!--[if IE]>
		<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->
	<!--[if IE 6]>
	<style>
		body {behavior: url("csshover3.htc");}
	</style>
	<![endif]-->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
	<script src="catalog/view/javascript/jquery/ui/external/jquery.cookie.js"></script>
	<script src="catalog/view/javascript/jquery/tabs.js"></script>
	<script src="catalog/view/javascript/common.js"></script>
	<script src="catalog/view/javascript/jquery/fancybox/jquery.fancybox-1.3.4.pack.js"></script>
	<link rel="stylesheet" type="text/css" href="catalog/view/javascript/jquery/fancybox/jquery.fancybox-1.3.4.css" media="screen" />
	<?php foreach ($scripts as $script) { ?>
		<script type="text/javascript" src="<?php echo $script; ?>"></script>
	<?php } ?>
	<?php echo $google_analytics; ?>
</head>
<body>
	<div id="container">
	<div id="header">
  		<div id="logo">
    		<a href="http://www.magazintao.com" title="<?php echo $name; ?>"><?php echo $name; ?></a>
    	</div>
		<div class="welcome" id="ws-welcome">
	        <?php 
	    	if (!$logged) { 	
	    		echo $text_welcome; 
	    	} else { 
	    		echo $text_logged; 
	    	} 
	    ?>     
	    </div>
	  <?php 
	  //If multiple language show language choices
	  if (count($languages) > 1) { ?>
		  <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">
		    <div id="language"><?php echo $text_language; ?><br />
		      <?php foreach ($languages as $language) { ?>
		      &nbsp;<img src="image/flags/<?php echo $language['image']; ?>" alt="<?php echo $language['name']; ?>" title="<?php echo $language['name']; ?>" onclick="$('input[name=\'language_code\']').attr('value', '<?php echo $language['code']; ?>').submit(); $(this).parent().parent().submit();" />
		      <?php } ?>
		      <input type="hidden" name="language_code" value="" />
		      <input type="hidden" name="redirect" value="<?php echo $redirect; ?>" />
		    </div>
		  </form>
	  <?php }?>
	  <?php 
	  //If multiple currencies show currenies choices
	  /*if (count($currencies) > 1) { ?>
		  <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">
		    <div id="currency"><?php echo $text_currency; ?><br />
		      <?php foreach ($currencies as $currency) { ?>
		      <?php if ($currency['code'] == $currency_code) { ?>
		      <?php if ($currency['symbol_left']) { ?>
		      <a title="<?php echo $currency['title']; ?>"><b><?php echo $currency['symbol_left']; ?></b></a>
		      <?php } else { ?>
		      <a title="<?php echo $currency['title']; ?>"><b><?php echo $currency['symbol_right']; ?></b></a>
		      <?php } ?>
		      <?php } else { ?>
		      <?php if ($currency['symbol_left']) { ?>
		      <a title="<?php echo $currency['title']; ?>" onclick="$('input[name=\'currency_code\']').attr('value', '<?php echo $currency['code']; ?>').submit(); $(this).parent().parent().submit();"><?php echo $currency['symbol_left']; ?></a>
		      <?php } else { ?>
		      <a title="<?php echo $currency['title']; ?>" onclick="$('input[name=\'currency_code\']').attr('value', '<?php echo $currency['code']; ?>').submit(); $(this).parent().parent().submit();"><?php echo $currency['symbol_right']; ?></a>
		      <?php } ?>
		      <?php } ?>
		      <?php } ?>
		      <input type="hidden" name="currency_code" value="" />
		      <input type="hidden" name="redirect" value="<?php echo $redirect; ?>" />
		    </div>
		  </form>
	  <?php } */?>
	  <ul class="site-nav" id="site-nav">
		<li class="site-subnav site-nav-me">
			<span><a rel="nofollow" href="<?php echo $account; ?>">Мои заказы</a></span>
			<ul>
				<li><a rel="nofollow" href="http://magazintao.com/index.php?route=account/order">История заказов <b id="my-orders"></b></a></li>
				<li><a rel="nofollow" href="http://magazintao.com/index.php?route=account/reward">Бонусные баллы</a></li>
				<li><a rel="nofollow" href="http://magazintao.com/index.php?route=account/transaction">История платежей</a></li>
			</ul>
		</li>
		<li class="site-nav-wish"> 
			<span><a rel="nofollow" href="<?php echo $wishlist; ?>" id="wishlist_total"><?php echo $text_wishlist; ?></a></span>
		</li>
		<?php /*<li class="site-nav-cart">
			<a rel="nofollow" href="<?php echo $cart; ?>"><?php echo $text_cart; ?><b id="my-shopcarts"></b></a>
		</li>*/?>
		<li class="site-subnav site-nav-help"> 
			<span><a href="#">Информация</a></span>
			<ul>
				<li><a href="http://magazintao.com/about">О нас</a></li>
				<li><a href="http://magazintao.com/live-chat">Онлайн-чат с оператором</a></li>
				<li><a href="http://magazintao.com/delivery-information">Информация о доставке</a></li>
				<li><a href="http://magazintao.com/privacy-policy">Политика безопасности</a></li>
				<li><a href="http://magazintao.com/terms-and-conditions">Terms &amp; Conditions</a></li>
				<li><a href="http://magazintao.com/earn">Хочу зарабатывать больше</a></li>
			</ul>
		</li>
		<?php /*<li class="site-subnav site-nav-bbs"> 
			<span><a href="#">Community</a></span>
			<ul>
				<li><a rel="nofollow" href="#">Buyer Forum</a></li>
				<li><a rel="nofollow" href="#">Blog</a></li>
				<li><a rel="nofollow" href="#">Facebook</a></li>
			</ul>
		</li> */?>
		<li class="site-nav-checkout">
			<span><a href="<?php echo $checkout; ?>"><?php echo $text_checkout; ?></a></span>
		</li>
	</ul>  
	</div>
	<div class="shop-toolbar">
		<?php 
			// if it it's not home page then show categories on header
			if (!isset($this->request->get['route']) || (isset($this->request->get['route']) && $this->request->get['route'] == 'common/home')) {
                $this->data['current_page'] = "homepage";
             	//echo $this->data['current_page'];
        	} 
        	if($this->data['current_page'] != "homepage"){
		?>
			<div class="allcats" id="allcats">
				<div class="cats_head"><a href="" class="cats-title"><?php echo  $text_all_category ?></a></div>
			  	<ul class="popup-cats">
					<li class="cats-title">
						<a href=""><?php echo $text_all_category ?></a>
					</li>
					<?php 
						//loop counter for categories drop down menu 
						$count = 0;
						//Maximum number of categories to show on Categories Drop Down menu
						$max_count = 15;
		        	?>
		        	<?php foreach ($categories as $category_1) { ?>
		        		<?php 
		        			$count++;
		        			if($count>$max_count) break;
		        		?>
			        	<li <?php if ($category_1['children']) echo 'class="haschild"'; ?>>
							<a href="<?php echo $category_1['href']; ?>">
								<?php echo $category_1['name']; ?>
							</a>
				        	<?php if ($category_1['children']) { ?>
				        		<ul>
				            		<?php foreach ($category_1['children'] as $category_2) { ?>
				            			<li>
											<a href="<?php echo $category_2['href']; ?>">
												<?php echo $category_2['name']; ?>
											</a>
				              				<?php /* if ($category_2['children']) { ?>
				              					<ul>
				                					<?php foreach ($category_2['children'] as $category_3) { ?>
				                						<li <?php if ($category_3['children']) echo 'class="haschild"'; ?>>
															<a href="<?php echo $category_3['href']; ?>">
																<?php echo $category_3['name']; ?>
															</a>
															<?php if ($category_3['children']) { ?>
								              					<ul>
								                					<?php foreach ($category_3['children'] as $category_4) { ?>
								                						<li>
																			<a href="<?php echo $category_4['href']; ?>">
																				<?php echo $category_4['name']; ?>
																			</a>
																		</li>
								               						<?php } ?>
								           						</ul>
							              					<?php } ?>
														</li>
				               						<?php } ?>
				           						</ul>
			              					<?php }*/ ?>
				            			</li>
				            		<?php } ?>
				          		</ul>
							<?php } ?>
				        </li>
			        <?php } ?>

					<?php /* foreach ($categories as $category) { ?>
		        		<li class="haschild">
		          			<a href="<?php echo $category['href']; ?>"><?php echo $category['name']; ?></a>
		          			<?php if ($category['children']) { ?>
		          				<ul>
		            			<?php foreach ($category['children'] as $child) { ?>
		            				<li>
		              					<a href="<?php echo $child['href']; ?>"><?php echo $child['name']; ?></a>
										<?php if ($child['children']) { ?>
			          						<ul>
			            					<?php foreach ($child['children'] as $child1) { ?>
			            						<li>
			              							<a href="<?php echo $child1['href']; ?>"><?php echo $child1['name']; ?></a>
			            						</li>
			            					<?php } ?>
			          						</ul>
			          					<?php } ?>
		            				</li>
		            			<?php } ?>
		          				</ul>
		          			<?php } ?>
		        		</li>
		        	<?php } */?>
		      </ul>
			</div>
		<?php } ?>
		<div class="searchbar">
			<?php /*
			<?php if ($filter_name) { ?>
		      <input type="text" name="filter_name" value="<?php echo $filter_name; ?>" />
		      <?php } else { ?>
		      <input type="text" name="filter_name" value="<?php echo $filter_name; ?>" onclick="this.value = '';" onkeydown="this.style.color = '000000'" style="color: #999;" />
		      <?php } ?>
		      <select name="filter_category_id">
		        <option value="0"><?php echo $text_category; ?></option>
		        <?php foreach ($categories as $category_1) { ?>
		        	
		        		<option value="<?php echo $category_1['category_id']; ?>">
		        			<?php echo $category_1['name']; ?>
		        		</option>
		        	
		        	<?php foreach ($category_1['children'] as $category_2) { ?>
		        		<option value="<?php echo $category_2['category_id']; ?>">
		        			-<?php echo $category_2['name']; ?>
		        		</option>
		        		<?php /*foreach ($category_2['children'] as $category_3) { ?>
		        			<?php if ($category_3['category_id'] == $filter_category_id) { ?>
		        				<option value="<?php echo $category_3['category_id']; ?>" selected="selected">
		        					--<?php echo $category_3['name']; ?>
		        				</option>
		        			<?php } else { ?>
		        				<option value="<?php echo $category_3['category_id']; ?>">
		        					--<?php echo $category_3['name']; ?>
		        				</option>
		        			<?php } ?>
		        		<?php } */?>
		        	<?php /* } ?>
		        <?php } ?>
		      </select>
	  		<a id="button-search" class="button"><span><?php echo $button_search; ?></span></a> */ ?>

		  <div id="search">
			<div class="button-search"></div>
			<?php if ($filter_name) { ?>
			<input type="text" name="filter_name" value="<?php echo $filter_name; ?>" />
			<?php } else { ?>
			<input type="text" name="filter_name" value="<?php echo $text_search; ?>" onclick="this.value = '';" onkeydown="this.style.color = '#000000';" />
			<?php } ?>
		  </div>
		</div>
		<div class="cart-bar" id="cart-bar">
		  <div id="cart">
			<div class="heading">
			  <h4><?php echo $text_cart; ?></h4>
			  <a><span id="cart_total"><?php echo $text_items; ?></span></a></div>
			<div class="content"></div>
		  </div>
		</div>
	</div>
	<div id="notification"></div>
