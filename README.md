# WPClass - Wordpress XML-RPC API PHP Class

## Usage

    require_once 'wpclass.php';
    $wp = new WordPress('username', 'password', 'http://wordpress.site.com/xmlrpc.php');
    
    $wp->publish_post('This is a test', 'this is some test content', array('tag 1', 'tag 2'), array('category test'));


