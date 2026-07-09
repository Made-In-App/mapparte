<!--magazine section start-->
<section class="magazine-wrapper">
        <div class="container gx-5">
            <h1 class="magazine-section-ttl"><?php the_title();?></h1>
        </div>
    </section>
    <!--magazine section end-->

    <!--magazine list section start-->
    <section class="spazi-wrapper">
        <div class="container-fluid gx-5">
            <div class="row justify-content-center">
            <?php
            $conta = 0;
            $terms = get_terms(['taxonomy' => 'activity','hide_empty' => false]);
            foreach($terms as $term) {
                $immagine = get_field("immagine",$term);
                if ($conta % 3 == 0) {
                ?>
                    </div>
                    <div class="row justify-content-center">
                <?php
                }
                ?>
                <div class="spazi-tile col-6 col-md-4 col-lg-2">
                    <a href="/spaces/?where=&s_activity=<?php echo $term->term_id;?>">
                        <img class="m-auto spazi-img" src="<?php echo $immagine;?>" alt="<?php echo $term->name;?>" />
                        <img class="my-4 divider" src="/wp-content/themes/mapparte/assets/images/divider.svg" alt="photo" />
                        <h4 class="spazi-ttl"><?php echo $term->name;?></h4>
                    </a>
                </div>
            
            <?php
            $conta++;
            }
            ?>
            </div>
        </div>
    </section>
    <!--magazine list section end-->