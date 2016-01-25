<?php    
    include_once("Parsedown.php");
    $Parsedown = new Parsedown();
    $post = file_get_contents("db_design.md");
    $output = $Parsedown->text($post);
?>
<html>
<head>
    <title>CC Cooler Database Design</title>
    <link rel="stylesheet" type="text/css" href="markdown.css">    
    <link rel="stylesheet" href="//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css">
</head>
<body>

<?php echo $output; ?>

</body>
</html>