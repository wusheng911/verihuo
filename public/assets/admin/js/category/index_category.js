$(document).ready(onReady);

function displaytree() {
	$("#tree").treeview({
		collapsed: true,
		animated: "medium",
		control:"#sidetreecontrol",
		persist: "location"
	});
	$("#tree1").treeview({
		collapsed: true,
		animated: "medium",
		control:"#sidetreecontrol",
		persist: "location"
	});
}
function onSelectPID(){
    console.log(event.target.innerText);
    console.log(event.srcElement.children[0].id);
    $("#ptitle").attr("value",event.target.innerText);
	$("#pid").attr("value",event.srcElement.children[0].id);
}
function setNull(){
	$("#pid").attr("value",'');
	$("#ptitle").attr("value",'');
    }
    function onFocus(){
    $("#tree1").fadeToggle();
    
    }

function onReady(){
	displaytree();
}