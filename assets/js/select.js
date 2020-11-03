$(function(){
    //ユーザー選択あとの連載の表示
    $(this).selects();
    $("[name='user_id']").change(function(){
        $(this).selects();
    });

});
$.fn.selects = function(){
    try{
        var _sel = $("[name='user_id']").val();
        if(!_sel) return false;
        $(".none").hide();
        
        var _u = "user"+_sel;
        $("."+_u).show();
    }catch(e){

    }
    return true;

}; 

