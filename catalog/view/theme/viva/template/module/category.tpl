<div class="box">
  <div class="box-heading"><?php echo $heading_title; ?></div>
  <div class="box-content">
    <div id="cate-list" class="cate-list">
      
        <?php 
          //loop counter for categories
          //$count = 0;

          //Maximum number of categories to show on Categories Panel
          //$max_count = 15;

        ?>

        <?php foreach ($categories as $category_1) { ?>
          
          <dl class="cate-list-item">
            <dt class="cate-name">
              <a href="<?php echo $category_1['href']; ?>"><?php echo $category_1['name']; ?></a>
            </dt>
            <?php if ($category_1['children']) { ?>
              <dd class="sub-cate-list" style="top: -30px; ">
                <div>
                  <a href="<?php echo $category_1['href']; ?>" rel="nofollow" class="new-cate-title">
                    Все подкатегории в <?php echo $category_1['name']; ?>
                  </a>
                </div>
                  <?php foreach ($category_1['children'] as $category_2) { ?>
                    
                    <dl>
                      <dt>
                        <a href="<?php echo $category_2['href']; ?>"><?php echo $category_2['name']; ?></a>
                      </dt>
                      <?php if ($category_2['children']) { ?>
                        <dd>
                          <?php foreach ($category_2['children'] as $category_3) { ?>
                            
                            <a href="<?php echo $category_3['href']; ?>"><?php echo $category_3['name']; ?></a>
                            <?php /*if ($category_3['children']) { ?>
                              <?php foreach ($category_3['children'] as $category_4) { ?>
                                <a href="<?php echo $category_4['href']; ?>"><?php echo $category_4['name']; ?></a>
                              <?php } ?>
                            <?php } */?>
                          <?php } ?>
                        </dd>
                      <?php } ?>
                    </dl>
                  <?php } ?>
              </dd>
            <?php } ?>
          </dl>


          <?php /*<li>
            <?php if ($category['category_id'] == $category_id) { ?>
              <a href="<?php echo $category['href']; ?>" class="active"><?php echo $category['name']; ?></a>
            <?php } else { ?>
              <a href="<?php echo $category['href']; ?>"><?php echo $category['name']; ?></a>
            <?php } ?>
          </li> */ ?>
        <?php } ?>
        <div class="cate-all-item"><a href="<?php echo $other_categories['href']; ?>"><?php echo $other_categories['name']; ?>...</a></div>
    </div>
  </div>
</div>
