
<header id="header">
  <h1 id="logo"><a href="/" title="NoNameBox's Account Management"> <img src="assets/img/gus_nnblogo.png" alt="No Name Box" class="header-logo"></a></h1>
  <nav id="nav">
      <ul>
          <li><a href="http://nonamebox.org/" title="NoNameBox's Website">Get back to the site</a></li>
          <?php
          if ($_SESSION["loggedin"]) {
            ?>
            <li><a href="logout" title="Log Out" class="btn-logout"><i class="fa fa-sign-out " aria-hidden="true"></i></a></li>
            <?php
          }
          ?>
      </ul>
  </nav>
</header>
