Core.modules["skills"] = function( parent ){
	this.parent = parent;

	var self = this;
	var page = 0;
	var perpage = 100;
	var data;
	var raceId = 1;

	var formats = {
		talents: function(data,cell){
			var html = "";
			return html;
		},
		editTalents: function(data, cell){
			var html = '<button class="editTalents" rowID="'+data+'">Edit Talents</button>';
			return html;
		},
		editAP: function(data, cell){
			var html = '<button class="editAP" rowID="'+data+'">Edit AP</button>';
			console.log(data, cell);
			return html;
		}
	}
	function reloadGrid( data ){
		$("#admin_skills").trigger("reloadGrid");
	}

	function insertRow(){
		$.ajax({
			url:"/api?m=skills&a=insert&race="+raceId,
			method:"get",
			complete: reloadGrid
		})
	}

	function deleteRow(e,i){
		if ( confirm("Are you sure you would like to delete the race, " + data[i].cell[0] + "?") ){
			$.ajax({
				url:"/api?m=skills&a=delete",
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
		$("#t_admin_skills").empty();
		$("#t_admin_skills").append(button).addClass("buttonToolbar");

		$(".deleteButton").each(function(i){
			$(this).on("click",function(e){
				deleteRow(e,i);
			});
		});
			
	}

	function deleteButton( data, cell ){
		var button = "<button ";
		button += 'class="deleteButton"';
		button += ">X</button>";
		return button;
	}

	function updateGrid(){
		raceId = $("#Race_List").val();
		$("#admin_skills").setGridParam({
			url: "/api?m=skills&a=getAll&race="+raceId
		})
		reloadGrid();
	}

	function registerEvents(){
		$("#Race_List").on("change",updateGrid);
	}

	function buildRaceSelect(data){
		if (data && data.rows){
			for ( var i in data.rows){
				var option = new Option(data.rows[i].cell[0], data.rows[i].id);
				$("#Race_List").append($(option));
			}
		}
		setTimeout(function(){
			//this is done just simply to let the DOM settle before accessing the select.
			registerEvents();
			updateGrid();
		},0);
	}

	function getRaces(){
		$.ajax({
			url:"/api",
			method:"GET",
			dataType:"json",
			data:{
				m:"races",
				a:"getAll",
				page:1,
				rows:100,
				sidx:"ID",
				sord:"asc"
			},
			success:buildRaceSelect
		});
	}

	function saveData(inData){
		//keeps a copy of the data for local use.
		if (inData && inData.rows){
			data = inData.rows;
		}
	}

	function gridComplete(){
		buildToolbar();
		console.log(self.parent);
		self.parent.skills_talents.update();
	}
	function buildGrid(){
		$("#admin_skills").jqGrid(Object.assign({
			url:"/api?m=skills&a=getBlank",
			colNames: ["Skill","Talents","","",""],
			colModel: [
				{ name:"name", width: 300, editable:true },
				{ name:"talents", width: 80, editable:false, formatter: formats.talents },
				{ name:"editTalents", width: 80, editable:false, formatter: formats.editTalents, classes:"centered" },
				{ name:"editAP", width: 80, editable:false, formatter: formats.editAP, classes:"centered" },
				{ name:"action", width: 20, editable:false, formatter: deleteButton, classes:"centered" }
			],
			toolbar: [true,"bottom"],
			rowNum: 100,
			pager: "#admin_pager",
			sortname:"name",
			caption:"Mabinogi Skills",
			cellEdit: true,
			cellsubmit:"remote",
			cellurl:"/api?m=skills&a=update",
			loadComplete: saveData,
			gridComplete: gridComplete
		},parent.genGridOptions));	
		getRaces();	
	}
	
	(function Constructor(){
		buildGrid();
	})();
}