<link rel="stylesheet" href="https://kendo.cdn.telerik.com/2021.1.224/styles/kendo.default-v2.min.css"/>
<script src="https://kendo.cdn.telerik.com/2021.1.224/js/kendo.all.min.js"></script>
<?php

if ( ! defined( 'WPINC' ) ) {
	die;
}
global $step_name, $space_data;
$step_name = __('Disponibilità',"mapparte");
?>
<div class="tab-content" id="myTabContent">
    <div class="tab-pane fade show active" id="italian" role="tabpanel"
         aria-labelledby="italian-tab">
        <h4 class="my-space-ttl"><?php echo esc_html( $step_name ); ?></h4>
        <p class="my-space-desc"><?php echo __("Sei libero di indicare o meno gli orari di disponibilità del tuo spazio.","mapparte"); ?></p>
        <div class="dimensioni-wrapper">
            <div class="row mb-4">
                <div class="col-sm-12">
                    <div class="form-check">
                        <input type="hidden" name="hide_availability" value="0">
                        <input class="form-check-input" type="checkbox" id="hide_availability"
                               name="hide_availability" value="1"
							<?php checked( ! empty( $space_data['hide_availability'] ) ); ?>>
                        <label class="form-check-label" for="hide_availability">
							<?php echo __( 'Preferisco non indicare gli orari e ricevere solo richieste di contatto', 'mapparte' ); ?>
                        </label>
                    </div>
                </div>
            </div>
            <div id="availability-fields">
            <div class="row">
                <div class="col-sm-12">
                    <div class="calendar-mobile" style="display:none">
                    <?php
					acf_form_head();
					$options = array(
						'field_groups' => array(), // this will find the field groups for this post (post ID's of the acf post objects)
						'fields'       => array( 'availability' ),
						'form'         => false, // set this to false to prevent the <form> tag from being created
						'html_before_fields' => '', // html inside form before fields
						'html_after_fields' => '', // html inside form after fields
					);
					acf_form( $options );
					?>
                    </div>
                    <div class="calendar-desktop" style="display:none">
					<?php

					$week_days = [ 'mon', 'tue', 'wed', 'thu', 'fri', 'sat', 'sun' ];
					$giorni = [ __('lun',"mapparte"), __('mar',"mapparte"), __('mer',"mapparte"), __('gio',"mapparte"), __('ven',"mapparte"), __('sab',"mapparte"), __('dom',"mapparte") ];

					$available_slots = [];

					foreach ( $week_days as $week_day ) {
					    if ( isset( $space_data['availability'] ) ) {
						    $available_slots[$week_day] = \Mapparte\Utils::get_slots_by_day( $space_data['availability'][ $week_day . '_opening_hours' ], $week_day );
                        }
					}

					?>
                    <div class="form-floating input-group">
                        <div id="grid"></div>
						<?php

						$slots = \Mapparte\Utils::prepare_time_slots( '00:00', '23:30' ); ?>

                        <script>
                            jQuery(document).ready(function ($) {
                                $("#grid").kendoGrid({
                                    dataSource: [
										<?php foreach ( $slots as $slot ) {
										echo "{";
										foreach ( $week_days as $day ) {
											echo "$day: \"$slot\",";
										}
										echo "},";
									} ?>
                                    ],
                                    selectable: 'multiple,cell,',
                                    ignoreOverlapped: true,
                                    editable: false,
                                    columns: [
										<?php
                                        foreach ( $week_days as $key => $day ) {
										echo '{ field: "' . esc_html( $day ) . '", title: "' . esc_html( $giorni[$key] ) . '" },';
									} ?>
                                    ]
                                }).data("kendoGrid");

                                var grid = $("#grid").data("kendoGrid");

								<?php foreach ( $slots as $key => $slot ) {
                                    foreach ( $week_days as $keyday => $day ) {
	                                    if ( isset($available_slots[$day] ) && in_array($slot, $available_slots[$day]) ) {
		                                    echo "var cell = grid.table.find('tbody tr:eq($key) td:eq($keyday)');grid.select(cell);\n";
	                                    }
                                    }
							    } ?>

                            });
                        </script>
                    </div>
                    </div>
                    <input type="hidden" id="available_slots" name="available_slots" value="">
                </div>
            </div>
            </div>
        </div>
    </div>
</div>
<script>
function detectMob() {
    const toMatch = [
        /Android/i,
        /webOS/i,
        /iPhone/i,
        /iPad/i,
        /iPod/i,
        /BlackBerry/i,
        /Windows Phone/i
    ];

    return toMatch.some((toMatchItem) => {
        return navigator.userAgent.match(toMatchItem);
    });
}
if (detectMob()){
    document.querySelector(".calendar-mobile").style.display = 'block';
}else{
    document.querySelector(".calendar-desktop").style.display = 'block';
}
</script>
