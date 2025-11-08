<?php
$user_timezone = new DateTimeZone('Asia/Phnom_Penh');
$now = new DateTime('now', $user_timezone);
mysqli_report(MYSQLI_REPORT_OFF);
// $connect = @new mysqli('localhost', 'root', '', 'newstoday');
$connect = @new mysqli('127.0.0.1', 'utngydbkpg', 'Ktx73fDVjY', 'utngydbkpg');
// $connect = @new mysqli('sql202.infinityfree.com', 'if0_40365026', '37NWAcVcLlUYm', 'if0_40365026_newstoday');

if ($connect->connect_error) {
    ?>
    <div class="container-fluid mt-5 px-4">
        <div class="alert alert-danger alert-dismissible fade show shadow-sm" role="alert">
            <h3 class="alert-heading">❌ Error!</h3>
            <p>Something went wrong. Please try again later or contact support.</p>
            <p><?php echo "Connection Error: " . $connect->connect_error ?></p>
            <hr>
            <p class="mb-0">If the issue persists, email us at <a href="mailto:example@gmail.com"
                    class="alert-link">example@gmail.com</a>.</p>
        </div>
    </div>
    <?php
    exit();
}
?>