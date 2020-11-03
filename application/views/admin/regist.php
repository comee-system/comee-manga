  
  <?php $this->load->view('topmenu'); ?>
  <?php $this->load->view('menu'); ?>
  <?php
    $txt = "登録"; 
    if($id > 0 ) $txt = "更新";

  ?>

  <!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">ユーザ<?=$txt?></h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="/Admin/">Home</a></li>
              <li class="breadcrumb-item active">ユーザ<?=$txt?></li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="card card-primary card-outline">
          <div class="card-header">
            <h3 class="card-title">
              <i class="fas fa-edit"></i>ユーザ<?=$txt?>
            </h3>
          </div>
          <div class="card-body pad table-responsive" >
            <!-- /.row -->
            <?php if(count($error)):?>
              <div class="alert alert-danger alert-dismissible">
                <?php foreach($error as $values):?>
                  <?=$values?><br />
                <?php endforeach; ?>
              </div>
            <?php endif; ?>
            <?php if($message):?>
              <div class="alert alert-success alert-dismissible">
                <?=$message?>
              </div>
            <?php endif; ?>
            
            <form action="" method="POST" >

              <div class="row mt20">
                <div class="col-lg-12 col-12">
                  <div class="form-group">
                    <label >アイコン登録</label>
                    <br />
                      <small >下記、枠内に画像をドラッグしてください。</small>
                      <label class="fileupload" style="background-image:url('/<?=$D_IMAGE?><?=$id?>/icon/<?=$iconimage?>')">
                        画像をドラッグしてください
                        <input type="file" name="upfile" class="upfile">
                        <input type="hidden" name="filepath" id="filepath" value="" />
                      </label>

                  </div>
                </div>
              </div>

              <div class="row mt20">
                <div class="col-lg-3 col-12">
                  <div class="form-group">
                    <label >メールアドレス</label>
                    <small class="badge badge-danger">必須</small>
                    <input type="text" class="form-control" name="email" placeholder="Enter email" value="<?=$email?>" />
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-lg-8 col-12">
                  <div class="form-group">
                    <label >パスワード</label>
                    <small class="badge badge-danger">必須</small>
                    <input type="text" class="form-control w300" name="password" placeholder="Enter password" value="<?=$password?>"/>
                    
                    <?php if($id > 0 ): ?>
                      <p>変更しない場合は空欄で更新を行ってください</p>
                    <?php endif;?>
                  </div>
                </div>
                
              </div>

              <div class="row">
                <div class="col-lg-3 col-12">
                  <div class="form-group">
                    <label >ユーザID</label>
                    <small class="badge badge-danger">必須</small>
                    <input type="text" class="form-control" name="userid"  placeholder="Enter UserID" value="<?=$userid?>" />
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-lg-4 col-12">
                  <div class="form-group">
                    <label >ユーザネーム</label>
                    <small class="badge badge-danger">必須</small>
                    <input type="text" class="form-control" name="username"  value="<?=$username?>" placeholder="Enter username" />
                  </div>
                </div>
              </div>
              <div class="row">
                <div class="col-lg-4 col-12">
                  <div class="form-group">
                    <label >フリガナ</label>
                    <small class="badge badge-danger">必須</small>
                    <input type="text" class="form-control" name="usernamekana" placeholder="Enter kana" value="<?=$usernamekana?>" />
                  </div>
                </div>
              </div>
              <div class="row">
                    <label >生年月日</label>
              </div>
              <div class="row">
                  <div class="col-md-2 ">
                    <input type="text" name="birth_y" value="<?=$birth_y?>" class="form-control" />
                  </div>
                  <div class="col-md-1 ">
                    <select name="birth_m" class="form-control">
                      <?php for($i=1;$i<=12;$i++):
                        $sel = "";
                        if($i == $birth_m ) $sel = "SELECTED";
                        ?>
                        <option value="<?=$i?>" <?=$sel?> ><?=$i?>月</option>
                      <?php endfor;?>
                    </select>
                  </div>
                  <div class="col-md-1 ">
                    <select name="birth_d" class="form-control">
                      <?php for($i=1;$i<=31;$i++):
                          $sel = "";
                          if($i == $birth_d ) $sel = "SELECTED";
                        ?>
                        <option value="<?=$i?>" <?=$sel?> ><?=$i?>日</option>
                      <?php endfor;?>
                    </select>
                  </div>
                  
              </div>


              
              <div class="row mt20">                
                  <label >年齢制限</label>
              </div>
              <div class="row"> 
                  <div class="btn-group btn-group-toggle" data-toggle="buttons">
                    <?php 
                      $chk0 = $chk1 = "";
                      $act0 = $act1 = "";
                      if(!$agelimit){
                        $chk0 = "checked";
                        $act0 = "active";
                      }else{
                        $chk1 = "checked";
                        $act1 = "active";
                      }
                    ?>

                    <label class="btn btn-secondary <?=$act0?>">
                      
                      <input type="radio" name="agelimit" value=0 autocomplete="off" <?=$chk0?> > しない
                    </label>
                    <label class="btn btn-secondary <?=$act1?>">
                      <input type="radio" name="agelimit" value=1 autocomplete="off" <?=$chk1?> > する
                    </label>
                    
                  </div>
              </div>
              <div class="row mt20">                
                  <label >ユーザタイプ</label>
              </div>
              <div class="row"> 
                  <div class="btn-group btn-group-toggle" data-toggle="buttons">

                    <?php 
                      $chk2 = $chk1 = "";
                      $act2 = $act1 = "";
                      if(!$type || $type == 2){
                        $chk2 = "checked";
                        $act2 = "active";
                      }elseif($type == 1){
                        $chk1 = "checked";
                        $act1 = "active";
                      }
                    ?>
                    <label class="btn btn-secondary <?=$act2?>">
                      <input type="radio" name="type"  autocomplete="off" value=2 <?=$chk2?> > 一般ユーザ
                    </label>
                    <label class="btn btn-secondary <?=$act1?>">
                      <input type="radio" name="type"  autocomplete="off" value=1 <?=$chk1?>> 管理者
                    </label>
                    
                  </div>
              </div>


              <div class="mt20">
              <input type="submit" name="regist" value="<?=$txt?>" class="btn btn-primary" />
              <input type="hidden" name="id" value="<?=$id?>" />
              </div>
            </form>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->
