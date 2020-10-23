<div class="login-page">
  <div class="form">

  	<img class="logo-solid" src="{{ asset('images/logo_completo.png') }}" width="100px">
  	<h2>
  		ADMIN | Panel
  	</h2>
  	<form class="form-horizontal login-form" method="POST" action="{{ route('admin.auth.loginAdmin') }}">
			{{ csrf_field() }}

      @if ($errors->has('login_fail'))
        Sos un boludo
      @endif

      <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
        <div class="col-md-6">
            <input id="email" type="email" class="form-control" name="email" placeholder="E-mail" value="{{ old('email') }}" required autofocus>

            @if ($errors->has('email'))
                <span class="help-block">
                    <strong>{{ $errors->first('email') }}</strong>
                </span>
            @endif
        </div>
   		</div>

      <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">      
        <div class="col-md-6">
            <input id="password" type="password" class="form-control" placeholder="Contraseña" name="password" required>

            @if ($errors->has('password'))
                <span class="help-block">
                    <strong>{{ $errors->first('password') }}</strong>
                </span>
            @endif
        </div>
    	</div>
      <button type="submit">INGRESAR</button>
    </form>
  </div>
</div>

<style type="text/css">
.login-page {
  width: 360px;
  padding: 8% 0 0;
  margin: auto;
}
.form {
  position: relative;
  z-index: 1;
  background: #FFFFFF;
  max-width: 360px;
  margin: 0 auto 100px;
  padding: 45px;
  text-align: center;
  box-shadow: 0 0 20px 0 rgba(0, 0, 0, 0.2), 0 5px 5px 0 rgba(0, 0, 0, 0.24);
}
.form input {
  outline: 0;
  background: #f2f2f2;
  width: 100%;
  border: 0;
  margin: 0 0 15px;
  padding: 15px;
  box-sizing: border-box;
  font-size: 14px;
}
.form button {
  text-transform: uppercase;
  outline: 0;
  background: #00a2ff;
  width: 100%;
  border: 0;
  padding: 15px;
  color: #FFFFFF;
  font-size: 14px;
  -webkit-transition: all 0.3 ease;
  transition: all 0.3 ease;
  cursor: pointer;
}
.form button:hover,.form button:active,.form button:focus {
  background: #00a2dd;
}
.form .message {
  margin: 15px 0 0;
  color: #b3b3b3;
  font-size: 12px;
}
.form .message a {
  color: #4CAF50;
  text-decoration: none;
}
.form .register-form {
  display: none;
}
.container {
  position: relative;
  z-index: 1;
  max-width: 300px;
  margin: 0 auto;
}
.container:before, .container:after {
  content: "";
  display: block;
  clear: both;
}
.container .info {
  margin: 50px auto;
  text-align: center;
}
.container .info h1 {
  margin: 0 0 15px;
  padding: 0;
  font-size: 36px;
  font-weight: 300;
  color: #1a1a1a;
}
.container .info span {
  color: #4d4d4d;
  font-size: 12px;
}
.container .info span a {
  color: #000000;
  text-decoration: none;
}
.container .info span .fa {
  color: #EF3B3A;
}
body {
  background: #00a2ff; /* fallback for old browsers */
  background: -webkit-radial-gradient(#f1f1f1, #00a2ff);
  background: -moz-radial-gradient(#f1f1f1, #00a2ff);
  background: -o-radial-gradient(#f1f1f1, #00a2ff);
  background: radial-gradient(#f1f1f1, #00a2ff);
  font-family: "Roboto", sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
}
h2 {
	font-weight: normal;
}
</style>