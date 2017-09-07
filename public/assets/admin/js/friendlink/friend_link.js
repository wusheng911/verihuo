/**
 * Created by wanghenshuai on 16/12/02.
 */


var friendlyLinkTable;
var friendlyLinkId;
var friendlyLinkCurrentRow=-1;
var friendlyLinkMaxRow
var servePath;
$(document).ready(function() {
    servePath = App.path;
    friendlyLinkTable = $("#tableFriendlyLink").DataTable(conf());
    $('#tableFriendlyLink tbody').on( 'dblclick', 'tr', mouseDoubleClick);
    $('#tableFriendlyLink tbody').on( 'click', 'tr', mouseClick);

    $(document).keydown(keyboardDown);
});

function keyboardDown(event){
    if(event.keyCode == 32 || event.keyCode == 37 || event.keyCode == 38 || event.keyCode == 39 || event.keyCode == 40 || event.keyCode == 13){
        event.preventDefault();
        if(!friendlyLinkTable){
            return ;
        }


        friendlyLinkMaxRow = friendlyLinkTable.rows()[0].length;
        if(event.keyCode == 13){
            if(!isNaN(friendlyLinkId)){
                window.open('/'+servePath+"/friendlink/"+friendlyLinkId+"/edit");
            }
        }
        if(event.keyCode == 40){
            friendlyLinkCurrentRow++;

        }else if(event.keyCode == 38){
            friendlyLinkCurrentRow--;
        }
        if(friendlyLinkCurrentRow>=friendlyLinkMaxRow){
            friendlyLinkCurrentRow = friendlyLinkMaxRow - 1;
        }
        if(friendlyLinkCurrentRow<0){
            friendlyLinkCurrentRow =0;
        }
        friendlyLinkTable.$('tr.highlight').removeClass('highlight');
        friendlyLinkId = friendlyLinkTable.row(friendlyLinkCurrentRow).data().id
        friendlyLinkId = parseInt(friendlyLinkId);
        $(friendlyLinkTable.row(friendlyLinkCurrentRow).node()).addClass('highlight');

    }

}
function mouseClick(){
    friendlyLinkCurrentRow = $(this).context._DT_RowIndex;
    friendlyLinkTable.$('tr.highlight').removeClass('highlight');
    $(this).addClass('highlight');
    friendlyLinkId = friendlyLinkTable.row(friendlyLinkCurrentRow).data().id;
}
function mouseDoubleClick(){
    friendlyLinkId = friendlyLinkTable.row(friendlyLinkCurrentRow).data().id;
    window.open('/'+servePath+"/friendlink/"+friendlyLinkId+"/edit");


}
function conf(){
    var conf = {
        serverSide: true,
        "bSort":true,
        iDisplayLength: 100,
        'asSorting':'desc',
        "lengthMenu": false,
        "bPaginate":true,
        "bLengthChange":false,
        ajax: {
            url: "/"+servePath+"/friendlink/list",
            data:{}
        },
        "columnDefs": [
            {"className": "dt-left", "targets": 0},
            {"className": "dt-center", "targets": "_all"}
        ],
        columns:[
            {data: 'id', width: '16px',"bSort":true,"asSorting":"desc"},
            {data: 'title'},
            {data: 'link'},
            {
                "ordering":true,
                'bSort':true,
                data: 'logo',
                width: '62px',
                orderable: false,
                "render": function ( data, type, full, meta ) {
                    var html = '<image style="width:30px;height:30px;" src='+data+'></imag>';
                    return html;
                }
            },
            {data: 'status'},
            {data: 'pos'},
        ]
    };

    return conf;
}
