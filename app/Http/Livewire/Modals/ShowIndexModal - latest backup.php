<?php

namespace App\Http\Livewire\Modals;

use App\Models\indexDeduction;
use App\Models\NewPayrollIndex;
use App\Models\PayrollIndex;
use App\Models\User;
use Livewire\Component;
use DB;
use Illuminate\Support\Carbon;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;

class ShowIndexModal extends Component
{
    public $payrollIndex,
            $payrollIndexUserPerYear,
            $payrollIndexPerUser = "",
            $userId,
            $joDeductions,
            $additionalDeductions,
            $uniqueAdditionalDeductionGroups;

    protected $listeners = ['openIndexModal'];

    public function openIndexModal($userId)
    {
        $this->userId = $userId;
    }

    public function downloadModifiedTemplate() {
        // Call the method to modify the template
        $modifiedPath = $this->modifyTemplate();
    
        // Return a response to trigger the download
        return response()->download($modifiedPath, 'modified_template.xlsx');
    }

    public function modifyTemplate() {
        $templatePath = storage_path('app/excel_templates/INDEX_OF_PAYMENTS_JO.xlsx');
        $spreadsheet = IOFactory::load($templatePath);

        $userId = 7; // Replace this with the desired user ID

        $indexDeductions = indexDeduction::join('payroll_indices', 'index_deductions.payroll_index_id', '=', 'payroll_indices.id')
            ->join('users', 'payroll_indices.user_id', '=', 'users.id')
            ->where('users.id', $userId)
            ->get();

        $newPayrollIndex = NewPayrollIndex::all();

        // Group the index deductions by year, period_covered, and deduction_group
        $data = NewPayrollIndex::all();
        
        // Modify the desired cell(s)
        $sheet = $spreadsheet->getActiveSheet();
        $rowStart = 15;
        foreach ($data as $year => $yearAndPeriods){
        $sheet->setCellValue('A10', 'INDEX OF PAYMENTS | CY ' . $year);

            foreach ($yearAndPeriods as $period => $current_period){
                $sheet->setCellValue('A'.$rowStart, $period);
                $sheet->setCellValue('B'.$rowStart, $current_period['days_rendered']);
                $sheet->setCellValue('C'.$rowStart, $current_period['daily_monthly_rate']);
                // $sheet->setCellValue('D'.$rowStart, $current_period['basic_pay']); //PENDING
                // $sheet->setCellValue('E'.$rowStart, $current_period['daily_monthly_rate']);

                    
    // Column reference of the target column (A)
    $targetColumnReference = 'E';

    // Calculate the column letter for the new column (B)
    $newColumnReference = $this->incrementColumnReference($targetColumnReference, 1);

    // Insert the new column to the right
    $sheet->insertNewColumnBefore($newColumnReference, 1);

    // Get the highest row number
    $highestRow = $sheet->getHighestRow();

    // Apply the style from the target cell to the new column
    for ($row = 1; $row <= $highestRow; $row++) {
        $targetCellReference = $targetColumnReference . $row;
        $newCellReference = $newColumnReference . $row;

        // Get the style of the target cell
        $targetCellStyle = $sheet->getStyle($targetCellReference);

        // Apply the style from the target cell to the new cell
        $sheet->duplicateStyle($targetCellStyle, $newCellReference);
    }

                // Update data in the new column (B)
                // $newColumn = $sheet->getColumnDimensionByColumn($targetColumnIndex + 1);
                // $newColumnLetter = $sheet->getDelegate()->columnIndexFromString($newColumn->getColumnIndex()) - 1;

                // $newColumnCells = $sheet->getColumnIteratorByColumn($newColumnLetter);
                // foreach ($newColumnCells as $cell) {
                //     // Insert your data here
                //     $cell->setValue('Data for new column');
                // }


                foreach ($current_period['deductions'] as $deductionGroup => $deductionItems){

                }

                $rowStart++;
            }
        }

        
        // Save the modified file
        $modifiedPath = storage_path('app/excel_templates/modified_template.xlsx');
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save($modifiedPath);

        // Return the path to the modified file
        return $modifiedPath;
    }

    // Function to increment a column reference by a given offset
    public function incrementColumnReference($reference, $offset) {
        $columnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($reference);
        $newColumnIndex = $columnIndex + $offset;
        return \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($newColumnIndex);
    }

    public function render()
    {

    // Retrieve index deductions for the specified user ID
    $userId = 7; // Replace this with the desired user ID

    $indexDeductions = indexDeduction::join('payroll_indices', 'index_deductions.payroll_index_id', '=', 'payroll_indices.id')
        ->join('users', 'payroll_indices.user_id', '=', 'users.id')
        ->where('users.id', $userId)
        ->get();

    $data = NewPayrollIndex::all();

    $excludedColumns = [
        'id',
        'name',
        'office_section', 
        'imo', 
        'position_title', 
        'sg_jg', 
        // 'daily_monthly_rate', 
        'employment_status', 
        // 'period_covered',
        'period_covered_from', 
        'period_covered_to', 
        'tin', 
        'phic_no', 
        'hdmf', 
        // 'days_rendered',
        // 'basic_pay',
        'pera', 
        'medical', 
        'meal', 
        'children',
        'total_allowances',
        'gross_amount',
        'gsis_premium', 
        'gsis_consoloan', 
        'gsis_salary_loan',
        'gsis_cash_adv',
        'gsis_emergency',
        'gsis_gfal',
        'gsis_mpl',
        'gsis_cpl',
        'incentives_benefits',
        'user_id',
        'created_at',
        'updated_at',
    ];

    $tableColumns = DB::select('show columns from '.(new NewPayrollIndex)->getTable());

    $filteredColumns = array_filter($tableColumns, function ($column) use ($excludedColumns) {
        return !in_array($column->Field, $excludedColumns);
    });
    
    $columnNames = array_map(function ($column) {
        return $column->Field;
    }, $filteredColumns);
    
    $columDeductions = [];
    
    foreach ($columnNames as $columnName) {
        // Check if the column should be excluded
        if (in_array($columnName, $excludedColumns)) {
            continue;
        }
    
        $words = explode('_', $columnName);
        $groupKey = $words[0];
    
        if (!isset($columDeductions[$groupKey])) {
            $columDeductions[$groupKey] = [];
        }
    
        $columDeductions[$groupKey][] = $columnName;
    }

    // dd($columDeductions);
    // dd($columnNames);
    // dd(json_decode($data));

        $numberOfDedCols = indexDeduction::with('payrollIndex')
        ->whereNotIn('deduction_group', ['PHIC', 'COOP', 'DISALLOWANCE', 'TAX', 'GSIS', 'HDMF'])
        ->distinct('deduction_group')
        ->count('deduction_group');

        $newNumberOfDedCols = count($columnNames) - 9;
        // dd($newNumberOfDedCols);

        return view('livewire.modals.show-index-modal', [
            'jsonOutput' => json_encode($data),
            'columnNames' => $columnNames,
            'numberOfDedCols' => $newNumberOfDedCols,
            'columDeductions' => $columDeductions
        ]);


    }
}
