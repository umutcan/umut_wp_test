<?php
//require_once('../wp-load.php' );
if($_SERVER['PHP_SELF']=="/wp/wp-admin/subtitle/load.php")
    die("Nereye birader?");
if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}
?>
<html>
    <body>
        <form action="<?php echo $_SERVER['PHP_SELF']?>?page=sub-man" method="post">
            <table>
                <tr>
                    <td>Project:</td>
                    <td><input type="text" name="project"/> </td>
                </tr>
                <tr>
                    <td>Bolum No:</td>
                    <td><input type="text" name="ep"/> </td>
                </tr>
                <tr>
                    <td>Dosya:</td>
                    <td><input type="text" name="file"/> </td>
                </tr>
                <tr>
                    <td>
                        <input type="submit"></input>
                    </td>
                </tr>
            </table>
            
        </form>
    </body>
</html>