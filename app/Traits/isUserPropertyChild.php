<?php

namespace App\Traits;

use \App\UserProperty;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Builder;

trait isUserPropertyChild {
    private function saveUserProperty() {
        // If UserProperties exist, find it. Else, create a instance
        $userProperties =  $this->getParent();

        // Set the UserProperties attributes
        $userProperties->user_id        = (int) $this->user_id;
        $userProperties->property_id    = (int) $this->property_id;
        $userProperties->type           = self::RELATION_TYPE;
        
        // Save or update 
        if(!$userProperties || !$userProperties->save())
            return false;
        
        return $userProperties->id;
    }

    public function save(array $options = [] ) {
        DB::beginTransaction();
        
        try {
            $my_attributes = $this->attributes;

            if($this->isChildTable()) {
                if( false === ($parent_id = $this->saveUserProperty()) ) 
                    throw new \Exception();

                
                // Keep related table fields (filter property_id and user_id)
                $this->attributes = array_diff_key($this->attributes, array_flip(static::getForeignAttributes()));
                
                // The UserProperty id it's the child id
                $this->id = $my_attributes['id'] = $parent_id;
            }
            else {
                $this->type = self::RELATION_TYPE;
            }
            

            if(!parent::save($options))
                throw new \Exception();
            
            $this->attributes = $my_attributes;
            
            /*DB::rollback();*/ DB::commit();

            return true;

        }
        catch(\Exception $e) {
            DB::rollback();
            throw $e;
        }
        
    }

    public function delete() {
        if($this->isChildTable()) {
            $this->user_property()->delete();
        }

        return parent::delete();
    }

    /**
    Static methods
     */
    static public function tableName() {
        return (new self)->table;
    }
    
    static public function boot(){
        parent::boot();

        if((new self)->isChildTable()) {
            static::addGlobalScope(new \App\Scopes\PropertyUserScope(self::tableName(), self::RELATION_TYPE));        
        }
        else {
            $relation_type = self::RELATION_TYPE;

            static::addGlobalScope('relation_type', function(Builder $builder) use ($relation_type) {
                $builder->where('type', '=', $relation_type);
            });
        }
    }

    static private function getForeignAttributes() {
        return ['property_id', 'user_id', 'type'];
    }
    
    /**
     * @return bool valide if the child table it's the same as parent table
     */
    private function isChildTable() {
        return self::tableName() != parent::tableName();
    }

    public function getParent() {
        $parent;

        if($this->exists) {
            $parent = $this->user_property()->get()->first();
        }
        else {
            $parent = new UserProperty();
            
        }

        return $parent;
    }

    public function user_property() {
        return $this->belongsTo('App\UserProperty', 'id');
    }

}
