<?php
/**
 * @link       http://onebyte.nz
 * @since      1.0.0
 * @package    Eventfinder Lite
 * @subpackage eventfinder/admin/partials
 * @author     Craig Sugden - onebyte.nz <info@onebyte.nz>
 */
?>
<div class="wrap">
    <h2><?php echo esc_html(get_admin_page_title());?></h2>
    <h2 id="tabs" class="nav-tab-wrapper">
      <a class="nav-tab nav-tab-active" href="#"><?php _e('General Setup', 'eventz-lite') ?></a>
      <a class="nav-tab" href="#"><?php _e('Display Options', 'eventz-lite') ?></a>
      <a class="nav-tab" href="#"><?php _e('Miscellaneous', 'eventz-lite') ?></a>
      <a class="nav-tab" href="#"><?php _e('Shortcode Guide', 'eventz-lite') ?></a>
    </h2>
    <div id='sections'>
        <form action="options.php" id="eventfindaOptions" method="post">
        <section>
                <?php
                    settings_fields('eventz-lite');
                    do_settings_sections($this->plugin_name . '_general');
                ?>
                <div id="info"></div>
        </section>
        <section>
            <?php
               settings_fields('eventz-lite');
               do_settings_sections($this->plugin_name . '_display');
               echo '<br/>';
               submit_button(__('Update', 'eventz-lite'), 'primary',  'eventfindaOptions', false);
           ?>
        </section>
        <section>
            <?php
               settings_fields('eventz-lite');
               do_settings_sections($this->plugin_name . '_misc');
            ?>
            <table class="form-table">
                <tbody>
                <tr>
                    <th scope="row"><label><?php _e('Review and Rate?', 'eventz-lite') ?></label></th>
                    <td><?php _e('If you like this plugin please', 'eventz-lite') ?> <a target="_blank" href="https://wordpress.org/support/plugin/eventz-lite/reviews/"><?php _e('review and rate it', 'eventz-lite') ?>.</a></td>
                </tr>
                </tbody>
            </table>
            <?php
               submit_button(__('Update', 'eventz-lite'), 'primary',  'eventfindaOptions', false);
            ?>
        </section>
        </form>
        <section>
            <p><em><strong><?php _e('How do I use the shortcode?', 'eventz-lite') ?></strong></em><br>
            <?php _e('Place the shortcode', 'eventz-lite') ?> <strong>[eventz-lite]</strong> <?php _e('in any page or post', 'eventz-lite') ?>.</p>
            <p><em><strong><?php _e('How do I refine the result sets?', 'eventz-lite') ?></strong></em><br>
            <?php _e('Visit', 'eventz-lite') ?> <a href="http://www.eventfinda.co.nz/" target="_blank">Eventfinda</a> <?php _e('and navigate to the “Parameters” section to see the available query parameters that can be used to retrieve the result sets', 'eventz-lite') ?>.</p>
            <p><em><strong><?php _e('I have events listed with Eventfinda, how do I display just those listings?', 'eventz-lite') ?></strong></em><br>
            <strong>[eventz-lite params=”username=<?php _e('my-eventfinda-username', 'eventz-lite') ?>”]</strong></p>
            <p><strong><?php _e('The Location Slug', 'eventz-lite') ?>:</strong><br>
            <?php _e('To find your location slug just visit', 'eventz-lite') ?> <a href="http://www.eventfinda.co.nz/" target="_blank">Eventfinda </a> <?php _e('and navigate to the location you would like to display listings for', 'eventz-lite') ?>.<br>
            <?php _e('The location slug is the last string in the url', 'eventz-lite') ?>:</p>
            <p>http://www.eventfinda.co.nz/whatson/events/<a href="http://www.eventfinda.co.nz/whatson/events/auckland-central" target="_blank">auckland-central</a></p>
            <p><?php _e('The location slug for the url above is', 'eventz-lite') ?> “auckland-central”.<br>
            <strong>[eventz-lite params=”location_slug=auckland-central”]</strong><br><br>
            <?php _e('The location slug can also be used for venues', 'eventz-lite') ?>.<br>
            <?php _e('Visit Eventfinda and search for your venue, example:', 'eventz-lite') ?> Henderson RSA.<br>
            <?php _e('Events for this venue will be displayed with a link to the venue, for the above example the link is', 'eventz-lite') ?>:<br>
            https://www.eventfinda.co.nz/venue/henderson-rsa-auckland-west<br>
            <?php _e('The location slug to enter for the above example is', 'eventz-lite') ?> henderson-rsa-auckland-west.<br>
            <strong>[eventz-lite params=”location_slug=henderson-rsa-auckland-west”]</strong></p>
            <p><strong><?php _e('The Category Slug', 'eventz-lite') ?>:</strong><br>
            <?php _e('To find the category slugs for your country visit your local Eventfinda site and click Find Events at the top of the page', 'eventz-lite') ?>.<br>
            <?php _e('When the Upcoming Events page has loaded you will see the event categories listed underneath the locations', 'eventz-lite') ?>.<br>
            <?php _e('Click the category you would like to display and get the category slug from the url', 'eventz-lite') ?>:</p>
            <p>http://www.eventfinda.co.nz/<a href="http://www.eventfinda.co.nz/concerts-gig-guide/events/new-zealand" target="_blank">concerts-gig-guide</a>/events/new-zealand</p>
            <p><?php _e('The category slug for the url above is', 'eventz-lite') ?> concerts-gig-guide.<br>
            <strong>[eventz-lite params=”category_slug=concerts-gig-guide”]</strong></p>
            <p><strong><?php _e('Example Shortcodes', 'eventz-lite') ?>:</strong></p>
            <p><?php _e('Auckland Events', 'eventz-lite') ?>:<strong><br>
            [eventz-lite params=”location_slug=auckland”]</strong><br>
            <?php _e('Auckland Gig Guide', 'eventz-lite') ?>:<strong><br>
            [eventz-lite params=”location_slug=auckland&amp;category_slug=concerts-gig-guide”]</strong></p>
            <p><?php _e('The paramaters for rows and offset are taken care of by the plugin (results per page in the admin setup)', 'eventz-lite') ?>.</p>
            <p><?php _e('More information on querying the', 'eventz-lite') ?> <a href="http://www.eventfinda.co.nz/api/v2/events" target="_blank">Eventfinda API</a><br>
            <a href="https://plugin.onebyte.nz/eventz-lite/docs/" target="_blank">https://plugin.onebyte.nz/eventz-lite/docs/</a></p>
        </section>
    </div>
</div>
<script type="text/javascript">
    jQuery(document).ready(function($){"use strict";var checkrun=false;var verified=false;var ajaxSubmit=false;var info_loading='<span id="info_loading" class="spinner" style="visibility:visible;float:none;"></span>';var validator=$("#eventfindaOptions").validate({errorElement:"span",submitHandler:function(){ajaxSubmit=false;if(!ajaxSubmit){$("#submit").attr("disabled",true);ajaxSubmit=false;HTMLFormElement.prototype.submit.call($('#eventfindaOptions')[0]);}else{$("#eventfindaOptions").ajaxSubmit({success:function(){$('#info').html('');},timeout:5000});ajaxSubmit=false;return false;}}});$(function(){$('[title]').mouseenter(function(e){var TooltipOpacity=0.9;var id=$(this).attr('id');var Tooltip=$(this).attr('title');if(Tooltip!==''&&id==='eventz-icon'){$(this).attr('customTooltip',Tooltip);$(this).attr('title','');}var customTooltip=$(this).attr('customTooltip');if(customTooltip!==''&&id==='eventz-icon'){var X=e.pageX;var Y=e.pageY;var offsetX=25;var offsetY=-35;var tipToBottom,tipToRight;$("body").append('<div id="eventztip">'+customTooltip+'</div>');var outerWidth=$('#eventztip').outerWidth();var outerHeight=$('#eventztip').outerHeight();tipToRight=$(window).width()-(X+offsetX+outerWidth+15);if(tipToRight<offsetX){X+=tipToRight+offsetX;Y-=outerHeight-25;}else{X+=offsetX;}tipToBottom=$(window).height()-(Y+offsetY+outerHeight+5);if(tipToBottom<offsetY){Y+=tipToBottom;}else{Y+=offsetY;}$('#eventztip').css('left',X);$('#eventztip').css('top',Y);$('#eventztip').fadeIn('1000');$('#eventztip').fadeTo('10',TooltipOpacity);}}).mousemove(function(e){var X=e.pageX;var Y=e.pageY;var offsetX=25;var offsetY=-35;var tipToBottom,tipToRight;var outerWidth=$('#eventztip').outerWidth();var outerHeight=$('#eventztip').outerHeight();tipToRight=$(window).width()-(X+offsetX+outerWidth+15);if(tipToRight<offsetX){X+=tipToRight+offsetX;Y-=outerHeight-25;}else{X+=offsetX;}$('#eventztip').css('left',X);tipToBottom=$(window).height()-(Y+offsetY+outerHeight+5);if(tipToBottom<offsetY){Y+=tipToBottom+offsetY;}else{Y+=offsetY;}$('#eventztip').css('top',Y);}).mouseleave(function(){$("body").children('div#eventztip').remove();});});$(document).on('click','.nav-tab-wrapper a',function(){$('section').hide();$('section').eq($(this).index()).show();$('.nav-tab-wrapper a').removeClass('nav-tab-active');$('.nav-tab-wrapper a').eq($(this).index()).addClass('nav-tab-active');return false;});$('#_username').on('change',function(){verified=false;$(this.form).valid();if(this.value&&$('#_password').val()){$(this).chkuser();}});$('#_password').on('change',function(){verified=false;$(this.form).valid();if(this.value&&$('#_username').val()){$(this).chkuser();}});$('#_endpoint').on('change',function(){verified=false;$(this.form).valid();if($('#_username').val()&&$('#_password').val()){$(this).chkuser();}else{$('#_apilink').attr('href','http://'+$('#_endpoint').val()+'/api/v2/index');}});$('body').on('change','#_delete_options',function(){if($(this).is(':checked')){var str='<p style="text-align:center;">'+'Checking this box will delete all of the plugins settings from the database '+'if the plugin is uninstalled. This action cannot be undone.'+'</p>';$('#info').attr('title','Delete Database Settings?');$("#info").html(str);$("#info").dialog({resizable:false,height:225,modal:true,buttons:{"Continue":function(){$(this).dialog('close');},Cancel:function(){$('form #_delete_options').attr('checked',false);$(this).dialog('close');}}});}});$('body').on('change','#_eventfinda_logo',function(){if($('form #_eventfinda_text').is(':checked')){$('form #_eventfinda_text').attr('checked',false);}else{$('form #_eventfinda_logo').attr('checked',true);}});$('body').on('change','#_eventfinda_text',function(){if($('form #_eventfinda_logo').is(':checked')){$('form #_eventfinda_logo').attr('checked',false);}else{$('form #_eventfinda_text').attr('checked',true);}});$('body').on('change','#_show_plugin_logo',function(){if($('form #_show_plugin_link').is(':checked')){$('form #_show_plugin_link').attr('checked',false);}});$('body').on('change','#_show_plugin_link',function(){if($('form #_show_plugin_logo').is(':checked')){$('form #_show_plugin_logo').attr('checked',false);}});$.fn.chkuser=function(){var _username=$('#_username').val();var _password=$('#_password').val();var _endpoint=$('#_endpoint').val();var data={'action':'check_user','username':_username,'password':_password,'endpoint':_endpoint};$('#info').attr('title','API Login');$('#info').html('<p style="text-align:center;">Logging in to Eventfinda<br/>Please Wait...<br/>'+info_loading+'</p>');$("#info").dialog({resizable:false,height:170,modal:true});$.post(ajaxurl,data,function(response){if(response!=='true'){checkrun=false;verified=false;$('#info').hide();$('#info').attr('title','An Error Occurred');$('#info').html('<p class="error" style="text-align:center;">'+response+'</p>');$("#info").dialog({resizable:false,height:205,modal:true});}else{verified=true;if(verified&&!checkrun){checkrun=true;ajaxSubmit=true;$('#info').attr('title','Success');$('#info').html('<p class="success">Eventfinda API Login Successful.<br/>Saving...<br/>'+info_loading+'</p>');$('#eventfindaOptions').submit();}}});};}(jQuery));
</script>
