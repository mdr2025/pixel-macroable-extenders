<?php

namespace PixelMacroableExtenders\MacroableExtenders;

use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Facades\DB;
use PixelApp\Config\ConfigFileIdentifiers\PixelAppLaravelConfigFileIdentifiers\PixelAppLaravelConfigFileIdentifier;
use PixelMacroableExtenders\Config\PixelMacroableExtendersConfigManager;

class PixelBuilderExtender extends PixelMacroableExtender
{
    public function extendMacroable() : void
    {
        $this->defineBuilderDatesFilteringMacro();
        $this->defineBuilderCustomOrderingMacro();
        $this->defineBuilderWorkCalendarDatesFilterMacro();
    }

    protected function defineBuilderDatesFilteringMacro() : void
    {
        Builder::macro('datesFiltering', function ($relation = null, $column = "created_at", $format = "Y-m-d H:i:s") 
        {

            if(!PixelMacroableExtendersConfigManager::isPixelCarbonExtenderLoaded())
            {
                PixelCarbonExtender::throwNotLoadedExtenderException();
            }

            /** @var Builder $this */

            $request = request();
            $requestFilters = $request->filter ?? [];
            $period_type = $requestFilters['period_type'] ?? null;
            $from_date =  $requestFilters['from_date'] ?? null;
            $to_date = $requestFilters['to_date'] ?? null;

            switch ($period_type) {
                case 'day':
                    $from = Carbon::parseOrNow($from_date)->startOfDay();
                    $to = Carbon::make($from_date)->endOfDay();
                    break;
                case 'month':
                    $from = Carbon::parseOrNow($from_date)->startOfMonth();
                    $to = Carbon::make($from_date)->endOfMonth();
                    break;
                case 'quarter':
                    $from = Carbon::parseOrNow($from_date)->startOfQuarter();
                    $to = Carbon::make($from_date)->endOfQuarter();
                    break;
                case 'year':
                    $from = Carbon::parseOrNow($from_date)->startOfYear();
                    $to = Carbon::make($from_date)->endOfYear();
                    break;
                case 'range':
                    $from = Carbon::parseOrNow($from_date)->startOfDay();
                    $to = Carbon::parseOrNow($to_date)->endOfDay();
                    break;
                default:
                    break;
            }
            if (isset($from) && isset($to)) 
            {
                $from = $from->format($format);
                $to = $to->format($format);
                if ($relation) 
                {
                    /** @var Builder $this */
                    return $this->whereHas(
                                                    $relation ,
                                                    function ($query) use ($column, $from, $to) 
                                                    {
                                                        $query->whereBetween($column, [$from, $to]);
                                                    }
                                          );
                }

                return $this->whereBetween($column, [$from, $to]);
            }

            return $this;
        });
    }
    
    protected function defineBuilderCustomOrderingMacro() : void
    {
        Builder::macro('customOrdering', function ($sortColumn = null, $sort = null) {
            try {
                /** 
                 * @var Builder $this 
                 * @var Relation $relationOb
                 * */
                $request = request();

                $requestSortColumn = $sortColumn ?? $request->sortColumn ?? 'id';
                $requestSort = $sort ?? $request->sort ?? 'desc';
        
                // Function to handle sorting by a column with numeric strings
                $numericSort = function ($column)
                               {
                                   return DB::raw("CAST($column AS UNSIGNED)")->getValue();
                               };
        
                if (str_contains($requestSortColumn, '.')) {
                    [$relation, $column] = explode('.', $requestSortColumn);
        
                    $relationOb = $this->getModel()->{$relation}();

                    // relation table name
                    $relationTable = $relationOb->getModel()->getTable();

                    // relation foreign key
                    $relationForeignKey = $relationOb->getForeignKeyName();

                    // base table
                    $queryTable = $this->from;
        
                    return $this->join($relationTable, "$relationTable.$relationForeignKey", "=", "$queryTable.id")
                                ->selectRaw("$queryTable.*")
                                ->selectRaw("$relationTable.$column, $relationTable.$relationForeignKey")
                                ->orderBy($numericSort("$relationTable.$column"), $requestSort);
                } else
                {
                    return $this->orderBy($numericSort($requestSortColumn), $requestSort);
                }
            } catch (\Exception $e) {
                // Handle exception
                throw $e;
            }
        });
    }
    
    protected function defineBuilderWorkCalendarDatesFilterMacro() : void
    {
        Builder::macro('workCalendarDatesFilter', function (?string $relation = null, string $column = 'date') 
        {
            
            if(!PixelMacroableExtendersConfigManager::isPixelCarbonExtenderLoaded())
            {
                PixelCarbonExtender::throwNotLoadedExtenderException();
            }

            /** 
             * @var Builder $this 
             * @var Relation $relationOb
             * 
             */
            $request = request();
                
            $from = $request->get('from');
            $to = $request->get('to');
        
            if ($from && $to)
            {
                $fromDate = Carbon::parseOrNow($from)->startOfDay()->format("Y-m-d H:i:s");
                $toDate = Carbon::parseOrNow($to)->endOfDay()->format("Y-m-d H:i:s");

                if ($relation)
                {
                    $this->whereHas($relation, fn($query) => $query->whereBetween($column, [$from, $to]));

                } else {
                    $this->whereBetween($column, [$fromDate, $toDate]);
                }
            }
        
            return $this;
        });
    }
} 
