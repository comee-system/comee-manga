/*********************
 * ファイルのアップロード
 */
$(function(){

    var obj = $("input[name='upfile']");

    obj.on('change', function (e) 
    {
        $(this).css('border', '2px dotted #0B85A1');    
        //We need to send dropped files to Server
        $(this).sendFileToServer();
    });
    /***********
     * hiddenに保存されているファイルを表示
     */
    $(this).displayImage();
});
$.fn.displayImage = function(){
    var _filepath = $("#filepath").val();
    if(_filepath){
        $("label.fileupload").css("background-image","url("+_filepath+")");
    }
};
$.fn.sendFileToServer = function(){
    let _upfile = $('input[name="upfile"]');
    let fd = new FormData();
    fd.append("upfile", _upfile.prop('files')[0]);
    $.ajax({
        url:'/Admin/Fileupload/file/',
        type:'post',
        data: fd,
        processData: false,
        contentType: false,
        cache: false,
    }).done(function (data) {
        // 成功時の処理
        if($.isNumeric(data)){
            alert("error");
        }else{
            var _path = data;
            $("label.fileupload").css("background-image","url(/assets/image/temp/"+_path+")");
            //hiddenにファイル名を保存
            $("#filepath").val("/assets/image/temp/"+_path);
        }
    }).fail(function() {
       // 失敗時の処理
       alert("error");
    });

};

