$(document).ready(function(){

  // Setup - add a text input to each footer cell
  $('#table thead tr#filterrow th').each( function () {
    var title = $('#table thead th').eq( $(this).index() ).text();
    var html = "";

    function genInput(name, title){
      return '<input name="' + name + '" type="text" onclick="stopPropagation(event);" placeholder="' + title + '" />';
    };

    if($(this).attr('filter') !== undefined){
      var filter = $(this).attr('filter');
      switch(filter){
      case "sn":
	html = genInput(filter, title + 'A-B');
	break;
      case "created_at":
      case "delivered_at":
      case "consignee_address":
	html = genInput(filter, title);
	break;
      case "status":
	console.log(CH.OrderStatus);
	var options = _.reduce(CH.OrderStatus, function(c, a){
	  return c + '<option value="' + a.id + '">' + a.description + '</option>';
	}, '');
	html = '<select id="status" multiple="multiple" name="status[] onclick="stopPropagation(event); ">' + options + '</select>';
	break;
      }
    }
    $(this).html(html);
  });

  // Apply the filter
  $("#table thead input").on( 'keyup change', function () {
    table.column( $(this).parent().index()+':visible' )
      .search( this.value )
      .draw();
  });

  var table = $("#table").DataTable(conf());

  $('input[name="created_at"]').daterangepicker(daterangeConf(), daterange1Callback);
  $('input[name="delivered_at"]').daterangepicker(daterangeConf(), daterange2Callback);

  //多选设置
  $('#status').multiselect({
    buttonClass: 'btn-sm btn-success',
    buttonWidth: '104px',
    numberDisplayed: 1,
    includeSelectAllOption: true,
    selectAllText: '全部状态',
    selectAllValue: 0,
    nonSelectedText: '请选择',
    allSelectedText: '全部状态',
    onSelectAll: function() {
      table.draw();
    },
    onDeselectAll: function() {
      table.draw();
    },
    onChange: function(option, checked, select) {
      table.draw();
    }
  });

  $('input[name="sn"], input[name="created_at"], \
     input[name="delivered_at"], #status, \
     input[name="total"], input[name="consignee_address"]')
    .keyup( function() {table.draw();} );

  $('.show-logistic-detail').click(function(event){
    var orderId = $(event.currentTarget).attr('oid');
    var lo = localStorage.getItem('admin.logisticOnline');
    var traces = [];
    // 设定刷新物流信息的时间， 6个小时
    if(lo && moment(JSON.parse(lo).when).isAfter(moment().subtract(6, 'hours'))){
      var data = JSON.parse(JSON.parse(lo).data);
      traces = data;
    }
    console.log(traces);
    // 空的话继续请求
    if(traces.length == 0){
      $.ajax({
	url: App.logisticUrl,
	data:{'id':orderId},
	async: false,
	method: 'GET',
	success: function(json){
	  if(json.status == "success"){
	    console.log(json);
	    localStorage.setItem('admin.logisticOnline', JSON.stringify({"when":moment(), "data":json.data}));
	    traces = JSON.parse(json.data);
	    if(traces.length == 0){
	      $.alert({
		content:"次订单暂无物流信息"
	      });
	    }
	  }
	}
      });
    }
    var html = _.reduce(traces, function(c, a){
      return c + '<label><span>' + a.AcceptTime + '</span> >>> <span class="text-danger">'
	+ a.AcceptStation + '</span></label><br>';
    }, "<br>");
    $(".logistic-detail").html(html);
    $(".logistic-detail").toggle();
  });

  function deleteRole(id){
    $.ajax({
      url: '/admin/role/'+id,
      method: 'DELETE',
      success: function(json){
	console.log(json);
      }
    });
  }

  function daterange1Callback(start, end, label) {
    $('input[name="created_at"]').data(
      'range', {'start': start.format('YYYY-MM-DD HH:mm:ss'),
		'end': end.format('YYYY-MM-DD HH:mm:ss')});
  }

  function daterange2Callback(start, end, label) {
    $('input[name="delivered_at"]').data(
      'range', {'start': start.format('YYYY-MM-DD HH:mm:ss'),
		'end': end.format('YYYY-MM-DD HH:mm:ss')});
  }

  function daterangeConf(){
    var conf = {
      startDate: '2016-01-01 00:00:00',
      endDate: moment().endOf('day'),
      minDate: '2016-01-01 00:00:00',
      maxDate: moment().endOf('day'),
      "showWeekNumbers": true,
      "showISOWeekNumbers": true,
      "timePicker24Hour": true,
      "ranges": {
	"所有": [
	  "2016-01-01 00:00:00",
	  moment().endOf('day'),
	],
	"今天": [
	  moment().subtract(0, 'day'),
	  moment().subtract(0, 'day')
	],
	"昨天": [
	  moment().subtract(1, 'day'),
	  moment().subtract(1, 'day')
	],
	"最近一周": [
	  moment().subtract(7, 'day'),
	  moment().subtract(0, 'day')
	],
	"最近30天": [
	  moment().subtract(30, 'day'),
	  moment().subtract(0, 'day')
	]
      },
      "locale": {
	"format": "YYYY-MM-DD",
	"separator": " ~ ",
	"applyLabel": "确定",
	"cancelLabel": "取消",
	"fromLabel": "从",
	"toLabel": "到",
	"customRangeLabel": "时间段选择",
	"weekLabel": "星期",
	"daysOfWeek": [
	  "日",
	  "一",
	  "二",
	  "三",
	  "四",
	  "五",
	  "六"
	],
	"monthNames": [
	  "一月",
	  "二月",
	  "三月",
	  "四月",
	  "五月",
	  "六月",
	  "七月",
	  "八月",
	  "九月",
	  "十月",
	  "十一月",
	  "十二月"
	],
	"firstDay": 1
      },
      "alwaysShowCalendars": false
    };
    return conf;
  }

  function conf(){
    var conf = {
      serverSide: true,
      bStateSave: true, 
      autoWidth: false,
      pagingType: "full_numbers",
      pageLength: 15,
      //iDisplayLength: 15,
      "bLengthChange": false,
      //"lengthMenu": [[10, 20, 50], [10, 20, 50]],
      "searching": false,
      ajax: {
	url: getListUrl,
	data: function(d){
	  d.sn = $('input[name="sn"]').val();
	  d.consignee_address = $('input[name="consignee_address"]').val();
	  d.created_at = $('input[name="created_at"]').data('range');
	  d.delivered_at = $('input[name="delivered_at"]').data('range');
	  var status = $('#status option:selected');
	  d.status = _.reduce(status, function(c, a){
	    return _.union(c , [$(a).val()]);
	  }, []);
	}
      },
      "order": [[1, 'desc']],
      "orderCellsTop": true,
      "columnDefs": [
	{"className": "dt-left", orderable: false, "targets": 7},
	{"className": "dt-center", "targets": "_all"}
      ],
      columns:[
	{data: 'sn', width: '60px'},
	{data: 'created_at', width: '116px'},
	{data: 'consignee_address',
	 render: function(data, type, full, meta) {
	   var html = '<p style="margin:0px;padding:0px;font-size:12px;">'+full.consignee_address+'</p>';
	   html += '<p style="margin:0px;padding:0px;font-size:12px;">'+full.consignee_phone+' '+full.consignee_name+'</p>';
	   return html;
	 }},
	{data: 'delivered_at', width:'116px' },
	{data: 'order_status.description', width: '68px',
	 render: function(data, type, full, meta) {
	   var c = '';
	   switch (full.status) {
	   case 1: c = 'text-warning'; break;
	   case 2: c = 'text-success'; break;
	   case 3: c = 'text-primary'; break;
	   case 4: c = 'text-info'; break;
	   case 5: c = 'text-info'; break;
	   case 6: c = 'text-success'; break;
	   case 7: c = 'text-danger'; break;
	     default : break;
	   }
	   
	   html = '<p class="' + c + '">' + data + '<p>';
	   return html;
	 }},
	{data: 'total', width: '44px'},
	{ data: 'paid_status', width: '22px',
	  render: function (data, type, full, meta) {
	    var html = '';
	    if(data == 1){
	      html =  '<p class="text-success">已支付</p>';
	    } else {
	      html =  '<p class="text-danger">未支付</p>';
	    }
	    return html;
	  }
	},
	{  data: null,
	   "orderable": false,
	   width: '48px',
	   "render": function ( data, type, full, meta ) {
	     var html = '<a class="edit glyphicon glyphicon-edit" href="order/'+data.id+'/edit" >处理</a>';
	     return html;
	   }
	}
      ]
    };

    return conf;
  }

  // order edit page
  $("#changeTotal").confirm({
    content: "您确定要修改订单的价格吗?",
    confirm: function(button) {
      var form = $("#changeTotal").parents("form");
      form.submit();
    }
  });

  $("#changeToPaid").confirm({
    content: "您确定要将未付款的订单修改为已付款吗?",
    confirm: function(button) {
      var form = $("#changeToPaid").parents("form");
      form.submit();
    }
  });

  $("#setLogistic").click(function(event){
    var t = $("#logistic-select").find("option:selected").text();
    var no = $('input[name="order[logistic_no]"]').val();

    var content = "您确定要将快递信息设置为：<br>"+'<h3>'+
	 t +' '+'<span class="text-danger">'+ no +'</span></h3>';
    $.confirm({
      content: content,
      confirm: function(button) {
	var form = $("#setLogistic").parents("form");
	form.submit();
      }
    });
  });

});//end document ready

function stopPropagation(evt) {
  if (evt.stopPropagation !== undefined) {
    evt.stopPropagation();
  } else {
    evt.cancelBubble = true;
  }
}
