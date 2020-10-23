@if ($user->invoice)
	@include('components.common.files', [
		'title' => "Última Boleta de Honorarios",
		'name' => 'last_invoice',
		'mimes' => 'application/pdf',
		'file' => $user->files()->where('name', 'last_service_invoice')->first()
	])
@endif
@if ($user->saves)
	@include('components.common.files', [
		'title' => "Constancia Bancaria / Estado Bancario  (Ahorros)",
		'name' => 'saves',
		'mimes' => 'application/pdf',
		'file' => $user->files()->where('name', 'saves')->first()
	])
@endif
@if ($user->afp)
	@include('components.common.files', [
		'title' => "Certificado de AFP",
		'name' => 'afp',
		'mimes' => 'application/pdf',
		'file' => $user->files()->where('name', 'afp')->first()
	])
@endif
