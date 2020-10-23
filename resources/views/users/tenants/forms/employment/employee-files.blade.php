@include('components.common.files', [
	'title' => "Liquidación 1",
	'name' => 'first_settlement',
	'mimes' => 'application/pdf',
	'file' => $user->files()->where('name', 'first_settlement')->first()
 ])
@include('components.common.files', [
	'title' => "Liquidación 2",
	'name' => 'second_settlement',
	'mimes' => 'application/pdf',
	'file' => $user->files()->where('name', 'second_settlement')->first()
])
@include('components.common.files', [
	'title' => "Liquidación 3",
	'name' => 'third_settlement',
	'mimes' => 'application/pdf',
	'file' => $user->files()->where('name', 'third_settlement')->first()
])
@include('components.common.files', [
	'title' => "Constancia de Trabajo",
	'name' => 'work_constancy',
	'mimes' => 'application/pdf',
	'file' => $user->files()->where('name', 'work_constancy')->first()
])
@include('components.common.files', [
	'title' => "Certificado de AFP",
	'name' => 'afp',
	'mimes' => 'application/pdf',
	'file' => $user->files()->where('name', 'afp')->first()
])
