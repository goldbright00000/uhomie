@include('components.common.files', [
	'title' => "Liquidación 1  (Ultimo Trabajo)",
	'name' => 'first_settlement',
	'mimes' => 'application/pdf',
	'file' => $user->files()->where('name', 'first_settlement')->first()
 ])
@include('components.common.files', [
	'title' => "Liquidación 2  (Ultimo Trabajo)",
	'name' => 'second_settlement',
	'mimes' => 'application/pdf',
	'file' => $user->files()->where('name', 'second_settlement')->first()
])
@include('components.common.files', [
	'title' => "Liquidación 3  (Ultimo Trabajo)",
	'name' => 'third_settlement',
	'mimes' => 'application/pdf',
	'file' => $user->files()->where('name', 'third_settlement')->first()
])
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
