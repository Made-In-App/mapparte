<!--breadcum section start-->
<?global $post;?>
<section class="breadcum-wrapper" style="padding: 0rem 0 1rem">
    <div class="container gx-5">
   
        <ul class="breadcum-items d-flex align-items-center">
            <li class="breadcum-item"><a class="breadcum-link" href="/"><?php echo __("Home","mapparte"); ?></a></li>
            <li class="breadcum-item">&nbsp;/ <a class="breadcum-link" href="/come-funziona/"><?php echo __("Come funziona","mapparte"); ?></a></li>
            <?php if ($post->post_parent > 0 ) {?>
            <li class="breadcum-item">&nbsp;/ <?php echo ucfirst(strtolower(get_the_title()));?></li>
            <?php }?>
        </ul>
        </ul>
    </div>
</section>
<!--breadcum section end-->