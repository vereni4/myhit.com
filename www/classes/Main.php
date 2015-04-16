<?php

class Main extends SiteCore {

  public function getContent() {
    echo '<div id="main">';
 
    $_config = 10;
    $_page = isset($_GET['page'])? intval($_GET['page']) : 1;
    if ($_page) {
        
      $_limits = ($_page - 1) * $_config;

      $_query_string =
        "SELECT
          id, title, author, date, img_src, discription
        FROM
          article_" . $_SESSION['language'] . "
        ORDER BY
          date DESC
        LIMIT $_limits, $_config";

      try {
        $_result = $this -> dataBase -> query($_query_string);
        while($_row = $_result -> fetch()) {

          if (!empty($this -> currentUser)) {
            $_edit_text = "<a href='?option=ArticleEdit&amp;id_text=" . $_row['id'] . "'>"
                          . SiteLang::getRending('EDIT') . "</a>
                          <a href='?option=ArticleDelete&amp;delete_id_text=" . $_row['id']
                          . "'>" . SiteLang::getRending('DELETE') . "</a></p>";
          }
          else {
            $_edit_text = '';
          }

          $_ellipsis = (strlen($_row['discription']) >= 150) ? substr($_row['discription'], 0, 150) . '...' : $_row['discription'];

          echo "<div style='margin: 10px; border-bottom: 2px solid #c2c2c2; height: 180px'>
                <p class='heading2' style='font-size:18px'>" . $_row['title'] . "</p>
                <p class='author'>" . $_row['author'] . " | " . $_row['date'] . "</p>
                <p><img style='margin-right:5px; width:150px; float: left' src='" . $_row['img_src'] . "' alt=''>"
                . $_ellipsis . "</p>
                <p><a href='?option=View&amp;id_text=" . $_row['id'] . "'>"
                . SiteLang::getRending('READ_MORE') . "</a> "
                . $_edit_text . "
                </div>
          ";
        }
        
        $_count = $this -> dataBase -> query('SELECT count(0) FROM article_' . $_SESSION['language']) -> fetch()['count(0)'];
        if ($_count > $_config) {
          $_pages = ceil($_count / $_config);
          echo $this -> pagination('?option=Main&amp;page=', '', $_pages, $_page);
        }
      }
      catch (PDOException $e) {
        echo $e -> getMessage();
      }
    }
    echo '</div>';
  }
}
?>