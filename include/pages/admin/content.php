<div class="container">
    <div class="row">
        <a href="<?php echo $App->getWebsite(); ?>/admin/content_form.html"><div class="btn btn-default btn-submit btn-new">New</div></a>
    </div>
    <div class="row">
        <table id="myTable" class="dataTable" cellspacing="0" cellpadding="0" border="0" >
            <thead>
            <tr>
                <th>Name</th>
                <th>Template</th>
                <th>Status</th>
                <th>Creado el</th>
                <th></th>
            </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>

<script>
    $(document).ready(function(){
        drawTable();
    });
    function drawTable(){

        $("#myTable").dataTable({
            "sDom": "Rlfrtip",
            "bPaginate": true,
            "bProcessing": true,
            "bServerSide": true,
            "sAjaxSource": "<?php echo $App->getWebsite(); ?>/admin/content.html",
            "oLanguage":{
                'sSearch':'Search by Name',
                "sLengthMenu": "Showing _MENU_ registries per page",
                "sZeroRecords": "No results",
                "sInfo": "Showing _START_ - _END_ from _TOTAL_ registries",
                "sInfoEmpty": "No results",
                "sInfoFiltered": "(Filtere _MAX_ total reistries)",
                "sProcessing": "Loading",
                "oPaginate": {
                    "sPrevious": "<span class='glyphicon glyphicon-chevron-left'></span>",
                    "sNext": "<span class='glyphicon glyphicon-chevron-right'></span>"
                }
            },
            "aaSorting": [[ 0, "desc" ]],
            "aoColumns":
                [
                    {"mDataProp": "name", "bSortable": false},
                    {"mDataProp": "template", "bSortable": false},
                    {"mDataProp": "status", "bSortable": false},
                    {"mDataProp": "created_at", "bSortable": false},
                    {"mDataProp": "id",  "bSortable": false,
                        "fnRender": function(obj) {
                            return '<a class="blue" href="<?php echo $App->getWebsite(); ?>/admin/content_form.html?id='+obj.aData.id+'"><span class="glyphicon glyphicon-edit"></span></a> '+
                                ' <a class="blue" href="<?php echo $App->getWebsite(); ?>/admin/content_delete?id='+obj.aData.id+'"><span class="glyphicon glyphicon-remove"></span></a>';
                       }}
                ],
            "fnServerData": function ( sSource, aoData, fnCallback ) {
                $.ajax( {
                    "dataType": 'json',
                    "type": "POST",
                    "url": sSource,
                    "data": aoData,
                    "success": fnCallback
                } );
            }
        });
    }

</script>

    
