<template>
	<div class="modal modal-access">
	    <div class="modal-background"></div>
	    <div class="modal-content">
	        <div class="toolbar">
	            <div class="container">
	                <button class="button is-outlined is-primary btn-close-modal">Cerrar</button>
	            </div>
	        </div>

	        <div class="container">
	            <div class="columns is-multiline">
	                <div class="column is-12">
	                    <h1 class="title top-title">Bienvenido a uHomie</h1>
	                </div>
	                <div class="column is-6 is-offset-3 form">
	                    <form action="" method="POST" id="form-login">
	                        <input class="input" type="email" autocomplete="off" name="email" placeholder="Email" required>
	                        <input class="input" type="password" autocomplete="off" name="password" placeholder="Contraseña" required>
	                    </form>
	                    <a href="#" class="recovery-link has-text-primary">¿Olvidó su contraseña?</a>
	                    <hr>
	                    <p>Al loguearte o registrarte aceptas los <a href="#">Términos y Condiciones de Uso</a> y <a href="#">Política de privacidad</a> de uHomie</p>
	                </div>
	                <div class="column is-6 is-offset-3">
	                    <p class="register-option">¿No tiene una cuenta en UHOMIE? <a href="#" class="has-text-primary">Regístrese</a></p>
	                    <input type="submit" value="Ingresa" form="form-login" class="button is-outlined is-primary btn-access">
	                </div>
	            </div>
	        </div>
	    </div>
	</div>
</template>
