<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" dir="<?php echo $direction; ?>" lang="<?php echo $lang; ?>" xml:lang="<?php echo $lang; ?>">
<head>
<title><?php echo $title; ?></title>
<base href="<?php echo $base; ?>" />
<?php if ($description) { ?>
<meta name="description" content="<?php echo $description; ?>" />
<?php } ?>
<?php if ($keywords) { ?>
<meta name="keywords" content="<?php echo $keywords; ?>" />
<?php } ?>
<?php if ($icon) { ?>
<link href="<?php echo $icon; ?>" rel="icon" />
<?php } ?>
<?php foreach ($links as $link) { ?>
<link href="<?php echo $link['href']; ?>" rel="<?php echo $link['rel']; ?>" />
<?php } ?>
<link rel="stylesheet" type="text/css" href="catalog/view/theme/viva/stylesheet/stylesheet.css" />
<?php foreach ($styles as $style) { ?>
<link rel="<?php echo $style['rel']; ?>" type="text/css" href="<?php echo $style['href']; ?>" media="<?php echo $style['media']; ?>" />
<?php } ?>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>
<script type="text/javascript" src="catalog/view/javascript/jquery/ui/external/jquery.cookie.js"></script>
<script type="text/javascript" src="catalog/view/javascript/jquery/tabs.js"></script>
<script type="text/javascript" src="catalog/view/javascript/common.js"></script>
<script type="text/javascript" src="catalog/view/javascript/jquery/fancybox/jquery.fancybox-1.3.4.pack.js"></script>
<link rel="stylesheet" type="text/css" href="catalog/view/javascript/jquery/fancybox/jquery.fancybox-1.3.4.css" media="screen" />

<?php foreach ($scripts as $script) { ?>
<script type="text/javascript" src="<?php echo $script; ?>"></script>
<?php } ?>
<?php echo $google_analytics; ?>
</head>
<body>
<div id="container">
<div id="header">
  <?php if ($logo) { ?>
  <div id="logo"><a href="<?php echo $home; ?>"><img src="<?php echo $logo; ?>" title="<?php echo $name; ?>" alt="<?php echo $name; ?>" /></a></div>
  <?php } ?>
  <?php if (count($languages) > 1) { ?>
  <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">
    <div id="language"><?php echo $text_language; ?><br />
      <?php foreach ($languages as $language) { ?>
      &nbsp;<img src="image/flags/<?php echo $language['image']; ?>" alt="<?php echo $language['name']; ?>" title="<?php echo $language['name']; ?>" onclick="$('input[name=\'language_code\']').attr('value', '<?php echo $language['code']; ?>').submit(); $(this).parent().parent().submit();" />
      <?php } ?>
      <input type="hidden" name="language_code" value="" />
      <input type="hidden" name="redirect" value="<?php echo $redirect; ?>" />
    </div>
  </form>
  <?php } ?>
  <?php if (count($currencies) > 1) { ?>
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
  <?php } ?>
  <div id="welcome">
    <?php if (!$logged) { ?>
    <?php echo $text_welcome; ?>
    <?php } else { ?>
    <?php echo $text_logged; ?>
    <?php } ?>
  </div>
  <div class="links"><a href="<?php echo $home; ?>"><?php echo $text_home; ?></a><a href="<?php echo $wishlist; ?>" id="wishlist_total"><?php echo $text_wishlist; ?></a><a href="<?php echo $account; ?>"><?php echo $text_account; ?></a><a href="<?php echo $cart; ?>"><?php echo $text_cart; ?></a><a href="<?php echo $checkout; ?>"><?php echo $text_checkout; ?></a></div>
</div>
<div class="shop-toolbar">
	<div class="allcats" id="allcats">
		<div class="cats_head"><a href="" class="cats-title"><?=$text_all_category ?></a></div>
	  	<ul class="popup-cats">
			<li class="cats-title">
				<a href="">
					<?=$text_all_category ?>
				</a>
			</li>
        	<?php foreach ($categories as $category_1) { ?>
	        	<li <?php if ($category_1['children']) echo 'class="haschild"'; ?>>
					<a href="<?php echo $category_1['href']; ?>">
						<?php echo $category_1['name']; ?>
					</a>
		        	<?php if ($category_1['children']) { ?>
		        		<ul>
		            		<?php foreach ($category_1['children'] as $category_2) { ?>
		            			<li <?php if ($category_2['children']) echo 'class="haschild"'; ?> >
									<a href="<?php echo $category_2['href']; ?>">
										<?php echo $category_2['name']; ?>
									</a>
		              				<?php if ($category_2['children']) { ?>
		              					<ul>
		                					<?php foreach ($category_2['children'] as $category_3) { ?>
		                						<li>
													<a href="<?php echo $category_3['href']; ?>">
														<?php echo $category_3['name']; ?>
													</a>
												</li>
		               						<?php } ?>
		           						</ul>
	              					<?php } ?>
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
	<div class="searchbar">
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
