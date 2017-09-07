$.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
});

// set up init confirm 

jconfirm.defaults = {
  title: '系统提示',
  content: '您确定要继续吗?',
  contentLoaded: function(){
  },
  icon: 'fa fa-warning text-warning',
  confirmButton: '确定',
  cancelButton: '取消',
  confirmButtonClass: 'btn-success',
  cancelButtonClass: 'btn-danger',
  theme: 'material',
  animation: 'top',
  closeAnimation: 'bottom',
  animationSpeed: 800,
  animationBounce: 1.2,
  keyboardEnabled: true,
  rtl: false,
  confirmKeys: [13], // ENTER key
  cancelKeys: [27], // ESC key
  container: 'body',
  autoClose: 'cancel|6000',
  confirm: function () {
  },
  cancel: function () {
  },
  backgroundDismiss: false,
  autoClose: false,
  closeIcon: null,
  columnClass: 'col-lg-6 col-lg-offset-3 col-md-4 col-md-offset-4 col-sm-6 col-sm-offset-3 col-xs-10 col-xs-offset-1',
  watchInterval: 100,
  onOpen: function(){
  },
  onClose: function(){
  },
  onAction: function(){
  }
};

$.extend( true, $.fn.dataTable.defaults, {
  "oLanguage": {
    "sSearch":"搜索",
    "sLengthMenu": "每页显示 _MENU_ 条记录",
    "sZeroRecords": "抱歉， 没有找到",
    "sInfo": "从 _START_ 到 _END_ /共 _TOTAL_ 条数据",
    "sInfoEmpty": "没有数据",
    "sInfoFiltered": "(从 _MAX_ 条数据中检索)",
    "oPaginate": {
      "sFirst": "首页",
      "sPrevious": "前一页",
      "sNext": "后一页",
      "sLast": "尾页"
    }
  }
});
