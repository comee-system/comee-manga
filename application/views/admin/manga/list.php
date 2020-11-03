  
  <?php $this->load->view('topmenu'); ?>
  <?php $this->load->view('menu'); ?>


  <!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">作品一覧</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">作品一覧</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <!-- Small boxes (Stat box) -->

        <!-- /.row -->
        <div class="row mt20">
          <div class="col-md-12">
            <div class="card card-success">
              <div class="card-header">
                <h3 class="card-title">フィルタリング</h3>
              </div>
              <div class="card-body">
                <form action="" method="POST" >
                  <div class="row">
                    <div class="col-md-3">
                      <label>連載タイトル</label>
                      <input type="text" name="manga_name" value="<?=$manga_name?>" class="form-control" >
                    </div>
                    <div class="col-md-3">
                      <label>ユーザー名</label>
                      <input type="text" name="username" value="<?=$username?>" class="form-control">
                    </div>
                    <div class="col-md-3">
                      <label>ジャンル</label>
                      <select name="genre_name" class="form-control">
                        <option value=""></option>
                        <?php foreach($genre as $key=>$value): 
                            $sel = "";
                            if($genre_name == $value->name){
                              $sel = "SELECTED";
                            }
                          ?>
                          <option value="<?=$value->name?>" <?=$sel?>><?=$value->name?></option>
                        <?php endforeach;?>
                      </select>
                    </div>
                    <div class="col-md-3">
                      <label>タグ</label>
                      <input type="text" name="tag_name" value="<?=$tag_name?>" class="form-control" >
                    </div>
                  </div>
                  <div class="row mt20">
                    <input type="submit" name="search" value="検索" class="btn btn-success" /> 
                  </div>
                </form>
              </div>
              <!-- /.card-body -->
            </div>
          </div>
        </div>

        <div class="row mt20">
          <div class="col-lg-12">
            <?=$pager?>
          </div>
        </div>

        <div class="row mt20">
          
          <div class="col-md-12">
            <div class="card overx" >
              <div class="card-header">
                <h3 class="card-title">作品一覧</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <?php if($message):?>
                  <div class="alert alert-success alert-dismissible">
                    <?=$message?>
                  </div>
                <?php endif; ?>

                <table class="table table-bordered" style="min-width:1200px;">
                  <thead>                  
                    <tr>
                      <th class="w250">機能</th>
                      <th class="w100">バナー</th>
                      <th class="w250">連載タイトル</th>
                      <th class="w250">ユーザ名</th>
                      <th>ジャンル</th>
                      <th>登録日</th>
                      <th>タグ</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach($list as $key=>$values): 
                      
                      ?>
                      <tr>
                          <td>
                            <a href="/Admin/Manga/regist/<?=$values->id?>" class="btn btn-primary">更新</a>
                            <a href="/Admin/Manga/delete/<?=$values->id?>" class="user_delete btn btn-danger">削除</a>
                            <a href="" class="btn btn-info">作品詳細</a>
                          </td>
                          <td>
                            <?php if($values->announce_image):?>
                            <img src="/assets/image/<?=$values->user_id?>/announce/<?=$values->id?>/xs_<?=$values->announce_image?>" />
                            <?php endif;?>
                          </td>
                          <td><?=remove_invisible_characters($values->manga_name)?></td>
                          <td><?=remove_invisible_characters($values->username)?></td>
                          <td><?=remove_invisible_characters($values->genre_name)?></td>
                          <td><?=date("Y/m/d",strtotime($values->update_ts))?></td>
                          <td><?=remove_invisible_characters($values->tag_name)?></td>
                          
                      </tr>

                    <?php endforeach;?>
                  </tbody>
                </table>
              </div>
              <!-- /.card-body -->
            </div>
            <!-- /.card -->
          </div>






        </div>
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>
<div class="modal"  >
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="myModalLabel">削除確認画面</h4></h4>
            </div>
            <div class="modal-body">
                <label>データを削除しますか？</label>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal" id="close">閉じる</button>
                <a href="" id="user_delete" class="btn btn-danger" >削除</a>
            </div>
        </div>
    </div>
</div>

<!-- /.content-wrapper -->
