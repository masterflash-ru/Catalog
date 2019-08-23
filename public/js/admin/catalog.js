"use strict";

function newTovar(type)
{
    $.get("/adm/admin_catalog/tovar_new",{type:type},function(data, textStatus, jqXHR){
        if (textStatus=="success"){
            $.get("/adm/universal-interface/tovar_detal",{id:data.id},function(data, textStatus, jqXHR){
                if (textStatus=="success"){
                    $("#dialog_catalog").html(data);
                    $("#dialog_catalog").dialog("open");
                } else {alert(textStatus);}
            });
        } else {alert(textStatus);}
    });
}


function editTovar(el)
{
    var rowid = $(el).closest("tr.jqgrow").attr("id");
    if (rowid){
        $.get("/adm/universal-interface/tovar_detal",{id:rowid},function(data, textStatus, jqXHR){
            if (textStatus=="success"){
                $("#dialog_catalog").html(data);
                $("#dialog_catalog").dialog("open");
            } else {alert(textStatus);}
        });
    } 
}



$(document).ready(function() {
//добавим диалог-окно
$("body").append('<div id="dialog_catalog" title="Редактор товара"></div>');
    $("#dialog_catalog").dialog({
        resizable:true,
        closeOnEscape:true,
        width:"auto",
    autoOpen:false,
    modal:true,
        position:{
            my:"left top",
            at:"left top",
            of:".fixed-top-item"
        },
    });
});