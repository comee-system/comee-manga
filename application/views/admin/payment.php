  
  <?php $this->load->view('topmenu'); ?>
  <?php $this->load->view('menu'); ?>


  <!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">ユーザ支払い情報</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="/Admin/">Home</a></li>
              <li class="breadcrumb-item active">ユーザ支払い情報</li>
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
              <i class="fas fa-edit"></i>ユーザ支払い情報
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
              <div class="row">
                <div class="col-md-12">
                  <label>ユーザID</label>
                </div>
                <div class="col-md-12">
                  <?=$user_id?>
                </div>
              </div>
              <div class="row mt20">
                <div class="col-md-12">
                  <label>氏名</label>
                  <small class="badge badge-danger">必須</small>
                </div>
                
                <div class="col-md-3">
                  <input type="text" name="sei" value="<?=$payment->sei?>" class="form-control" placeholder="姓" />
                </div>
                <div class="col-md-3">
                  <input type="text" name="mei" value="<?=$payment->mei?>" class="form-control" placeholder="名"/>
                </div>
              </div>
              <div class="row mt20">
                <div class="col-md-12">
                  <label>フリガナ</label>
                  <small class="badge badge-danger">必須</small>
                </div>
                <div class="col-md-3">
                  <input type="text" name="sei_kana" value="<?=$payment->sei_kana?>" class="form-control" placeholder="姓" />
                </div>
                <div class="col-md-3">
                  <input type="text" name="mei_kana" value="<?=$payment->mei_kana?>" class="form-control" placeholder="名"/>
                </div>
              </div>
              <div class="row mt20">
                <div class="col-md-12">
                  <label>郵便番号/住所</label>
                  <small class="badge badge-danger">必須</small>
                </div>
                <div class="col-md-3">
                  <input type="text" name="post" value="<?=$payment->post?>" class="form-control" placeholder="000-0000" onKeyUp="AjaxZip3.zip2addr(this,'','address','address');" />
                </div>
              </div>
              <div class="row ">
                <div class="col-md-12">
                  <input type="text" name="address" value="<?=$payment->address?>" class="form-control" placeholder="住所"/>
                </div>
              </div>
              <div class="row mt20">
                <div class="col-md-12">
                  <label>電話番号</label>
                  <small class="badge badge-danger">必須</small>
                </div>
                <div class="col-md-3">
                  <input type="text" name="tel" value="<?=$payment->tel?>" class="form-control" placeholder="000-0000-0000" />
                </div>
              </div>
              <div class="row mt20">
                <div class="col-md-12">
                  <label>銀行名/支店名/銀行コード</label>
                  <small class="badge badge-danger">必須</small>
                </div>
                <div class="col-md-3">
                  <input type="text" name="bankname" value="<?=$payment->bankname?>" class="form-control" placeholder="〇〇銀行" />
                </div>
                <div class="col-md-3">
                  <input type="text" name="shopname" value="<?=$payment->shopname?>" class="form-control" placeholder="〇〇支店" />
                </div>
                <div class="col-md-3">
                  <input type="text" name="bankcode" value="<?=$payment->bankcode?>" class="form-control" placeholder="000" />
                </div>
              </div>
              <div class="row mt20">
                <div class="col-md-12">
                  <label>口座タイプ/口座名義/口座名義かな</label>
                  <small class="badge badge-danger">必須</small>
                </div>
                <div class="col-md-2">
                  
                  <div class="btn-group btn-group-toggle" data-toggle="buttons">
                    <?php foreach($g_array_account as $key=>$val):
                        $act = $chk = "";
                        
                        if($this->input->post("banktype")):
                          if($this->input->post("banktype") == $key):
                            $chk = "checked";
                            $act = "active";
                          endif;
                        else:
                          if($payment->banktype ):
                            if($payment->banktype == $key):
                              $act = "active";
                              $chk = "checked";
                            endif;
                          elseif($key == 1):
                            $act = "active";
                            $chk = "checked";
                          endif;
                        endif;
                      ?>
                      <label class="btn bg-olive <?=$act?>">
                        <input type="radio" name="banktype"  autocomplete="off" <?=$chk?> value="<?=$key?>" > <?=$val;?>
                      </label>
                      
                    <?php endforeach;?>
                    
                  </div>
                </div>
                <div class="col-md-3">
                  <input type="text" name="account_name" value="<?=$payment->account_name?>" class="form-control" placeholder="口座名" />
                </div>
                <div class="col-md-3">
                  <input type="text" name="account_name_kana" value="<?=$payment->account_name_kana?>" class="form-control" placeholder="口座名かな" />
                </div>
              </div>
              
                    
                  
              


              <div class="mt20">
              <input type="submit" name="regist" value="更新" class="btn btn-primary" />
              <input type="hidden" name="user_id" value="<?=$user_id?>" />
              </div>
            </form>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->
