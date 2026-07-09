<!-- pagination -->
<div class="pagination-wrapper">
<nav  aria-label="pagination">
<?php 
global $paged;
if (empty($paged)) $paged = 1;

$range = 4;
if ($paged == 1) $range = 9;
if ($paged == 2) $range = 8;
if ($paged == 3) $range = 7;
if ($paged == 4) $range = 6;
if ($paged == 5) $range = 4;

$morepages = ($range * 2)+1;

global $wp_query;
$pages = $wp_query->max_num_pages;

if (!$pages) $pages = 1;


$querystring = (!empty($_SERVER['QUERY_STRING'])) ? "?".$_SERVER['QUERY_STRING'] : "" ;

if (1 != $pages) {
    echo '<ul class="pagination justify-content-center align-items-center">';
    if ($paged > 1 && $morepages < $pages) {
        echo '<li class="page-item"><a class="page-link" href="'.explode("?", get_pagenum_link($paged - 1))[0].$querystring.'">';
        echo '<span aria-hidden="true"><svg class="svg-inline--fa fa-arrow-left fa-w-14" aria-hidden="true" focusable="false" data-prefix="fa" data-icon="arrow-left"  role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" data-fa-i2svg="">';
        echo '<path fill="currentColor"  d="M257.5 445.1l-22.2 22.2c-9.4 9.4-24.6 9.4-33.9 0L7 273c-9.4-9.4-9.4-24.6 0-33.9L201.4 44.7c9.4-9.4 24.6-9.4 33.9 0l22.2 22.2c9.5 9.5 9.3 25-.4 34.3L136.6 216H424c13.3 0 24 10.7 24 24v32c0 13.3-10.7 24-24 24H136.6l120.5 114.8c9.8 9.3 10 24.8.4 34.3z"></path></svg>';
        echo '</span>';
        echo '</a></li>';
    }
    for ($i=1; $i <= $pages; $i++) {
        if (1 != $pages &&(!($i >= $paged+$range+1 || $i <= $paged-$range-1) || $pages <= $morepages)) {
            $active = ($paged == $i) ? 'active' : '';
            echo '<li class="page-item"><a class="page-link '.$active.'" href="'.explode("?", get_pagenum_link($i))[0].$querystring.'">'.$i.'</a></li>';
        }
    }
    if ($paged < $pages && $morepages < $pages) {
        echo '<li class="page-item"><a class="page-link next-link" href="'.explode("?", get_pagenum_link($paged + 1))[0].$querystring.'">';
        echo '<span aria-hidden="true"><svg class="svg-inline--fa fa-arrow-right fa-w-14"aria-hidden="true" focusable="false" data-prefix="fa" data-icon="arrow-right" role="img" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" data-fa-i2svg="">';
        echo '<path fill="currentColor" d="M190.5 66.9l22.2-22.2c9.4-9.4 24.6-9.4 33.9 0L441 239c9.4 9.4 9.4 24.6 0 33.9L246.6 467.3c-9.4 9.4-24.6 9.4-33.9 0l-22.2-22.2c-9.5-9.5-9.3-25 .4-34.3L311.4 296H24c-13.3 0-24-10.7-24-24v-32c0-13.3 10.7-24 24-24h287.4L190.9 101.2c-9.8-9.3-10-24.8-.4-34.3z"> </path>';
        echo '</svg>';
        echo '</a></li>';
    }
    echo '</ul>';
}
?>
</nav>
</div>
<!-- /pagination -->
