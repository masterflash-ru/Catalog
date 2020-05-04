"use strict";

/**
* изменение значений в полях вывода диапазона
*/
var mrange = $("[data-provide]").slider();
mrange.on("slide",(function(){
 let v=mrange.slider('getValue'),name="#"+$("[data-provide]").attr("name"); 
    $(name+"s").val(v[0]);$(name+"e").val(v[1]);
})
);
/**
* обратное действие, изменение с клавиатуры двигает бегунки
*/
$(".range-display").on("keyup",function(e){
    var newval=[$(this).data("min"),$(this).data("max")];
    $(".range-display").each(function(n,el){
        let vv=$(this).val();
        if (vv <= $(this).data("max") && vv >=$(this).data("min")  ){
            newval[n]=parseInt($(this).val());
        } 
    });
    mrange.slider('setValue', newval)
});
