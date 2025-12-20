<?php

declare(strict_types=1);

namespace App\Controllers;

use Framework\TemplateEngine;
use App\Services\{TransactionService, RecieptService};

class ReceiptController
{
  public function __construct(
    private TemplateEngine $view,
    private TransactionService $transactionService,
    private RecieptService $recieptService
  ) {
  }

  public function uploadView(array $params)
  {
    $transaction = $this->transactionService->getUserTransaction($params['transaction']);

    if (!$transaction) {
      redirectTo("/");
    }

    echo $this->view->render("receipts/create.php");
  }

  public function upload(array $params)
  {
    $transaction = $this->transactionService->getUserTransaction($params['transaction']);

    if (!$transaction) {
      redirectTo("/");
    }

    $receiptFile = $_FILES['receipt'] ?? null;

    $this->recieptService->validateFile($receiptFile);

    $this->recieptService->upload($receiptFile, $transaction['id']);

    redirectTo("/");
  }

  public function download(array $params){
    $transaction = $this->transactionService->getUserTransaction(
      $params['transaction']
    );
    if(empty($transaction)){
      redirectTo('/');
    }

    $receipt = $this->recieptService->getReceipt($params['receipt']);
    if(empty($receipt)){
      redirectTo('/');
    }

    if($receipt['transaction_id'] !== $transaction['id']){
      redirectTo('/');
    }

    $this->recieptService->read($receipt);
  }

  public function delete(array $params)
  {
    $transaction = $this->transactionService->getUserTransaction(
      $params['transaction']
    );
    if(empty($transaction)){
      redirectTo('/');
    }

    $receipt = $this->recieptService->getReceipt($params['receipt']);
    if(empty($receipt)){
      redirectTo('/');
    }

    if($receipt['transaction_id'] !== $transaction['id']){
      redirectTo('/');
    }


    $this->recieptService->delete($receipt);

    redirectTo('/');
  }

}