<?php if ($box_status) { ?>
<div class="box">
  <div class="box-heading"><?php echo $heading; ?></div>

  <?php if($nick != ''){ ?>
  <div id="" class="box-seller">
  	<div id="seller-info" class="">
  		<ul id="" class="">
  			<li>Имя: <?php echo $nick; ?></li>
  			<li>Рейтинг: <img src="catalog/view/theme/viva/image/tao/level_<?php echo $products[0]['score']; ?>.gif" alt="<?php echo $products[0]['score']; ?>"></li>
  			<?php /*<li>Положительные отзывы: 100.00%</li>*/?>
  			<li>Город: <?php echo $products[0]['location_city']; ?></li>
  		</ul><!-- / -->
  	</div><!-- / -->
  	<h3>Другие товары продавца</h3>
    <?php //echo $nick; ?>
    <?php foreach ($products as $product) { ?>
      <div>
        <?php if ($product['thumb']) { ?>
        <div class="image"><a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>" /></a></div>
        <?php } ?>
        <?php if ($product['price']) { ?>
  	      <div class="price">
  	        <?php if (!$product['special']) { ?>
  	        <?php echo $product['price']; ?>
  	        <?php } else { ?>
  	        <span class="price-old"><?php echo $product['price']; ?></span> <span class="price-new"><?php echo $product['special']; ?></span>
  	        <?php } ?>
  	        <?php if ($product['tax']) { ?>
  	        <br />
  	        <span class="price-tax"><?php echo $text_tax; ?> <?php echo $product['tax']; ?></span>
  	        <?php } ?>
  	      </div>
        <?php } ?>
  	  
        <?php /*<div class="name"><?=$this->language->get('text_seller') ?>: <label href="#" style="color:#38b0e3;"><?=$product['nick'] ?></label></div>
        <div class="description"></div>
  	  
        <?php if ($product['score']) { ?>
        <div class="score"><img src="catalog/view/theme/viva/image/tao/level_<?php echo $product['score']; ?>.gif" alt="<?php echo $product['score']; ?>" /></div>
        <?php } ?>
        */?>
      </div>
    <?php } ?>
    <div id="" class="">
      <a href="http://magazintao.com/index.php?route=product/search&filter_name=<?php echo $nick; ?>&search_type=shop" title="">Все товары продавца</a>
    </div><!-- / -->
  </div><!-- / -->
  <?php } ?>
</div>
<?php } else { ?>
<div class="welcome"><?php echo $heading; ?></div>
<?php echo $message; ?>
<?php } ?>