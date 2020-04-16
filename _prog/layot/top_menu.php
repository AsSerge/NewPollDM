<!-- Fixed navbar -->
<style>
    .dm_admin_style{
        background-color: #b9e2ff;
        color: $FFF;
    }
</style>   
    <div class="navbar navbar-default navbar-fixed-top dm_admin_style" role="navigation">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="#">Анкеты и опросы</a>
        </div>
        <div class="navbar-collapse collapse">
          <ul class="nav navbar-nav">
           <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">Администратор <b class="caret"></b></a>
              <ul class="dropdown-menu">
                <li class="dropdown-header">Пользователи</li>
                <li><a href="../../_prog/adm/user_list.php">Список администраторов</a></li>                
                <?php 
                if($userdata['user_role'] == "mgr"){
                    echo "<li><a href='../../_prog/login/register.php'>Регистрация нового администратора</a></li>";
                }
                ?>            
              </ul>
            </li>
           <li class="dropdown">
              <a href="#" class="dropdown-toggle" data-toggle="dropdown">Настройки системы <b class="caret"></b></a>
              <ul class="dropdown-menu">
              <li class="dropdown-header">Управление опросами</li>
                <li><a href="../../_prog/">Список опросов</a></li>
              <li class="dropdown-header">Управление пользователями</li>  
                <li><a href="../../_prog/polls/edit_user.php">Список пользователей</a></li>
                <li><a href="../../_prog/polls/edit_author.php">Список авторов</a></li>
              <li class="dropdown-header">Управление группами</li>  
                <li><a href="../../_prog/polls/edit_group.php">Список групп</a></li>  
              </ul>
            </li>
          </ul>
          <ul class="nav navbar-nav navbar-right">
            <li><a href="#"><?php echo $userdata['user_name']."&nbsp;".$userdata['user_surname'];?></a></li>
            <li><a href="/_prog/" onClick = "clearCookie('id')">Выход</a></li>
          </ul>
        </div><!--/.nav-collapse -->
      </div>
    </div>