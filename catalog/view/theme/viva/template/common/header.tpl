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
	<link rel="stylesheet" type="text/css" href="catalog/view/theme/viva/stylesheet/jquery-ui-1.8.18.custom.css" />
	<!--[if IE]>
		<script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->
	<!--[if IE 6]>
		<link rel="stylesheet" type="text/css" href="catalog/view/theme/viva/stylesheet/ie6.css" />
	<![endif]-->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
	<script src="catalog/view/javascript/jquery/ui/jquery-ui-1.8.18.custom.min.js"></script>
	<script src="catalog/view/javascript/jquery/ui/external/jquery.cookie.js"></script>
	<?php 
    if($current_page!= "homepage"){ ?>
		<link rel="stylesheet" type="text/css" href="catalog/view/javascript/jquery/fancybox/jquery.fancybox-1.3.4.css" media="screen" />
		<script src="catalog/view/javascript/jquery/tabs.js"></script>
		<script src="catalog/view/javascript/jquery/fancybox/jquery.fancybox-1.3.4.pack.js"></script>
	<?php } ?>
	<script src="catalog/view/javascript/common.js"></script>
	<script type="text/javascript" src="http://userapi.com/js/api/openapi.js?49"></script>
	<script type="text/javascript">
		VK.init({apiId: API_ID, onlyWidgets: true});
	</script>
	<?php foreach ($scripts as $script) { ?>
		<script type="text/javascript" src="<?php echo $script; ?>"></script>
	<?php } ?>
	<?php echo $google_analytics; ?>
</head>
<body>
	<div id="container">
	<div id="header">
  		<div id="logo"><a href="http://www.magazintao.com" title="<?php echo $name; ?>"><?php echo $name; ?></a></div>
		<div class="welcome" id="ws-welcome">
	        <?php 
		    	if (!$logged) 	echo $text_welcome; 
		    	else 			echo $text_logged; 
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
		//If multiple currencies show currencies choices
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
			<?php /*<li class="site-nav-checkout"><span><a href="<?php echo $checkout; ?>"><?php echo $text_checkout; ?></a></span></li>*/ ?>
		</ul> 
		<div id="phone" style="color: #5C6970; position: absolute; top: 32px; right: 155px; height: 28px; width: 165px; font-size: 14px;font-weight: bold;">+7 (499) 703-14-58</div>
		<div id="livechat"><a href="http://www.magazintao.com/live-chat" title="Live Chat">Чат с Оператором</a></div>

	</div><!-- END #header div -->
	<div class="shop-toolbar">
		<div class="allcats" id="allcats">
			<?php // if it it's home page then don't show dropdown categories on header
	    	if($current_page!= "homepage"){ ?>
				<div class="cats_head"><a href="" class="cats-title"><?php echo $text_all_category ?></a></div>
			  	<ul class="popup-cats">
		        	<?php foreach ($categories as $category_1) { ?>
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
				            			</li>
				            		<?php } ?>
				          		</ul>
							<?php } ?>
				        </li>
			        <?php } ?>
			        <li class="cate-all-item">
			        	<a href="<?php echo $other_categories['href']; ?>"><?php echo $other_categories['name']; ?>...</a>
			        </li>
		      	</ul>
			<?php } ?>
		</div> <!-- END #allcats -->
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
