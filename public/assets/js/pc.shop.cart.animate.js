function MoveBox(obj) {
	var divTop = $(obj).offset().top;
	var divLeft = $(obj).offset().left;
	$(obj).css({ "position": "absolute", "z-index": "500", "left": divLeft + "px", "top": divTop + "px" });
	$(obj).animate({ "left": ($("#shopBarCart").offset().left - $("#shopBarCart").width()) + "px", "top": ($(document).scrollTop() + 30) + "px", "width": "40px", "height": "40px" }, 500, function () {
		$(obj).animate({ "left": $("#shopBarCart").offset().left + "px", "top": $("#shopBarCart").offset().top+ "px" }, 500).fadeTo('slow','0');
	});
}
