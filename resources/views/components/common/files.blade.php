<h2 class="file-title">{{ $title }}</h2>
<div class="file has-name">
	<label class="file-label">
		<input class="file-input" type="file" name="{{ $name }}" max="1" accept="{{ $mimes }}">
		<span class="file-cta">
			<span class="file-label">
				Examinar...
			</span>
		</span>
		<span class="file-name">
			{{ $file && $file->path ? $file->original_name : "No se seleccionó un archivo" }}
		</span>
	</label>
	<span class="file-show">
		@if ($file && $file->path)
			<img src="{{asset('images/icono-tilde-azul-g.png')}}">
			<a target="_blank" href="{{ $file->getPublicURL() }}">
				<img src="{{asset('images/icono-descarga-g.png')}}">
			</a>
		@else
			<img src="{{asset('images/icono-atencion.png')}}">
		@endif
	</span>
</div>
