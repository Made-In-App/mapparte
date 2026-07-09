<!--breadcum section start-->
<section class="breadcum-wrapper">
    <div class="container gx-5">
        <?php get_template_part( 'template-parts/content/social-icons' );?>
        <ul class="breadcum-items d-flex align-items-center">
            <li class="breadcum-item"><a class="breadcum-link" href="/">Home</a></li>
            <li class="breadcum-item">&nbsp;/ <a class="breadcum-link" href="/spaces/"><?php echo __("Cerca uno spazio","mapparte");?></a></li>
            <li class="breadcum-item">&nbsp;/ <?php the_title(); ?></li>
        </ul>
    </div>
</section>
<!--breadcum section end-->