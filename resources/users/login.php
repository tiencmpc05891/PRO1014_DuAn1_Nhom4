<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (empty($_POST['email']) || empty($_POST['password'])) {
        $error = "Vui lòng điền đầy đủ email và mật khẩu.";
    } else {
        $email = $_POST['email'];
        $password = $_POST['password'];
        $recaptchaResponse = $_POST['g-recaptcha-response'];
        $secretKey = '6LcT3LQpAAAAAGm1fDRgMIvqG2TcTZabSrGFwshj';
        $recaptchaUrl = 'https://www.google.com/recaptcha/api/siteverify?secret=' . $secretKey . '&response=' . $recaptchaResponse;
        $recaptchaResponseData = json_decode(file_get_contents($recaptchaUrl));

        if (!$recaptchaResponseData->success) {
            $error = "Vui lòng xác nhận bạn không phải là robot.";

        } else {
            $existing_user = $users->sign_in($email);

            if ($existing_user) {
                if ($password === $existing_user['password']) {
                    $_SESSION['user'] = $existing_user;
                    $_SESSION['customer_id'] = $existing_user['customer_id'];
                    if ($existing_user['role'] === 'admin') {
                        header("Location: ../admin/index.php");
                        exit();
                    } else {
                        header("Location: index.php");
                        exit();
                    }
                } else {
                    $error = "Mật khẩu không chính xác.";
                }
            } else {
                $error = "Email không tồn tại trong hệ thống.";
            }
        }
    }
}
?>


<!--================Login Box Area =================-->
<section class="login_box_area section-margin mt-4">
    <div class="container">
        <div class="row">
            <div class="col-lg-6">
                <div class="login_box_img">
                    <div class="hover">
                        <h4>Mới vào trang web của chúng tôi?</h4>
                        <p>Có những tiến bộ đang được thực hiện hàng ngày trong khoa học và công nghệ, và một ví dụ điển
                            hình về điều này là</p>
                        <a class="button button-account" href="index.php?url=register">Tạo tài khoản</a>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="login_form_inner">
                    <h3>Đăng nhập</h3>
                    <form class="row login_form" action="index.php?url=login" id="contactForm" method="post">
                        <div class="col-md-12 form-group">
                            <input type="text" class="form-control" id="email" name="email" placeholder="Email"
                                required>
                        </div>
                        <div class="col-md-12 form-group">
                            <input type="password" class="form-control" id="password" name="password"
                                placeholder="Password" required>
                        </div>
                        <div class="col-md-12 form-group">
                            <button type="submit" value="submit" class="button button-login w-100">Đăng nhập</button>
                        </div>
                        <div class="g-recaptcha" data-sitekey="6LcT3LQpAAAAAIhMydn4HWV1wbvy965o85whFaDk"></div>

                        <?php if (isset($error)): ?>
                            <div class="col-md-12 form-group">
                                <p class="text-danger">
                                    <?php echo $error; ?>
                                </p>
                            </div>
                        <?php endif; ?>
                        Quên mật khẩu?&nbsp;<a href="index.php?url=forgotpassword">Lấy lại mật khẩu tại đây</a>
                        <div class="col-md-12 mt-4 form-group">
                            <a href="https://accounts.google.com/o/oauth2/auth?client_id=606727185499-4st803lcpbu7kaq87oo5e8or1lo17bpg.apps.googleusercontent.com&redirect_uri=http://localhost/PRO1014_DuAn1_Nhom4/&response_type=code&scope=email%20profile"
                                class="button button-login w-100">
                                <img src="public/img/instagram/google-logo-6278331_960_720.webp" width="30px"
                                    alt="Google icon">
                                Đăng nhập bằng Google
                            </a>
                        </div>

                    </form>
                </div>
            </div>
        </div>
    </div>
</section>
<!--================End Login Box Area =================-->
<script src="https://www.google.com/recaptcha/api.js" async defer></script>