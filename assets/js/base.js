$(function(){
    /*******************
     * ログアウトリンク
     */
    $("#logout").click(function(){
        if(confirm("ログアウトします。よろしいですか？")){
            return true;

        }else{
            return false;
        }
    });
    /******************
     * 登録ボタン
     */
    $("[name='regist']").click(function(){
        if(confirm("処理を行います。よろしいですか。")){
            return true;
        }else{
            return false;
        }
    });
    /**************
     * ユーザー一覧削除ボタン
     */
    // モーダルのボタンをクリックした時
    $('.user_delete').on('click', function() {
        var _href = $(this).attr("href");
        $("#user_delete").attr("href",_href);
        $('.modal').fadeIn();
        return false;
    });

    // ×やモーダルの背景をクリックした時
    $('#close').click(function(){
        $('.modal').fadeOut(); // モーダルを非表示にする
    });




    //連載の選択
    $('.duallistbox').bootstrapDualListbox({
        filterTextClear:'全件表示',
        filterPlaceHolder:'検索',
        moveSelectedLabel:false,
        moveAllLabel:'選択済みに全て移動',
        removeSelectedLabel:false,
        removeAllLabel:'選択を全て解除',
        moveOnSelect: false,
        nonSelectedListLabel: '一覧',
        selectedListLabel: '選択済み一覧',
        infoText:false,
        showFilterInputs:true,
        infoTextEmpty:false,
        infoTextFiltered:false,
    });
});
