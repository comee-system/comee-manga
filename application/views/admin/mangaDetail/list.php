  
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
                      <br />
                      <?php
                        $title = $this->input->post("title");
                      ?>
                      <input type="text" name="title" value="<?=$title?>" class="form-control" />
                    </div>
                    <div class="col-md-6">
                      <label>連載名</label>
                      <br />
                      <select name="manga_id" class="form-control">
                        <option value=""></option>
                        <?php foreach($manga as $key=>$values):
                          $sel = "";
                          if($this->input->post("manga_id") == $values->id){
                            $sel = "selected";
                          }
                          ?>
                          <option value="<?=$values->id?>" <?=$sel?> ><?=$values->manga_name?></option>
                        <?php endforeach;?>
                      </select>
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
                <h3 class="card-title">連載詳細一覧</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                <?php if($message):?>
                  <div class="alert alert-success alert-dismissible">
                    <?=$message?>
                  </div>
                <?php endif; ?>

                <table class="table table-bordered" >
                  <thead>                  
                    <tr>
                      <th nowrap >機能</th>
                      <th>画像</th>
                      <th>連載タイトル</th>
                      <th>連載名</th>

                    </tr>
                  </thead>
                  <tbody>
                    <?php foreach($list as $key=>$values): 
                      
                      ?>
                      <tr>
                          <td nowrap>
                            <a href="/Admin/MangaDetail/regist/<?=$values->id?>" class="btn btn-primary">更新</a>
                            <a href="/Admin/Manga/delete/<?=$values->id?>" class="user_delete btn btn-danger">削除</a>
                            <a href="" class="btn btn-info">詳細画像登録</a>
                          </td>
                          <td>
                            <?php if($values->thumnail_filename):?>
                            <img src="/<?=$D_IMAGE?>/<?=$values->user_id?>/detail/<?=$values->id?>/thum/xs_<?=$values->thumnail_filename?>" />
                            <?php endif; ?>
                          </td>
                          <td><?=htmlspecialchars($values->title)?></td>
                          <td><?=preg_replace("/,/","<hr />",htmlspecialchars($values->manga_name))?></td>
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
