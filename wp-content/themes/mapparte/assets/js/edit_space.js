jQuery(document).ready(function ($) {

    function syncGalleryImgs() {
        var images = [];
        $("#sortable li img[id]").each(function () {
            var id = $(this).attr('id');
            if (id) images.push(id);
        });
        $("#gallery_imgs").val(images.join(','));
    }

    if (typeof MutationObserver !== 'undefined' && $("#sortable").length) {
        var observer = new MutationObserver(function () { syncGalleryImgs(); });
        observer.observe(document.getElementById('sortable'), { childList: true, subtree: true });
    }
    $("body").on('DOMSubtreeModified', "#sortable", syncGalleryImgs);
    $(document).on('click', '.remove', function () {
        $(this).parent().remove();
        syncGalleryImgs();
    });

    $("#next, #save").click(function (e) {
        e.preventDefault();
        var termsCheckbox = document.getElementById("space_terms_accepted");
        if (termsCheckbox && !termsCheckbox.checked) {
            termsCheckbox.reportValidity();
            return;
        }
        var allSelected = $("#grid td.k-state-selected");
        var allSelectedModels = {
            'mon': [],
            'tue': [],
            'wed': [],
            'thu': [],
            'fri': [],
            'sat': [],
            'sun': []
        };

        $.each(allSelected, function (e) {
            var cell = $(this);
            var row = cell.closest("tr");
            var colIdx = $("td", row).index(cell);
            var colName = $('#grid').find('th').eq(colIdx)[0].dataset.field;
            allSelectedModels[colName].push(cell[0].innerHTML);
        });

        $("#available_slots").val(JSON.stringify(allSelectedModels));
        $("#action").val( $(this).val() );

        $("form#edit_space_form").submit();
    });

    $("#annulla").click(function (e) {
        window.location.href=$(this).attr('href');
    });
});
