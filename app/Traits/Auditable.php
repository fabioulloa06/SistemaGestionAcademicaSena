<?php

namespace App\Traits;

use App\Models\AuditLog;
use Illuminate\Support\Facades\Auth;

trait Auditable
{
    public static function bootAuditable()
    {
        static::created(function ($model) {
            self::logChange('create', $model);
        });

        static::updated(function ($model) {
            self::logChange('update', $model);
        });

        static::deleted(function ($model) {
            self::logChange('delete', $model);
        });
    }

    protected static function logChange($action, $model)
    {
        if (!Auth::check()) return;

        $changes = null;
        if ($action === 'update') {
            $changes = [
                'before' => array_intersect_key($model->getOriginal(), $model->getDirty()),
                'after' => $model->getDirty(),
            ];
        } elseif ($action === 'create') {
            $changes = $model->getAttributes();
        } elseif ($action === 'delete') {
            $changes = $model->getAttributes();
        }

        AuditLog::create([
            'user_id' => Auth::id(),
            'action' => $action,
            'model' => get_class($model),
            'model_id' => $model->id,
            'changes' => $changes,
        ]);
    }
}
