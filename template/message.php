
<?php  
if(isset($_SESSION["msg"])){
?>

<div class="bg-danger text-white my-1 p-1" style="text-align:center;">
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