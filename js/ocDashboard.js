var refreshId = new Array();

$(document).ready(function() {
	$(".dashboardItem").fadeIn();

	$('.dashboardItem').each(function(i, current){
		if($("#" + current.id).data('interval') != 0) {
		    refreshId[i] = setInterval(function() {
		    	    
	    	    	//console.log("refreshing " + current.id);
				    loadWidget(current.id);
		    	}
		    , $('#' + current.id).data('interval'));	
		    
		    //set status at start
    	    if($("#" + current.id).data('interval') != 0) {
    	    	setBgShadowColor(current.id,$('#' + current.id).data('status'));
    	    }
		    
    	    bindReload(current.id);
	    }
	});
});

//set bg color for widgetItem
function setBgShadowColor(id, status) {
	colors = new Array("black","black","darkgreen","orange","red");
	$('#' + id).css('-webkit-box-shadow','0px 5px 15px -7px ' + colors[status]);
	$('#' + id).css('-moz-box-shadow','0px 5px 15px -7px ' + colors[status]);
	$('#' + id).css('box-shadow','0px 5px 15px -7px ' + colors[status]);
	return true;
}

//bind click function to reload widget via ajax
function bindReload(id) {
    $('#' + id + ' .ocDashboard.head span').bind('click', function () {showWaitSymbol(id);loadWidget(id);});
}

//load widget via ajax and set in html
function loadWidget(id) {
	$.ajax({
	    dataType: "json",
	    url:  OC.filePath('ocDashboard', 'ajax', 'reloadWidget.php') + '?widget=' + id,
	    success: function(res) {
			if (res.success) {
				$('#' + res.id).fadeOut();
				$('#' + res.id).replaceWith(res.HTML);
				$('#' + res.id).fadeIn("slow");
			    //set new status
			    setBgShadowColor(id,$('#' + id).data('status'));
			    bindReload(id);
			}
			else {
				setBgShadowColor(id,4);
				console.log("no success from server");
			}
		},
        error: function(xhr, status, error) {
			setBgShadowColor(id,4);
			console.log("ajax error");
        }
    });
}

function showWaitSymbol(id) {
	$('.ocDashboard.inAction.' + id).fadeIn();
}

function hideWaitSymbol(id) {
	$('.ocDashboard.inAction.' + id).fadeOut();	
}