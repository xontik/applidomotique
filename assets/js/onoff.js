$(function(){
	function checkalloff(){
    var alloff = true;

	    $(".device").each(function(){
	    	if($(this).attr("class").indexOf("on") > -1){
	    		alloff = false;
	    	}
	    });
	    return alloff;
	}
	$(".device").click(function(){
		var id = $(this).attr("id");
		var state = $(this).attr("class").replace("device ","");
		var newState;
		if (state.indexOf("off") > -1){
			newState = "on";
		}else{
			newState="off";
		}

		jQuery.ajax({
			type: "POST",
			url: base_url + "core/ajax_id",
			dataType: 'json',
			data: {device_id: $(this).attr("id").replace("dev",""), state:newState},
			success: function(res,statut){
				if(res.success == true){
					if($("#"+id).attr("class").indexOf("off") > -1){
			    		$("#"+id).attr("class","on device");
			    		$("#switchall").attr("class","on");
			    	}else{
			    		$("#"+id).attr("class","off device");
			    		if(checkalloff()){
			    			$("#switchall").attr("class","off");
			    		}
			    	}
			    	
			    }else{
			    	console.log(res.success);
			    }
			},
			error : function(resultat, statut, erreur){
				
				console.log(resultat);
       		}

    	});
    	
    });

    $("#switchall").click(function(){
    	var id = $(this).attr("id");
		var state = $(this).attr("class");
		var newState;
		if (state.indexOf("off") > -1){
			newState = "on";
		}else{
			newState="off";
		}
		console.log(room);
		jQuery.ajax({
			type: "POST",
			url: base_url + "core/ajax_room",
			dataType: 'json',
			data: {room: room, state:newState},
			success: function(res,statut){
				if(res.success == true){
					$(".device").attr("class",newState+" device");
					if($("#"+id).attr("class").indexOf("off") > -1){
			    		$("#"+id).attr("class","on");
			    	}else{
			    		$("#"+id).attr("class","off");
			    	}
			    }else{
			    	console.log(res.success);
			    }
			},
			error : function(resultat, statut, erreur){
				alert("Oups ! Something happened !\n" + resultat.responseText);
				console.log(resultat);
       		}

    	});
    	
    });
    (function pullfromserveur(){
    	var states = [];
    	$(".device").each(function (){
    		states[$(this).attr("id").replace("dev", "")] = $(this).attr("class").replace(" device", "");
    	});
    	console.log(states);
    	jQuery.ajax({
			type: "POST",
			url: base_url + "core/ajax_new",
			dataType: 'json',
			data: {room: room, states:states},
			success: function(res,statut){
				if(res.change == true){
					for(var id in res.states){
						console.log(id);
						$("#dev"+id).attr("class",res.states[id]+" device");
					}
					if(checkalloff()){
			    			$("#switchall").attr("class","off");
			    	}else{
			    		$("#switchall").attr("class","on");
			    	}
			    }else{
			    	console.log("pas de changement");
			    }
			},
			error : function(resultat, statut, erreur){
				
				console.log(resultat);
       		}

    	});/**/
    	setTimeout(pullfromserveur,1000);

    })()

});