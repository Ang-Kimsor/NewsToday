<?php
session_start();
require_once '../../root/header.php';
?>
<style>
    body {
        margin: 0;
        padding: 0;
        height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        background: transparent;
    }
</style>
</head>

<body>
    <script>
        window.onload = function () {
            Swal.fire({
                title: 'Are you sure?',
                text: "Do you want to logout?",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#3498db',
                cancelButtonColor: '#e74c3c',
                confirmButtonText: 'Yes, logout!',
                allowOutsideClick: false,
                width: '400px',
                padding: '2em',
                background: '#fff',
            }).then((result) => {
                if (result.isConfirmed)
                    window.parent.location.href = '../../models/login/logout.php';
                else
                    window.history.back();

            });
        }
    </script>
    <?php
    require_once '../../root/footer.php';
    ?>
</body>

</html>