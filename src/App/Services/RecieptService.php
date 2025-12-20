<?php

declare(strict_types=1);

namespace App\Services;

use Framework\Database;
use Framework\Exceptions\ValidationExeption;
use App\Config\Paths;

class RecieptService
{
    public function __construct(private Database $db)
    {
        
    }

    public function validateFile(?array $file)
    {
        if(!$file || $file['error'] !== UPLOAD_ERR_OK){
            throw new ValidationExeption([
                'receipt' => ['Failed to upload file']
            ]);   
        }

        $maxFileSizeMB = 3 * 1024 * 1024;
        if($file['size'] > $maxFileSizeMB){
            throw new ValidationExeption([
                'receipt' => ['File Upload is too Large max-size is 3mb']
            ]);
        }

        $originalFileName = $file['name'];
        if(!preg_match('/^[A-Za-z0-9\s._-]+$/', $originalFileName)){
            throw new ValidationExeption([
                'receipt' => ['Invalid filename']
            ]);
        }

        $clientMimeType = $file['type'];
        $allowedMimeTypes = ['image/jpeg', 'image/png', 'application/pdf'];

        if(!in_array($clientMimeType, $allowedMimeTypes)){
            throw new ValidationExeption([
                'receipt' => ['Invalid file type']
            ]);
        }

        // dd($file);    
    }

    public function upload(array $file, int $transaction)
    {
        $fileExtension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $newFilename = bin2hex(random_bytes(16)) . "." . $fileExtension;

        $uploadPath = Paths::STORAGE_UPLOADS . "/" . $newFilename;
        // dd($newFilename);

        if (!move_uploaded_file($file['tmp_name'], $uploadPath)){
            throw new ValidationExeption([
                'receipt' => ['Failed to upload file']
            ]);
        }

        $this->db->query(
            "INSERT INTO receipts(transaction_id, original_filename, storage_filename, media_type) VALUES(:transaction_id, :original_filename, :storage_filename, :media_type)", [
                'transaction_id' => $transaction,
                'original_filename' => $file['name'],
                'storage_filename' => $newFilename,
                'media_type' => $file['type']
            ]
        );
    }

    public function getReceipt(string $id){
        $receipt = $this->db->query(
            "SELECT * FROM receipts WHERE id = :id", [
            'id' => $id
        ])->find();

        return $receipt;
    }

    public function read(array $receipt){
        $filePath = Paths::STORAGE_UPLOADS . "/" . $receipt['storage_filename'];

        if(!file_exists($filePath)){
            redirectTo('/');
        }

        header("Content-Disposition: inline;filename={$receipt['original_filename']}");
        header("Content-type: {$receipt['media_type']}");

        readfile($filePath);
    }

    public function delete(array $receipt)
    {
        $filePath = Paths::STORAGE_UPLOADS . "/" . $receipt['storage_filname'];
        
        unlink($filePath);

        $this->db->query(
            "DELETE FROM receipts WHERE id = :id",
            [
                'id' => $receipt['id']
            ]
            );
    }
}