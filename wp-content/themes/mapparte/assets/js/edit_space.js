jQuery(document).ready(function ($) {
	$('body').attr('data-mapparte-wizard-ready', '1');

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

	function toggleAvailabilityFields() {
        var hideAvailability = $("#hide_availability").is(":checked");
        $("#availability-fields").toggle(!hideAvailability);
    }

    $("#hide_availability").on("change", toggleAvailabilityFields);
	toggleAvailabilityFields();

	var requiredWizardFields = {
		space_mq: "Dimensione in metri quadri",
		max_people: "Numero massimo di persone",
		accessibility: "Accessibilità per disabili",
		floor_type: "Pavimento",
		space_access: "Accesso allo spazio",
		services: "Servizi",
		features: "Caratteristiche"
	};

	function fieldHasValue($field) {
		var $checks = $field.find('input[type="checkbox"], input[type="radio"]');
		if ($checks.length) {
			return $checks.filter(':checked').length > 0;
		}

		var $control = $field.find('input:not([type="hidden"]), select, textarea');
		if (!$control.length) {
			return true;
		}

		return $.trim($control.val() || '') !== '';
	}

	function validateRequiredWizardFields() {
		$('.mapparte-required-error').remove();
		var $firstInvalid = $();

		$.each(requiredWizardFields, function (fieldName, label) {
			var $field = $('.acf-field[data-name="' + fieldName + '"]');
			if (!$field.length || fieldHasValue($field)) {
				return;
			}

			$field.append('<p class="text-danger mapparte-required-error">Il campo ' + label + ' è obbligatorio.</p>');
			if (!$firstInvalid.length) {
				$firstInvalid = $field;
			}
		});

		if ($firstInvalid.length) {
			$('html, body').animate({scrollTop: Math.max(0, $firstInvalid.offset().top - 80)}, 250);
			return false;
		}

		return true;
	}

	function syncAvailability() {
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

		$.each(allSelected, function () {
			var cell = $(this);
			var row = cell.closest("tr");
			var colIdx = $("td", row).index(cell);
			var header = $('#grid').find('th').eq(colIdx)[0];
			if (header && header.dataset.field) {
				allSelectedModels[header.dataset.field].push(cell[0].innerHTML);
			}
		});

		$("#available_slots").val(JSON.stringify(allSelectedModels));
	}

	$(document).on('click', '#next, #save', function (e) {
		e.preventDefault();
		var isSaveAndClose = this.id === 'save';
		var form = document.getElementById('edit_space_form');

		if (!isSaveAndClose) {
			if (!validateRequiredWizardFields()) {
				return;
			}
			if (!form.checkValidity()) {
				form.reportValidity();
				return;
			}
		}

		syncAvailability();
		$("#action").val(isSaveAndClose ? 'salva e chiudi' : ($(this).data('action') || 'continua'));
		HTMLFormElement.prototype.submit.call(form);
	});

    $("#annulla").click(function (e) {
        window.location.href=$(this).attr('href');
    });
});
