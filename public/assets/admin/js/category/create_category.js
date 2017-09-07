$(document).ready(onMyReady);


function displaytree() {
	$("#tree").treeview({
		collapsed: true,
		animated: "medium",
		control:"#sidetreecontrol",
		persist: "location"
	});
}
function onSelectPID(event){
    $("#ptitle").attr("value",event.target.innerText);
	$("#pid").attr("value",event.srcElement.children[0].id);
	console.log($('#pid'));
}
function setNull(){
		$("#pid").attr("value",'');
		$("#ptitle").attr("value",'');
    }
function onFocus(){
	$("#tree").fadeToggle();
}
function oncategorychange(){
	$("#content_category_id").attr("value",categoryId);
}
    
function onMyReady(){
	displaytree();
	maxLevel = 2;
	$('#sure-pid').on('click', function () {
		$('#pid').attr('value',aimId);
		$('#ptitle').attr('value',aimName);
		})
}