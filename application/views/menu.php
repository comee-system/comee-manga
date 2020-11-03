  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="index3.html" class="brand-link">
      <span class="brand-text font-weight-light">COMEE</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
 
      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

          <li class="nav-item">
            <a href="pages/widgets.html" class="nav-link">
              <i class="nav-icon fas fa-th"></i>
              <p>
                Widgets
                <span class="right badge badge-danger">New</span>
              </p>
            </a>
          </li>
          <?php if(empty($users_open)) $users_open = ""; ?>
          <li class="nav-item has-treeview <?=$users_open?>">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-copy"></i>
              <p>
                ユーザ一覧
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="/Admin/Users/list/" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>一覧</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="/Admin/Users/regist/" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>登録</p>
                </a>
              </li>
              
            </ul>
          </li>

          <?php if(empty($manga_open)) $manga_open = ""; ?>
          <li class="nav-item has-treeview <?=$manga_open?>">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-copy"></i>
              <p>
                連載一覧
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="/Admin/Manga/list/" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>一覧</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="/Admin/Manga/regist/" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>登録</p>
                </a>
              </li>
              
            </ul>
          </li>
          <?php if(empty($manga_detail_open)) $manga_detail_open = ""; ?>
          <li class="nav-item has-treeview <?=$manga_detail_open?>">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-copy"></i>
              <p>
                連載詳細
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="/Admin/MangaDetail/list/" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>一覧</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="/Admin/MangaDetail/regist/" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>登録</p>
                </a>
              </li>
              
            </ul>
          </li>
         
          <li class="nav-item">
            <a href="/Auth/logout/" class="nav-link" id="logout">
              <i class="nav-icon fas  fa-undo"></i>
              <p>
                ログアウト
              </p>
            </a>
          </li>

        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>