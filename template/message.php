
<?php  
if(isset($_SESSION["msg"])){
?>

<div style="color:red; background-color:gray; text-align:center;">
    <h2>

    <?php  
        $msg = $_SESSION["msg"];
        echo $msg;
        unset($_SESSION["msg"]);
    ?>
    
    </h2>
</div>    

<?php  
}
?>