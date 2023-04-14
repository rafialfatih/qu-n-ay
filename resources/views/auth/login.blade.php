<a href="{{ route('auth.register') }}">register</a>
<h2>Login</h2>
<form action="{{ route('auth.login') }}" method="post">
  @csrf
  <h5>Email</h5>
  <input type="text" name="email">
  <h5>Password</h5>
  <input type="password" name="password">
  <button type="submit">login</button>
</form>
