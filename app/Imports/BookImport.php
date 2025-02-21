<?php

namespace App\Imports;

use App\Models\Book;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class BookImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        return new Book([
            'title'     => $row['title'],
            'category_id'    => $row['category_id'],
            'user_id' => $row['user_id'],
            'publisher_id' => $row['publisher_id'],
            'cover_book' => $row['cover_book']
        ]);
    }
}
