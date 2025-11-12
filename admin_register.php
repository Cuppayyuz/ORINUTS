<?php
session_start();
if (isset($_SESSION['user'])) {
    header("Location: index.php");
    exit();
}
$error = '';
// Insert data
if (!empty($_POST)) {
    $pdo = require 'koneksi.php';
    if ($_POST['username'] == '' || $_POST['email'] == '' || $_POST['password'] == '') {
        $error = 'Field wajib diisi!';
    } else if ($_POST['password'] != $_POST['password2']) {
        $error = 'password harus cocok!';
    } else if (strlen($_POST['password']) < 6) {
        $error = 'Password minimal 6 karakter';
    } else {
        $sql = 'select count(*) from admins where email = :emailUser';
        $query = $pdo->prepare($sql);
        $query->execute(array('emailUser' => $_POST['email']));
        $count = $query->fetchColumn();
        if ($count > 0) {
            $error = 'Email sudah terdaftar';
        } else {
            $sql = "INSERT INTO admins (username, email, password, profile) VALUES (:username, :email, :password, :profile)";
            $query2 = $pdo->prepare($sql);
            $query2->execute(array(
                "username" => $_POST['username'],
                "email" => $_POST['email'],
                "password" => sha1($_POST['password']),
                "profile" => file_get_contents('content/profil.webp'),
            ));
            unset($_POST);
            echo "<script>alert('Pendaftaran berhasil! Silakan login.'); window.location.href='admin_login.php';</script>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Register Admin</title>
    <link rel="stylesheet" href="src/outputail.css" />
    <link
        href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600;700&display=swap"
        rel="stylesheet" />
    <link
        rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" />

    <style>
        /* Gaya kustom yang TIDAK DAPAT menjadi utilitas Tailwind: */
        .imgl {
            filter: drop-shadow(-24px 40px 5px rgba(0, 0, 0, 0.3));
        }

        .imgr {
            filter: drop-shadow(20px 15px 5px rgba(0, 0, 0, 0.3));
        }

        /* GAYA TOMBOL DISABLED (MENGGUNAKAN CSS BIASA) */
        .btn-disabled {
            opacity: 0.6;
            cursor: not-allowed;
            box-shadow: none;
        }

        /* GAYA IKON PASSWORD (MENGGUNAKAN CSS BIASA) */
        .password-input-wrapper {
            position: relative;
            width: 100%;
        }

        .password-toggle-icon {
            position: absolute;
            right: 0.75rem;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: rgb(107 114 128 / 1);
            z-index: 20;
            font-size: 1.125rem;
        }

        .password-toggle-icon:hover {
            color: #a7483d;
        }

        /* Kelas untuk pembungkus input di Langkah 2 (w-2/3) */
        .full-input-container {
            width: 100%;
            /* Default: Mobile (100% lebar) */
        }

        @media (min-width: 768px) {

            /* md: */
            .full-input-container {
                width: 66.666667%;
                /* Desktop: w-2/3 */
            }
        }
    </style>
</head>

<body class="bg-[#F4EFD8] flex justify-center items-center min-h-screen">
    <div
        class="register-container flex w-full sm:w-[85%] max-w-[1000px] shadow-2xl rounded-lg overflow-hidden">
        <div
            id="panel-kiri"
            class="image-area hidden md:block md:w-[40%] bg-[#cb3d3d] p-10 relative overflow-hidden">
            <div class="absolute inset-0 flex justify-center items-end">
                <img
                    src="content/kacang/mente1-removebg-preview.png"
                    alt="Kacang Mente Kiri"
                    class="absolute w-32 h-auto z-100 bottom-75 -rotate-70 left-27" />
                <img
                    src="content/kacang/mente1-removebg-preview.png"
                    alt="Kacang Mente Kecil"
                    class="absolute w-24 h-auto bottom-4 z-30 rotate-145 left-41" />
                <img
                    src="content/product/ori-merah-removebg-preview.png"
                    alt="Produk Orinuts Merah"
                    class="imgr absolute h-auto w-48 bottom-15 -right-10 z-20" />
                <img
                    src="content/product/ori-4mighty-removebg-preview.png"
                    alt="Energy Booster Mix"
                    classl="imgl absolute h-auto w-80 bottom-15 -right-48 transform -translate-x-1/2 z-10" />
            </div>
        </div>

        <div
            class="form-area w-full md:w-[60%] bg-white p-10 sm:p-12 md:p-16 lg:p-20 flex flex-col items-center justify-center relative">
            <div
                class="flex justify-center items-center gap-8 mb-10 relative z-10 top-4 w-full px-10 sm:px-0">
                <div
                    class="absolute top-1/2 left-0 right-0 h-[2px] bg-gray-300 z-5 -translate-y-1/2">
                    <div
                        id="step-line-fill"
                        class="absolute top-0 left-0 h-full w-0 bg-[#a7483d] transition-all duration-300 ease-in-out"></div>
                </div>
            </div>

            <form id="registerForm" class="w-full" method="post" action="">
                <div id="step-1" class="step-content">
                    <h2
                        class="text-3xl sm:text-4xl text-[#a76657] font-semibold mb-8 tracking-widest uppercase font-reglog text-center">
                        REGISTER ADMIN
                    </h2>
                    <?php
                    echo "<p class='text-sm text-red-500 font-bold'>" . $error . "</p>";
                    ?>
                    <div class="space-y-6">
                        <div class="input-group relative">
                            <input
                                name="username"
                                type="text"
                                id="username"
                                placeholder="Username"
                                value="<?php echo isset($_POST['username']) ? $_POST['username'] : '';?>"
                                class="w-full py-2.5 px-4 border border-gray-300 rounded-lg shadow-sm text-base text-gray-800 placeholder-gray-400 bg-white transition-all duration-300 ease-in-out focus:outline-none focus:ring-2 focus:ring-[#a7483d] focus:ring-opacity-50 focus:border-[#a7483d] focus:shadow-md" />

                        </div>
                        <div class="input-group relative">
                            <input
                                name="email"
                                type="email"
                                id="email"
                                placeholder="Email"
                                value="<?php echo isset($_POST['email']) ? $_POST['email'] : '';?>"
                                class="w-full py-2.5 px-4 border border-gray-300 rounded-lg shadow-sm text-base text-gray-800 placeholder-gray-400 bg-white transition-all duration-300 ease-in-out focus:outline-none focus:ring-2 focus:ring-[#a7483d] focus:ring-opacity-50 focus:border-[#a7483d] focus:shadow-md" />

                        </div>
                        <div class="input-group relative">
                            <div class="password-input-wrapper">
                                <input
                                    name="password"
                                    type="password"
                                    id="password"
                                    placeholder="Password"
                                    value="<?php echo isset($_POST['password']) ? $_POST['password'] : '';?>"
                                    class="w-full py-2.5 px-4 border border-gray-300 rounded-lg shadow-sm text-base text-gray-800 placeholder-gray-400 bg-white transition-all duration-300 ease-in-out focus:outline-none focus:ring-2 focus:ring-[#a7483d] focus:ring-opacity-50 focus:border-[#a7483d] focus:shadow-md pr-10" />
                                <span
                                    class="password-toggle-icon"
                                    onclick="togglePasswordVisibility('password')"><i class="fas fa-eye"></i></span>
                            </div>

                        </div>
                        <div class="input-group relative">
                            <div class="password-input-wrapper">
                                <input
                                    name="password2"
                                    type="password"
                                    id="confirmPassword"
                                    placeholder="Confirm Password"
                                    class="w-full py-2.5 px-4 border border-gray-300 rounded-lg shadow-sm text-base text-gray-800 placeholder-gray-400 bg-white transition-all duration-300 ease-in-out focus:outline-none focus:ring-2 focus:ring-[#a7483d] focus:ring-opacity-50 focus:border-[#a7483d] focus:shadow-md pr-10" />
                                <span
                                    class="password-toggle-icon"
                                    onclick="togglePasswordVisibility('confirmPassword')"><i class="fas fa-eye"></i></span>
                            </div>

                        </div>
                    </div>

                    <div class="mt-8">
                        <button
                            type="submit"
                            id="btnSubmit"
                            class="create-account-btn w-full bg-[#a7483d] text-white py-3 rounded-md text-lg font-semibold transition duration-300 shadow-lg">
                            Create account
                        </button>
                        <p class="text-sm text-center text-red-500 mt-2">
                            *Data yang diisi harus lengkap
                        </p>

                        <p class="login-prompt mt-6 text-sm text-gray-600">
                            Sudah mempunyai akun?
                            <a
                                href="login.php"
                                class="login-link text-blue-700 font-semibold hover:underline">Login</a>
                        </p>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        function togglePasswordVisibility(id) {
            const inputWrapper = document.getElementById(id).parentElement;
            const passwordInput = document.getElementById(id);
            const toggleIcon = inputWrapper.querySelector(
                ".password-toggle-icon i"
            );

            if (passwordInput.type === "password") {
                passwordInput.type = "text";
                toggleIcon.classList.remove("fa-eye");
                toggleIcon.classList.add("fa-eye-slash");
            } else {
                passwordInput.type = "password";
                toggleIcon.classList.remove("fa-eye-slash");
                toggleIcon.classList.add("fa-eye");
            }
        }
    </script>
</body>

</html>