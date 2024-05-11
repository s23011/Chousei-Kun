
<?php  
if(isset($_SESSION["msg"])){
?>

<div class="bg-danger text-white rounded-4  mt-3 p-1" style="text-align:center;">
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