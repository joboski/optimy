<h2>Sign in</h2>
<form method="post" enctype="multipart/form-data" action="/login">
  <div class="form-group">
        <label>Username / Email</label>
        <input type="email" name="email" class="form-control">
    </div>
    <div class="form-group">
        <label>Password</label>
        <input type="password" name="password" class="form-control">
    </div>
    <button type="submit" class="btn btn-primary">Submit</button>
    <a class="btn btn-outline-danger" href="#">Cancel</a>
</form>