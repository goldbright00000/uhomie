<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Membership;
use App\Role;
use Illuminate\Support\Carbon;
use App\Notifications\ExpiresMembership;
use App\Notifications\TwilioPush;

class UpdateMemberships extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'membership:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Actualiza y rectifica las membresias de los usuarios';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        try {

        $perfiles = Role::where('hidden', 0)->get();
            
        foreach($perfiles as $perfil)
        {
            /**
             * Membresías != (BASIC || DEFAULT)
             */
            $membresias = $perfil->memberships()->where('name', '!=', 'Default')->where('name', '!=', 'Basic')->get();
            foreach($membresias as $membresia)
            {
                $usuarios = $membresia->users()->get();
                foreach($usuarios as $usuario)
                {
                    
                    if( $usuario->pivot->expires_at ){
                        $carbon_fecha_expiracion = new Carbon($usuario->pivot->expires_at);
                        
                        $hoy = Carbon::now();
                        //$hace5dias = $hoy->copy()->subDays(5);
                        /**
                         * Si el usuario tiene una membresia que le quedan 5 dias o menos para que expire, se le notifica por email y sms
                         */
                        if( $hoy->between($carbon_fecha_expiracion, $carbon_fecha_expiracion->copy()->subDays(5)) ){
                            /** Notificacion por email */
                            $usuario->notify( new ExpiresMembership($membresia, $usuario->pivot->expires_at) );
                            /** Notificacion por sms */
                            $usuario->notify( new TwilioPush("Buenas ".$usuario->firstname." ".$usuario->lastname.". Te informamos que tu membresia ".$membresia->name." le quedan menos de ".$carbon_fecha_expiracion->diffInDays($hoy)." dias por expirar. Te invitamos a renovar tu membresia en https://www.uhomie.cl"  ) );
                        }
                        /**
                         * Si el usuario tiene una membresia expirada se le cambia la membresia a Basic
                         */
                        if( $hoy->greaterThan($carbon_fecha_expiracion) ){
                            
                            /**
                             * Obteniendo la instancia de la membresia basic del perfil correspondiente
                             */
                            $membresia_basic_por_perfil = Membership::where('name', 'Basic')->where('role_id', $perfil->id)->first();
                            /**
                             * Desasociando membresia select o premium, expirada
                             */
                            $usuario->memberships()->detach($membresia->id);
                            /**
                             * Asociando membresia basic
                             */
                            $usuario->memberships()->attach($membresia_basic_por_perfil->id, [ 'expires_at' => $hoy->addMonth()->toDateTimeString() ]);
                        }
                    }
                    
                }
            }
            /**
             * Membresías == BASIC
             */
            $membresias = $perfil->memberships()->where('name', 'Basic')->get();
            foreach($membresias as $membresia)
            {
                $usuarios = $membresia->users()->get();
                foreach($usuarios as $usuario)
                {
                    if( $usuario->pivot->expires_at ){
                        $carbon_fecha_expiracion = new Carbon($usuario->pivot->expires_at);
                        $hoy = Carbon::now();
                        /**
                         * Si el usuario se le venció la membresia basic se le renueva por 30 dias
                         */
                        if( $hoy->greaterThan($carbon_fecha_expiracion) ){
                            $usuario->memberships()->updateExistingPivot($membresia->id, [ 'expires_at' => $hoy->addMonth()->toDateTimeString() ]);
                        }
                    }
                    
                }
            }
        }
        
        }catch(\Exception $e){
            dd($e);
        }
    }
}
