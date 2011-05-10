jQuery(document).ready(function($) {
	if ( $('#callout').length ) {	
	  $("#callout").delay(500).animate({ fontSize: "14px", width: "140px", paddingTop: "21px", paddingRight: "21px", paddingBottom: "21px", paddingLeft: "21px" }, 100 );
	  $("#callout").animate({ fontSize: "12px", width: "120px", paddingTop: "18px", paddingRight: "18px", paddingBottom: "18px", paddingLeft: "18px" }, 200 );
	}
});
