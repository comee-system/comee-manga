var _nowUrl = location.href;
$(function(){
    /************************
     * タグ追加
     */
    $(".tagAddButton").click(function(){
        
        //var _url = "/Admin/Manga/regist/";
        var _url = location.href;
        
        var _tagname = $("input[name='tagadd']").val();
        var _uid = $("[name='user_id']").val();
        var _data = {
            "ajax":"on",
            "name":_tagname,
            "user_id":_uid
        };
        $.ajax({
            url:_url,
            type:'post',
            data: _data
        }).done(function (data) {
            $(this).getTag();
        });
        return false;
    });

    $(this).getTag();
    $("[name='user_id']").change(function(){
        $(this).getTag();
    });
});
$.fn.getTag = function(){
    
    $("#tagList").html("");
    //var _url = "/Admin/Manga/regist/";
    var _url = location.href;
    
    var _uid = $("[name='user_id']").val();
    var _data = {
        "getTag":"on",
        "user_id":_uid
    };
    var _html = "";
    $.ajax({
        url:_url,
        type:'post',
        data: _data,
        datatype: "json",
    }).done(function (data) {
        
        $.each(data,function(key,value){
            var _chk = "";
            if($("#tag"+value['id']).val() == "on" ) _chk="CHECKED";
            _html = "<div class='icheck-primary mt10 col-md-3'>";
            _html += "<input type='checkbox' id='checkboxTag"+value['id']+"' name='tag["+value['id']+"]' value='on' "+_chk+">";
            _html += "<label for='checkboxTag"+value['id']+"'>"+value['name']+"</label>";
            _html += "</div>";
            
            $("#tagList").append(_html);
        });
        
    });
    return false;
};