  
  <?php $this->load->view('topmenu'); ?>
  <?php $this->load->view('menu'); ?>


  <!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">ユーザ一覧</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">ユーザ一覧</li>
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
        <div class="row">
          <div class="col-lg-12">
            <?=$pager?>
          </div>

        </div>
        <!-- /.row -->
        <div class="row mt20" style="width:1200px;">

          <div class="col-md-12">
              <div class="card">
                <div class="card-header">
                  <h3 class="card-title">ユーザ一覧</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body">
                  <?php if($message):?>
                    <div class="alert alert-success alert-dismissible">
                      <?=$message?>
                    </div>
                  <?php endif; ?>

                  <table class="table table-bordered">
                    <thead>                  
                      <tr>
                        <th >ユーザID</th>
                        <th>ユーザネーム</th>
                        <th>登録日</th>
                        <th>利用型</th>
                        <th class="w250">作品</th>
                        <th class="w400" >機能</th>
                      </tr>
                    </thead>
                    <tbody>
                    <?php foreach($userlist as $values):?>
                      <tr>
                        <td><?=htmlspecialchars($values->userid)?></td>
                        <td><?=htmlspecialchars($values->username)?></td>
                        <td><?=htmlspecialchars($values->update_ts)?></td>
                        <td nowrap><?=htmlspecialchars($g_array_type[$values->type])?></td>
                        <td class="w250"><?=preg_replace("/,/","<hr />",$values->item)?></td>
                        <td>
                          <a href="/Admin/Users/regist/<?=$values->id?>" class="btn btn-primary">更新</a>
                          <a href="/Admin/Users/delete/<?=$values->id?>" class="btn btn-danger user_delete">削除</a>
                          <a href="/Admin/Users/payment/<?=$values->userid?>" class="btn btn-success">支払先</a>
                          <a href="/Admin/Users/card/<?=$values->id?>" class="btn btn-info">カード</a>
                          <a href="/Admin/Users/manga/<?=$values->id?>" class="btn btn-warning">作品登録</a>
                        </td>
                      </tr>
                    <?php endforeach; ?>
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
