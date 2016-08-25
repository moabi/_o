var colors = " <input style='background:#25a0e8' value='#25a0e8' readonly />  <input style='background:#5e4886' value='#5e4886' readonly />  <input  style='background:#a2c739' value='#a2c739' readonly /><br />";
var formBtn = '<div style="display:hidden"> <form id="btn-ear"><div id="lyra-btn"> <p><input  type="text" value="" placeholder="Button Link" name="lyra-link" id="lyra-link" /></p><p><input type="text" value="" placeholder="Button Text" name="text" id="lyra-text" /></p><p><select name="lyra-align" id="lyra-align"><option value="alignleft">Button Align Left</option><option value="aligncenter">Button Align Center</option><option value="alignright">Button Align Right</option></select> </p><p><label>'+colors+'</label><input type="text" value="" placeholder="Background Color" name="lyra-background" id="lyra-background" class="color-field" /><input type="hidden" value="" id="lyra-clr" /></p><p><select id="lyra-target" name="lyra-target"><option value="_self">open in Same window</option><option value="_blank">open in New window</option></select></p><p><input  type="text" value="" placeholder="Elegant Icon" name="icon" id="lyra-icon" /><a href="http://www.elegantthemes.com/blog/resources/elegant-icon-font" target="_blank">See list here</a> </p><p><input  type="text" value="" placeholder="Custom image uploaded via Wordpress" name="lyra-image" id="lyra-image" /></p><p><select name="lyra-icon-align" id="lyra-icon-align"><option value="10%">Icon Align Left</option><option value="50%">Icon Align Center</option><option value="90%">Icon Align Right</option></select> </p><p><input type="text" value="" placeholder="SubTitle (optional)" name="lyra-sub" id="lyra-sub" /></p><p><input type="submit" id="closeCustomBtn" value="Insert Shortcode button" class="button button-primary button-large"></p></div></form></div>';

jQuery(document).ready(function(jQuery) {

    tinymce.create('tinymce.plugins.wpse72394_plugin', {
        init : function(ed, url) {
            // Register command for when button is clicked
            ed.addCommand('wpse72394_insert_shortcode', function() {
                selected = tinyMCE.activeEditor.selection.getContent();

                jQuery('.color-field').wpColorPicker({
                    palettes: ['#25a0e8', '#5e4886', '#a2c739'],
                    change: function(event, ui) {
                        var selectedColor = ui.color.toString();
                        jQuery('#lyra-clr').val(selectedColor);
                    }
                });

                if(jQuery('#lyra-btn').length === 0 ){
                    jQuery('body').append(formBtn);
                }

                tb_show("Lyra Network - Custom Button" , "#TB_inline?height=800&amp;width=820&amp;inlineId=btn-ear");
                jQuery('#lyra-btn input').each(function(){
                    jQuery(this).keyup(function(){
                        jQuery(this).attr('value',jQuery(this).val());
                    });
                });

                jQuery('#closeCustomBtn').one("click",function(e){
                    e.preventDefault();

                    self.parent.tb_remove();
                    var target = jQuery('#lyra-target option:selected').val();
                    if (typeof target === "undefined") {
                        var target = "_self";
                    }
                    var align = jQuery('#lyra-align option:selected').val();
                    if (typeof align === "undefined") {
                        var align = "alignleft";
                    }
                    var iconAlign = jQuery('#lyra-icon-align option:selected').val();
                    if (typeof iconAlign === "undefined") {
                        var iconAlign = "90%";
                    }
                    var link = jQuery('#lyra-link').val();
                    var text = jQuery('#lyra-text').val();
                    var background =  jQuery('#lyra-background').val();
                    if (typeof background === "undefined") {
                        var background = "#25a0e8";
                    }
                    var icon = jQuery('#lyra-icon').val();
                    var image = jQuery('#lyra-image').val();
                    var sub = jQuery('#lyra-sub').val();


                    var content =  '[button link="'+ link +'" text="'+ text +'" sub="'+ sub +'" background="'+ background +'" target="'+ target +'" icon="'+ icon +'" image="'+ image +'" align="'+ align +'" iconalign="'+ iconAlign +'"]';
                    if(link !== ''){
                        tinymce.execCommand('mceInsertContent', false, content);
                        return false;
                    } else {
                        alert('link is missing...');
                        return false;
                    }

                    return false;
                });

            });

            // Register buttons - trigger above command when clicked
            ed.addButton('wpse72394_button', {title : 'Insert Button with Icon', cmd : 'wpse72394_insert_shortcode', image: url + '/one62.png' });
            return false;
        }

    });

    // Register our TinyMCE plugin
    // first parameter is the button ID1
    // second parameter must match the first parameter of the tinymce.create() function above
    tinymce.PluginManager.add('wpse72394_button', tinymce.plugins.wpse72394_plugin);
    return false;
});