<a href="{{ route('auth.login') }}">login</a>
<form action="{{ route('auth.store') }}" method="post">
  @csrf
    <h5>Email</h5>
    <input type="email" name="email">
    <h5>Name</h5>
    <input type="text" name="name">
    <h5>Username</h5>
    <input type="text" name="username">
    <h5>Password</h5>
    <input type="password" name="password">
    <h5>Confirm Passowrd</h5>
    <input type="password" name="password_confirmation">
    <button type="submit">Register</button>
  </form>
