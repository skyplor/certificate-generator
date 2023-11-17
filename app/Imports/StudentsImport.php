<?php

namespace App\Imports;

use App\Student;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class StudentsImport implements ToModel, WithBatchInserts, WithChunkReading, WithHeadingRow
{
    use Importable;
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Student([
            'name' => $row['name'],
            'phone1' => $row['phone1'],
            'email' => $row['email'],
            'phone2' => $row['phone2'],
            'address' => $row['address'],
            'id_proof' => $row['id_proof'],
            'age' => (int) $row['age'],
            'gender' => $row['gender'],
            'fathersname' => $row['fathersname'],
            'social1_name' => $row['social1_name'],
            'social1_url' => $row['social1_url'],
            'social2_name' => $row['social2_name'],
            'social2_url' => $row['social2_url'],
            'social3_name' => $row['social3_name'],
            'social3_url' => $row['social3_url'],
        ]);
    }

    public function batchSize(): int
    {
        return 1000;
    }

    public function chunkSize(): int
    {
        return 1000;
    }
}
