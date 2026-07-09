<form class="row magazine-form" id="ricerca" action="/" method="get">
    <div class="col-md-12 col-sm-9 col-12 order-1 order-sm-2 mb-3">
        <div class="d-flex align-items-center">
            <input type="text" name="s" id="s" value="<?php echo (isset($_GET["s"])) ? esc_attr( $_GET["s"] ) : ''?>" placeholder="<?php echo __("Cerca nel magazine","mapparte"); ?>" class="form-control me-sm-4">
            <div class="action-btn m-3">
                <a href="Javascript:void(0)" onClick="cerca()"><img class="search-btn" src="<?php echo get_template_directory_uri();?>/assets/images/search.svg" alt="search"></a>
            </div>
        </div>
    </div>
</form>
<script>
function cerca(){
    if (document.forms.ricerca.s.value != "") document.forms.ricerca.submit();
}
</script>