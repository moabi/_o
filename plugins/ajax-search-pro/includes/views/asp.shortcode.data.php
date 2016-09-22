<?php
/* Prevent direct access */
defined('ABSPATH') or die("You can't access this file directly.");
?>
<div id="asp_hidden_data">

    <div class='asp_item_overlay'>
        <div class='asp_item_inner'>
            <?php
            if (w_isset_def($style['i_res_custom_magnifierimage'], "") == "" &&
                pathinfo(w_isset_def($style['i_res_magnifierimage'], "/ajax-search-pro/img/svg/magnifiers/magn4.svg"), PATHINFO_EXTENSION) == 'svg'
            ) {
                echo file_get_contents(WP_PLUGIN_DIR . '/' . w_isset_def($style['i_res_magnifierimage'], "/ajax-search-pro/img/svg/magnifiers/magn4.svg") );
            } else if (w_isset_def($style['i_res_custom_magnifierimage'], "") != "") {
                echo "<img src='".$style['i_res_custom_magnifierimage']."'>";
            }
            ?>
            <?php do_action('asp_layout_in_loading', $id); ?>
        </div>
    </div>

    <svg style="position:absolute" height="0" width="0">
        <filter id="aspblur">
            <feGaussianBlur in="SourceGraphic" stdDeviation="4"/>
        </filter>
    </svg>
    <svg style="position:absolute" height="0" width="0">
        <filter id="no_aspblur"></filter>
    </svg>

</div>
<script>
    <?php echo $scope; ?>(document).ready(function ($) {
        $('.asp-try a').on('click', function(e){
            e.preventDefault();
            var id = $(this).parent().attr('id').match(/^asp-try-(.*)/)[1];
            var $searchParent = $('#ajaxsearchpro' + id);
            var i = 1;
            while ($searchParent.hasClass('asp_main_container') == false) {
                $searchParent = $searchParent.prev();
                i++;
                if (i>15) return;
            }
            if ($searchParent.hasClass('asp_compact')) {
                var state = $searchParent.attr('asp-compact')  || 'closed';
                if (state == 'closed')
                    $('.promagnifier .innericon', $searchParent).click();
            }
            $('input', $searchParent).val('');
            $('input.orig', $searchParent).val($(this).html()).keydown();
            $('form', $searchParent).trigger('submit', 'ajax');
        });
    });
</script>