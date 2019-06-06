"use strict";

function newTovar()
{
     var grid = $("#catalog_tovar");
    var rowid = grid.jqGrid('getGridParam', 'selrow');
    alert(rowid)
}


function editTovar()
{
     var grid = $("#catalog_tovar");
    var rowid = grid.jqGrid('getGridParam', 'selrow');
    alert(rowid);

}