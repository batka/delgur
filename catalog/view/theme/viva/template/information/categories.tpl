<?php echo $header; ?><?php echo $column_left; ?><?php echo $column_right; ?>
<div id="content"><?php echo $content_top; ?>
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <h1><?php echo $heading_title; ?></h1>
  <div class="categories">
    <div class="category-column">
      <?php $count = 0; ?>
        <?php foreach ($categories as $category_1) { ?>
        <div class="first-level">
          <a href="<?php echo $category_1['href']; ?>"><?php echo $category_1['name']; ?></a>
          <?php if ($category_1['children']) { ?>
          <ul>
            <?php foreach ($category_1['children'] as $category_2) { ?>
            <li><a href="<?php echo $category_2['href']; ?>"><?php echo $category_2['name']; ?></a>
              <?php if ($category_2['children']) { ?>
              <ul>
                <?php foreach ($category_2['children'] as $category_3) { ?>
                <li><a href="<?php echo $category_3['href']; ?>"><?php echo $category_3['name']; ?></a>
                  <?php if ($category_3['children']) { ?>
                    <ul>
                      <?php foreach ($category_3['children'] as $category_4) { ?>
                      <li><a href="<?php echo $category_4['href']; ?>"><?php echo $category_4['name']; ?></a>
                        <?php if ($category_4['children']) { ?>
                          <ul>
                            <?php foreach ($category_4['children'] as $category_5) { ?>
                              <li><a href="<?php echo $category_5['href']; ?>"><?php echo $category_5['name']; ?></a></li>
                            <?php } ?>
                          </ul>
                        <?php } ?>
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
          </ul>
          <?php } ?>
        </div>
        <?php if(++$count == 17) echo '</div><div class="category-column">'; ?>
        <?php } ?>
    </div>
  </div>
  <?php echo $content_bottom; ?></div>
<?php echo $footer; ?>