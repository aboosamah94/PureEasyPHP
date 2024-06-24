<?php
$pageTitle = translate('nav_auth');
?>

<div class="d-flex justify-content-center align-items-center" style="min-height: 50vh;">
    <div class="card p-4 shadow-lg" style="width: 100%; max-width: 400px;">
        <h2 class="card-title text-center mb-4">Login</h2>
        <form>
            <div class="form-floating mb-3">
                <input type="email" class="form-control" id="floatingInput" placeholder="name@example.com">
                <label for="floatingInput">Email address</label>
            </div>
            <div class="form-floating mb-3">
                <input type="password" class="form-control" id="floatingPassword" placeholder="Password">
                <label for="floatingPassword">Password</label>
            </div>

            <div class="checkbox mb-3">
                <label>
                    <input type="checkbox" value="remember-me"> Remember me
                </label>
            </div>
            <button class="w-100 btn btn-lg btn-primary" type="submit">Sign in</button>
        </form>
    </div>
</div>