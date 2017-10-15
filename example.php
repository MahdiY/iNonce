<?php

include_once( 'src/iNonce.php' );

use iNonce\iNonce;

iNonce::setSalt( 'Some_salt_to_create_secure_nonce_change_it!' );

?>
<html>
<head>
    <title>Example use of iNonce</title>
    <meta name="author" content="MahdiY">
</head>
<body>
<?php
if( isset( $_POST['form_submitted'] ) ) {

    switch( true ) {
        case isset( $_POST['form_one'] ) && iNonce::verify( $_POST['_nonce'], 'form-one' ):
            echo "<p>Form One Validated!</p>";
            break;
        case isset( $_POST['form_two'] ) && iNonce::verify( $_POST['_nonce'], 'form-two' ):
            echo "<p>Form Two Validated!</p>";
            break;
        default:
            echo "<p>Form Not Validated! You can't do anything!<p>";
    }

} elseif( isset( $_GET['_nonce'], $_GET['id'] ) ) {

    if( iNonce::verify( $_GET['_nonce'], 'delete', $_GET['id'] ) ) {
        echo "<p>You can delete $_GET[id]</p>";
    }

    if( iNonce::verify( $_GET['_nonce'], 'add', $_GET['id'] ) ) {
        echo "<p>You can add $_GET[id]</p>";
    }

} elseif( isset( $_GET['_nonce'] ) ) {

    switch( true ) {
        case iNonce::verify( $_GET['_nonce'], 'action_1' ):
            echo "<p>Action 1 Validated!</p>";
            break;
        case iNonce::verify( $_GET['_nonce'], 'action_2' ):
            echo "<p>Action 2 Validated!</p>";
            break;
        default:
            echo "<p>Action Not Validated! You can't do anything!<p>";
    }

}
?>
<h3>Form Examples:</h3>
<form action="" method="post">
    <input type='submit' name='form_one' value="Form One"/>
    <input type='hidden' name='form_submitted' value='1'/>
    <?php iNonce::input( 'form-one' ); ?>
</form>

<form action="" method="post">
    <input type='submit' name='form_two' value="Form Two"/>
    <input type='hidden' name='form_submitted' value='1'/>
    <?php iNonce::input( 'form-two' ); ?>
</form>

<h3>Link Examples:</h3>
<a href="example.php?<?php echo iNonce::query( 'action_1' ); ?>">Action 1</a><br/>
<a href="example.php?<?php echo iNonce::query( 'action_2' ); ?>">Action 2</a><br/>

<h3>Same action Examples:</h3>
<a href="example.php?id=1&<?php echo iNonce::query( 'delete', 1 ); ?>">Delete 1</a><br/>
<a href="example.php?id=2&<?php echo iNonce::query( 'delete', 2 ); ?>">Delete 2</a><br/>
<a href="example.php?id=3&<?php echo iNonce::query( 'delete', 3 ); ?>">Delete 3</a><br/>

<a href="example.php?id=1&<?php echo iNonce::query( 'add', 1 ); ?>">Add 1</a><br/>
<a href="example.php?id=2&<?php echo iNonce::query( 'add', 2 ); ?>">Add 2</a><br/>
<a href="example.php?id=3&<?php echo iNonce::query( 'add', 3 ); ?>">Add 3</a><br/>

</body>
</html>
