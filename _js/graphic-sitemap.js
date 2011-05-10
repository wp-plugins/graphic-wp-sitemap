// JavaScript Document
jQuery(document).ready(function() {

var menu_width = 0;
jQuery('#ia_toplevel > li').each(function() {
menu_width += jQuery(this).outerWidth( true );
});
jQuery('#ia_toplevel').css('width', menu_width);

jQuery("#Gsm_modal_inner a").attr({ target: "_blank" });

//--------------------------------------------
 
//select all the a tag with name equal to modal
jQuery('a[name=modal]').click(function(e) {
	//Cancel the link behavior
	e.preventDefault();
	//Get the A tag
	var Gsm_modal_id = jQuery(this).attr('href');
 
	var maskHeight = jQuery(document).height();
	jQuery('body').append('<div id="Gsm_mask"></div>');
	jQuery('#Gsm_mask').css({'height':maskHeight});
	jQuery('body').css({'overflow':'hidden'});

	//transition effect     
	jQuery('#Gsm_mask').fadeIn(1000, function () {
		jQuery('#Gsm_modal_outer').addClass('fullscreen');
		jQuery('#Gsm_modal_outer').prependTo('body');
		var winH = jQuery(window).height();
		jQuery('#Gsm_modal_outer').css('top',  winH/2-jQuery('ul#ia_topmenu').height()/2);
		jQuery('#Gsm_modal_outer').css('max-height', winH-100);
	});    
 });
 
//if close button is clicked
jQuery('.Gsm_window .close').click(function (e) {
	//Cancel the link behavior
	e.preventDefault();
	//Revert values to boxed dashboard widget
	jQuery("#Gsm_mask").remove();
	//Changes class of widget content to get modal
	jQuery('#Gsm_modal_outer').removeClass('fullscreen');
	jQuery('#Gsm_modal_outer').prependTo('#Gsm_container');
	jQuery('body').removeAttr("style");
});     
 
//if mask is clicked
jQuery('#Gsm_mask').click(function () {
	jQuery(this).hide();
	jQuery('.Gsm_window').hide();
});         
 
});
