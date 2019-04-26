<!DOCTYPE html>
<html>
 <head>
  <meta charset="utf-8">
  <title>Тег META, атрибут charset</title>
    </head>
    <body>
<?php 
require('../../wp-blog-header.php' );
require_once('../../wp-includes/registration.php');
require_once('../../wp-includes/post.php');


define( 'SHORTINIT', true );
define('WP_USE_THEMES', false);
require('../../wp-load.php');
global $wpdb;

//SCRIPT SETTINGS
ini_set('max_execution_time',10000);
set_time_limit(10000);
error_reporting( E_ALL );
#display_errors(1);
/*
require_once( '../wp-load.php' );
require_once ('../wp-includes/user.php');
require_once ('../wp-includes/formatting.php');
require_once ('../wp-includes/capabilities.php');
require_once ('../wp-includes/pluggable.php');
require_once ('../wp-includes/kses.php');
require_once ('../wp-includes/meta.php');
//
require_once('../wp-includes/functions.php');
#require_once('../wp-includes/registration.php');
*/
#require_once('../header.php');
#get_header();
/*
//USER DATA
$user_email = 'newuser@mail.ru';
$user_login = 'newuser';
$user_pass = '123456';
$user_url = '';
$first_name = 'New';
$last_name = 'User';
$role = '0';
//QUESTION DATA
//ANSWER DATA
$admin_id = 1;
//ANSPRESS DATA
$table_name = 'wp_ap_qameta';
*/

//OPERATION
#addQA(16, 'Василиса', '2015-05-06', 'Привет, как дела?', 'Отлично, ответ добавлен!', 'user16@mail.ru', $wpdb);

echo 'Выводим ...<br/>';


$handle = fopen("file_15.csv", "r");
if ($handle) {
    while (($line = fgets($handle)) !== false) {
        // process the line read.
        #echo $line."<br/>";
        $comment = explode("|", $line);
        $comment_index = $comment[0];
        $page_index = $comment[1];
        $name = $comment[2];
        $date = $comment[3];
            $d = explode(".", $date);
            $date = $d[2]."-".$d[1]."-".$d[0];
        $question = $comment[4];
        $answer = $comment[5]; 
        $email = $comment[6];
        echo "<div>
                <b>CID</b>:<span>".$comment_index."</span><br/>
                <b>PID</b>:<span>".$page_index."</span><br/>
                <b>Имя</b>:<span>".$name."</span><br/>
                <b>Дата</b>:<span>".$date."</span> <br/>
                <b>Вопрос</b>:<span>".text_limit($question)."</span><br/>
                <b>Ответ</b>:<span>".$answer."</span><br/>
                <b>Почта</b>:<span>".$email."</span><br/>
             </div>
              <hr/>";
        addQA($comment_index, $name, $date, $question, $answer, $email, $wpdb);
    }
    fclose($handle);
} else {
    // error opening the file.
    echo `Ошибка чтения ...<br/>`;
} 


//$file = new SplFileObject("file_5_2.csv");

// Loop until we reach the end of the file.
/*
while (!$file->eof()) {
    // Echo one line from the file.
    //echo $file->fgets().'<br/>';
    
        $comment = explode("|", $file->fgets());
        $comment_index = $comment[0];
        $page_index = $comment[1];
        $name = $comment[2];
        $date = $comment[3];
            $d = explode(".", $date);
            $date = $d[2]."-".$d[1]."-".$d[0];
        $question = $comment[4];
        $answer = $comment[5]; 
        $email = $comment[6];

//        echo "<div>
//                <b>CID</b>:<span>".$comment_index."</span><br/>
//                <b>PID</b>:<span>".$page_index."</span><br/>
//                <b>Имя</b>:<span>".$name."</span><br/>
//                <b>Дата</b>:<span>".$date."</span> <br/>
//                <b>Вопрос</b>:<span>".text_limit($question)."</span><br/>
//                <b>Ответ</b>:<span>".$answer."</span><br/>
//                <b>Почта</b>:<span>".$email."</span><br/>
//             </div>
//              <hr/>";

        //echo $file->fgets().'<br/>';
        //addQA($comment_index, $name, $date, $question, $answer, $email, $wpdb);
        //echo $file->fgets().'<br/>';
}
$file = null;
*/


#$wpdb->query("DELETE FROM `wp_posts` WHERE `post_type` LIKE 'question' OR `post_type` LIKE 'answer'");
#$wpdb->query("DELETE FROM `wp_users` WHERE `user_login` != 'admin'");
#$wpdb->query("DELETE FROM `wp_ap_qameta`");


//FUNCTIONS
function text_limit($string)
{
    $string = strip_tags($string);
    if (strlen($string) > 250) {

        // truncate string
        $stringCut = substr($string, 0, 250);
        $endPoint = strrpos($stringCut, ' ');

        //if the string doesn't contain any space then it will cut without word basis.
        $string = $endPoint? substr($stringCut, 0, $endPoint) : substr($stringCut, 0);
        $string .= '... ';
    }
    return $string; 
}

function addQA($id, $name, $date, $question, $answer, $email, $wpdb){
    //USER DATA
    $user_email = $email;
    $user_login = 'user'.$id;
    $user_pass = '123456';
    $user_url = '';
    $first_name = $name;
    $last_name = '';
    $role = '0';
    $user_id =  add_user($user_email, $user_login, $user_pass, $user_url, $first_name, $last_name, $role);
    if($user_id !="exist")
    {
        //QUESTION DATA
        $lim_title = text_limit($question);
        $post_question_id = add_post($lim_title, 
                $date. ' 12:10:50',#'2015-05-06 12:00:00', 
                $question, 
                $user_id, 0, 'question', 'open');

        //ANSWER DATA
        $admin_id = 1;
        $post_answer_id = add_post($lim_title, 
                $date. ' 13:30:10',
                $answer,
                $admin_id, $post_question_id, 'answer', 'closed');

        //ANSPRESS DATA
        $table_name = 'wp_ap_qameta';
        $wpdb->query("UPDATE $table_name SET answers='1' WHERE post_id=$post_question_id");
    }else
    {
        echo "Добавление приостановлено! Дублирование юзера!<br/>";
    }
}

/*
$user_id =  add_user($user_email, $user_login, $user_pass, $user_url, $first_name, $last_name, $role);


$post_qestion_id = add_post("Где зимуют раки?", 
        '2015-05-06 12:00:00', 
        "Где зимуют раки?", 
        $user_id, 0, 'question', 'open');
$post_answer_id = add_post("Где зимуют раки?", 
        '2015-05-06 12:00:30',
        "Не плнял вас",
        $admin_id, $post_qestion_id, 'answer', 'closed');
$wpdb->query("UPDATE $table_name SET answers='1' WHERE post_id=$post_qestion_id");
*/


function add_post($title, $date, $content, $authorid, $parentid, $type, $ping_status){
    // Create post object
    $my_post = array(
      'post_title'    => wp_strip_all_tags( $title ),
      'post_content'  => $content,
      'post_date_gmt' => $date,
      'ping_status'   => $ping_status, //'open',
      'post_status'   => 'publish',
      'post_author'   => $authorid,
      'post_type'     => $type,
      'post_parent'   => $parentid,
      'comment_status' => 'closed',
      //'post_category' => array( 8,39 )
    );
    /*
        'post_author'           => $user_id,
        'post_content'          => '',
        'post_content_filtered' => '',
        'post_title'            => '',
        'post_excerpt'          => '',
        'post_status'           => 'draft',
        'post_type'             => 'post',
        'comment_status'        => '',
        'ping_status'           => '',
        'post_password'         => '',
        'to_ping'               => '',
        'pinged'                => '',
        'post_parent'           => 0,
        'menu_order'            => 0,
        'guid'                  => '',
        'import_id'             => 0,
        'context'               => '',
     */
    //

    // Insert the post into the database
    return wp_insert_post( $my_post );
}
function add_user($user_email, $user_login, $user_pass, $user_url, $first_name, $last_name, $role)
{
    if (email_exists($user_email)) {

            echo '<p>Email already registered: '. $user_email .'</p>';
            return "exist";
    } elseif (username_exists($user_login)) {

            echo '<p>Username already registered: '. $user_login .'</p>';
            return "exist";
    } else {

            $user_pass = wp_generate_password(16, false);

    return  $user_id = wp_insert_user(
                    array(
                            'user_email' => $user_email,
                            'user_login' => $user_login,
                            'user_pass'  => $user_pass,
                            'user_url'   => $user_url,
                            'first_name' => $first_name,
                            'last_name'  => $last_name,
                            'role'       => $role,
                    )
            );
    } 
}
?>
    </body>
</html>