<?php
    $page_settings = [
        "header_visible" => false,
        "security_level" => 0
    ];
    include 'includes.php';
?>

<form
    class="form-signin"
    method='POST'
    action='process_register.php'>
    <h2
        class="form-signin-heading">
        Register
    </h2>
    <label
        for='inputName'
        class='sr-only'>
        Name
    </label>
    <input
        type='text'
        name='name'
        id='inputName'
        class='form-control'
        placeholder='Name'
        required
        autofocu>
    <label
        for="inputEmail"
        class="sr-only">
        Email address
    </label>
    <input
        type="email"
        name="email"
        id="inputEmail"
        class="form-control"
        placeholder="Email address"
        required>
    <label
        for="inputPassword"
        class="sr-only">
        Password
    </label>
    <input
        type="password"
        id="inputPassword"
        name='password'
        class="form-control"
        placeholder="Password"
        required>
    <button
        class="btn btn-lg btn-primary btn-block"
        type="submit">
        Register
    </button>
    <a
        href="login.php"
        class="btn btn-lg btn-info btn-block">
        Have an account?
    </a>
</form>