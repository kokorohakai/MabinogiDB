Core.modules["races"] = function( parent ){
	this.parent = parent;
	var page = 0;
	var perpage = 100;
	var data;

	function reloadGrid( data ){
		$("#admin_races").trigger("reloadGrid");
	}

	function insertRow(){
		$.ajax({
			url:"/api?m=races&a=insert",
			method:"get",
			complete: reloadGrid
		})
	}

	function deleteRow(e,i){
		if ( confirm("Are you sure you would like to delete the race, " + data[i].cell[0] + "?") ){
			$.ajax({
				url:"/api?m=races&a=delete",
				method:"post",
				data:{
					"id":data[i].id
				},
				complete: reloadGrid
			});
		}
	}

	function buildToolbar(){
		var button = $("<button>");
		button.append("New Row");
		button.on("click",insertRow);
		$("#t_admin_races").empty();
		$("#t_admin_races").append(button).addClass("buttonToolbar");

		$(".deleteButton").each(function(i){
			$(this).on("click",function(e){
				deleteRow(e,i);
			});
		});
			
	}

	function deleteButton(data, cell ){
		var button = "<button ";
		button += 'class="deleteButton"';
		button += ">X</button>";
		return button;
	}

	function saveData(inData){
		//keeps a copy of the data for local use.
		if (inData && inData.rows){
			data = inData.rows;
		}
	}
	
	(function Constructor(){
		$("#admin_races").jqGrid(Object.assign({
			url:"/api?m=races&a=getAll",
			colNames: ["Race",""],
			colModel: [
				{ name:"name", width: 180, editable:true },
				{ name:"action", width: 20, editable:false, formatter: deleteButton, classes:"centered" }
			],
			toolbar: [true,"bottom"],
			width: 200,
			rowNum: 100,
			pager: "#admin_pager",
			sortname:"name",
			caption:"Mabinogi Races",
			cellEdit: true,
			cellsubmit:"remote",
			cellurl:"/api?m=races&a=update",
			loadComplete: saveData,
			gridComplete: buildToolbar
		},parent.genGridOptions));
	})();
}