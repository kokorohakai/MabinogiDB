function Core(){
	this.genGridOptions = {
		loadtext:"please wait...",
		datatype:"json",
		mtype:"GET",
		altRows: true,
		sortorder:"desc",
		height:"auto"
	}

	this.checkErrors = function( result ){
		if ( result && result.errors ){
			var errors = result.errors;
			if ( errors ){
				var html = "";
				html+="The following errors were detected:<br><ul>";
				for (i in errors){
					html+="<li>";
					html+=errors[i];
					html+="</li>";
				}
				html+="</ul>";
				$("#body-errors").html(html).show();
			}
		}
	}

	var self = this;

	(function Constructor(){
		for (i in Core.modules){
			if ( typeof(Core.modules[i]) == "function"){
				self[i] = new Core.modules[i]( self );
			}
		}
		//$(document).ajaxStop( events.ajax );
	})();
}

//assign modules with name : class method
Core.modules = {};

$(function(){
	new Core();
})