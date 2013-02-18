<?php
require_once('../inc/header.php');
require_once('../sqldb/connect.php');

if (!isset($_SESSION['uid']))
	die('you are not logged in!');

isset($_GET['c']) ? $cid=$_GET['c'] : die('?');
if (!is_numeric($cid)) die();

$data_array = explode('.', $cid);
$cid = (int)$data_array[0];
isset($data_array[1]) ? $part=(int)$data_array[1] : $part=1;

$sql = db_connect('danmaku', 'main');
$sql -> set_charset('utf8');
date_default_timezone_set('America/Toronto');

// security check!
$uid = $_SESSION['uid'];
$result = $sql->query("SELECT `id`, `uid` FROM data.handler WHERE uid=$uid AND id=$cid");
//print_r($result->fetch_assoc());
if($result->num_rows !== 1)
    die('and exits.');

// deleting
if(isset($_POST['delete_cmts']))
    if($_POST['cid'] == $cid && isset($_POST['cmt'])){
        $ids = array_map(create_function('$id','return $id + 0;'),$_POST['cmt']); //cast to int
        $idstr = implode(',',$ids);
        $result = $sql->query("DELETE FROM `$cid` WHERE id IN ($idstr)");
    }

// select for displaying
$query = 'SELECT * FROM `'.$cid.'`';
$result = $sql->query($query);


?>
    <form method="POST" action="<?php echo $_SERVER['REQUEST_URI']?>">
        <input type="hidden" value="<?php echo $cid;?>" name="cid" id="cid" />
        <table>
            <thead>
                <tr>
                    <th>
                        <input type="checkbox" onclick="toggle(event)" />
                    </th>
                    <th style="width: 3em;">Color</th>
                    <th style="width: 3em;">Mode</th>
                    <th style="width: 3em;">Time</th>
                    <th style="width: 3em;">Size</th>
                    <th>Content</th>
                    <th style="width: 70px;">Post Time</th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <th>
                        <input type="checkbox" onclick="toggle(event)" />
                    </th>
                    <th>Color</th>
                    <th>Mode</th>
                    <th>Time</th>
                    <th>Size</th>
                    <th>Content</th>
                    <th>Post Time</th>
                </tr>
            </tfoot>
            <tbody>
            <?php
                if ($result->num_rows > 0)
                    while($row = $result->fetch_row()){
                      echo '<tr>
                                <td>
                                    <input type="checkbox" value="',$row[0],'" name="cmt[]" />
                                </td>
                                <td>
                                    <div class="colorbox" style="background-color:',sprintf('#%06X',$row[4]),'" />
                                </td>
                                <td>', $row[2],'</td>
                                <td>', $row[1],'</td>
                                <td>', $row[3],'</td>
                                <td>', htmlspecialchars($row[6]),'</td>
                                <td>', date('Y-m-d H:i:s',strtotime($row[5])),'</td>
                            </tr>';
                    }
            ?>
            </tbody>
        </table>

        <input type="submit" name="delete_cmts" value="Delete" />
    </form>

    <style type='text/css'>.colorbox{float:left;width:1em;height:1em;border:1px solid #D4D4D4;}</style>
    <script type='text/javascript'>
    /* <![CDATA[ */
        function toggle(event) {
          event = event ? event : window.event;
          var target = event.srcElement ? event.srcElement : event.target;
          var tmp = target.checked;
          var cbs=document.getElementsByTagName('input');
          for(var i = 0;i < cbs.length; i++) {
            if(cbs[i].getAttribute('type') == 'checkbox') {
              cbs[i].checked = tmp;
            }
          }
        }
    /* ]]> */
    </script>
<?php
require_once('../inc/footer.php');
?>
