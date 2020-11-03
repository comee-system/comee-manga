  
  <?php $this->load->view('topmenu'); ?>
  <?php $this->load->view('menu'); ?>


  <!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">連載登録</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">連載登録</li>
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
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">連載登録</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
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
                  <div class="row mt20">
                    <div class="col-lg-3 col-12">
                      <div class="form-group">
                      <label>ユーザー名選択</label>
                        <small class="badge badge-danger">必須</small>
                        
                        <?php 
                          if($id > 0 ): 
                        ?>
                          <br clear=all />
                          <?php foreach($user as $key=>$value): ?>
                          <?php if($value->id == $user_id):?>
                          <?=$value->username?>
                          <?php endif;?>
                          <?php endforeach;?>
                          <input type="hidden" name="user_id" value="<?=$user_id?>" />
                          
                        <?php else: ?>
                          
                          
                          <select  class="form-control" name="user_id"  >
                          <?php foreach($user as $key=>$value): ?>
                            <?php $sel = "";
                              if($value->id == $user_id) $sel = "SELECTED";
                            ?>
                            <option value="<?=$value->id?>" <?=$sel?> ><?=$value->username?></option>
                          <?php endforeach;?>
                          </select>

                        <?php endif; ?>
                      </div>
                    </div>
                  </div>
                  <div class="row mt20">
                    <div class="col-lg-6 col-12">
                      <div class="form-group">
                      <label>連載タイトル</label>
                        <small class="badge badge-danger">必須</small>
                        <input type="text" name="manga_name" value="<?=$manga_name?>" class="form-control" />
                      </div>
                    </div>
                  </div>
                  <div class="row mt20">
                    <div class="col-lg-6 col-12">
                      <div class="form-group">
                      <label>連載タイトルかな</label>
                        <small class="badge badge-danger">必須</small>
                        <input type="text" name="manga_kana" value="<?=$manga_kana?>" class="form-control" />
                      </div>
                    </div>
                  </div>
                  <div class="row mt20">
                    <div class="col-lg-12 col-12">
                      <div class="form-group">
                      <label>連載説明</label>
                        <small class="badge badge-danger">必須</small>
                        <textarea name="message" class="form-control" rows=3><?=$message?></textarea>
                      </div>
                    </div>
                  </div>
                  <div class="row mt20">
                    <div class="col-lg-3 col-12">
                      <div class="form-group">
                      <label>販売設定</label>
                        <select name="sale" class="form-control">
                          <?php foreach($g_array_sale as $key=>$value):
                              $sel = "";
                              if($sale == $key) $sel = "SELECTED";
                            ?>
                            <option value="<?=$key?>" <?=$sel?> ><?=$value?></option>
                          <?php endforeach;?>
                        </select>
                      </div>
                    </div>
                    
                  </div>
                  <div class="row">
                    <div class="col-lg-12 col-12">
                      <dl class="row small">
                        <dt class="col-sm-1">無料</dt>
                        <dd class="col-sm-11">誰でも閲覧することができる</dd>
                        <dt class="col-sm-1">有料月額</dt>
                        <dd class="col-sm-11">月額金額を支払っている人が閲覧することができる</dd>
                        <dt class="col-sm-1">売切り</dt>
                        <dd class="col-sm-11">固定の金額を支払った人が閲覧することができる</dd>

                      </dl>
                    </div>
                  </div>

                  <div class="row mt20">
                    <div class="col-lg-3 col-12">
                      <div class="form-group">
                      <label>販売価格</label>
                        <input type="text" name="price" value="<?=$price?>" class="form-control" />
                      </div>
                    </div>
                  </div>
                  <div class="row mt20">
                    <div class="col-lg-6 col-12">
                      <div class="form-group">
                      <label>告知画像</label>
                      <small >下記、枠内に画像をドラッグしてください。</small>
                        <label class="fileupload" style="background-image:url('/<?=$D_IMAGE?><?=$user_id?>/announce/<?=$id?>/<?=$filepath?>')">
                          画像をドラッグしてください
                          <input type="file" name="upfile" class="upfile">
                          <input type="hidden" name="filepath" id="filepath" value="" />
                        </label>
                      </div>
                    </div>
                  </div>
                  <div class="row mt20">
                    <div class="col-lg-12 col-12">
                      <div class="form-group">
                        <label>告知文言</label>
                        <textarea class="form-control" name="announce_text" rows=3 ><?=$announce_text?></textarea>
                      </div>
                    </div>
                  </div>
                  <div class="row mt20">
                    <div class="col-lg-3 col-12">
                      <div class="form-group">
                        <label>公開/非公開</label>
                        <select name="display" class="form-control">
                          <?php foreach($g_array_disp as $key=>$value):?>
                            <?php
                              $sel = "";
                              if($key == $display) $sel = "SELECTED";
                            ?>
                            <option value="<?=$key?>" <?=$sel?> ><?=$value?></option>
                          <?php endforeach;?>
                        </select>
                      </div>
                    </div>
                  </div>
                  <div class="row mt20">
                    <div class="col-lg-12 col-12">
                      <div class="form-group">
                        <label>漫画ジャンル</label>
                          <div class="row">
                          <?php foreach($genremaster as $value): ?>
                            <div class="col-md-3 col-3">
                              <div class="icheck-primary   mt10">
                                <?php 
                                  $chk = "";  
                                  if(!empty($check_genre[$value->id]) && $check_genre[$value->id]) $chk = "checked";
                                ?>
                                <input type="checkbox" id="checkboxPrimary<?=$value->id?>" name="genre[<?=$value->id?>]" value="on" <?=$chk?> >
                                <label for="checkboxPrimary<?=$value->id?>"><?=$value->name?>
                                </label>
                              </div>
                            </div>
                          <?php endforeach; ?>
                          </div>
                      </div>
                    </div>
                  </div>

                  <div class="row">
                    <label>タグ新規追加</label>
                  </div>
                  <div class="row">
                    <div class="col-md-2 ">
                      <input type="text" name="tagadd" value="" class="form-control">
                    </div>
                    <div class="col-md-2 ">
                      <button class="btn btn-info tagAddButton" type="button">追加</button>
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
                    <div class="col-lg-3 col-12">
                      <div class="form-group">
                        <label>連載型</label>
                        <select name="type" class="form-control" >
                            <?php foreach($g_array_manga_type as $key=>$value): 
                              $sel = "";
                              if($key == $type) $sel = "SELECTED";
                              
                              ?>
                              <option value="<?=$key?>" <?=$sel?> ><?=$value?></option>
                            <?php endforeach;?>
                        </select>
                      </div>
                    </div>
                  </div>
                  <div class="row mt20">
                    <div class="col-lg-6 col-12">
                      <div class="form-group">
                        <label>年齢制限</label>
                        <div class="row">
                        <?php foreach($g_array_ageflag as $key=>$value):?>
                          <div class="col-md-3">
                            <label>
                              <?php 
                                $sel = "";
                                if($age_flag == $key):
                                  $sel = "CHECKED";
                                else:
                                  if($key == 0) $sel = "CHECKED";
                                endif;
                                ?>
                                <input type="radio" name="age_flag" value="<?=$key?>" <?=$sel?> />
                              <?=$value?>
                            </label>
                          </div>
                        <?php endforeach;?>
                        </div>
                      </div>
                    </div>
                  </div>
                  <div class="row mt20">
                    <label>表示期間</label>
                  </div>
                  <div class="row">
                    
                    <?php
                      $chk0 = "checked"; 
                      $chk1 = "";

                      if($term_flag):
                          $chk0 = "";
                          $chk1 = "checked";
                      endif;
                      ?>
                    <div class="col-lg-2 col-12">
                      <input type="radio" name="term_flag" id="term_flag0" value="0" <?=$chk0?> />
                      <label for="term_flag0" >未指定</label>
                    </div>
                    <div class="col-lg-2 col-12">
                      <input type="radio" name="term_flag" id="term_flag1" value="1" <?=$chk1?> />
                      <label for="term_flag1" >指定</label>
                    </div>

                  </div>
                  <div class="row mt20">
                    <label>表示期間</label>
                  </div>
                  <div class="row">
                    <div class="col-lg-2 ">
                      <?php
                        if(empty($term_start)){
                          $term_start = date('Y/m/d');
                          if($this->input->post('term_start')) $term_start = $this->input->post('term_start');
                        }
                        if(empty($term_end)){
                          $term_end = date('Y/m/d',strtotime($term_start.' +1 month'));
                          if($this->input->post('term_end')) $term_end = $this->input->post('term_end');
                        }
                        
                      ?>
                      <input type="text" name="term_start" value="<?=preg_replace("/\-/","/",$term_start)?>" class="form-control datepicker" readonly />
                    </div>
                    <div class="col-lg-1  text-center">～</div>
                    <div class="col-lg-2 ">
                      <input type="text" name="term_end" value="<?=preg_replace("/\-/","/",$term_end)?>" class="form-control datepicker"  readonly />
                    </div>
                  </div>
                    
                  <div class="row mt20">
                    <div class="col-lg-12 col-12">
                      <?php 
                      $btn = "登録";
                      if($id > 0 ) $btn = "更新";
                      ?>
                      <input type="submit" name="regist" class="btn btn-primary w200" value="<?=$btn?>" />
                    </div>
                  </div>

                </form>

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
<!-- /.content-wrapper -->
