<?php
    include 'includes.php';
?>
<div class='page-header'>
    <h1>Update Profile</h1>
</div>
<form method='POST' action='process_update_profile.php'>
    <div class='form-group'>
        <label>Name</label>
        <input type='text' name='name' class='form-control' placeholder='Name' value='<?=$user_info['name']?>'>
    </div>
    <div class='form-group'>
        <label>Employer</label>
        <input type='text' name='current_employer' class='form-control' placeholder='Current Employer' value='<?=$user_info['current_employer']?>'>
    </div>
    <div class='form-group'>
        <label>Avatar</label><br>
        <?php if (isset($user_info['avatar'])) { ?>
        <img src='<?=$user_info['avatar']?>' height='100px' width='100px'>
        <?php
        }
        ?>
        <input type='text' name='avatar' class='form-control' placeholder='Avatar URL' value='<?=$user_info['avatar']?>'>
    </div>
    <div class='form-group'>
        <label>Email</label>
        <input type='text' name='email' class='form-control' placeholder='Email Address' value='<?=$user_info['email']?>'>
    </div>
    <div class='form-group'>
        <label>Phone Number</label>
        <input type='number' name='phone' class='form-control' placeholder='Phone Number' value='<?=$user_info['phone']?>'>
    </div>
    <div class='form-group'>
        <label>Summary</label>
        <textarea name='summary' class='form-control' placeholder='Summary' rows='3'><?=$user_info['summary']?></textarea>
    </div>
    <button type='submit' class='btn btn-primary btn-block'>Update</button>
</form>
<?php
    include 'footer.php';
?>