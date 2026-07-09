<!--breadcum section start-->
<section class="breadcum-wrapper">
    <div class="container gx-5">
    <?php if( \Mapparte\Frontend_Utils::is_blog_page() || is_page("lista-spazi") ) :?>
        <ul class="breadcum-items d-flex align-items-center">
            <li class="breadcum-item"><a class="breadcum-link" href="/">Home</a></li>
            <li class="breadcum-item">&nbsp;/ <a class="breadcum-link" href="/magazine/">Magazine</a></li>
            <?php if (is_single() ) {
                $categories = get_the_category(get_the_ID());
            ?>
            <li class="breadcum-item">&nbsp;/ <a class="breadcum-link" href="<?php echo get_category_link($categories[0]->cat_ID)?>"><?php echo $categories[0]->name;?></a></li>
            <?php }?>
        </ul>
    </div>
    <?php endif;?>
</section>
<!--breadcum section end-->