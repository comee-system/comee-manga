  
  <?php $this->load->view('topmenu'); ?>
  <?php $this->load->view('menu'); ?>


  <!-- Bootstrap4 Duallistbox -->
  <link rel="stylesheet" href="/assets/adminlte/plugins/bootstrap4-duallistbox/bootstrap-duallistbox.min.css">

  <!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">連載詳細登録</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">連載詳細登録</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
      <?php if($success):?>
        <div class="alert alert-success alert-dismissible">
          <?=$success?>
        </div>
      <?php endif; ?>
      <?php if(count($error) > 0 ):?>
        <div class="alert alert-danger alert-dismissible">
          <?php foreach($error as $value):?>
            <?=$value?><br />
          <?php endforeach; ?>
        </div>
      <?php endif; ?>

      <form action="" method="POST" >
        <div class="container-fluid">
          <!-- Small boxes (Stat box) -->
          <!-- /.row -->
          
            <div class="row mt20">
                <div class="col-lg-3 col-12">
                    <label>ユーザを選択</label>
                    <?php if($id < 1 ):?>
                      <small class="badge badge-danger">必須</small>
                      <select name="user_id" class="form-control">
                      <?php 
                        foreach($user as $key=>$values): 
                          $sel = "";
                          if($values->id == $param[ 'user_id' ]){
                            $sel = "SELECTED";
                          }
                        ?>
                          <option value="<?=$values->id?>" <?=$sel?> ><?=$values->username?></option>
                      <?php endforeach;?>
                      </select>
                    <?php else:
                      ?>
                      <p><?=$user->username?></p>
                      <input type="hidden" name="user_id" value="<?=$param[ 'user_id' ]?>" />
                    <?php endif; ?>
                </div>
            </div>
            <div class="row mt20">
                <div class="col-lg-12 col-12">
                    <label>連載の選択</label>
                    <small class="badge badge-danger">必須</small>
                    <select class="duallistbox" name="manga_id[]" id="duallistbox" multiple="multiple">
                        <?php foreach($manga as $key=>$value):
                            $sel = "";
                            if(in_array($value->id , $param['manga_id'])){
                              $sel = "SELECTED";
                            }
                          ?>
                            <option value="<?=$value->id?>" class="pd10 none user<?=$value->user_id?>" <?=$sel?> ><?=$value->manga_name?></option>
                        <?php endforeach;?>
                    </select>
                </div>         
            </div>
            <div class="row mt20">
                <div class="col-lg-9 col-12">
                    <label>連載タイトル</label>
                    <small class="badge badge-danger">必須</small>
                    <?php 
                      $title="";
                      if(!empty($param[ 'title' ])) $title = $param[ 'title' ];
                      ?>
                    <input type="text" class="form-control" name="title" value="<?=$title?>" />
                </div>         
            </div>
            <div class="row mt20">
                <div class="col-lg-9 col-12">
                    <label>連載キャプション</label>
                    <small class="badge badge-danger">必須</small>
                    <?php 
                      $caption="";
                      if(!empty($param[ 'caption' ])) $caption = $param[ 'caption' ];
                      ?>
                    <textarea class="form-control" name="caption" rows=4><?=$caption?></textarea>
                </div>         
            </div>
            <div class="row mt20">
              <div class="col-lg-2 col-12">
                <label>タグ新規追加/選択</label>
              </div>            
            </div>
            <div class="row ">
              <div class="col-lg-2 col-12">
                  <input type="text" class="form-control" name="tagadd" value="" />
              </div>
              <div class="col-lg-2 col-12">
                  <input type="button" name="tagAddButton" value="タグ追加" class=" tagAddButton btn btn-info" />
              </div>
            </div>

            <div class="row mt20">
              <div class="col-lg-12 col-12">
                <div class="form-group">
                  <label>タグ</label>
                  <div id="tagList" class="row"></div>
                  <?php 
                    if(!empty($check_tag) && count($check_tag)):
                      foreach($check_tag as $key=>$value):?>
                      <input type="hidden" id="tag<?=$key?>" value="on" />
                  <?php 
                      endforeach;
                    endif;
                  ?>
                </div>
              </div>
            </div>

            <div class="row mt20">
              <div class="col-lg-2 col-12">
              <label>年齢制限</label>
              <small class="badge badge-danger">必須</small>
              </div>            
            </div>
            <div class="row ">
                <div class="col-lg-9 col-12">
                    <?php foreach($g_array_ageflag as $key=>$value):
                        $chk = "";
                        $detail_age_limit = "";
                        if(
                          empty($param['detail_age_limit'])  &&
                          $key== "0"
                          ){
                            $chk = "CHECKED";
                          }
                          if(
                          isset($param['detail_age_limit'])  &&
                          $param['detail_age_limit'] == $key
                          ){
                            $chk = "CHECKED";
                          } 
                      ?>
                      <label>
                      <input type="radio" name="detail_age_limit"  value="<?=$key?>" <?=$chk?> />
                      <?=$value?>
                      </label>
                      <br />
                    <?php endforeach;?>
                </div>         
            </div>


            <div class="row mt20">
              <div class="col-lg-2 col-12">
              <label>表現内容</label>
              </div>            
            </div>
            <div class="row">
                
              <?php 
                foreach($expresstiondata as $key=>$value):
                  $chk = "";
                  if(isset($param['expression'][ $value->expression_id ]) && 
                  $param['expression'][ $value->expression_id ]){
                    $chk = "CHECKED";
                  }
                ?>
                <div class="col-lg-3 icheck-success">
                  <input type="checkbox" id="expression-<?=$key?>" name="expression[<?=$value->expression_id?>]"  value="on" <?=$chk?> />
                <label for="expression-<?=$key?>"><?=$value->name?></label>
                </div>
              <?php endforeach;?>
            </div>

            <div class="row mt20">
              <div class="col-lg-12 col-2">
              <label>販売設定</label>
                <small class="badge badge-danger">必須</small>
              </div>
              <div class="col-lg-12 col-2">
                <small class="">
                  ※有料の場合は、有料月額に加入すれば見える<br />
                  ※無料の場合は、有料月額に加入せず見える<br />
                </small>  
              </div>
            </div>
            <div class="row">
              <div class="col-lg-2 col-2">
                <select name="sale_flag" class="form-control" > 
                <?php 
                  foreach($g_array_sale_detail as $key=>$value):
                    $sel = "";
                    if($this->input->post('sale_flag') == $key) $sel = "SELECTED";
                  ?>
                  <option value="<?=$key?>" <?=$sel?> ><?=$value?></option>
                <?php endforeach; ?>
                </select>
              </div>
            </div>
            <div class="row mt20">
              <div class="col-lg-2 col-2">
              <label>リード文</label>
              </div>            
            </div>
            <div class="row">
              <div class="col-lg-12 col-12">
                <?php 
                $outline_text = "";
                if(!empty($param[ 'outline_text' ])):
                  $outline_text = $param[ 'outline_text' ];  
                endif;
                ?>
                <textarea class="form-control" name="outline_text" rows=3><?=$outline_text?></textarea>
              </div>
            </div>
            <div class="row mt20">
              <div class="col-lg-6 col-12">
                <div class="form-group">
                <label>サムネイル画像</label>
                <small >下記、枠内に画像をドラッグしてください。</small>
                  <label class="fileupload fileuploadthum" >
                    画像をドラッグしてください
                    <input type="file" name="upfile" class="upfile">
                    
                    <input type="hidden" name="filepath" id="filepath" value="/<?=$D_IMAGE?><?=$user_id?>/detail/<?=$id?>/thum/<?=$filepath?>" />
                  </label>
                </div>
              </div>
            </div>


            <div class="mt20 row pd10">
              <div class="col-lg-12 col-12">
                <input type="submit" class="btn btn-primary" name="regist" value="登録" />
              
                <input type="submit" class="btn btn-success" name="regist" value="漫画画像アップロード画面" />
              </div>
          </div>
        </div><!-- /.container-fluid -->
        
      </form>
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->
