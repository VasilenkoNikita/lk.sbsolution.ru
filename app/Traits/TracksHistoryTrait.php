<?php

namespace App\Traits;

use App\Models\User;
use App\Models\History;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

trait TracksHistoryTrait
{
    protected function track(Model $model, callable $func = null, $tableName = null, $table = null, $id = null)
    {
        // Allow for overriding of table if it's not the model table
        $table = $table ?: $model->getTable();
        // Allow for overriding of id if it's not the model id
        $id = $id ?: $model->id;
        // Allow for customization of the history record if needed
        $func = $func ?: [$this, 'getHistoryBody'];

        // Get the dirty fields and run them through the custom function, then insert them into the history table
        $this->getUpdated($model)
            ->map(function ($value, $field) use ($func) {
                return call_user_func_array($func, [$value, $field]);
            })
            ->each(function ($fields) use ($tableName, $table, $id) {
                History::create([
                        'reference_table' => $tableName ?: $table,
                        'reference_id'    => $id,
                        'user_id'        => Auth::user()->id,
                    ] + $fields);
            });
    }

    protected function getHistoryBody($value, $field)
    {
        return [
            'body' => "Обновлено поле {$field} - ${value}",
        ];
    }

    protected function getUpdated($model)
    {

        return collect($model->getDirty())->filter(function ($value, $key) {
            // We don't care if timestamps are dirty, we're not tracking those
            return !in_array($key, ['created_at', 'updated_at']);
        })->mapWithKeys(function ($value, $key) use($model) {
            // Take the field names and convert them into human readable strings for the description of the action
            // e.g. first_name -> first name
            //return [str_replace('_', ' ', $key) => $value];
            if ($model->getRawOriginal((string) $key)) {
                $changes = 'Было: "' . $model->getRawOriginal((string)$key) . '" <br>Стало: "' . $value . '".';
            }else{
                $changes = 'Добавлено: "' . $value . '"';
            }
            return [$key => $changes];
        });
    }
}
