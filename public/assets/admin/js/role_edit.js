function onMouseDown(){
  if(event.target.id == "sureAndBackList"){
    $('#backtype').attr('value',0);
  }else{
    $('#backtype').attr('value',1);
  }
}
